<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>{{ $product->Title }} - NCB</title>
  </head>

  <body class="bg-white flex flex-col min-h-screen">
    
    @include('partials.header')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mx-[282px] mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mx-[282px] mt-[50px] mb-7">
      <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 text-[20px] ml-15 text-black hover:text-[#F54E4E] transition-colors w-max">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Back to Catalog
      </a>
    </div>

    <div class="flex justify-center mx-[282px]">
      
      {{-- Left: Book Image --}}
      <div class="w-[384px] h-[512px] ml-15 mr-10 flex-shrink-0 border border-gray-200 shadow-sm">
        <img src="{{ asset('images/SampleBook.png') }}" alt="{{ $product->Title }}" class="w-full h-full object-cover" />
      </div>

      {{-- Right: Book Info --}}
      <div class="flex flex-col flex-1">
        <p class="text-[40px] font-bold leading-tight">{{ $product->Title }}</p>
        <p class="text-[20px] mt-2">By {{ $product->Author }}</p>
        
        {{-- Dynamic Stars --}}
        <div class="flex items-center mt-4 w-[125px]">
            @php $currentRating = round($product->Rating ?? 5); @endphp
            @for($i = 1; $i <= 5; $i++)
                <img src="{{ asset($i <= $currentRating ? 'images/StarVal.png' : 'images/StarNone.png') }}" alt="Star" class="w-[20px] h-[20px] mr-1" />
            @endfor
        </div>
        
        <p class="text-[27px] font-semibold mt-5">₱{{ number_format($product->Price, 2) }}</p>
        
        {{-- Add to Cart Logic --}}
        @if($product->Stock > 0)
            @auth('customer')
                <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                    @csrf
                    <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                    <input type="hidden" name="quantity" id="input-qty" value="1">
                    
                    <p class="text-[20px]">Quantity</p>
                    <div class="flex justify-start items-center mt-[10px]">
                      <div class="bg-white border border-[#FCAE42] border-2 w-[45px] h-[40px] flex items-center justify-center">
                        <button type="button" id="minusBtn" class="hover:cursor-pointer w-full h-full flex items-center justify-center">
                          <span class="text-black text-[30px] font-bold leading-none mb-1">-</span>
                        </button>
                      </div>

                      <div class="bg-[#FCAE42] w-[85px] h-[55px] text-[24px] font-bold flex items-center justify-center">
                        <p id="quantityDisplay">1</p>
                      </div>

                      <div class="bg-white border border-[#FCAE42] border-2 w-[45px] h-[40px] flex items-center justify-center">
                        <button type="button" id="plusBtn" class="hover:cursor-pointer w-full h-full flex items-center justify-center">
                          <span class="text-black text-[30px] font-bold leading-none mb-1">+</span>
                        </button>
                      </div>
                    </div>

                    {{-- I added this Add to Cart button so users can actually submit the form! --}}
                    <button type="submit" class="bg-[#FCAE42] text-black font-bold text-[20px] py-3 px-10 mt-6 w-[250px] hover:bg-yellow-500 transition-colors cursor-pointer">
                        Add to Cart
                    </button>
                </form>
            @else
                <div class="mt-8">
                    <p class="text-[20px] text-gray-600 mb-2">Login required to purchase.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-gray-200 border-2 border-gray-400 h-[50px] w-[250px] text-[20px] font-bold hover:bg-gray-300 transition-colors">
                        Log in to Buy
                    </a>
                </div>
            @endauth
        @else
            <p class="text-[#ED1B24] font-bold text-[26px] mt-8">Out of Stock</p>
        @endif

        <p class="mt-16 mb-6 text-[24px] text-[#5D5454]">Description</p>
        <p class="text-[20px] text-black text-justify">
          {{ $product->Review ?? 'No description available for this book.' }}
        </p>
        
        <p class="mt-10 text-[24px] text-[#5D5454]">Product Details</p>
        
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
          <p class="text-[22px] pr-8 font-bold {{ $product->Stock > 0 ? 'text-green-600' : 'text-[#ED1B24]' }}">
            {{ $product->Stock > 0 ? $product->Stock : 'Out of Stock' }}
          </p>
        </div>
      </div>
    </div>

    <div class="mx-[282px] mt-24 mb-20">
      <p class="text-[28px] font-bold text-[#5D5454] border-b border-gray-300 pb-2">
        Customer Reviews
      </p>

      {{-- Write a Review Form --}}
      <div class="mt-8 border border-gray-300 p-6 bg-white shadow-sm">
        @auth('customer')
            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                {{-- Hidden input to store the star rating value --}}
                <input type="hidden" name="rating" id="review-rating-input" value="0">
                
                <div class="mb-4">
                  <p class="font-bold text-[18px]">Write a Review</p>
                  
                  {{-- Interactive Stars --}}
                  <div class="flex items-center gap-1 mt-2 cursor-pointer" id="interactiveRating">
                    <img src="{{ asset('images/StarNone.png') }}" alt="1 star" class="review-star w-[24px] h-[24px]" data-value="1" />
                    <img src="{{ asset('images/StarNone.png') }}" alt="2 stars" class="review-star w-[24px] h-[24px]" data-value="2" />
                    <img src="{{ asset('images/StarNone.png') }}" alt="3 stars" class="review-star w-[24px] h-[24px]" data-value="3" />
                    <img src="{{ asset('images/StarNone.png') }}" alt="4 stars" class="review-star w-[24px] h-[24px]" data-value="4" />
                    <img src="{{ asset('images/StarNone.png') }}" alt="5 stars" class="review-star w-[24px] h-[24px]" data-value="5" />
                  </div>
                </div>

                <textarea name="review_text" class="w-full h-[120px] p-4 border border-gray-300 rounded-sm focus:outline-none focus:border-[#FCAE42] resize-none text-[18px]" placeholder="Share your thoughts about this book..."></textarea>

                <div class="flex justify-end mt-4">
                  <button type="button" onclick="alert('Review submission logic will be implemented here.')" class="bg-[#FCAE42] text-black font-bold text-[18px] py-2 px-8 hover:bg-yellow-500 transition-colors cursor-pointer">
                    Submit
                  </button>
                </div>
            </form>
        @else
            <div class="py-4 text-center">
                <p class="text-gray-600 text-[18px]">
                    Please <a href="{{ route('login') }}" class="text-[#ED1B24] hover:underline font-bold">log in</a> to write a review.
                </p>
            </div>
        @endauth
      </div>

      {{-- Static Placeholder Review --}}
      <div class="mt-10 border-b border-gray-300 pb-6">
        <div class="flex items-start justify-between">
          <div>
            <p class="font-bold text-[18px]">Jane Smith</p>
            <div class="flex items-center mt-1">
              <img src="{{ asset('images/StarVal.png') }}" class="w-[18px] h-[18px]" alt="Star" />
              <img src="{{ asset('images/StarVal.png') }}" class="w-[18px] h-[18px]" alt="Star" />
              <img src="{{ asset('images/StarVal.png') }}" class="w-[18px] h-[18px]" alt="Star" />
              <img src="{{ asset('images/StarVal.png') }}" class="w-[18px] h-[18px]" alt="Star" />
              <img src="{{ asset('images/StarNone.png') }}" class="w-[18px] h-[18px]" alt="Empty Star" />
            </div>
          </div>
          <span class="text-gray-500 text-[16px]">2 days ago</span>
        </div>
        <p class="mt-4 text-[18px] text-black text-justify">
          Great introductory book! It really helped me understand the basics
          without getting too overwhelmed with the math. Highly recommend for
          non-majors who want to grasp civil engineering concepts.
        </p>
      </div>
    </div>

    @include('partials.footer')

    <script>
      document.addEventListener("DOMContentLoaded", () => {
          
          // --- Dynamic Quantity Logic ---
          const minusBtn = document.getElementById("minusBtn");
          const plusBtn = document.getElementById("plusBtn");
          const quantityDisplay = document.getElementById("quantityDisplay");
          const inputQty = document.getElementById("input-qty");
          
          const maxStock = {{ $product->Stock ?? 0 }};
          let quantity = 1;

          // If out of stock, zero everything out
          if(maxStock === 0) {
              quantity = 0;
              if(quantityDisplay) quantityDisplay.textContent = '0';
              if(inputQty) inputQty.value = 0;
          }

          if(plusBtn && minusBtn) {
              plusBtn.addEventListener("click", () => {
                if (quantity < maxStock) {
                    quantity++;
                    quantityDisplay.textContent = quantity;
                    inputQty.value = quantity;
                } else {
                    alert("Only " + maxStock + " items available in stock.");
                }
              });

              minusBtn.addEventListener("click", () => {
                if (quantity > 1) {
                  quantity--;
                  quantityDisplay.textContent = quantity;
                  inputQty.value = quantity;
                }
              });
          }

          // --- Interactive Star Rating Logic ---
          const reviewStars = document.querySelectorAll(".review-star");
          const ratingInput = document.getElementById("review-rating-input");
          
          let selectedRating = 0; 
          const starFilled = "{{ asset('images/StarVal.png') }}";
          const starEmpty = "{{ asset('images/StarNone.png') }}";

          reviewStars.forEach((star) => {
            // Highlight stars on hover
            star.addEventListener("mouseover", function () {
              const hoverValue = this.getAttribute("data-value");
              updateStarVisuals(hoverValue);
            });

            // Revert to clicked rating when mouse leaves
            star.addEventListener("mouseout", function () {
              updateStarVisuals(selectedRating);
            });

            // Set the rating when clicked
            star.addEventListener("click", function () {
              selectedRating = this.getAttribute("data-value");
              if(ratingInput) ratingInput.value = selectedRating; // Update hidden form input
              updateStarVisuals(selectedRating);
            });
          });

          // Function to swap the images based on the rating value
          function updateStarVisuals(ratingValue) {
            reviewStars.forEach((star) => {
              if (star.getAttribute("data-value") <= ratingValue) {
                star.src = starFilled;
              } else {
                star.src = starEmpty;
              }
            });
          }

      });
    </script>
  </body>
</html>