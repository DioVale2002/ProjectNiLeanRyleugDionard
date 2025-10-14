<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/userAccount.css">
    <title>Your Addresses</title>
</head>

<body>
    <header class="header">
        <a href="#"><img class="logo" src="/images/Logo(1).png" alt="logo"></a>
        <input class="search-bar" type="text" placeholder="Search ">
        <div class="navigation">
            <p>Welcome, {{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }}!</p>
            <a href="#"><img src="/images/User.png" alt="Profile Picture"></a>
            <a href="#"><img src="/images/cart.png" alt="cart"></a>
        </div>
    </header>
    <nav class="navbar">
        <div class="navlinks-container">
            <a href="#">BOOKS</a>
            <a href="#">E-BOOKS</a>
            <a href="#">BEST SELLERS</a>
            <a href="#">NEW</a>
            <a href="#">COLLECTIONS</a>
        </div> 
    </nav> 

    <main>
        <div class="accountheader">
            <h1>Welcome</h1>
            <p>{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }}, Email: {{ Auth::guard('customer')->user()->email }}</p>
        </div>

        <div class="mainContainer">
            <div class="sideNav">
                    <a href="{{ route('account.orders') }}" class="sideNavLink">
                        <img src="/images/Icon Delivery.png" class="navIcon" alt="Icon Delivery">
                        <p>My Orders</p>
                    </a>
                    <a href="{{ route('account.addresses') }}" class="sideNavLink active">
                        <img src="/images/AdressIcon.png" class="navIcon" alt="Addresses Icon">
                        <p>Addresses</p>
                    </a>
                    <a href="{{ route('account.security') }}" class="sideNavLink">
                        <img src="/images/SecurityIcon.png" class="navIcon" alt="Login & Security Icon">
                        <p>Login & Security</p>
                    </a>
                    <a href="{{ route('account.archived') }}" class="sideNavLink">
                        <img src="/images/ArchiveIcon.png" class="navIcon" alt="Archive Orders Icon">
                        <p>Archived Orders</p>
                    </a>
                    <div class="divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sideNavLink">
                            <img src="/images/LogoutIcon(1).png" class="navIcon" alt="Logout Icon">
                            <p>Logout</p>
                        </button>
                    </form>
            </div>

            <div>
                @if (session('success'))
                    <div class="success" style="padding: 15px; margin: 20px 0; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error" style="padding: 15px; margin: 20px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('account.address.update') }}" class="mainContent" id="updateAddressForm">
                    @csrf
                    @method('PUT')
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1>Your Address Information</h1>
                        <button type="button" class="button2" id="editButton" onclick="toggleEdit()">Edit</button>
                    </div>
                    <div class="divider"></div>

                    <div class="form">
                        <div class="labels">
                            <label for="Country">Country</label>
                            <label for="Province">Province</label>
                            <label for="City">City</label>
                            <label for="Barangay">Barangay</label>           
                            <label for="Zip/Postal Code">Zip/Postal Code</label>   
                        </div>

                        <div class="input-fields">
                            <input type="text" name="country" id="country" value="{{ old('country', $address->country ?? 'Not set') }}" readonly>
                            <input type="text" name="province" id="province" value="{{ old('province', $address->province ?? 'Not set') }}" readonly>
                            <input type="text" name="city" id="city" value="{{ old('city', $address->city ?? 'Not set') }}" readonly>
                            <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $address->barangay ?? 'Not set') }}" readonly>
                            <input type="text" name="zip_postal_code" id="zip_postal_code" value="{{ old('zip_postal_code', $address->zip_postal_code ?? 'Not set') }}" placeholder="Numbers only" readonly>
                        </div>
                    </div>

                    <div class="buttonContainer" id="saveButton" style="display: none;">
                        <button type="button" class="button2" onclick="cancelEdit()">Cancel</button>
                        <button type="submit" class="button1">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleEdit() {
            const inputs = document.querySelectorAll('#updateAddressForm input');
            inputs.forEach(input => {
                input.removeAttribute('readonly');
                // Clear "Not set" placeholder when editing
                if (input.value === 'Not set') {
                    input.value = '';
                }
            });
            
            document.getElementById('editButton').style.display = 'none';
            document.getElementById('saveButton').style.display = 'flex';
        }

        function cancelEdit() {
            location.reload();
        }
    </script>
</body>
</html>