<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form action="/register" method="POST">
        @csrf
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}">
            @error('username') <p>{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name') <p>{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            @error('email') <p>{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            @error('password') <p>{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>
        <div>
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="donor">Donor</option>
                <option value="panti">Panti</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>
</body>
</html>
