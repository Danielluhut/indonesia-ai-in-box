<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password | INDONESIA AI IN BOX</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white min-h-screen flex items-center justify-center">

    <div class="bg-gray-800 rounded-xl shadow-xl w-full max-w-sm px-8 py-10">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('icons/Logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-4 animate-bounce">
            <h1 class="text-xl font-semibold text-white">INDONESIA AI</h1>
            <p class="text-sm text-gray-400 tracking-widest">IN THE BOX</p>
        </div>

        <h2 class="text-lg font-semibold text-center mb-6">Forgot Password</h2>

        <div class="text-sm text-gray-400 mb-4 text-center">
            Forgot your password? Enter your email and we’ll send you a reset link.
        </div>

        @if (session('status'))
            <div class="mb-4 text-green-400 font-medium text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="Email"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:ring focus:ring-blue-500">

            @error('email')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition duration-200 font-semibold">
                Send Reset Link
            </button>
        </form>

        <a href="{{ route('login') }}"
            class="mt-4 block text-center border border-gray-500 text-white py-2 rounded hover:bg-gray-700 transition duration-200 font-medium">
            Return to Login
        </a>
    </div>

</body>

</html>
