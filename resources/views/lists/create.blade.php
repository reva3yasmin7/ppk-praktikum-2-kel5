<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New List - PPK Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-indigo-600 text-white p-4 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('lists.index') }}" class="text-indigo-200 hover:text-white text-sm font-semibold flex items-center gap-1 transition-colors">
                &larr; Back to Lists
            </a>
            <span class="text-indigo-300">|</span>
            <h1 class="text-xl font-bold">Create New List</h1>
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

    <main class="max-w-2xl mx-auto p-6 mt-8">
        <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">New Todo List</h2>
                <p class="text-sm text-gray-500 mt-1">Create a new list to organize your tasks and collaborate with team members.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center mb-1">
                        <span class="text-red-700 font-semibold text-sm">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lists.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">List Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                        placeholder="e.g., Praktikum PPK Modul 2"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-1">Deadline <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-400 mt-1">Leave empty if this list has no target completion date.</p>
                </div>

                <div class="border-t border-gray-100 pt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('lists.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow transition">
                        Create List
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
