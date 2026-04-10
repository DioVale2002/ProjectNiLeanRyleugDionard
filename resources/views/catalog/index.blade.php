<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Books - NCB</title>
</head>
<body class="bg-gray-50">

    @include('partials.header')

    {{-- Messages --}}
    @if(session('success'))
        <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main content area --}}
    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8">

        {{-- Filters section --}}
        <form action="{{ route('catalog.index') }}" method="GET" class="mb-8">
            <div class="w-[1195px] h-full bg-white border border-gray-400 mx-auto">
                <div class="grid grid-cols-8 p-7 gap-4">
                    {{-- Genre filter --}}
                    <div class="relative w-full">
                        <button type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer">
                            <span class="truncate pointer-events-none">GENRE</span>
                        </button>
                    </div>

                    {{-- Price filter --}}
                    <div class="relative w-full">
                        <input
                            type="number"
                            name="min_price"
                            value="{{ request('min_price') }}"
                            placeholder="MIN PRICE"
                            min="0"
                            class="w-full px-2 py-2 text-[#ED1B24] bg-white border border-[#ED1B24] flex justify-center items-center text-center font-bold"
                        />
                    </div>

                    {{-- Max price --}}
                    <div class="relative w-full">
                        <input
                            type="number"
                            name="max_price"
                            value="{{ request('max_price') }}"
                            placeholder="MAX PRICE"
                            min="0"
                            class="w-full px-2 py-2 text-[#ED1B24] bg-white border border-[#ED1B24] flex justify-center items-center text-center font-bold"
                        />
                    </div>

                    {{-- Search box --}}
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="SEARCH"
                            class="w-full px-2 py-2 text-[#ED1B24] bg-white border border-[#ED1B24] text-center font-bold"
                        />
                    </div>

                    {{-- Remaining filter placeholders --}}
                    <div class="relative w-full">
                        <button type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer">
                            <span class="truncate pointer-events-none">FORMAT</span>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <button type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer">
                            <span class="truncate pointer-events-none">RATING</span>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <button type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer">
                            <span class="truncate pointer-events-none">AGE</span>
                        </button>
                    </div>

                    {{-- FILTER Button --}}
                    <button type="submit" class="text-white bg-[#ED1B24] font-bold px-2 py-2 w-full border border-[#ED1B24] flex items-center justify-center gap-2 hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        FILTER
                    </button>
                </div>
            </div>
        </form>

        {{-- Results header --}}
        <div class="mb-6 mt-8">
            <p class="text-[35px] font-bold text-black">Suggested Books</p>
        </div>

        {{-- Product grid --}}
        @if($products->isEmpty())
            <div class="text-center py-16">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No books found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your filters or search terms</p>
                <a href="{{ route('catalog.index') }}" class="inline-block bg-[#ED1B24] text-white font-semibold px-6 py-2 rounded-lg hover:bg-red-700 transition">
                    View All Books
                </a>
            </div>
        @else
            <div class="grid grid-cols-5 gap-y-8 mb-6 px-[237px]">
                @foreach($products as $product)
                    <div class="h-full w-[265px] border border-gray-400 shadow-md relative">
                        {{-- Product image --}}
                        <a href="{{ route('catalog.show', $product) }}" class="block">
                            <div class="flex justify-center items-center pt-6">
                                <img src="/images/SampleBook.png" alt="{{ $product->Title }}" class="h-40 object-contain" />
                            </div>
                        </a>

                        {{-- Product info --}}
                        <div class="p-4 flex flex-col">
                            <div class="bg-[#FCAE42] w-[97px] mb-2">
                                <p class="text-[13px] font-bold px-[7px] py-[2px]">BESTSELLERS</p>
                            </div>

                            <a href="{{ route('catalog.show', $product) }}" class="block">
                                <p class="text-black text-[15px] overflow-hidden line-clamp-2">
                                    {{ $product->Title }}
                                </p>
                            </a>
                            
                            <p class="text-black text-[11px] mt-[7px]">{{ $product->Author }}</p>

                            {{-- Rating --}}
                            @if($product->Rating)
                                <div class="flex items-center mt-1 ml-0 w-[125px]">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($product->Rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            @endif

                            {{-- Price --}}
                            <p class="text-black text-[15px] font-bold mt-[10px]">₱ {{ number_format($product->Price, 2) }}</p>

                            {{-- Button --}}
                            <div class="w-full flex justify-center px-[22px] py-[9px] mt-auto">
                                @auth('customer')
                                    @if($product->Stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="bg-white text-black border-[#FCAE42] border-2 w-full py-[10px] transition-colors hover:bg-[#FCAE42] hover:text-white hover:font-bold hover:cursor-pointer">
                                                ADD TO CART
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" disabled class="bg-white text-gray-400 border-[#FCAE42] border-2 w-full py-[10px] cursor-not-allowed">
                                            OUT OF STOCK
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="bg-white text-black border-[#FCAE42] border-2 w-full py-[10px] text-center transition-colors hover:bg-[#FCAE42] hover:text-white hover:font-bold">
                                        ADD TO CART
                                    </a>
                                @endauth
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>

    @include('partials.footer')

</body>
</html>