<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    {{-- TODO: Frontend dev links catalog.css here --}}
    <title>Catalog - New Century Books</title>
</head>
<body>

    {{-- ================================
         HEADER
         Same structure as account pages
         ================================ --}}
    <header class="header">
        <a href="{{ route('catalog.index') }}">
            <img class="logo" src="/images/Logo(1).png" alt="NCB Logo">
        </a>

        {{-- Search bar --}}
        <form action="{{ route('catalog.index') }}" method="GET" class="search-form">
            <input class="search-bar" type="text" name="search"
                   placeholder="Search books, authors..."
                   value="{{ request('search') }}">
            @if(request('genre'))
                <input type="hidden" name="genre" value="{{ request('genre') }}">
            @endif
            <button type="submit" class="search-btn">Search</button>
        </form>

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

    {{-- ================================
         NAV - Genre filter tabs
         Active class = currently selected genre
         ================================ --}}
    <nav class="navbar">
        <div class="navlinks-container">
            <a href="{{ route('catalog.index') }}"
               class="nav-link {{ !request('genre') && !request('search') ? 'active' : '' }}">
                ALL BOOKS
            </a>
            @foreach($genres as $genre)
                <a href="{{ route('catalog.index', ['genre' => $genre]) }}"
                   class="nav-link {{ request('genre') === $genre ? 'active' : '' }}">
                    {{ strtoupper($genre) }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- ================================
         MAIN CONTENT
         ================================ --}}
    <main class="catalog-main">

        {{-- Page heading - changes based on context --}}
        <div class="catalog-header">
            <h1 class="catalog-title">
                @if(request('search'))
                    Results for "{{ request('search') }}"
                @elseif(request('genre'))
                    {{ request('genre') }}
                @else
                    All Books
                @endif
            </h1>
            <p class="catalog-count">{{ $products->total() }} book(s) found</p>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($products->isEmpty())
            {{-- Empty state --}}
            <div class="empty-state">
                <p>No books found.</p>
                <a href="{{ route('catalog.index') }}" class="btn-link">View all books →</a>
            </div>
        @else
            {{-- ================================
                 PRODUCT GRID
                 Each card = one book
                 Figma reference: book card with cover, title, author, price, Add to Cart
                 ================================ --}}
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">

                        {{-- Book cover image area
                             TODO: replace placeholder with actual cover image
                             when image upload is implemented --}}
                        <a href="{{ route('catalog.show', $product) }}" class="product-card-link">
                            <div class="product-cover">
                                {{-- Placeholder until image upload is built --}}
                                <div class="product-cover-placeholder">📖</div>
                                {{-- Future: <img src="{{ $product->cover_image }}" alt="{{ $product->Title }}"> --}}

                                {{-- Low stock badge --}}
                                @if($product->Stock <= 5)
                                    <span class="badge badge-low-stock">Only {{ $product->Stock }} left</span>
                                @endif
                            </div>

                            <div class="product-card-body">
                                <h3 class="product-title">{{ $product->Title }}</h3>
                                <p class="product-author">{{ $product->Author }}</p>
                                <p class="product-genre">{{ $product->Genre }}</p>

                                {{-- Rating stars placeholder --}}
                                @if($product->Rating)
                                    <p class="product-rating">★ {{ number_format($product->Rating, 1) }}</p>
                                @endif

                                <p class="product-price">₱{{ number_format($product->Price, 2) }}</p>
                            </div>
                        </a>

                        {{-- Add to Cart — directly on card, matches Figma --}}
                        @auth('customer')
                            <form action="{{ route('cart.add') }}" method="POST" class="card-cart-form">
                                @csrf
                                <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-to-cart">ADD TO CART</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-add-to-cart btn-login-to-buy">
                                LOGIN TO BUY
                            </a>
                        @endauth

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @endif

    </main>

</body>
</html>