<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Register - NCB</title>
</head>

{{-- Using inline style with asset() for the background to ensure it loads across all environments --}}
<body class="bg-cover bg-no-repeat min-h-screen flex flex-col" style="background-image: url('{{ asset('images/AuthBG.jpg') }}');">

    {{-- Changed to justify-between to push text left and card right, matching the Login page --}}
    <div class="flex-1 flex items-center justify-between mx-[228px] mt-[80px] mb-[40px]">
        
        {{-- Left Section: Heading --}}
        <div>
            <p class="text-white text-[32px] font-bold">
                Learn + Achieve, all in One Bookstore
            </p>
            <p class="text-white text-[24px] w-[788px] font-bold mt-4 mr-[136px]">
                A space for students and educators to access textbooks and resources.
                Study smarter and achieve more with every book in one place.
            </p>
        </div>

        {{-- Right Section: Sign Up Form Card --}}
        <div class="bg-white w-[540px] rounded-[25px] flex flex-col items-center pt-8 pb-8">
            <img src="{{ asset('images/LoginLogo.png') }}" alt="NCB Logo" class="h-12 mb-6" />
            <p class="text-[36px] font-bold">Create your Account</p>

            {{-- Error Messages --}}
            @if($errors->any())
            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4 w-[395px]">
                @foreach($errors->all() as $error)
                <p class="text-red-700 text-sm font-bold">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" class="flex flex-col mt-2">
                @csrf

                {{-- First Name --}}
                <input
                    id="first_name"
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    placeholder="First Name"
                    required
                    autofocus
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" />

                {{-- Last Name --}}
                <input
                    id="last_name"
                    type="text"
                    name="last_name"
                    value="{{ old('last_name') }}"
                    placeholder="Last Name"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" />

                {{-- Contact Number --}}
                <input
                    id="contact_num"
                    type="tel"
                    name="contact_num"
                    value="{{ old('contact_num') }}"
                    placeholder="Contact Number"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" />

                {{-- Email --}}
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" />

                {{-- Password (Will likely be removed when you transition fully to OTP) --}}
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" />

                {{-- Confirm Password --}}
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px] focus:outline-none focus:border-[#ED1B24]" />

                {{-- Register Button --}}
                <button type="submit" class="bg-[#FCAE42] w-[395px] h-[55px] text-[24px] text-black font-bold rounded-[10px] mt-[30px] hover:bg-[#e09b3b] transition-colors">
                    Register
                </button>
            </form>

            {{-- Sign In Link --}}
            <div class="flex mt-6 mb-8">
                <p class="text-[15px] mr-1">Already have an account?</p>
                <a href="{{ route('login') }}" class="text-[15px] text-[#ED1B24] font-bold hover:underline">Sign In</a>
            </div>
        </div>
    </div>

    <footer class="mt-auto p-6 w-full">
        <p class="text-white">© {{ date('Y') }} New Century Books. All rights reserved.</p>
        <div class="flex gap-6 mt-2">
            <a class="text-white text-[16px] hover:underline" href="#">Privacy Policy</a>
            <a class="text-white text-[16px] hover:underline" href="#">Terms of Service</a>
        </div>
    </footer>

</body>
</html>