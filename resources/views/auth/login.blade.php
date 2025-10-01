<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bookstore</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 14px; }
        .success { color: green; font-size: 14px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Login</h2>
    
    @if (session('success'))
        <div class="success">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    
    <p style="margin-top: 20px;">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</body>
</html>