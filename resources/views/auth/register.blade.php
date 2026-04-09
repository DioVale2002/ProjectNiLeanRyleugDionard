<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Register - NCB</title>
</head>
<body class="bg-[url('/images/AuthBG.jpg')] bg-cover bg-no-repeat min-h-screen flex flex-col">

    <div class="flex-1 flex items-center justify-center mx-[228px] mt-[80px] mb-[40px]">
        <div>
            <p class="text-white text-[32px] font-bold">
                Learn + Achieve, all in One Bookstore
            </p>
            <p class="text-white text-[24px] w-[788px] font-bold mt-4 mr-[136px]">
                A space for students and educators to access textbooks and resources.
                Study smarter and achieve more with every book in one place.
            </p>
        </div>

        <div class="bg-white w-[540px] h-full rounded-[25px] flex flex-col items-center">
            <img src="/images/LoginLogo.png" alt="NCB Logo" />
            <p class="text-[36px] font-bold">Create your Account</p>

            @if($errors->any())
                <div class="w-[395px] mt-4">
                    @foreach($errors->all() as $error)
                        <p class="text-[#ED1B24] text-[14px]">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="flex flex-col" action="{{ route('register') }}" method="POST">
                @csrf
                <input
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                    type="text" name="first_name"
                    placeholder="First Name"
                    value="{{ old('first_name') }}"
                    required>
                <input
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                    type="text" name="last_name"
                    placeholder="Last Name"
                    value="{{ old('last_name') }}"
                    required>
                <input
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                    type="tel" name="contact_num"
                    placeholder="Contact Number"
                    value="{{ old('contact_num') }}"
                    required>
                <input
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                    type="email" name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required>
                <input
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                    type="password" name="password"
                    placeholder="Password"
                    required>
                <input
                    class="border border-gray-400 rounded-[10px] w-[395px] h-[55px] mt-[20px] p-5 text-[24px]"
                    type="password" name="password_confirmation"
                    placeholder="Confirm Password"
                    required>
                <button
                    class="bg-[#FCAE42] flex items-center justify-center h-[55px] text-[24px] text-black py-2 px-4 rounded-[10px] mt-[30px]"
                    type="submit">
                    Register
                </button>
            </form>

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