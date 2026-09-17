<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollaboratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_add_collaborator(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Project Alpha',
            'creator_id' => $creator->id,
        ]);

        $response = $this->post("/lists/{$list->id}/collaborators", [
            'username' => 'targetuser',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_creator_can_add_collaborator_by_user_id(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $collaborator = User::create([
            'username' => 'collaborator1',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Project Alpha',
            'creator_id' => $creator->id,
        ]);

        $response = $this->actingAs($creator)->post("/lists/{$list->id}/collaborators", [
            'user_id' => $collaborator->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('collaborators', [
            'todo_list_id' => $list->id,
            'user_id' => $collaborator->id,
        ]);
        $this->assertTrue($list->fresh()->collaborators->contains($collaborator));
    }

    public function test_creator_can_add_collaborator_by_username(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $collaborator = User::create([
            'username' => 'akmal',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Project Beta',
            'creator_id' => $creator->id,
        ]);

        $response = $this->actingAs($creator)->post("/lists/{$list->id}/collaborators", [
            'username' => 'akmal',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('collaborators', [
            'todo_list_id' => $list->id,
            'user_id' => $collaborator->id,
        ]);
    }

    public function test_creator_cannot_add_self_as_collaborator(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Personal List',
            'creator_id' => $creator->id,
        ]);

        $response = $this->actingAs($creator)->post("/lists/{$list->id}/collaborators", [
            'user_id' => $creator->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('collaborators', [
            'todo_list_id' => $list->id,
            'user_id' => $creator->id,
        ]);
    }

    public function test_cannot_add_duplicate_collaborator(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $collaborator = User::create([
            'username' => 'gading',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Shared List',
            'creator_id' => $creator->id,
        ]);

        $list->collaborators()->attach($collaborator->id);

        $response = $this->actingAs($creator)->post("/lists/{$list->id}/collaborators", [
            'user_id' => $collaborator->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, $list->fresh()->collaborators()->where('user_id', $collaborator->id)->count());
    }

    public function test_non_creator_cannot_add_collaborator(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $otherUser = User::create([
            'username' => 'stranger',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $targetUser = User::create([
            'username' => 'target',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Creator Only List',
            'creator_id' => $creator->id,
        ]);

        $response = $this->actingAs($otherUser)->post("/lists/{$list->id}/collaborators", [
            'user_id' => $targetUser->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('collaborators', [
            'todo_list_id' => $list->id,
            'user_id' => $targetUser->id,
        ]);
    }

    public function test_creator_can_remove_collaborator(): void
    {
        $creator = User::create([
            'username' => 'creator',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $collaborator = User::create([
            'username' => 'collab_to_remove',
            'password' => 'password123',
            'list_access' => 'user',
        ]);

        $list = TodoList::create([
            'title' => 'Removal List',
            'creator_id' => $creator->id,
        ]);

        $list->collaborators()->attach($collaborator->id);
        $this->assertDatabaseHas('collaborators', [
            'todo_list_id' => $list->id,
            'user_id' => $collaborator->id,
        ]);

        $response = $this->actingAs($creator)->delete("/lists/{$list->id}/collaborators/{$collaborator->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('collaborators', [
            'todo_list_id' => $list->id,
            'user_id' => $collaborator->id,
        ]);
    }
}
