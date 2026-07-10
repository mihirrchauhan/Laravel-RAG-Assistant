<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow rounded-lg p-6">

        <h2 class="text-2xl font-bold text-center mb-6">
            Admin Login
        </h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">

            @csrf

            <div class="mb-4">
                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded p-2"
                    required
                >
            </div>

            <div class="mb-4">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded p-2"
                    required
                >
            </div>

            <button
                class="w-full bg-blue-600 text-white rounded p-2"
            >
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>