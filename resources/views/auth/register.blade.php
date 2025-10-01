<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bookstore</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 14px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Register</h2>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <label>First Name</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}" required>
        
        <label>Last Name</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}" required>
        
        <label>Contact Number</label>
        <input type="text" name="contact_num" value="{{ old('contact_num') }}" required>
        
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>
        
        <button type="submit">Register</button>
    </form>
    
    <p style="margin-top: 20px;">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
</body>
</html>