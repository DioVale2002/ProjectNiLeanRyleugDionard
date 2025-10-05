<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/header.css">
    <link rel="stylesheet" href="/styles/userAccount.css">
    <title>Login & Security</title>
</head>
<body>
    <header class="header">
        <a href="#"></a><img class="logo" src="images/Logo(1).png" alt="logo"></a>
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
                    <a href="{{ route('logout') }}" class="sideNavLink">
                        <img src="/images/LogoutIcon(1).png" class="navIcon" alt="Logout Icon">
                        <p>Logout</p>
                    </a>
            </div>


            <div>
            <form method="POST" action="{{ route('account.info.update') }}" class="mainContent">
                @csrf
                @method('PUT')
                <h1>Your Personal Information</h1>
                <div class="divider"></div>

                <div class="form">
                    <div class="labels">
                    <label for="first_name">First Name</label>
                    <label for="last_name">Last Name</label>
                    <label for="email">Email</label>
                    <label for="contact_number">Contact Number</label>           
                    <label for="password">Password</label>   
                    <label for="new_password">New Password</label>
                </div>

                <div class="input-fields">
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required>
                    <input type="text" name="contact_num" value="{{ old('contact_num', $customer->contact_num) }}" required>
                    <input type="password" value="••••••••••••••••" disabled>
                    <input type="password" name="new_password" placeholder="Leave blank to keep current password">
                </div>

                </div>
                    <div class="buttonContainer">
                    <button type="submit" class="button1" style="margin-right: 50px;">Save</button>
                </div>
            </form>
            <form method="POST" action="{{ route('account.delete') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');" class="mainContent2">
                @csrf
                @method('DELETE')
                <h1>Delete Account</h1>
                <div class="warning-badge" style="color: red;">
                    <p>Warning: Deleting your account is irreversible. All your data will be permanently removed.</p>
                </div>
                <div class="divider"></div>

                <div class="form">
                    <div class="labels">
                    <label for="password">Confirm Password</label>
                </div>

                <div class="input-fields">
                    <input type="password" name="password" required>
                </div>
                </div>
                    <div class="buttonContainer">
                    <button type="submit" class="button1" style="margin-right: 50px;">DELETE</button>
                </div>
            </form>
            </div>
    </main>
</body>
</html>






