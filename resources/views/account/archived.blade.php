<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/userAccount.css">
    <title>Archived Orders</title>
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
                    <a href="{{ route('account.security') }}" class="sideNavLink">
                        <img src="/images/SecurityIcon.png" class="navIcon" alt="Login & Security Icon">
                        <p>Login & Security</p>
                    </a>
                    <a href="{{ route('account.archived') }}" class="sideNavLink active">
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

            <div class="mainContent">
                <h1>Archived Orders</h1>
                <div class="divider"></div>
                
                @if($orders->isEmpty())
                    <h3 style="color: red; margin-top: 50px;">No Orders yet.</h3>
                @else
                    @foreach($orders as $order)
                        <div style="border: 1px solid #ccc; padding: 15px; margin: 20px 0; border-radius: 5px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3>Order #{{ $order->order_id }}</h3>
                                    <p>Date: {{ $order->order_date->format('M d, Y') }}</p>
                                    <p>Status: <strong style="color: {{ $order->order_status == 'Completed' ? 'green' : ($order->order_status == 'Cancelled' ? 'red' : 'gray') }}">{{ $order->order_status }}</strong></p>
                                </div>
                                <div style="text-align: right;">
                                    <h3>₱{{ number_format($order->total_price, 2) }}</h3>
                                    <p>{{ $order->paymentMethod->methodName }}</p>
                                </div>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <h4>Items:</h4>
                            @foreach($order->cart->items as $item)
                                <div style="display: flex; justify-content: space-between; margin: 10px 0;">
                                    <p>{{ $item->product->Title }} by {{ $item->product->Author }}</p>
                                    <p>{{ $item->quantity }} x ₱{{ number_format($item->unitPrice, 2) }} = ₱{{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>
</body>
</html>
