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
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6 shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 font-bold ml-4">&times;</button>
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-800">Your Lists</h2>
            <a href="{{ route('lists.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New List
            </a>
        </div>

        @if($lists->isEmpty())
            <div class="bg-white p-12 rounded-2xl shadow text-center border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-50 rounded-full flex items-center justify-center text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <p class="text-gray-700 font-semibold text-lg">You don't have any lists yet.</p>
                <p class="text-gray-400 text-sm mt-1 mb-6">Create a todo list to get started organizing your project.</p>
                <a href="{{ route('lists.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow transition inline-flex items-center gap-1.5">
                    Create Your First List
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($lists as $list)
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $list->title }}</h3>
                            
                            <div class="text-sm text-gray-500 mb-4">
                                Deadline: {{ $list->deadline ? \Carbon\Carbon::parse($list->deadline)->format('d M Y') : 'No deadline' }}
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm font-semibold text-gray-700">Collaborators:</p>
                                    @if($list->creator_id === Auth::id())
                                        <button type="button" 
                                                onclick="openCollaboratorModal({{ $list->id }}, '{{ addslashes($list->title) }}')"
                                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add
                                        </button>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2 items-center">
                                    @forelse($list->collaborators as $collaborator)
                                        <span class="inline-flex items-center gap-1 bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs px-2.5 py-1 rounded-full">
                                            <span>{{ $collaborator->username }}</span>
                                            @if($list->creator_id === Auth::id())
                                                <form action="{{ route('collaborators.destroy', [$list->id, $collaborator->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus {{ $collaborator->username }} dari kolaborator?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-indigo-400 hover:text-red-600 ml-0.5 font-bold transition-colors" title="Hapus kolaborator">
                                                        &times;
                                                    </button>
                                                </form>
                                            @endif
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs italic">No collaborators</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-4">
                            <div class="text-sm text-gray-600">
                                @php
                                    $completedTasks = $list->tasks->where('is_completed', true)->count();
                                    $totalTasks = $list->tasks->count();
                                @endphp
                                <span class="font-semibold text-green-600">{{ $completedTasks }}</span> / {{ $totalTasks }} Tasks Completed
                            </div>
                            
                            <a href="{{ route('lists.show', $list) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View Details &rarr;</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Modal Tambah Kolaborator -->
    <div id="collaboratorModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Tambah Kolaborator</h3>
                <button type="button" onclick="closeCollaboratorModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">
                Pilih atau masukkan pengguna untuk berkolaborasi pada list: <strong id="modalListTitle" class="text-indigo-600"></strong>
            </p>

            <form id="collaboratorForm" method="POST" action="">
                @csrf
                @php
                    $availableUsers = \App\Models\User::where('id', '!=', Auth::id())->get();
                @endphp

                <div class="space-y-4">
                    @if($availableUsers->isNotEmpty())
                        <div>
                            <label for="modal_user_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                Pilih dari Pengguna Terdaftar
                            </label>
                            <select name="user_id" id="modal_user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Pilih User --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="text-xs text-gray-400 uppercase">atau</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>
                    @endif

                    <div>
                        <label for="modal_username" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                            Ketik Username
                        </label>
                        <input type="text" 
                               name="username" 
                               id="modal_username" 
                               placeholder="Contoh: akmal, gading" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeCollaboratorModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition-colors">
                        + Tambahkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCollaboratorModal(listId, listTitle) {
            const modal = document.getElementById('collaboratorModal');
            const titleEl = document.getElementById('modalListTitle');
            const form = document.getElementById('collaboratorForm');
            const selectEl = document.getElementById('modal_user_id');
            const inputEl = document.getElementById('modal_username');

            if (selectEl) selectEl.value = '';
            if (inputEl) inputEl.value = '';

            titleEl.textContent = listTitle;
            form.action = `/lists/${listId}/collaborators`;
            modal.classList.remove('hidden');
        }

        function closeCollaboratorModal() {
            const modal = document.getElementById('collaboratorModal');
            modal.classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('collaboratorModal');
            if (e.target === modal) {
                closeCollaboratorModal();
            }
        });
    </script>
</body>
</html>
