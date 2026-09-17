<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - PPK Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .table-row-hover:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(4px);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .animate-slide-in {
            animation: slideInRight 0.4s ease-out forwards;
        }
        .badge-user {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }
        .badge-admin {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen">

    {{-- Navbar --}}
    <nav class="glass-panel text-white px-6 py-4 shadow-2xl flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold bg-gradient-to-r from-white to-purple-200 bg-clip-text text-transparent">User Management</h1>
        </div>
        <div class="flex items-center gap-4">
            @auth
            <span class="text-sm text-gray-300">Welcome, {{ Auth::user()->username }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 border border-white/10 hover:border-white/20">
                    Logout
                </button>
            </form>
            @endauth
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6 mt-8">

        {{-- Success Alert - sesuai flowchart: Alert "User berhasil ditambahkan!" --}}
        @if(session('success'))
            <div class="animate-fade-in-up mb-6 bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 px-5 py-4 rounded-xl flex items-center gap-3 shadow-lg">
                <svg class="w-6 h-6 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8 animate-fade-in-up">
            <div>
                <h2 class="text-3xl font-extrabold text-white mb-1">Daftar Pengguna</h2>
                <p class="text-gray-400 text-sm">Kelola semua pengguna yang terdaftar dalam sistem</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-purple-500/25 transform transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-500/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah User
            </a>
        </div>

        {{-- Tabel viewUser --}}
        {{-- Sesuai flowchart: Cek apakah ada data user, jika tidak tampilkan "Belum ada pengguna" --}}
        @if($users->isEmpty())
            <div class="glass-panel p-16 rounded-2xl text-center animate-fade-in-up">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/20 flex items-center justify-center">
                    <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <p class="text-gray-400 text-lg font-medium">Belum ada pengguna</p>
                <p class="text-gray-500 text-sm mt-2">Klik tombol "Tambah User" untuk menambahkan pengguna baru</p>
            </div>
        @else
            {{-- Sesuai flowchart: Ulangi setiap user, buat baris tabel dengan kolom No, Username, Password, list_access --}}
            <div class="glass-panel rounded-2xl overflow-hidden shadow-2xl animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="px-6 py-4 text-left text-xs font-bold text-purple-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-purple-300 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-purple-300 uppercase tracking-wider">Password</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-purple-300 uppercase tracking-wider">List Access</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($users as $index => $user)
                                <tr class="table-row-hover transition-all duration-200 animate-slide-in" style="animation-delay: {{ $index * 0.05 }}s">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 text-sm font-bold text-gray-300">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                {{ strtoupper(substr($user->username, 0, 1)) }}
                                            </div>
                                            <span class="text-white font-medium">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-400 font-mono text-sm tracking-wider">••••••••</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold shadow-md
                                            {{ $user->list_access === 'admin' ? 'badge-admin text-white' : 'badge-user text-white' }}">
                                            {{ ucfirst($user->list_access) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer info --}}
                <div class="px-6 py-4 border-t border-white/5 flex justify-between items-center">
                    <span class="text-sm text-gray-400">
                        Total: <span class="font-bold text-purple-300">{{ $users->count() }}</span> pengguna
                    </span>
                </div>
            </div>
        @endif
    </main>

</body>
</html>
