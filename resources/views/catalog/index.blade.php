<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products - NCB</title>
    @vite(['resources/css/app.css'])

</head>
<body class="bg-gray-50">

    @include('partials.header')

    <form action="{{ route('catalog.index') }}" method="GET" id="filter-form" class="w-[1195px] h-full bg-white border border-gray-400 mx-auto mt-[20px]">
        <div class="grid grid-cols-4 p-7 gap-4">
            
            <div class="relative w-full dropdown-wrapper">
                <button id="genre-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer">
                    <span id="genre-btn-text" class="truncate pointer-events-none">
                        {{ request('genre') ? 'GENRE - ' . count(request('genre')) . ' SELECTED' : 'GENRE' }}
                    </span>
                </button>
                <div id="genre-popup" class="hidden absolute top-full left-0 mt-1 w-[150%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="genre[]" value="Fiction" class="custom-checkbox" {{ in_array('Fiction', request('genre', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Fiction</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="genre[]" value="Non-Fiction" class="custom-checkbox" {{ in_array('Non-Fiction', request('genre', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Non-Fiction</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="genre[]" value="Fantasy" class="custom-checkbox" {{ in_array('Fantasy', request('genre', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Fantasy</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="relative w-full dropdown-wrapper">
                <button id="language-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer overflow-hidden">
                    <span id="language-btn-text" class="truncate pointer-events-none">
                        {{ request('language') ? 'LANGUAGE - ' . count(request('language')) . ' SELECTED' : 'LANGUAGE' }}
                    </span>
                </button>
                <div id="language-popup" class="hidden absolute top-full left-0 mt-1 w-[120%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="language[]" value="English" class="custom-checkbox" {{ in_array('English', request('language', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">English</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="language[]" value="Tagalog" class="custom-checkbox" {{ in_array('Tagalog', request('language', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Tagalog</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="language[]" value="Bisaya" class="custom-checkbox" {{ in_array('Bisaya', request('language', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Bisaya</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="relative w-full dropdown-wrapper">
                <button id="price-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer overflow-hidden">
                    <span id="price-btn-text" class="truncate pointer-events-none">
                        {{ request('min_price') || request('max_price') ? 'PRICE - ₱'.request('min_price', 0).' TO ₱'.request('max_price', 5000) : 'PRICE' }}
                    </span>
                </button>
                <div id="price-popup" class="hidden absolute top-full left-0 mt-1 w-[140%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-5">
                        <div class="flex justify-between items-center gap-2 text-white font-bold">
                            <div class="flex flex-col w-1/2">
                                <label class="text-[10px] uppercase mb-1">Min (₱)</label>
                                <input type="number" name="min_price" id="price-min-input" value="{{ request('min_price', 0) }}" min="0" max="5000" class="w-full bg-white text-black font-bold px-2 py-1 outline-none text-center" />
                            </div>
                            <span class="mt-4">-</span>
                            <div class="flex flex-col w-1/2">
                                <label class="text-[10px] uppercase mb-1">Max (₱)</label>
                                <input type="number" name="max_price" id="price-max-input" value="{{ request('max_price', 5000) }}" min="0" max="5000" class="w-full bg-white text-black font-bold px-2 py-1 outline-none text-center" />
                            </div>
                        </div>

                        <div class="relative w-full h-6 flex items-center">
                            <div class="absolute w-full h-1 bg-white/40 rounded"></div>
                            <div id="price-progress" class="absolute h-1 bg-white rounded" style="left: 0%; right: 0%"></div>
                            <input type="range" id="price-min-range" min="0" max="5000" value="{{ request('min_price', 0) }}" class="absolute w-full h-full pointer-events-none custom-range" />
                            <input type="range" id="price-max-range" min="0" max="5000" value="{{ request('max_price', 5000) }}" class="absolute w-full h-full pointer-events-none custom-range" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <button id="price-apply" type="submit" class="w-full bg-white text-[#ED1B24] font-bold py-1 hover:bg-gray-200 transition-colors">APPLY</button>
                            <button id="price-clear" type="button" class="hidden w-full bg-transparent text-white border border-white font-bold py-1 hover:bg-red-800 transition-colors text-xs">CLEAR</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative w-full dropdown-wrapper">
                <button id="pub-date-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer overflow-hidden">
                    <span id="pub-date-btn-text" class="truncate pointer-events-none">
                        {{ request('year_start') || request('year_end') ? 'PUB DATE - ' . request('year_start', 'ANY') . ' TO ' . request('year_end', 'ANY') : 'PUBLICATION YEAR' }}
                    </span>
                </button>
                <div id="pub-date-popup" class="hidden absolute top-full left-0 mt-1 w-[120%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-3">
                        <span class="text-white font-bold uppercase tracking-wide text-sm mb-1">Custom Range</span>
                        <div class="flex items-center gap-2">
                            <input type="number" name="year_start" id="pub-year-start" value="{{ request('year_start') }}" placeholder="2010" min="1900" max="2026" class="w-full bg-white text-black font-bold px-2 py-1 outline-none text-center placeholder-gray-400" />
                            <span class="text-white font-bold">-</span>
                            <input type="number" name="year_end" id="pub-year-end" value="{{ request('year_end') }}" placeholder="2024" min="1900" max="2026" class="w-full bg-white text-black font-bold px-2 py-1 outline-none text-center placeholder-gray-400" />
                        </div>
                        <button id="pub-date-apply" type="submit" class="w-full bg-white text-[#ED1B24] font-bold py-1 mt-2 hover:bg-gray-200 transition-colors">APPLY</button>
                        <button id="pub-date-clear" type="button" class="hidden w-full bg-transparent text-white border border-white font-bold py-1 mt-1 hover:bg-red-800 transition-colors text-xs">CLEAR</button>
                    </div>
                </div>
            </div>

            <div class="relative w-full dropdown-wrapper">
                <button id="format-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer overflow-hidden">
                    <span id="format-btn-text" class="truncate pointer-events-none">
                        {{ request('format') ? 'FORMAT - ' . count(request('format')) . ' SELECTED' : 'FORMAT' }}
                    </span>
                </button>
                <div id="format-popup" class="hidden absolute top-full left-0 mt-1 w-[120%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="format[]" value="Hardcover" class="custom-checkbox" {{ in_array('Hardcover', request('format', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Hardcover</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="format[]" value="E-Book" class="custom-checkbox" {{ in_array('E-Book', request('format', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">E-Book/ Digital</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="format[]" value="Paperback" class="custom-checkbox" {{ in_array('Paperback', request('format', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Paperback</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="format[]" value="Box Set" class="custom-checkbox" {{ in_array('Box Set', request('format', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Box Set/ Collection</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="relative w-full dropdown-wrapper">
                <button id="rating-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer overflow-hidden">
                    <span id="rating-btn-text" class="truncate pointer-events-none">
                        {{ request('rating') ? 'RATING - ' . count(request('rating')) . ' SELECTED' : 'RATING' }}
                    </span>
                </button>
                <div id="rating-popup" class="hidden absolute top-full left-0 mt-1 w-[120%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-4">
                        @for($i = 5; $i >= 1; $i--)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="rating[]" value="{{ $i }}" class="custom-checkbox" {{ in_array($i, request('rating', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">{{ $i }} Stars</span>
                        </label>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="relative w-full dropdown-wrapper">
                <button id="age-btn" type="button" class="text-[#ED1B24] bg-white font-bold w-full px-2 py-2 border border-[#ED1B24] flex justify-center items-center z-10 relative cursor-pointer overflow-hidden">
                    <span id="age-btn-text" class="truncate pointer-events-none">
                        {{ request('age') ? 'AGE - ' . count(request('age')) . ' SELECTED' : 'AGE GROUP' }}
                    </span>
                </button>
                <div id="age-popup" class="hidden absolute top-full left-0 mt-1 w-[120%] bg-[#ED1B24] p-5 z-50 shadow-lg">
                    <div class="flex flex-col gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="age[]" value="Children" class="custom-checkbox" {{ in_array('Children', request('age', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Children (0-12)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="age[]" value="Teens" class="custom-checkbox" {{ in_array('Teens', request('age', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Teens (13-18)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="age[]" value="YA" class="custom-checkbox" {{ in_array('YA', request('age', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Young Adult</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="age[]" value="Adult" class="custom-checkbox" {{ in_array('Adult', request('age', [])) ? 'checked' : '' }} />
                            <span class="text-white font-bold uppercase tracking-wide">Adult</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="text-white bg-[#ED1B24] font-bold px-2 py-2 w-full border border-[#ED1B24] flex items-center justify-center gap-2 hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                FILTER
            </button>
        </div>
    </form>

    <p class="text-[35px] font-bold text-black mt-[38px] ml-[245px]">Suggested Books</p>
    
    <div class="grid grid-cols-5 gap-y-8 mt-[57px] mb-6 px-[237px]">
        @foreach($products as $product)
        <div class="h-full w-[265px] border border-gray-400 shadow-md relative bg-white flex flex-col">
            <a href="{{ route('catalog.show', $product) }}">
                <div class="flex justify-center items-center pt-6">
                    <img src="{{ asset('images/SampleBook.png') }}" alt="{{ $product->Title }}" class="w-[180px] h-[240px] object-cover" />
                </div>
                <p class="text-black text-[15px] mt-[18px] mx-[20px] font-bold h-10 overflow-hidden leading-tight">
                    {{ $product->Title }}
                </p>
                <p class="text-gray-500 text-[11px] mt-[7px] mx-[20px] truncate">{{ $product->Author }}</p>
            </a>

            {{-- Accurate Star Logic with Laravel Asset Helper --}}
            <div class="flex items-center mt-1 mx-[20px]">
                @php
                    $currentRating = round($product->Rating ?? 5); 
                @endphp
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $currentRating)
                        <img src="{{ asset('images/StarVal.png') }}" class="w-3 h-3" alt="Star" />
                    @else
                        <img src="{{ asset('images/StarNone.png') }}" class="w-3 h-3" alt="Empty Star" />
                    @endif
                @endfor
            </div>

            <p class="text-black text-[15px] font-bold mt-[10px] mx-[20px]">₱ {{ number_format($product->Price, 2) }}</p>
            
            <div class="w-full flex justify-center px-[22px] py-[9px] mb-4 mt-auto">
                <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="product_ID" value="{{ $product->product_ID }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="bg-white text-black border-[#FCAE42] border-2 w-full py-[10px] transition-colors hover:bg-[#FCAE42] hover:text-white hover:font-bold">
                        ADD TO CART
                    </button>
                </form>
            </div>

            <div class="bg-[#FCAE42] w-[97px] absolute top-11">
                <p class="text-[13px] font-bold px-[7px] py-[2px] text-center">BESTSELLER</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="px-[237px] mb-20">
        {{ $products->links() }}
    </div>

    @include('partials.footer')

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        function setupDropdown(btnId, popupId, textId, checkboxName, defaultText) {
          const btn = document.getElementById(btnId);
          const popup = document.getElementById(popupId);
          const btnText = document.getElementById(textId);
          const checkboxes = document.querySelectorAll(`input[name="${checkboxName}"]`);

          if (!btn || !popup) return;

          btn.addEventListener("click", (e) => {
            e.preventDefault();
            document.querySelectorAll('[id$="-popup"]').forEach((otherPopup) => {
                if (otherPopup.id !== popupId) {
                  otherPopup.classList.add("hidden");
                }
              });
            popup.classList.toggle("hidden");
          });

          document.addEventListener("click", (event) => {
            if (!btn.contains(event.target) && !popup.contains(event.target)) {
              popup.classList.add("hidden");
            }
          });

          checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", () => {
              const selected = Array.from(checkboxes).filter((cb) => cb.checked);
              const count = selected.length;
              if (count === 0) {
                btnText.textContent = defaultText;
              } else if (count === 1) {
                const labelText = selected[0].nextElementSibling.textContent;
                btnText.textContent = `${defaultText} - ${labelText}`;
              } else {
                btnText.textContent = `${defaultText} - ${count} SELECTED`;
              }
            });
          });
        }

        // --- PRICE LOGIC ---
        const priceBtn = document.getElementById("price-btn");
        const pricePopup = document.getElementById("price-popup");
        const minRange = document.getElementById("price-min-range");
        const maxRange = document.getElementById("price-max-range");
        const minInput = document.getElementById("price-min-input");
        const maxInput = document.getElementById("price-max-input");
        const progress = document.getElementById("price-progress");
        const priceClear = document.getElementById("price-clear");

        if(minRange && maxRange) {
            const priceGap = 50; // Adjusted for PHP currency limits
            const maxSliderValue = parseInt(minRange.max);

            function updateSliderVisuals() {
              const minVal = parseInt(minRange.value);
              const maxVal = parseInt(maxRange.value);
              const minPercent = (minVal / maxSliderValue) * 100;
              const maxPercent = (maxVal / maxSliderValue) * 100;
              progress.style.left = minPercent + "%";
              progress.style.right = 100 - maxPercent + "%";
              minInput.value = minVal;
              maxInput.value = maxVal;
            }

            // Sync visual state on load
            updateSliderVisuals();
            if(minInput.value > 0 || maxInput.value < maxSliderValue) {
                priceClear.classList.remove("hidden");
            }

            priceClear.addEventListener("click", () => {
              minRange.value = 0;
              maxRange.value = maxSliderValue;
              updateSliderVisuals();
              priceClear.classList.add("hidden");
            });

            minRange.addEventListener("input", () => {
              if (parseInt(maxRange.value) - parseInt(minRange.value) < priceGap) {
                minRange.value = parseInt(maxRange.value) - priceGap;
              }
              updateSliderVisuals();
            });

            maxRange.addEventListener("input", () => {
              if (parseInt(maxRange.value) - parseInt(minRange.value) < priceGap) {
                maxRange.value = parseInt(minRange.value) + priceGap;
              }
              updateSliderVisuals();
            });

            priceBtn.addEventListener("click", (e) => {
              e.preventDefault();
              document.querySelectorAll('[id$="-popup"]').forEach((p) => {
                if (p.id !== "price-popup") p.classList.add("hidden");
              });
              pricePopup.classList.toggle("hidden");
            });
            
            document.addEventListener("click", (event) => {
                if (!priceBtn.contains(event.target) && !pricePopup.contains(event.target)) {
                  pricePopup.classList.add("hidden");
                }
            });
        }

        // --- PUBLICATION DATE LOGIC ---
        const pubDateBtn = document.getElementById("pub-date-btn");
        const pubDatePopup = document.getElementById("pub-date-popup");
        const pubYearStart = document.getElementById("pub-year-start");
        const pubYearEnd = document.getElementById("pub-year-end");
        const pubDateClear = document.getElementById("pub-date-clear");

        if(pubDateBtn) {
            if(pubYearStart.value || pubYearEnd.value) {
                pubDateClear.classList.remove("hidden");
            }

            pubDateBtn.addEventListener("click", (e) => {
              e.preventDefault();
              document.querySelectorAll('[id$="-popup"]').forEach((p) => {
                if (p.id !== "pub-date-popup") p.classList.add("hidden");
              });
              pubDatePopup.classList.toggle("hidden");
            });

            document.addEventListener("click", (e) => {
              if (!pubDateBtn.contains(e.target) && !pubDatePopup.contains(e.target)) {
                pubDatePopup.classList.add("hidden");
              }
            });

            pubDateClear.addEventListener("click", () => {
              pubYearStart.value = "";
              pubYearEnd.value = "";
              pubDateClear.classList.add("hidden");
            });
        }

        // Initialize Dropdowns
        setupDropdown("genre-btn", "genre-popup", "genre-btn-text", "genre[]", "GENRE");
        setupDropdown("format-btn", "format-popup", "format-btn-text", "format[]", "FORMAT");
        setupDropdown("language-btn", "language-popup", "language-btn-text", "language[]", "LANGUAGE");
        setupDropdown("rating-btn", "rating-popup", "rating-btn-text", "rating[]", "RATING");
        setupDropdown("age-btn", "age-popup", "age-btn-text", "age[]", "AGE GROUP");
      });
    </script>
</body>
</html>