<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/lists')->assertRedirect('/login');
        $this->get('/lists/create')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_create_list_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/lists/create');

        $response->assertStatus(200);
        $response->assertSee('New Todo List');
    }

    public function test_authenticated_user_can_create_todo_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/lists', [
            'title' => 'Project Alpha',
            'deadline' => '2026-10-15',
        ]);

        $response->assertRedirect('/lists');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('todo_lists', [
            'title' => 'Project Alpha',
            'creator_id' => $user->id,
        ]);

        $list = TodoList::where('title', 'Project Alpha')->first();
        $this->assertNotNull($list);
        $this->assertEquals('2026-10-15', $list->deadline->format('Y-m-d'));
    }

    public function test_create_list_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/lists', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_can_view_list_details(): void
    {
        $user = User::factory()->create();
        $list = TodoList::create([
            'title' => 'Biology Assignment',
            'creator_id' => $user->id,
            'deadline' => '2026-11-01',
        ]);

        $task = $list->tasks()->create([
            'task_name' => 'Research genetics paper',
            'is_priority' => true,
            'deadline' => '2026-10-20',
            'is_completed' => false,
        ]);

        $response = $this->actingAs($user)->get("/lists/{$list->id}");

        $response->assertStatus(200);
        $response->assertSee('Biology Assignment');
        $response->assertSee('Research genetics paper');
        $response->assertSee('Priority');
    }

    public function test_unauthorized_user_cannot_view_another_users_list(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $list = TodoList::create([
            'title' => 'Secret List',
            'creator_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)->get("/lists/{$list->id}");

        $response->assertStatus(403);
    }

    public function test_collaborator_can_view_list_details(): void
    {
        $owner = User::factory()->create();
        $collaborator = User::factory()->create();

        $list = TodoList::create([
            'title' => 'Shared List',
            'creator_id' => $owner->id,
        ]);

        $list->collaborators()->attach($collaborator->id);

        $response = $this->actingAs($collaborator)->get("/lists/{$list->id}");

        $response->assertStatus(200);
        $response->assertSee('Shared List');
    }

    public function test_user_can_view_create_task_page(): void
    {
        $user = User::factory()->create();
        $list = TodoList::create([
            'title' => 'Web Dev Practicum',
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/lists/{$list->id}/tasks/create");

        $response->assertStatus(200);
        $response->assertSee('Add New Task');
        $response->assertSee('Web Dev Practicum');
    }

    public function test_user_can_add_task_to_list(): void
    {
        $user = User::factory()->create();
        $list = TodoList::create([
            'title' => 'Web Dev Practicum',
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post("/lists/{$list->id}/tasks", [
            'task_name' => 'Complete Laravel Routes',
            'deadline' => '2026-09-25',
            'is_priority' => '1',
        ]);

        $response->assertRedirect("/lists/{$list->id}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tasks', [
            'todo_list_id' => $list->id,
            'task_name' => 'Complete Laravel Routes',
        ]);

        $task = Task::where('task_name', 'Complete Laravel Routes')->first();
        $this->assertNotNull($task);
        $this->assertTrue($task->is_priority);
        $this->assertFalse($task->is_completed);
        $this->assertEquals('2026-09-25', $task->deadline->format('Y-m-d'));
    }

    public function test_task_creation_requires_name(): void
    {
        $user = User::factory()->create();
        $list = TodoList::create([
            'title' => 'Web Dev Practicum',
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post("/lists/{$list->id}/tasks", [
            'task_name' => '',
        ]);

        $response->assertSessionHasErrors('task_name');
    }

    public function test_user_can_toggle_task_status(): void
    {
        $user = User::factory()->create();
        $list = TodoList::create([
            'title' => 'Sprint 1',
            'creator_id' => $user->id,
        ]);

        $task = $list->tasks()->create([
            'task_name' => 'Deploy app',
            'is_completed' => false,
        ]);

        $response = $this->actingAs($user)->patch("/tasks/{$task->id}/toggle");

        $response->assertRedirect();
        $this->assertTrue($task->fresh()->is_completed);

        // Toggle back
        $this->actingAs($user)->patch("/tasks/{$task->id}/toggle");
        $this->assertFalse($task->fresh()->is_completed);
    }
}
