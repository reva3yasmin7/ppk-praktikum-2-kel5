<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create(TodoList $list)
    {
        $this->authorizeListAccess($list);

        return view('tasks.create', compact('list'));
    }

    public function store(Request $request, TodoList $list)
    {
        $this->authorizeListAccess($list);

        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            'is_priority' => ['nullable', 'boolean'],
        ]);

        $list->tasks()->create([
            'task_name' => $validated['task_name'],
            'is_priority' => $request->boolean('is_priority'),
            'deadline' => $validated['deadline'] ?? null,
            'is_completed' => false,
        ]);

        return redirect()->route('lists.show', $list)->with('success', 'Task added successfully!');
    }

    public function toggle(Task $task)
    {
        $this->authorizeListAccess($task->todoList);

        $task->update([
            'is_completed' => ! $task->is_completed,
        ]);

        $status = $task->is_completed ? 'completed' : 'marked active';

        return back()->with('success', "Task {$status}!");
    }

    private function authorizeListAccess(TodoList $list): void
    {
        $user = Auth::user();

        $isCreator = $list->creator_id === $user->id;
        $isCollaborator = $list->collaborators()->where('user_id', $user->id)->exists();

        if (! $isCreator && ! $isCollaborator) {
            abort(403, 'Unauthorized access to this list.');
        }
    }
}
