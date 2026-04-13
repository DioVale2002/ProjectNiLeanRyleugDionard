<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Login - NCB</title>
  </head>

  {{-- Using inline style for the background image so Laravel's asset() helper resolves the path perfectly --}}
  <body class="bg-cover bg-no-repeat min-h-screen flex flex-col" style="background-image: url('{{ asset('images/AuthBG.jpg') }}');">
    <div class="flex-1 flex items-center justify-center mx-[228px] mt-[80px] mb-[40px]">
      
      {{-- Marketing Text --}}
      <div>
        <p class="text-white text-[32px] font-bold">
          Learn + Achieve, all in One Bookstore
        </p>
        <p class="text-white text-[24px] w-[788px] font-bold mt-4 mr-[136px]">
          A space for students and educators to access textbooks and resources.
          Study smarter and achieve more with every book in one place.
        </p>
      </div>
      
      <div class="bg-white w-[540px] h-full rounded-[25px] flex flex-col items-center pt-[30px]">
        <img src="{{ asset('images/LoginLogo.png') }}" alt="Login Image">
        <p class="text-[36px] font-bold mt-4">Log In to NCB</p>

        {{-- Laravel Error Messages --}}
        @if($errors->any())
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-[10px] w-[395px]">
                @foreach($errors->all() as $error)
                    <p class="text-sm font-bold">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Functional Form --}}
        <form class="flex flex-col" method="POST" action="{{ route('login') }}">
            @csrf

            <input class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" 
                   type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
            
            {{-- This acts as your password field for now, but retains your OTP placeholder text --}}
            <input class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" 
                   type="password" name="password" placeholder="OTP Code" required>
            
            {{-- Placeholder OTP Button (Changed to type="button" so it doesn't trigger the login) --}}
            <button class="bg-[#FCAE42] h-[55px] text-[24px] text-black font-bold py-2 px-4 rounded-[10px] mt-[30px] hover:bg-[#e09b3b] transition-colors" 
                    type="button" onclick="alert('OTP generation logic will be implemented here later.')">
                Send OTP
            </button>
            
            {{-- Log In Button (Changed from <a> tag to <button type="submit"> to submit the Laravel form) --}}
            <button class="bg-[#FCAE42] h-[55px] text-[24px] text-center text-black font-bold py-2 px-4 rounded-[10px] mt-[20px] hover:bg-[#e09b3b] transition-colors" 
                    type="submit">
                Log In
            </button>
        </form>

        <div class="flex mt-6 mb-14">
            <p class="text-[15px] mr-0.5">Don’t have an account?</p>
            <a href="{{ route('register') }}" class="text-[15px] text-[#ED1B24] font-bold hover:underline">Sign Up</a>
        </div>
      </div>
    </div>

    <footer class="mt-auto p-6 w-full">
      <p class="text-white">© 2026 New Century Books. All rights reserved.</p>
      <div class="flex gap-6 mt-2">
        <a class="text-white text-[16px] hover:underline" href="#">Privacy Policy</a>
        <a class="text-white text-[16px] hover:underline" href="#">Terms of Service</a>
      </div>
    </footer>
  </body>
</html>