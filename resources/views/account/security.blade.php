<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login & Security - NCB</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @php 
        $customer = Auth::guard('customer')->user(); 
    @endphp

    {{-- Header Section (Original Figma Design) --}}
    <div class="ml-[282px] mt-[50px] mb-[50px]">
        <p class="text-[36px] text-black font-bold">Your Account</p>
        <div class="flex">
            <p class="text-[17px] text-black/50 mr-1">{{ $customer->first_name }} {{ $customer->last_name }},</p>
            <p class="text-[17px] text-black/50 mr-1">Email:</p>
            <p class="text-[17px] text-black/50">{{ $customer->email }}</p>
        </div>
    </div>

    <div class="flex mx-[282px] mb-[80px]">
        
        {{-- Navigation Sidebar --}}
        <div class="w-[342px]">
            @include('partials.account-nav', ['active' => 'security'])
        </div>

        {{-- Main Content --}}
        <div class="flex flex-col gap-7">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="ml-[63px] w-[900px] bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="ml-[63px] w-[900px] bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- CARD 1: Personal Information --}}
            <div class="border border-black/50 rounded-lg ml-[63px] w-[900px] h-full bg-white">
                <div class="m-7">
                    <p class="font-bold text-[32px]">Your Personal Information</p>
                    <hr class="my-4 border-gray-300" />
                    
                    <form action="{{ route('account.info.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mt-6 flex flex-col gap-6">
                            {{-- Row: First Name --}}
                            <div class="flex items-center">
                                <label class="text-[20px] font-bold w-[220px]" for="first_name">First Name</label>
                                <input class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:border-[#ED1B24]" 
                                       type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" required />
                            </div>

                            {{-- Row: Last Name --}}
                            <div class="flex items-center">
                                <label class="text-[20px] font-bold w-[220px]" for="last_name">Last Name</label>
                                <input class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:border-[#ED1B24]" 
                                       type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" required />
                            </div>

                            {{-- Row: Contact Number --}}
                            <div class="flex items-center">
                                <label class="text-[20px] font-bold w-[220px]" for="contact_num">Contact Number</label>
                                <input class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:border-[#ED1B24]" 
                                       type="text" name="contact_num" id="contact_num" value="{{ old('contact_num', $customer->contact_num) }}" required />
                            </div>

                            {{-- Row: Email --}}
                            <div class="flex items-center">
                                <label class="text-[20px] font-bold w-[220px]" for="email">Email</label>
                                <input class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:border-[#ED1B24]" 
                                       type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 mr-7">
                            <button type="submit" class="bg-[#ED1B24] text-white font-bold py-3 px-24 rounded-md hover:bg-[#c1101a] transition-colors">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CARD 2: Delete Account (OTP) --}}
            <div class="border border-red-500 rounded-lg ml-[63px] w-[900px] h-full bg-white">
                <div class="m-7">
                    <p class="font-bold text-[32px] text-[#ED1B24]">Delete Account</p>
                    <p class="text-[16px] text-[#ED1B24]">Deleting your account will remove all your data permanently and cannot be undone.</p>
                    <hr class="my-4 border-red-300" />

                    <form action="{{ route('account.delete.otp.request') }}" method="POST">
                        @csrf
                        <div class="flex justify-end mt-8 mr-7">
                            <button type="submit" class="bg-[#ED1B24] text-white font-bold py-3 px-10 rounded-md hover:bg-[#c1101a] transition-colors">
                                Send OTP
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('account.delete') }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.')">
                        @csrf
                        @method('DELETE')

                        <div class="mt-6 flex flex-col gap-6">
                            <div class="flex items-center">
                                <label class="text-[20px] font-bold w-[220px]" for="otp_code">OTP Code</label>
                                <input class="border border-red-400 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:border-[#ED1B24]" 
                                       type="text" name="otp_code" id="otp_code" placeholder="Enter the OTP sent to your email" required />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 mr-7">
                            <button type="submit" class="bg-[#ED1B24] text-white font-bold py-3 px-10 rounded-md hover:bg-[#c1101a] transition-colors">
                                Delete My Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')

</body>
</html>