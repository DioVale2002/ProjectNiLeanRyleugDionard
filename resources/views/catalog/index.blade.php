<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Products - NCB</title>
  </head>

  <body class="bg-gray-50 flex flex-col min-h-screen">
    
    @include('partials.header')

    {{-- Success Message for Cart --}}
    @if(session('success'))
        <div class="mx-[237px] mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex mt-[30px] mb-12 mx-[237px]">
      
      {{-- LEFT SIDEBAR: Filters --}}
      <div class="p-4 w-[200px] mr-[40px]">
  <div class="flex mb-5">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
    </svg>
    <p class="font-semibold">SEARCH FILTER</p>
  </div>

  <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
    
    <div class="mb-5 border-b border-gray-200 pb-3">
      <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="category-options">
        Category
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="category-options" class="mt-3 filter-content">
        @foreach($genres ?? [] as $index => $genre)
          <div class="flex items-center mb-2 {{ $index >= 3 ? 'hidden extra-item' : '' }}">
            <input type="checkbox" id="genre-{{ Str::slug($genre) }}" name="genre[]" value="{{ $genre }}" class="mr-2" onchange="this.form.submit()" {{ in_array($genre, request('genre', [])) ? 'checked' : '' }} />
            <label for="genre-{{ Str::slug($genre) }}" class="text-black">{{ $genre }}</label>
          </div>
        @endforeach
        
        @if(isset($genres) && count($genres) > 3)
          <button type="button" class="show-more-btn ml-6 mt-1 flex items-center gap-4 cursor-pointer text-gray-500 hover:text-black">
            <span class="more-text">More</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 more-icon transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
            </svg>
          </button>
        @endif
      </div>
    </div>

    <div class="mb-5 border-b border-gray-200 pb-3">
      <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="price-options">
        Price Range
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="price-options" class="mt-3 filter-content">
        <div class="flex flex-col mb-2">
            <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price</label>
          <input type="number" name="min_price" id="min" placeholder="Min" value="{{ request('min_price') }}" class="w-full px-2 py-1 border border-gray-300 rounded-sm" />
        </div>
        <div class="flex flex-col mb-2">
            <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price</label>
          <input type="number" name="max_price" id="max" placeholder="Max" value="{{ request('max_price') }}" class="w-full px-2 py-1 border border-gray-300 rounded-sm" />
        </div>
        <button type="submit" class="bg-[#FCAE42] text-black border-[#FCAE42] border-2 w-full transition-colors hover:bg-yellow-500 hover:text-white hover:font-bold hover:cursor-pointer mt-1">
          Apply
        </button>
      </div>
    </div>

    <div class="mb-5 border-b border-gray-200 pb-3">
      <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="date-options">
        Publication Date
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="date-options" class="mt-3 filter-content">
        <div class="flex flex-col mb-2">
          <label class="block text-sm font-medium text-gray-700" for="mindate">Min date</label>
          <input type="date" name="min_date" id="mindate" value="{{ request('min_date') }}" class="w-full max-w-full px-1 py-1 text-[13px] border border-gray-300 rounded-sm" />
        </div>
        <div class="flex flex-col mb-2">
          <label class="block text-sm font-medium text-gray-700" for="maxdate">Max date</label>
          <input type="date" name="max_date" id="maxdate" value="{{ request('max_date') }}" class="w-full max-w-full px-1 py-1 text-[13px] border border-gray-300 rounded-sm" />
        </div>
        <button type="submit" class="bg-[#FCAE42] text-black border-[#FCAE42] border-2 w-full transition-colors hover:bg-yellow-500 hover:text-white hover:font-bold hover:cursor-pointer mt-1">
          Apply
        </button>
      </div>
    </div>

    <div class="mb-5 border-b border-gray-200 pb-3">
      <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="rating-options">
        Rating
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="rating-options" class="mt-3 filter-content">
        @for($i = 5; $i >= 1; $i--)
          <label class="flex items-center gap-2 cursor-pointer mt-1">
            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" onchange="this.form.submit()" {{ request('rating') == $i ? 'checked' : '' }} />
            <div class="px-2 py-1 flex gap-2 items-center rounded-md peer-checked:bg-[#FCAE42] transition-colors w-[130px]">
              @for($j = 1; $j <= 5; $j++)
                  <img src="{{ asset($j <= $i ? 'images/StarVal.png' : 'images/StarNone.png') }}" class="w-[14px] h-[14px]" />
              @endfor
            </div>
          </label>
        @endfor
      </div>
    </div>

    <div class="mb-5 border-b border-gray-200 pb-3">
      <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="language-options">
        Language
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="language-options" class="mt-3 filter-content">
        <div class="flex items-center mb-2">
          <input type="checkbox" id="English" name="language[]" value="English" class="mr-2" onchange="this.form.submit()" {{ in_array('English', request('language', [])) ? 'checked' : '' }} />
          <label for="English" class="text-black">English</label>
        </div>
        <div class="flex items-center mb-2">
          <input type="checkbox" id="Tagalog" name="language[]" value="Tagalog" class="mr-2" onchange="this.form.submit()" {{ in_array('Tagalog', request('language', [])) ? 'checked' : '' }} />
          <label for="Tagalog" class="text-black">Tagalog</label>
        </div>
        <div class="flex items-center mb-2">
          <input type="checkbox" id="Bisaya" name="language[]" value="Bisaya" class="mr-2" onchange="this.form.submit()" {{ in_array('Bisaya', request('language', [])) ? 'checked' : '' }} />
          <label for="Bisaya" class="text-black">Bisaya</label>
        </div>
      </div>
    </div>

    <div class="mb-5 border-b border-gray-200 pb-3">
      <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="format-options">
        Format
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="format-options" class="mt-3 filter-content">
        <div class="flex items-center mb-2">
          <input type="checkbox" id="Paperback" name="format[]" value="Paperback" class="mr-2" onchange="this.form.submit()" {{ in_array('Paperback', request('format', [])) ? 'checked' : '' }} />
          <label for="Paperback" class="text-black">Paperback</label>
        </div>
        <div class="flex items-center mb-2">
          <input type="checkbox" id="Ebook" name="format[]" value="Ebook" class="mr-2" onchange="this.form.submit()" {{ in_array('Ebook', request('format', [])) ? 'checked' : '' }} />
          <label for="Ebook" class="text-black">Ebook</label>
        </div>
        <div class="flex items-center mb-2">
          <input type="checkbox" id="Hardcover" name="format[]" value="Hardcover" class="mr-2" onchange="this.form.submit()" {{ in_array('Hardcover', request('format', [])) ? 'checked' : '' }} />
          <label for="Hardcover" class="text-black">Hardcover</label>
        </div>
        
        <button type="button" class="ml-6 mt-1 flex items-center gap-4 cursor-pointer text-gray-500">
          More
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
          </svg>
        </button>
      </div>
    </div>

    {{-- DYNAMIC AGE GROUP FILTER--}}
    <div class="mb-5 border-b border-gray-200 pb-3">
    <button type="button" class="filter-toggle flex justify-between items-center w-full font-semibold text-black cursor-pointer" aria-controls="age-options">
        Age Group
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div id="age-options" class="mt-3 filter-content">
        @foreach($ageGroups ?? [] as $index => $age)
        <div class="flex items-center mb-2 {{ $index >= 3 ? 'hidden extra-item' : '' }}">
            <input type="checkbox" id="age-{{ Str::slug($age) }}" name="agegroup[]" value="{{ $age }}" class="mr-2 cursor-pointer" onchange="this.form.submit()" {{ in_array($age, request('agegroup', [])) ? 'checked' : '' }} />
            <label for="age-{{ Str::slug($age) }}" class="text-black cursor-pointer">{{ $age }}</label>
        </div>
        @endforeach

        @if(isset($ageGroups) && count($ageGroups) > 3)
        <button type="button" class="show-more-btn ml-6 mt-1 flex items-center gap-4 cursor-pointer text-gray-700 hover:text-black hover:font-bold">
            <span class="more-text">More</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 more-icon transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
            </svg>
        </button>
        @endif
    </div>
    </div>

    <a href="{{ route('catalog.index') }}" class="block text-center bg-[#F54E4E] text-white border-[#FCAE42] mt-6 border-2 py-2 w-full transition-colors hover:bg-red-600 hover:text-white hover:font-bold hover:cursor-pointer">
      Clear All
    </a>
  </form>
</div>

      {{-- RIGHT SIDE: Products Grid --}}
      <div class="flex-1">
        <p class="text-[30px] font-bold text-black mb-7">Suggested Books</p>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
          @forelse($products as $product)
            {{-- Product Card --}}
            <div class="h-full w-[265px] border border-gray-400 shadow-md relative hover:border-[#F54E4E] transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl bg-white flex flex-col">
              <a href="{{ route('catalog.show', $product) }}">
                <div class="flex justify-center items-center pt-6">
                  <img src="{{ asset('images/SampleBook.png') }}" alt="{{ $product->Title }}" class="w-[180px] h-[240px] object-cover" />
                </div>
                <p class="text-black text-[15px] mt-[18px] mx-[20px] font-bold h-10 overflow-hidden leading-tight">
                  {{ $product->Title }}
                </p>
                <p class="text-gray-500 text-[11px] mt-[7px] mx-[20px] truncate">
                  {{ $product->Author }}
                </p>
              </a>
              
              {{-- Dynamic Stars --}}
              <div class="flex items-center mt-2 mx-[20px]">
                @php $currentRating = round($product->Rating ?? 5); @endphp
                @for($i = 1; $i <= 5; $i++)
                    <img src="{{ asset($i <= $currentRating ? 'images/StarVal.png' : 'images/StarNone.png') }}" alt="Star" class="w-[14px] h-[14px]" />
                @endfor
              </div>
              
              <p class="text-black text-[15px] font-bold mt-[10px] mx-[20px]">
                ₱ {{ number_format($product->Price, 2) }}
              </p>
              
              {{-- Add to Cart Form --}}
              <div class="w-full flex justify-center px-[22px] py-[9px] mb-4 mt-auto">
                <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="bg-white text-black border-[#FCAE42] border-2 w-full py-[10px] transition-colors hover:bg-[#FCAE42] hover:text-white hover:font-bold hover:cursor-pointer">
                        ADD TO CART
                    </button>
                </form>
              </div>

              {{-- Sale Tag --}}
              <div class="bg-[#FCAE42] absolute top-11 left-0 shadow-sm">
                <p class="text-[13px] text-center font-bold px-3 py-[2px]">New Arrival</p>
              </div>
            </div>
          @empty
            <div class="col-span-4 py-20 text-center">
                <p class="text-gray-500 text-xl font-bold">No books found matching your filters.</p>
            </div>
          @endforelse
        </div>
        
        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
      </div>
    </div>

    @include('partials.footer')

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        
        // 1. Dropdown Accordion Logic
        const toggleButtons = document.querySelectorAll(".filter-toggle");
        toggleButtons.forEach((button) => {
          button.addEventListener("click", () => {
            const targetId = button.getAttribute("aria-controls");
            const targetContent = document.getElementById(targetId);
            const icon = button.querySelector("svg");

            targetContent.classList.toggle("hidden");
            icon.classList.toggle("rotate-180");
          });
        });

        // 2. Show More / Show Less Logic (Only applies to dynamic lists like Genre)
        const showMoreBtns = document.querySelectorAll('.show-more-btn');
        showMoreBtns.forEach((btn) => {
          btn.addEventListener('click', function(e) {
            e.preventDefault(); 
            
            const container = this.closest('.filter-content');
            const extraItems = container.querySelectorAll('.extra-item');
            const moreText = this.querySelector('.more-text');
            const moreIcon = this.querySelector('.more-icon');

            let isExpanded = false;

            extraItems.forEach((item) => {
              item.classList.toggle('hidden');
              if (!item.classList.contains('hidden')) {
                isExpanded = true;
              }
            });

            if (isExpanded) {
              moreText.textContent = 'Less';
              moreIcon.classList.add('rotate-180');
            } else {
              moreText.textContent = 'More';
              moreIcon.classList.remove('rotate-180');
            }
          });
        });

      });
    </script>
  </body>
</html>