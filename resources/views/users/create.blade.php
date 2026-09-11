<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - PPK Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3);
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

    <main class="max-w-2xl mx-auto p-6 mt-8">

        {{-- Back button --}}
        <div class="mb-6 animate-fade-in-up">
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors duration-200 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="text-sm font-medium">Kembali ke Daftar User</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="glass-panel rounded-2xl p-8 shadow-2xl animate-fade-in-up" style="animation-delay: 0.1s">
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-xl shadow-purple-500/25">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-white">Tambah User Baru</h2>
                <p class="text-gray-400 text-sm mt-1">Isi formulir di bawah untuk menambahkan pengguna baru</p>
            </div>

            {{-- Error Messages - sesuai flowchart alerts --}}
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-5 py-4 rounded-xl mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-bold text-sm">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- addUser Form --}}
            {{-- Sesuai flowchart: Ambil nilai input username, password, dan list_access --}}
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6" id="addUserForm">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-200 mb-2">
                        Username <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        class="input-glow w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-all duration-200">
                    @error('username')
                        <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-200 mb-2">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                        placeholder="Masukkan password"
                        class="input-glow w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-all duration-200">
                    @error('password')
                        <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- List Access --}}
                <div>
                    <label for="list_access" class="block text-sm font-semibold text-gray-200 mb-2">
                        List Access <span class="text-red-400">*</span>
                    </label>
                    <select id="list_access" name="list_access"
                        class="input-glow w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-purple-500 transition-all duration-200 appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%239ca3af%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 20px;">
                        <option value="" class="bg-slate-800">-- Pilih List Access --</option>
                        <option value="user" {{ old('list_access') === 'user' ? 'selected' : '' }} class="bg-slate-800">User</option>
                        <option value="admin" {{ old('list_access') === 'admin' ? 'selected' : '' }} class="bg-slate-800">Admin</option>
                    </select>
                    @error('list_access')
                        <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-purple-500/25 transform transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-500/30 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-slate-900 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
