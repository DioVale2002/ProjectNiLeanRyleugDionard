<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Orders - Bookstore</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="account-container">
        <h2>Your Account</h2>
        <p>User Account Access: {{ Auth::guard('customer')->user()->email }}</p>

        <div class="sidebar">
            <a href="{{ route('account.orders') }}">My Orders</a>
            <a href="{{ route('account.addresses') }}">Your Addresses</a>
            <a href="{{ route('account.security') }}">Login & Security</a>
            <a href="{{ route('account.archived') }}" class="active">Archive Orders</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>

        <div class="content">
            <h3>Archive Orders</h3>
            <div class="no-orders">
                <p>No orders yet</p>
            </div>
        </div>
    </div>
</body>
</html>