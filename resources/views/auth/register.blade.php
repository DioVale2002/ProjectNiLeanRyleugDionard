<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Register - NCB</title>
</head>
<body class="bg-[url('/images/AuthBG.jpg')] bg-cover bg-no-repeat min-h-screen flex flex-col">
    
    <div class="flex-1 flex items-center justify-center mx-[228px] mt-[80px] mb-[40px]">
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
            <img src="/images/LoginLogo.png" alt="NCB Logo" class="h-12 mb-6" />
            <p class="text-[36px] font-bold">Create your Account</p>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4 w-[395px]">
                    @foreach($errors->all() as $error)
                        <p class="text-red-700 text-sm">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" class="flex flex-col mt-6">
                @csrf

                {{-- First Name --}}
                <input
                    id="first_name"
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    placeholder="First Name"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                />

                {{-- Last Name --}}
                <input
                    id="last_name"
                    type="text"
                    name="last_name"
                    value="{{ old('last_name') }}"
                    placeholder="Last Name"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                />

                {{-- Contact Number --}}
                <input
                    id="contact_num"
                    type="tel"
                    name="contact_num"
                    value="{{ old('contact_num') }}"
                    placeholder="Contact Number"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                />

                {{-- Email --}}
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email"
                    required
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                />

                {{-- Next Button --}}
                <a href="{{ route('register') }}" class="bg-[#FCAE42] flex items-center justify-center h-[55px] text-[24px] text-black py-2 px-4 rounded-[10px] mt-[30px] no-underline cursor-pointer">
                    <button type="submit">Next</button>
                </a>
            </form>

            {{-- Sign In Link --}}
            <div class="flex mt-6 mb-14">
                <p class="text-[15px] mr-0.5">Already have an account?</p>
                <a href="{{ route('login') }}" class="text-[15px] text-[#ED1B24]">Sign In</a>
            </div>
        </div>
    </div>

    <footer class="mt-auto p-6 w-full">
        <p class="text-white">© 2025 New Century Books. All rights reserved.</p>
        <div class="flex gap-6 mt-2">
            <a class="text-white text-[16px]" href="#">Privacy Policy</a>
            <a class="text-white text-[16px]" href="#">Terms of Service</a>
        </div>
    </footer>

</body>
</html>