<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $list->title }} - PPK Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-indigo-600 text-white p-4 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('lists.index') }}" class="text-indigo-200 hover:text-white text-sm font-semibold flex items-center gap-1 transition-colors">
                &larr; Back to Lists
            </a>
            <span class="text-indigo-300">|</span>
            <h1 class="text-xl font-bold truncate max-w-xs md:max-w-md">{{ $list->title }}</h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm">Welcome, {{ Auth::user()->username }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-800 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-6 mt-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6 shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 font-bold ml-4">&times;</button>
            </div>
        @endif

        <!-- List Header Details Card -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-800">{{ $list->title }}</h2>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-2">
                        <span>Created by: <strong class="text-gray-700">{{ $list->creator->username ?? 'Unknown' }}</strong></span>
                        <span>&bull;</span>
                        <span>Deadline: <strong class="text-gray-700">{{ $list->deadline ? \Carbon\Carbon::parse($list->deadline)->format('d M Y') : 'No deadline' }}</strong></span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('tasks.create', $list) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Task
                    </a>
                </div>
            </div>

            <!-- Collaborators Section -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Collaborators:</span>
                <div class="flex flex-wrap gap-2">
                    @forelse ($list->collaborators as $collaborator)
                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-medium border border-indigo-100">
                            {{ $collaborator->username }}
                        </span>
                    @empty
                        <span class="text-gray-400 text-xs italic">No collaborators assigned</span>
                    @endforelse
                </div>
            </div>

            <!-- Progress Bar -->
            @php
                $totalCount = $list->tasks->count();
                $completedCount = $list->tasks->where('is_completed', true)->count();
                $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
            @endphp
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="font-semibold text-gray-700">Completion Progress</span>
                    <span class="text-sm font-bold text-indigo-600">{{ $completedCount }} of {{ $totalCount }} completed ({{ $percentage }}%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </div>

        <!-- Tasks List Section -->
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Tasks</h3>
            <span class="text-sm text-gray-500">{{ $totalCount }} total</span>
        </div>

        @if ($list->tasks->isEmpty())
            <div class="bg-white p-12 rounded-2xl shadow text-center border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-gray-700 mb-1">No tasks in this list yet</h4>
                <p class="text-gray-500 text-sm mb-6">Break down your goals by adding your first task.</p>
                <a href="{{ route('tasks.create', $list) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add First Task
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($list->tasks as $task)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between hover:shadow-md transition {{ $task->is_completed ? 'opacity-70 bg-gray-50/70' : '' }}">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <!-- Toggle Form -->
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-6 h-6 rounded-md border flex items-center justify-center transition {{ $task->is_completed ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-indigo-500' }}"
                                    title="{{ $task->is_completed ? 'Mark as incomplete' : 'Mark as completed' }}">
                                    @if ($task->is_completed)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold {{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }} truncate">
                                    {{ $task->task_name }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 ml-4">
                            @if ($task->is_priority)
                                <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-semibold border border-amber-200">
                                    Priority
                                </span>
                            @endif

                            @if ($task->deadline)
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
