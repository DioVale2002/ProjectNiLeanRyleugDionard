<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account - Bookstore</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; margin-right: 10px; }
        button:hover { background: #0056b3; }
        .back { background: #6c757d; text-decoration: none; display: inline-block; padding: 10px 20px; color: white; }
        .back:hover { background: #5a6268; }
        .error { color: red; font-size: 14px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; margin-bottom: 20px; }
        hr { margin: 30px 0; }
        h3 { margin-top: 30px; }
    </style>
</head>
<body>
    <h2>Edit Account</h2>
    
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

    <form method="POST" action="{{ route('account.update') }}">
        @csrf
        @method('PUT')
        
        <h3>Account Information</h3>
        
        <label>First Name</label>
        <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required>
        
        <label>Last Name</label>
        <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required>
        
        <label>Contact Number</label>
        <input type="text" name="contact_num" value="{{ old('contact_num', $customer->contact_num) }}" required>
        
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $customer->email) }}" required>
        
        <h3>Address (Optional)</h3>
        
        <label>Country</label>
        <input type="text" name="country" value="{{ old('country', $address->country ?? '') }}">
        
        <label>Province</label>
        <input type="text" name="province" value="{{ old('province', $address->province ?? '') }}">
        
        <label>City</label>
        <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}">
        
        <label>Barangay</label>
        <input type="text" name="barangay" value="{{ old('barangay', $address->barangay ?? '') }}">
        
        <label>ZIP/Postal Code</label>
        <input type="text" name="zip_postal_code" value="{{ old('zip_postal_code', $address->zip_postal_code ?? '') }}">
        
        <button type="submit">Update Account</button>
    </form>

    <hr>

    <h3>Change Password</h3>
    <form method="POST" action="{{ route('account.password') }}">
        @csrf
        @method('PUT')
        
        <label>Current Password</label>
        <input type="password" name="current_password" required>
        
        <label>New Password</label>
        <input type="password" name="password" required>
        
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation" required>
        
        <button type="submit">Update Password</button>
    </form>

    <div style="margin-top: 30px;">
        <a href="{{ route('dashboard') }}" class="back">Back to Dashboard</a>
    </div>
</body>
</html>
