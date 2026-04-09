<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Books - NCB</title>
</head>
<body>

    @include('partials.header')

    {{-- Flash --}}
    @if(session('success'))
        <div class="mx-[261px] mt-4 bg-green-100 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter bar --}}
    <form action="{{ route('catalog.index') }}" method="GET"
          class="w-[1195px] h-full bg-white border border-gray-400 mx-auto mt-[20px]">
        <div class="grid grid-cols-4 p-7 gap-4">

            {{-- Genre filter --}}
            <div class="relative w-full">
                <button id="genre-btn" type="button"
                    class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer">
                    <span id="genre-btn-text" class="truncate pointer-events-none">
                        {{ request('genre') ? strtoupper(request('genre')) : 'GENRE' }}
                    </span>
                </button>
                <div id="genre-popup"
                    class="hidden absolute top-full left-0 mt-1 w-[120%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-4">
                        @foreach($genres as $genre)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="genre" value="{{ $genre }}"
                                    {{ request('genre') === $genre ? 'checked' : '' }}
                                    class="cursor-pointer" />
                                <span class="text-white font-bold uppercase tracking-wide">
                                    {{ $genre }}
                                </span>
                            </label>
                        @endforeach
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="genre" value=""
                                {{ !request('genre') ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">All</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Search box spanning remaining columns --}}
            <div class="col-span-2 flex items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by title or author..."
                    class="w-full px-4 py-2 border border-[#ED1B24] focus:outline-none" />
            </div>

            <button type="submit"
                class="bg-[#ED1B24] text-white font-bold py-2 px-4 hover:bg-[#FCAE42] hover:text-black transition-colors">
                Filter Results
            </button>
        </div>
    </form>

    {{-- Results heading --}}
    <div class="w-[1195px] mx-auto mt-6 mb-4 flex justify-between items-center">
        <p class="text-xl text-black/60">
            @if(request('search'))
                Results for "{{ request('search') }}"
            @elseif(request('genre'))
                {{ request('genre') }}
            @else
                All Books
            @endif
            <span class="text-sm">({{ $products->total() }} found)</span>
        </p>
        @if(request('search') || request('genre'))
            <a href="{{ route('catalog.index') }}" class="text-[#ED1B24] text-sm">Clear filters</a>
        @endif
    </div>

    {{-- Product grid --}}
    <div class="w-[1195px] mx-auto">
        @if($products->isEmpty())
            <div class="py-20 text-center text-gray-500">
                <p class="text-[24px]">No books found.</p>
                <a href="{{ route('catalog.index') }}" class="text-[#ED1B24] mt-4 inline-block">View all books</a>
            </div>
        @else
            <div class="grid grid-cols-4 gap-6 mt-4">
                @foreach($products as $product)
                    <div class="flex flex-col border border-gray-200 shadow-sm hover:shadow-md transition-shadow">

                        {{-- Cover --}}
                        <a href="{{ route('catalog.show', $product) }}" class="block">
                            <div class="w-full h-[220px] bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img src="/images/SampleBook.png" alt="{{ $product->Title }}"
                                    class="h-full object-cover" />
                            </div>
                        </a>

                        {{-- Info --}}
                        <a href="{{ route('catalog.show', $product) }}" class="flex flex-col flex-1 p-3">
                            <p class="font-bold text-black text-[15px] leading-tight mb-1 line-clamp-2">
                                {{ $product->Title }}
                            </p>
                            <p class="text-gray-500 text-[13px] mb-1">{{ $product->Author }}</p>
                            <p class="text-gray-400 text-[12px] mb-2">{{ $product->Genre }}</p>

                            @if($product->Rating)
                                <div class="flex items-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <img src="{{ $i <= round($product->Rating) ? '/images/StarVal.png' : '/images/StarNone.png' }}"
                                            alt="" class="w-4 h-4" />
                                    @endfor
                                </div>
                            @endif

                            <p class="text-black font-bold text-[18px] mt-auto">
                                ₱{{ number_format($product->Price, 2) }}
                            </p>

                            @if($product->Stock <= 5)
                                <p class="text-[#ED1B24] text-[12px] mt-1">Only {{ $product->Stock }} left!</p>
                            @endif
                        </a>

                        {{-- Add to cart --}}
                        @auth('customer')
                            <form action="{{ route('cart.add') }}" method="POST" class="p-3 pt-0">
                                @csrf
                                <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    class="w-full bg-white border border-[#FCAE42] text-black font-bold py-2 hover:bg-[#FCAE42] transition-colors text-[14px]">
                                    ADD TO CART
                                </button>
                            </form>
                        @else
                            <div class="p-3 pt-0">
                                <a href="{{ route('login') }}"
                                    class="block w-full text-center bg-white border border-[#FCAE42] text-black font-bold py-2 hover:bg-[#FCAE42] transition-colors text-[14px]">
                                    LOGIN TO BUY
                                </a>
                            </div>
                        @endauth
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8 mb-8">{{ $products->links() }}</div>
        @endif
    </div>

    @include('partials.footer')

</body>
<script>
    // Genre dropdown toggle
    const genreBtn = document.getElementById('genre-btn');
    const genrePopup = document.getElementById('genre-popup');
    genreBtn.addEventListener('click', () => genrePopup.classList.toggle('hidden'));
    document.addEventListener('click', (e) => {
        if (!genreBtn.contains(e.target) && !genrePopup.contains(e.target)) {
            genrePopup.classList.add('hidden');
        }
    });
</script>
</html>