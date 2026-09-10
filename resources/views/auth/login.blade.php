<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PPK Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-black min-h-screen flex items-center justify-center p-4">
    <div class="glass-panel p-8 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 hover:scale-105">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-gray-300 text-sm">Please sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-6 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="username" class="block text-sm font-medium text-gray-200 mb-1">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-200 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-lg font-semibold shadow-lg transform transition-all duration-200 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
