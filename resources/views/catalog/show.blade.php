<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    {{-- TODO: Frontend dev links catalog.css here --}}
    <title>{{ $product->Title }} - New Century Books</title>
</head>
<body>

    <header class="header">
        <a href="{{ route('catalog.index') }}">
            <img class="logo" src="/images/Logo(1).png" alt="NCB Logo">
        </a>
        <div class="navigation">
            @auth('customer')
                <p>Welcome, {{ Auth::guard('customer')->user()->first_name }}!</p>
                <a href="{{ route('account.orders') }}">
                    <img src="/images/User.png" alt="My Account">
                </a>
            @else
                <a href="{{ route('login') }}" class="login-link">Login</a>
            @endauth
            <a href="{{ route('cart.index') }}">
                <img src="/images/cart.png" alt="Cart">
            </a>
        </div>
    </header>

    <nav class="navbar">
        <div class="navlinks-container">
            <a href="{{ route('catalog.index') }}" class="nav-link">ALL BOOKS</a>
        </div>
    </nav>

    <main class="product-detail-main">

        <a href="{{ route('catalog.index') }}" class="back-link">← Back to Catalog</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ================================
             PRODUCT DETAIL LAYOUT
             Left: cover image
             Right: info + add to cart
             ================================ --}}
        <div class="product-detail-layout">

            {{-- Left: Cover --}}
            <div class="product-detail-cover">
                {{-- Placeholder until image upload built --}}
                <div class="product-cover-placeholder-lg">📖</div>
                {{-- Future: <img src="{{ $product->cover_image }}" alt="{{ $product->Title }}"> --}}
            </div>

            {{-- Right: Info --}}
            <div class="product-detail-info">
                <h1 class="detail-title">{{ $product->Title }}</h1>
                <p class="detail-author">by {{ $product->Author }}</p>
                <p class="detail-price">₱{{ number_format($product->Price, 2) }}</p>

                @if($product->Rating)
                    <p class="detail-rating">★ {{ number_format($product->Rating, 1) }} / 5</p>
                @endif

                {{-- Book metadata table --}}
                <table class="detail-meta-table">
                    <tr>
                        <th>Genre</th>
                        <td>{{ $product->Genre }}</td>
                    </tr>
                    <tr>
                        <th>Publisher</th>
                        <td>{{ $product->Publisher }}</td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>{{ $product->ISBN }}</td>
                    </tr>
                    @if($product->Age_Group)
                    <tr>
                        <th>Age Group</th>
                        <td>{{ $product->Age_Group }}</td>
                    </tr>
                    @endif
                    @if($product->Length && $product->Width)
                    <tr>
                        <th>Dimensions</th>
                        <td>{{ $product->Length }} x {{ $product->Width }} cm</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Availability</th>
                        <td class="{{ $product->Stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                            {{ $product->Stock > 0 ? $product->Stock . ' in stock' : 'Out of Stock' }}
                        </td>
                    </tr>
                </table>

                @if($product->Review)
                    <div class="detail-description">
                        <h4>Description</h4>
                        <p>{{ $product->Review }}</p>
                    </div>
                @endif

                {{-- Add to cart section --}}
                @if($product->Stock > 0)
                    @auth('customer')
                        <form action="{{ route('cart.add') }}" method="POST" class="detail-cart-form">
                            @csrf
                            <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                            <div class="qty-selector">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity"
                                       value="1" min="1" max="{{ $product->Stock }}">
                            </div>
                            <button type="submit" class="btn-add-to-cart">ADD TO CART</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-add-to-cart">LOGIN TO BUY</a>
                    @endauth
                @else
                    <p class="out-of-stock-msg">This item is currently out of stock.</p>
                @endif

            </div>
        </div>

    </main>

</body>
</html>