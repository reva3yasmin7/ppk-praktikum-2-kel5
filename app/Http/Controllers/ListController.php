<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get lists where user is creator
        $lists = TodoList::where('creator_id', $user->id)
            ->with(['collaborators', 'tasks'])
            ->get();

        return view('lists.index', compact('lists'));
    }

    public function create()
    {
        return view('lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
        ]);

        TodoList::create([
            'title' => $validated['title'],
            'creator_id' => Auth::id(),
            'deadline' => $validated['deadline'] ?? null,
        ]);

        return redirect()->route('lists.index')->with('success', 'Todo list created successfully!');
    }

    public function show(TodoList $list)
    {
        $user = Auth::user();

        $isCreator = $list->creator_id === $user->id;
        $isCollaborator = $list->collaborators()->where('user_id', $user->id)->exists();

        if (! $isCreator && ! $isCollaborator) {
            abort(403, 'Unauthorized access to this list.');
        }

        $list->load(['tasks' => function ($query) {
            $query->orderBy('is_completed')
                ->orderByDesc('is_priority')
                ->orderBy('deadline');
        }, 'collaborators', 'creator']);

        return view('lists.show', compact('list'));
    }
}
