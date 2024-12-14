<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <form action="/login" method="POST">
        @csrf
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}">
            @error('username') <p>{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            @error('password') <p>{{ $message }}</p> @enderror
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
