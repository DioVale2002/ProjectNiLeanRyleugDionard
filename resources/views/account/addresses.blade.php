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
        <a href="#"></a><img class="logo" src="/images/Logo(1).png" alt="logo"></a>
        <input class="search-bar" type="text" placeholder="Search ">
        <div class="navigation">
            <p class="cusname">Customer Name</p>
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
            <p>Customer Name, Email: {{ Auth::guard('customer')->user()->email }}</p>
        </div>

        <div class="mainContainer">
            <div class="sideNav">
                    <a href="{{ route('account.orders') }}" class="sideNavLink">
                        <img src="/images/Icon Delivery.png" class="navIcon" alt="Icon Delivery">
                        <p>My Orders</p>
                    </a>
                    <a href="{{ route('account.addresses') }}" class="sideNavLink">
                        <img src="/images/AdressIcon.png" class="navIcon" alt="Adresses Icon">
                        <p>Adresses</p>
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


            <form method="POST" action="{{ route('account.address.update') }}" class="mainContent" >
                @csrf
                @method('PUT')
                <h1>Your Address Information</h1>
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
                    <input type="text" name="country" value="{{ old('country', $address->country ?? '') }}">
                    <input type="text" name="province" value="{{ old('province', $address->province ?? '') }}">
                    <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}">
                    <input type="text" name="barangay" value="{{ old('barangay', $address->barangay ?? '') }}">
                    <input type="text" name="zip_postal_code" value="{{ old('zip_postal_code', $address->zip_postal_code ?? '') }}">
                </div>
                
                </div>
                    <div class="buttonContainer">
                    <button type="submit" class="button1">Save</button>
                </div>
            </div>
                
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
        </form>
    </main> 

</body>
</html>