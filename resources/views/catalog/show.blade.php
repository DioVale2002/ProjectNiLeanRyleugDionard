<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $product->Title }} - NCB</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    @include('partials.header')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mx-[282px] mt-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 gap-x-6 mt-[103px] mx-[282px]">
        {{-- Left: Book Image --}}
        <div class="flex justify-center items-center mr-5">
            <img class="w-[384px] h-[507px] object-cover" src="{{ asset('images/SampleBook.png') }}" alt="{{ $product->Title }}" />
        </div>
        
        {{-- Right: Book Info --}}
        <div>
            {{-- Title & Price Box (Exact Figma Design) --}}
            <div class="border border-black border-2 shadow shadow-black w-full pb-[16px] pt-[16px]">
                <p class="text-black text-[40px] ml-[16px] mb-[16px] line-clamp-2 text-overflow-ellipsis leading-tight">
                    {{ $product->Title }}
                </p>
                <p class="text-black text-[20px] ml-[16px]">By {{ $product->Author }}</p>
                
                {{-- Dynamic Stars --}}
                <div class="flex items-center mt-5 w-[125px] ml-[16px]">
                    @php $currentRating = round($product->Rating ?? 5); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $currentRating)
                            <img src="{{ asset('images/StarVal.png') }}" class="w-5 h-5" alt="Star" />
                        @else
                            <img src="{{ asset('images/StarNone.png') }}" class="w-5 h-5" alt="Empty Star" />
                        @endif
                    @endfor
                </div>

                <p class="text-black text-[36px] ml-[16px] mt-[30px] font-bold">₱{{ number_format($product->Price, 2) }}</p>
            </div>
            
            {{-- Add to Cart Logic --}}
            @if($product->Stock > 0)
                @auth('customer')
                    <p class="text-[24px] text-[#5D5454] mt-[47px]">Quantity:</p>
                    
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                        <input type="hidden" name="quantity" id="input-qty" value="1">
                        
                        {{-- Quantity Selector --}}
                        <div class="flex justify-start items-center mt-[20px]">
                            <div class="bg-white border border-[#FCAE42] border-2 w-[45px] h-[40px] flex items-center justify-center">
                                <button type="button" id="btn-minus" class="hover:cursor-pointer w-full h-full flex items-center justify-center">
                                    <span class="text-black text-[30px] font-bold leading-none mb-1">-</span>
                                </button>
                            </div>
                            <div class="bg-[#FCAE42] w-[85px] h-[55px] text-[24px] font-bold flex items-center justify-center">
                                <p id="display-qty">1</p>
                            </div>
                            <div class="bg-white border border-[#FCAE42] border-2 w-[45px] h-[40px] flex items-center justify-center">
                                <button type="button" id="btn-plus" class="hover:cursor-pointer w-full h-full flex items-center justify-center">
                                    <span class="text-black text-[30px] font-bold leading-none mb-1">+</span>
                                </button>
                            </div>
                        </div>

                        {{-- Add to Cart Button --}}
                        <button type="submit" class="bg-[#FCAE42] h-[57.56px] w-[475px] mt-[47px] text-[26px] font-bold hover:bg-[#e09b3b] transition-colors cursor-pointer">
                            Add to Cart
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center justify-center bg-gray-200 border-2 border-gray-400 h-[57.56px] w-[475px] mt-[47px] text-[26px] font-bold hover:bg-gray-300 transition-colors">
                        Log in to Buy
                    </a>
                @endauth
            @else
                <p class="text-[#ED1B24] font-bold text-[26px] mt-[47px]">Out of Stock</p>
            @endif

            {{-- Back to Catalog Link --}}
            <a href="{{ route('catalog.index') }}" class="block mt-[20px] text-[#ED1B24] font-bold text-[20px] hover:underline">
                ← Back to Catalog
            </a>
        </div>
    </div>

    <div class="mt-[164px] mb-[70px] mx-[280px]">
        <p class="text-[24px] text-[#5D5454]">Description</p>
        <p class="text-[24px] text-black mt-3 text-justify">
            {{ $product->Review ?? 'No description available for this book.' }}
        </p>
    </div>

    <div class="mx-[280px] mb-16">
        <p class="text-[24px] text-[#5D5454]">Product Details</p>
        
        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">ISBN</p>
            <p class="text-black text-[22px] pr-8">{{ $product->ISBN ?? 'N/A' }}</p>
        </div>

        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">Publisher</p>
            <p class="text-black text-[22px] pr-8">{{ $product->Publisher ?? 'N/A' }}</p>
        </div>

        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">Genre</p>
            <p class="text-black text-[22px] pr-8">{{ $product->Genre ?? 'N/A' }}</p>
        </div>

        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">Age Group</p>
            <p class="text-black text-[22px] pr-8 uppercase">{{ $product->Age_Group ?? 'N/A' }}</p>
        </div>

        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">Length</p>
            <p class="text-black text-[22px] pr-8">{{ $product->Length ?? 'N/A' }} Inches</p>
        </div>

        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">Width</p>
            <p class="text-black text-[22px] pr-8">{{ $product->Width ?? 'N/A' }} Inches</p>
        </div>

        <div class="flex justify-between items-center bg-white border border-black border w-full h-[45px] mt-6">
            <p class="text-black text-[22px] pl-5">Availability</p>
            <p class="text-black text-[22px] pr-8 font-bold {{ $product->Stock > 0 ? 'text-green-600' : 'text-[#ED1B24]' }}">
                {{ $product->Stock > 0 ? $product->Stock : 'Out of Stock' }}
            </p>
        </div>
    </div>

    <!-- Reviews Section To be Added -->
    <div class="mx-[280px] mb-16">
        <p class="text-[24px] text-black">Reviews</p>
        
        <div class="mt-10 border border-[#ED1B24] border-2 flex w-full h-full">
            <div class="px-6 py-6 w-32">
                <img class="rounded-full w-17 h-17" src="{{ asset('images/DefaultAvatar.jpg') }}" alt="User profile" />
            </div>
            <div>
                <p class="text-black text-[20px] pt-7 font-bold">Lean Adrian Murillo</p>
                <p class="text-gray-500 text-[15px]">2 Days ago</p>
                <div class="flex items-center mt-3 mb-5">
                    @for($i = 0; $i < 5; $i++)
                        <img src="{{ asset('images/StarVal.png') }}" alt="" class="w-4 h-4" />
                    @endfor
                </div>
                <p class="text-black text-justify pr-5 mb-6">
                    The book feels like sitting down to listen to a seasoned architect explain the blueprint of the world. It’s practical, grounded, and yet full of the grand potential that shapes our landscape.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-[280px] mb-32">
        <p class="text-[24px] text-black font-bold mb-6">Write a Review</p>
        
        @auth('customer')
            <form action="#" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                
                <textarea name="review_text" rows="5" 
                    class="w-full border border-[#ED1B24] border-2 p-4 text-[18px] focus:outline-none" 
                    placeholder="What did you think about this book? Share your thoughts..."></textarea>
                
                <button type="button" onclick="alert('Review submission logic will be implemented here.')" 
                    class="bg-[#FCAE42] text-black font-bold text-[20px] py-3 px-10 mt-2 w-[250px] hover:bg-[#e09b3b] transition-colors cursor-pointer">
                    Submit Review
                </button>
            </form>
        @else
            <div class="bg-gray-100 border border-gray-300 p-6 text-center">
                <p class="text-gray-600 text-[20px]">
                    Please <a href="{{ route('login') }}" class="text-[#ED1B24] hover:underline font-bold">log in</a> to leave a review.
                </p>
            </div>
        @endauth
    </div>

        @include('partials.footer')
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnMinus = document.getElementById('btn-minus');
            const btnPlus = document.getElementById('btn-plus');
            const displayQty = document.getElementById('display-qty');
            const inputQty = document.getElementById('input-qty');
            
            const maxStock = {{ $product->Stock ?? 0 }};
            let currentQty = 1;

            if(maxStock === 0) {
                currentQty = 0;
                if(displayQty) displayQty.textContent = '0';
                if(inputQty) inputQty.value = 0;
            }

            if(btnMinus && btnPlus) {
                btnMinus.addEventListener('click', () => {
                    if (currentQty > 1) {
                        currentQty--;
                        displayQty.textContent = currentQty;
                        inputQty.value = currentQty;
                    }
                });

                btnPlus.addEventListener('click', () => {
                    if (currentQty < maxStock) {
                        currentQty++;
                        displayQty.textContent = currentQty;
                        inputQty.value = currentQty;
                    } else {
                        alert("Only " + maxStock + " items available in stock.");
                    }
                });
            }
        });
    </script>

</body>
</html>