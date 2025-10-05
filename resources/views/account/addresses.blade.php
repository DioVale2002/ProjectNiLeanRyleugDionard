<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Addresses - Bookstore</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="account-container">
        <h2>Your Account</h2>
        <p>User Account Access: {{ Auth::guard('customer')->user()->email }}</p>

        <div class="sidebar">
            <a href="{{ route('account.orders') }}">My Orders</a>
            <a href="{{ route('account.addresses') }}" class="active">Your Addresses</a>
            <a href="{{ route('account.security') }}">Login & Security</a>
            <a href="{{ route('account.archived') }}">Archive Orders</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>

        <div class="content">
            <h3>My Address</h3>

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

            <form method="POST" action="{{ route('account.address.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" value="{{ old('country', $address->country ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" value="{{ old('province', $address->province ?? '') }}">
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" value="{{ old('barangay', $address->barangay ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Zip/Postal Code</label>
                    <input type="text" name="zip_postal_code" value="{{ old('zip_postal_code', $address->zip_postal_code ?? '') }}">
                </div>

                <button type="submit" class="btn-save">Save</button>
            </form>
        </div>
    </div>
</body>
</html>