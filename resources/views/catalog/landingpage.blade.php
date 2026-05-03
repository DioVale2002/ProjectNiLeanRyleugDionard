<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/NCB-LOGO 1.png') }}" type="image/png" />
    <title>Home - NCB</title>
  </head>

  <body class="bg-white overflow-x-hidden">
    
    {{-- Header --}}
    @include('partials.header')

    <div class="w-screen h-185 bg-cover bg-center relative" style="background-image: url('{{ asset('images/Ldpg-img/lib-books.png') }}');">
      <p class="font-bold text-white text-[128px] pl-[28px] ml-[103px] pt-[89px] leading-tight">
        Affordable Textbook Options, Your Way
      </p>
      
      {{-- Three Overlapping Info Cards --}}
      <div class="flex items-center justify-evenly absolute -bottom-[450px] w-full">
        
        {{-- Card 1 --}}
        <div class="w-[485px] h-[578px] bg-[#F54E4E]">
          <div class="w-[485px] h-[323px] bg-cover bg-center" style="background-image: url('{{ asset('images/Ldpg-img/guy-library.png') }}');"></div>
          <p class="text-white text-[24px] font-bold pt-[17px] pl-[11px]">
            Life Skills Made Simple. College Made Easier.
          </p>
          <p class="text-white text-[20px] pt-[10px] pl-[11px] pr-[15px]">
            Tuck a little extra help into your college adventure. From Adulting
            For Dummies to Auto Repair basics, get practical guides that turn
            life's challenges into simple solutions.
          </p>
        </div>

        {{-- Card 2 --}}
        <div class="w-[485px] h-[578px] bg-[#F54E4E]">
          <div class="w-[485px] h-[323px] bg-cover bg-center" style="background-image: url('{{ asset('images/Ldpg-img/guy-laptop.png') }}');"></div>
          <p class="text-white text-[24px] font-bold pt-[17px] pl-[11px]">
            Your Way to Learn. Your Choice of Format.
          </p>
          <p class="text-white text-[20px] pt-[10px] pl-[11px] pr-[15px]">
            Prefer print? Love digital? We've got you covered. Access your
            textbooks instantly with eBooks or get print editions delivered.
          </p>
        </div>

        {{-- Card 3 --}}
        <div class="w-[485px] h-[578px] bg-[#F54E4E]">
          <div class="w-[485px] h-[323px] bg-cover bg-center" style="background-image: url('{{ asset('images/Ldpg-img/guy-hand.png') }}');"></div>
          <p class="text-white text-[24px] font-bold pt-[17px] pl-[11px]">
            Immediate Access. Lasting Success.
          </p>
          <p class="text-white text-[20px] pt-[10px] pl-[11px] pr-[15px]">
            Browse world-class titles across business, psychology, engineering,
            health sciences and more—all at prices designed for student budgets.
          </p>
        </div>

      </div>
    </div>

    <div class="flex justify-evenly items-start mt-[600px] w-full">
      <div class="flex flex-col items-center justify-start w-64">
        <img src="{{ asset('images/Ldpg-img/cash.png') }}" alt="cash" class="h-[113px] object-contain" />
        <p class="text-center font-bold text-black text-[24px] mt-3.5">
          Affordable prices
        </p>
      </div>
      <div class="flex flex-col items-center justify-start w-64">
        <img src="{{ asset('images/Ldpg-img/flexibility.png') }}" alt="flexibility" class="h-[113px] object-contain" />
        <p class="text-center font-bold text-black text-[24px] mt-3.5">
          Flexible purchases
        </p>
      </div>
      <div class="flex flex-col items-center justify-start w-64">
        <img src="{{ asset('images/Ldpg-img/box.png') }}" alt="box" class="h-[113px] object-contain" />
        <p class="text-center font-bold text-black text-[24px] mt-3.5">
          Fast Delivery
        </p>
      </div>
      <div class="flex flex-col items-center justify-start w-64">
        <img src="{{ asset('images/Ldpg-img/student.png') }}" alt="student" class="h-[113px] object-contain" />
        <p class="text-center font-bold text-black text-[24px] mt-3.5">
          College-Bound
        </p>
      </div>
    </div>

    <p class="text-center mt-[132px] font-bold text-[48px]">Shop by Subject</p>
    
    <div class="grid grid-cols-3 gap-[111px] px-[125px] mt-29 mb-[162px]">
      
      {{-- Subject 1 --}}
      <div class="flex flex-col h-[455px] w-[483px]">
        <img src="{{ asset('images/Ldpg-img/Business.png') }}" alt="Business" />
        <p class="font-bold text-black text-[24px] mt-[27px] mb-[18px] pl-[28px]">
          Business
        </p>
        <p class="text-black text-[19px] pl-[28px] mb-[18px] pr-4">
          Make shopping for business textbooks simple with delivery and eBook
          options.
        </p>
        <button onclick="window.location.href='{{ route('catalog.index') }}'" class="flex items-center mt-[18px] bg-[#ffffff] text-black pl-[28px] hover:underline cursor-pointer">
          Shop Now <img class="ml-4" src="{{ asset('images/Ldpg-img/V.png') }}" alt="" />
        </button>
      </div>

      {{-- Subject 2 --}}
      <div class="flex flex-col h-[455px] w-[483px]">
        <img src="{{ asset('images/Ldpg-img/PhysSci.png') }}" alt="Psychology" />
        <p class="font-bold text-black text-[24px] mt-[27px] mb-[18px] pl-[28px]">
          Psychology
        </p>
        <p class="text-black text-[19px] pl-[28px] mb-[18px] pr-4">
          Find psychology textbooks in a format that fits you with print and
          eBook options.
        </p>
        <button onclick="window.location.href='{{ route('catalog.index') }}'" class="flex items-center mt-[18px] bg-[#ffffff] text-black pl-[28px] hover:underline cursor-pointer">
          Shop Now <img class="ml-4" src="{{ asset('images/Ldpg-img/V.png') }}" alt="" />
        </button>
      </div>

      {{-- Subject 3 --}}
      <div class="flex flex-col h-[455px] w-[483px]">
        <img src="{{ asset('images/Ldpg-img/Engineering.png') }}" alt="Engineering" />
        <p class="font-bold text-black text-[24px] mt-[27px] mb-[18px] pl-[28px]">
          Engineering
        </p>
        <p class="text-black text-[19px] pl-[28px] mb-[18px] pr-4">
          Find flexible options to suit you across systems and mechanical
          engineering and more.
        </p>
        <button onclick="window.location.href='{{ route('catalog.index') }}'" class="flex items-center mt-[18px] bg-[#ffffff] text-black pl-[28px] hover:underline cursor-pointer">
          Shop Now <img class="ml-4" src="{{ asset('images/Ldpg-img/V.png') }}" alt="" />
        </button>
      </div>

      {{-- Subject 4 --}}
      <div class="flex flex-col h-[455px] w-[483px]">
        <img src="{{ asset('images/Ldpg-img/LifeScience.png') }}" alt="Life Sciences" />
        <p class="font-bold text-black text-[24px] mt-[27px] mb-[18px] pl-[28px]">
          Life Sciences
        </p>
        <p class="text-black text-[19px] pl-[28px] mb-[18px] pr-4">
          Find the life science textbooks you need at affordable prices.
        </p>
        <button onclick="window.location.href='{{ route('catalog.index') }}'" class="flex items-center mt-[18px] bg-[#ffffff] text-black pl-[28px] hover:underline cursor-pointer">
          Shop Now <img class="ml-4" src="{{ asset('images/Ldpg-img/V.png') }}" alt="" />
        </button>
      </div>

      {{-- Subject 5 --}}
      <div class="flex flex-col h-[455px] w-[483px]">
        <img src="{{ asset('images/Ldpg-img/Vet.png') }}" alt="Veterinary Medicine" />
        <p class="font-bold text-black text-[24px] mt-[27px] mb-[18px] pl-[28px]">
          Veterinary Medicine
        </p>
        <p class="text-black text-[19px] pl-[28px] mb-[18px] pr-4">
          Browse world-class veterinary titles in print and eBook formats.
        </p>
        <button onclick="window.location.href='{{ route('catalog.index') }}'" class="flex items-center mt-[18px] bg-[#ffffff] text-black pl-[28px] hover:underline cursor-pointer">
          Shop Now <img class="ml-4" src="{{ asset('images/Ldpg-img/V.png') }}" alt="" />
        </button>
      </div>

      {{-- Subject 6 --}}
      <div class="flex flex-col h-[455px] w-[483px]">
        <img src="{{ asset('images/Ldpg-img/PhysSci.png') }}" alt="Physical Sciences" />
        <p class="font-bold text-black text-[24px] mt-[27px] mb-[18px] pl-[28px]">
          Physical Sciences
        </p>
        <p class="text-black text-[19px] pl-[28px] mb-[18px] pr-4">
          Explore top titles across physics, chemistry, and more.
        </p>
        <button onclick="window.location.href='{{ route('catalog.index') }}'" class="flex items-center mt-[18px] bg-[#ffffff] text-black pl-[28px] hover:underline cursor-pointer">
          Shop Now <img class="ml-4" src="{{ asset('images/Ldpg-img/V.png') }}" alt="" />
        </button>
      </div>

    </div>

    {{-- Footer --}}
    @include('partials.footer')

  </body>
</html>