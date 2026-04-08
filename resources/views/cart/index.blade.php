<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/userAccount.css">
    {{-- TODO: Frontend dev links cart.css here --}}
    <title>My Cart - New Century Books</title>
</head>
<body>

    <header class="header">
        <a href="{{ route('catalog.index') }}">
            <img class="logo" src="/images/Logo(1).png" alt="NCB Logo">
        </a>
        <div class="navigation">
            <p>{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }}</p>
            <a href="{{ route('account.orders') }}">
                <img src="/images/User.png" alt="My Account">
            </a>
            <a href="{{ route('cart.index') }}">
                <img src="/images/cart.png" alt="Cart">
            </a>
        </div>
    </header>

    <nav class="navbar">
        <div class="navlinks-container">
            <a href="{{ route('catalog.index') }}" class="nav-link">← Continue Shopping</a>
        </div>
    </nav>

    <main class="cart-main">
        <h1 class="cart-title">My Cart</h1>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if(!$cart || $cart->items->isEmpty())
            {{-- Empty cart state --}}
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="{{ route('catalog.index') }}" class="btn-link">Browse Books →</a>
            </div>
        @else
            <div class="cart-layout">

                {{-- ================================
                     LEFT: Cart Items List
                     ================================ --}}
                <div class="cart-items-section">
                    <p class="cart-item-count">{{ $cart->items->count() }} item(s)</p>

                    @foreach($cart->items as $item)
                        <div class="cart-item">

                            {{-- Book cover --}}
                            <div class="cart-item-cover">
                                <div class="cart-cover-placeholder">📖</div>
                            </div>

                            {{-- Book info --}}
                            <div class="cart-item-info">
                                <h3 class="cart-item-title">{{ $item->product->Title }}</h3>
                                <p class="cart-item-author">{{ $item->product->Author }}</p>
                                <p class="cart-item-price">₱{{ number_format($item->unitPrice, 2) }} each</p>
                            </div>

                            {{-- Quantity + subtotal + remove --}}
                            <div class="cart-item-controls">
                                {{-- Update quantity --}}
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="qty-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="qty-control">
                                        <input type="number" name="quantity"
                                               value="{{ $item->quantity }}"
                                               min="1"
                                               max="{{ $item->product->Stock }}">
                                        <button type="submit" class="btn-update">Update</button>
                                    </div>
                                </form>

                                <p class="cart-item-subtotal">₱{{ number_format($item->subtotal, 2) }}</p>

                                {{-- Remove item --}}
                                <form action="{{ route('cart.remove', $item) }}" method="POST"
                                      onsubmit="return confirm('Remove this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove">Remove</button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- ================================
                     RIGHT: Order Summary + Checkout
                     ================================ --}}
                <aside class="cart-summary">
                    <h2 class="summary-title">Order Summary</h2>

                    {{-- Price breakdown --}}
                    <div class="summary-breakdown">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($total, 2) }}</span>
                        </div>
                        {{-- Discount row shown after voucher applied (future JS enhancement) --}}
                        <div class="summary-row summary-total-row">
                            <span>Total</span>
                            <strong>₱{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    {{-- Checkout form --}}
                    <form action="{{ route('orders.store') }}" method="POST" class="checkout-form">
                        @csrf

                        {{-- Validation errors --}}
                        @if($errors->any())
                            <div class="alert alert-error">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        {{-- Payment method --}}
                        <div class="checkout-field">
                            <label for="paymentMethod_id">Payment Method</label>
                            <select name="paymentMethod_id" id="paymentMethod_id" required>
                                <option value="">-- Select Payment Method --</option>
                                @foreach(\App\Models\PaymentMethod::all() as $method)
                                    <option value="{{ $method->paymentMethod_id }}"
                                        {{ old('paymentMethod_id') == $method->paymentMethod_id ? 'selected' : '' }}>
                                        {{ $method->methodName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Delivery address --}}
                        <div class="checkout-field">
                            <label>Delivery Address</label>
                            @php $address = Auth::guard('customer')->user()->address; @endphp
                            @if($address)
                                <div class="address-box">
                                    <p>{{ $address->barangay }}, {{ $address->city }}</p>
                                    <p>{{ $address->province }}, {{ $address->country }} {{ $address->zip_postal_code }}</p>
                                </div>
                                <input type="hidden" name="add_id" value="{{ $address->add_id }}">
                                <a href="{{ route('account.addresses') }}" class="btn-link-sm">Change address</a>
                            @else
                                <div class="no-address-warning">
                                    <p>No delivery address set.</p>
                                    <a href="{{ route('account.addresses') }}" class="btn-link">Add address →</a>
                                </div>
                            @endif
                        </div>

                        {{-- Voucher --}}
                        <div class="checkout-field">
                            <label for="voucher_id">Voucher (optional)</label>
                            <select name="voucher_id" id="voucher_id">
                                <option value="">-- No voucher --</option>
                                @foreach(\App\Models\Voucher::all() as $voucher)
                                    <option value="{{ $voucher->voucher_id }}"
                                        {{ old('voucher_id') == $voucher->voucher_id ? 'selected' : '' }}>
                                        {{ $voucher->voucherName }}
                                        ({{ $voucher->voucherType === 'percentage'
                                            ? $voucher->voucherAmount . '% off'
                                            : '₱' . number_format($voucher->voucherAmount, 2) . ' off' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn-place-order">PLACE ORDER</button>
                    </form>
                </aside>

            </div>
        @endif
    </main>

</body>
</html>