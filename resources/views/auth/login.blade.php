<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lavishly+Yours&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Stack+Sans+Notch:wght@200..700&display=swap" rel="stylesheet">
    <title>Welcome back - SEDAP Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex">
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-pink-50 to-purple-50 items-center justify-center p-12 relative overflow-hidden">
            <img src="images/bahlil.jpg" alt="Background" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-pink-500/30 to-purple-500/30"></div>
            
            <!-- Logo di pojok kiri atas -->
            <div class="absolute top-8 left-8 z-10">
                <img src="logov.png" alt="SEDAP Logo" class="h-16 w-auto drop-shadow-lg">
            </div>

            <!-- Tulisan selamat datang di pojok kiri bawah -->
            <div class="absolute bottom-8 left-8 z-10 max-w-md">
                <h2 class="text-4xl font-bold text-white mb-4 drop-shadow-lg">Selamat Datang di SEDAP</h2>
                <p class="text-white text-lg drop-shadow-md">Sistem Deteksi Angka Pengangguran</p>
            </div>
        </div>
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center lg:text-left">Welcome back</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf 
                    @if ($errors->any())
                        <div class="text-sm text-red-600 mb-4 p-2 border border-red-300 bg-red-50 rounded-lg">
                            Kredensial ini tidak cocok dengan data kami.
                        </div>
                    @endif
                    <div class="mb-4">
                        <input 
                            id="username" 
                            type="text" 
                            name="username" 
                            value="{{ old('username') }}" 
                            placeholder="Enter username"
                            required 
                            autofocus 
                            autocomplete="username"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent placeholder-gray-400 text-sm shadow-sm @error('username') border-red-500 @enderror"
                        />
                    </div>
                    <div class="mb-6">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent placeholder-gray-400 text-sm shadow-sm @error('password') border-red-500 @enderror"
                        />
                    </div>
                    <button 
                        type="submit"
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-3 px-4 rounded-lg transition-colors text-sm shadow-lg"                    >
                        Continue
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        Selamat datang di aplikasi SEDAP Created By:
                        <a href="#" class="text-gray-700 hover:underline">Jay</a>, 
                        <a href="#" class="text-gray-700 hover:underline">Zam</a>,
                        <a href="#" class="text-gray-700 hover:underline">Die</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>