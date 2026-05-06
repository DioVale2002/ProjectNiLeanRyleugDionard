<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>{{ $product->Title }} - NCB</title>
  </head>

  <body class="bg-white min-h-screen flex flex-col">
    @include('partials.header')

    @if(session('success'))
      <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8">
      <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-700 hover:text-[#F54E4E] mb-6">
        <span aria-hidden="true"><-</span>
        Back to Catalog
      </a>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-2">
          <div class="rounded-xl border border-gray-200 p-4 shadow-sm bg-white">
            <img
              src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/SampleBook.png') }}"
              alt="{{ $product->Title }}"
              class="w-full h-[420px] object-cover rounded-md"
            />
          </div>
        </div>

        <div class="lg:col-span-3">
          <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $product->Title }}</h1>
          <p class="text-lg text-gray-700 mt-2">By {{ $product->Author }}</p>

          <div class="flex items-center gap-3 mt-4">
            <div class="flex items-center">
              @php $currentRating = round($product->Rating ?? 5); @endphp
              @for($i = 1; $i <= 5; $i++)
                <img src="{{ asset($i <= $currentRating ? 'images/StarVal.png' : 'images/StarNone.png') }}" alt="Star" class="w-4 h-4" />
              @endfor
            </div>
            <span class="text-sm text-gray-500">{{ number_format((float)($product->Rating ?? 0), 1) }}/5</span>
          </div>

          <div class="mt-5 flex items-center gap-3">
            <p class="text-3xl font-semibold text-[#ED1B24]">PHP {{ number_format($product->Price, 2) }}</p>
            @if($product->Stock > 0 && $product->Stock <= 5)
              <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Low stock</span>
            @endif
          </div>

          <div class="mt-6 overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full text-sm">
              <tr class="border-b border-gray-200"><th class="text-left px-4 py-3 w-40 text-gray-500">Genre</th><td class="px-4 py-3">{{ $product->Genre }}</td></tr>
              <tr class="border-b border-gray-200"><th class="text-left px-4 py-3 text-gray-500">Publisher</th><td class="px-4 py-3">{{ $product->Publisher }}</td></tr>
              <tr class="border-b border-gray-200"><th class="text-left px-4 py-3 text-gray-500">ISBN</th><td class="px-4 py-3">{{ $product->ISBN }}</td></tr>
              <tr><th class="text-left px-4 py-3 text-gray-500">Availability</th><td class="px-4 py-3 font-semibold {{ $product->Stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $product->Stock > 0 ? $product->Stock . ' in stock' : 'Out of stock' }}</td></tr>
            </table>
          </div>

          @if($product->Description)
            <div class="mt-6">
              <h2 class="text-sm font-bold text-gray-700 mb-2">Description</h2>
              <p class="text-gray-700 leading-relaxed">{{ $product->Description }}</p>
            </div>
          @endif

          @if($product->Stock > 0)
            @auth('customer')
              <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                @csrf
                <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                <div class="flex items-center gap-3">
                  <label for="qty" class="text-sm font-semibold text-gray-700">Quantity</label>
                  <input id="qty" name="quantity" type="number" min="1" max="{{ $product->Stock }}" value="1" class="w-20 rounded-md border border-gray-300 px-3 py-2" />
                </div>
                <button type="submit" class="mt-4 bg-[#FCAE42] text-black font-bold px-6 py-3 rounded-lg hover:bg-yellow-500 transition-colors">
                  Add to Cart
                </button>
              </form>
            @else
              <div class="mt-8">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-gray-200 border border-gray-300 px-5 py-3 rounded-md text-sm font-bold hover:bg-gray-300">
                  Log in to Buy
                </a>
              </div>
            @endauth
          @else
            <p class="mt-8 text-red-600 font-bold">Out of Stock</p>
          @endif
        </div>
      </div>
    </div>

    @include('partials.footer')
  </body>
</html>
