<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/userAccount.css">
    <title>Login & Security</title>
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
                <a href="{{ route('account.addresses') }}" class="sideNavLink">
                    <img src="/images/AdressIcon.png" class="navIcon" alt="Adresses Icon">
                    <p>Addresses</p>
                </a>
                <a href="{{ route('account.security') }}" class="sideNavLink active">
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

                <form method="POST" action="{{ route('account.info.update') }}" class="mainContent" id="updateInfoForm">
                    @csrf
                    @method('PUT')
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1>Your Personal Information</h1>
                        <button type="button" class="button2" id="editButton" onclick="toggleEdit()">Edit</button>
                    </div>
                    <div class="divider"></div>

                    <div class="form">
                        <div class="labels">
                            <label for="first_name">First Name</label>
                            <label for="last_name">Last Name</label>
                            <label for="email">Email</label>
                            <label for="contact_number">Contact Number</label>
                            <label for="current_password">Current Password</label>
                            <label for="new_password">New Password</label>
                            <label for="new_password_confirmation">Confirm New Password</label>
                        </div>

                        <div class="input-fields">
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" readonly required>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" readonly required>
                            <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" readonly required>
                            <input type="text" name="contact_num" id="contact_num" value="{{ old('contact_num', $customer->contact_num) }}" readonly required>
                            <input type="password" name="current_password" id="current_password" placeholder="Enter current password to change" readonly>
                            <input type="password" name="new_password" id="new_password" placeholder="Leave blank to keep current password" readonly>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" readonly>
                        </div>
                    </div>

                    <div class="buttonContainer" id="saveButton" style="display: none;">
                        <button type="button" class="button2" onclick="cancelEdit()">Cancel</button>
                        <button type="submit" class="button1" style="margin-right: 50px;">Save</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('account.delete') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');" class="mainContent2" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1>Delete Account</h1>
                        <button type="button" class="button2" id="editDeleteButton" onclick="toggleDeleteEdit()">Enter Password</button>
                    </div>
                    <div class="warning-badge" style="color: red;">
                        <p>Warning: Deleting your account is irreversible. All your data will be permanently removed.</p>
                    </div>
                    <div class="divider"></div>

                    <div class="form">
                        <div class="labels">
                            <label for="password">Confirm Password</label>
                        </div>

                        <div class="input-fields">
                            <input type="password" name="password" id="delete_password" placeholder="Enter password to delete account" readonly required>
                        </div>
                    </div>
                    <div class="buttonContainer" id="deleteButton" style="display: none;">
                        <button type="button" class="button2" onclick="cancelDeleteEdit()">Cancel</button>
                        <button type="submit" class="button1" style="margin-right: 50px; background-color: #dc3545;">DELETE</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleEdit() {
            const inputs = document.querySelectorAll('#updateInfoForm input');
            inputs.forEach(input => {
                input.removeAttribute('readonly');
            });

            document.getElementById('editButton').style.display = 'none';
            document.getElementById('saveButton').style.display = 'flex';
        }

        function cancelEdit() {
            location.reload();
        }

        function toggleDeleteEdit() {
            const passwordInput = document.getElementById('delete_password');
            passwordInput.removeAttribute('readonly');

            document.getElementById('editDeleteButton').style.display = 'none';
            document.getElementById('deleteButton').style.display = 'flex';
        }

        function cancelDeleteEdit() {
            const passwordInput = document.getElementById('delete_password');
            passwordInput.setAttribute('readonly', true);
            passwordInput.value = '';

            document.getElementById('editDeleteButton').style.display = 'block';
            document.getElementById('deleteButton').style.display = 'none';
        }
    </script>
</body>

</html>