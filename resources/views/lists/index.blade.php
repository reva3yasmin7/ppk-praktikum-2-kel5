<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Lists - PPK Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-indigo-600 text-white p-4 shadow-lg flex justify-between items-center">
        <h1 class="text-xl font-bold">My Todo Lists</h1>
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

    <main class="max-w-6xl mx-auto p-6 mt-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-800">Your Lists</h2>
            <!-- Note: addList feature will be implemented by Gading -->
            <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold shadow transition-colors cursor-not-allowed opacity-75" title="Feature coming soon by Gading">
                + Create New List
            </button>
        </div>

        @if($lists->isEmpty())
            <div class="bg-white p-10 rounded-2xl shadow text-center border border-gray-100">
                <p class="text-gray-500 text-lg">You don't have any lists yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($lists as $list)
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $list->title }}</h3>
                        
                        <div class="text-sm text-gray-500 mb-4">
                            Deadline: {{ $list->deadline ? \Carbon\Carbon::parse($list->deadline)->format('d M Y') : 'No deadline' }}
                        </div>

                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Collaborators:</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($list->collaborators as $collaborator)
                                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">
                                        {{ $collaborator->username }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 text-xs italic">No collaborators</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                @php
                                    $completedTasks = $list->tasks->where('is_completed', true)->count();
                                    $totalTasks = $list->tasks->count();
                                @endphp
                                <span class="font-semibold text-green-600">{{ $completedTasks }}</span> / {{ $totalTasks }} Tasks Completed
                            </div>
                            
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View Details &rarr;</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
