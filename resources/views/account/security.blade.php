<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Security - Bookstore</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="account-container">
        <h2>Your Account</h2>
        <p>User Account Access: {{ Auth::guard('customer')->user()->email }}</p>

        <div class="sidebar">
            <a href="{{ route('account.orders') }}">My Orders</a>
            <a href="{{ route('account.addresses') }}">Your Addresses</a>
            <a href="{{ route('account.security') }}" class="active">Login & Security</a>
            <a href="{{ route('account.archived') }}">Archive Orders</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>

        <div class="content">
            <h3>Your Personal Information</h3>

            @if (session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('account.info.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_num" value="{{ old('contact_num', $customer->contact_num) }}" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" value="••••••••••••••••" disabled>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Leave blank to keep current password">
                </div>

                <button type="submit" class="btn-save">Save</button>
            </form>

            <hr style="margin: 40px 0;">

            <h3>Delete Account</h3>
            <p style="color: red;">Warning: This action cannot be undone. All your data will be permanently deleted.</p>
            
            <form method="POST" action="{{ route('account.delete') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('DELETE')

                <div class="form-group">
                    <label>Confirm Password to Delete Account</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn-delete">Delete My Account</button>
            </form>
        </div>
    </div>
</body>
</html>