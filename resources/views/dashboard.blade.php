<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bookstore</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        button, a { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
        button:hover, a:hover { background: #0056b3; }
        .logout { background: #dc3545; }
        .logout:hover { background: #c82333; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Dashboard (Placeholder Page)</h2>
    
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <p>Welcome, {{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }}!</p>
    <p>Email: {{ Auth::guard('customer')->user()->email }}</p>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('account.edit') }}">Edit Account</a>
        
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout">Logout</button>
        </form>
    </div>
    
    <p style="margin-top: 30px; color: #666;">This is a placeholder page. Your frontend team can style this later.</p>
</body>
</html>