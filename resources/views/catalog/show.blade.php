<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>{{ $product->Title }} - NCB</title>
</head>

<body>

    @include('partials.header')

    @if(session('success'))
    <div class="mx-4 md:mx-10 xl:mx-[282px] mt-4 bg-green-100 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    {{-- Book detail --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-6 mt-[50px] xl:mt-[103px] mx-4 md:mx-10 xl:mx-[282px] mb-[80px]">

        {{-- Left: Cover --}}
        <div class="flex justify-center items-start xl:mr-5 mb-8 xl:mb-0">
            <img src="/images/SampleBook.png" alt="{{ $product->Title }}" class="max-h-[500px] object-contain" />
        </div>

        {{-- Right: Info --}}
        <div>
            <div class="border border-black border-2 shadow shadow-black w-full p-4">
                <p class="text-black text-[32px] xl:text-[40px] leading-tight">{{ $product->Title }}</p>
                <p class="text-black text-[18px] xl:text-[20px] mt-2">By {{ $product->Author }}</p>

                @if($product->Rating)
                <div class="flex items-center mt-5">
                    @for($i = 1; $i
                    <= 5; $i++)
                        <img src="{{ $i <= round($product->Rating) ? '/images/StarVal.png' : '/images/StarNone.png' }}"
                        alt="" class="w-5 h-5" />
                    @endfor
                    <span class="ml-2 text-gray-500 text-[14px]">{{ number_format($product->Rating, 1) }}/5</span>
                </div>
                @endif

                <p class="text-black text-[32px] xl:text-[36px] mt-[20px]">₱{{ number_format($product->Price, 2) }}</p>
            </div>

            {{-- Metadata --}}
            <table class="w-full mt-6 text-[16px]">
                <tr class="border-b border-gray-200">
                    <th class="text-left py-2 text-gray-500 w-[140px]">Genre</th>
                    <td class="py-2">{{ $product->Genre }}</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-2 text-gray-500">Publisher</th>
                    <td class="py-2">{{ $product->Publisher }}</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-2 text-gray-500">ISBN</th>
                    <td class="py-2">{{ $product->ISBN }}</td>
                </tr>
                @if($product->Age_Group)
                <tr class="border-b border-gray-200">
                    <th class="text-left py-2 text-gray-500">Age Group</th>
                    <td class="py-2">{{ $product->Age_Group }}</td>
                </tr>
                @endif
                <tr class="border-b border-gray-200">
                    <th class="text-left py-2 text-gray-500">Availability</th>
                    <td class="py-2 {{ $product->Stock > 0 ? 'text-green-600' : 'text-[#ED1B24]' }} font-bold">
                        {{ $product->Stock > 0 ? $product->Stock . ' in stock' : 'Out of Stock' }}
                    </td>
                </tr>
            </table>

            @if($product->Review)
            <p class="text-[#5D5454] text-[16px] mt-6">{{ $product->Review }}</p>
            @endif

            {{-- Add to cart --}}
            @if($product->Stock > 0)
            @auth('customer')
            <p class="text-[24px] text-[#5D5454] mt-[40px] xl:mt-[47px]">Quantity:</p>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                <div class="flex justify-start items-center mt-[20px]">
                    <div class="bg-white border border-[#FCAE42] border-2 w-[45px] h-[40px] flex items-center justify-center">
                        <button type="button" onclick="decrementQty()"
                            class="hover:cursor-pointer w-full text-center text-black text-[30px] font-bold leading-none">-</button>
                    </div>
                    <input id="qty" name="quantity" type="number"
                        class="bg-[#FCAE42] w-[85px] h-[55px] text-[24px] font-bold text-center border-none"
                        value="1" min="1" max="{{ $product->Stock }}" />
                    <div class="bg-white border border-[#FCAE42] border-2 w-[45px] h-[40px] flex items-center justify-center">
                        <button type="button" onclick="incrementQty()"
                            class="hover:cursor-pointer w-full text-center text-black text-[30px] font-bold leading-none">+</button>
                    </div>
                </div>
                <button type="submit"
                    class="mt-6 bg-[#FCAE42] text-black font-bold text-[20px] py-3 px-10 w-full xl:w-auto hover:bg-[#F54E4E] hover:text-white transition-colors">
                    ADD TO CART
                </button>
            </form>
            @else
            <a href="{{ route('login') }}"
                class="inline-block mt-6 bg-[#FCAE42] text-black font-bold text-[20px] py-3 px-10 w-full xl:w-auto text-center hover:bg-[#F54E4E] hover:text-white transition-colors">
                LOGIN TO BUY
            </a>
            @endauth
            @else
            <p class="mt-6 text-[#ED1B24] font-bold text-[20px]">Out of Stock</p>
            @endif

            <a href="{{ route('catalog.index') }}" class="inline-block mt-6 text-[#ED1B24] hover:underline">
                ← Back to Catalog
            </a>
        </div>
    </div>

    @include('partials.footer')

</body>
<script>
    function incrementQty() {
        const input = document.getElementById('qty');
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
    }

    function decrementQty() {
        const input = document.getElementById('qty');
        if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    }
</script>

</html>