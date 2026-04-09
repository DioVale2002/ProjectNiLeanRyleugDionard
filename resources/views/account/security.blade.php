<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Login & Security - NCB</title>
</head>
<body>
    @include('partials.header')
    @php $customer = Auth::guard('customer')->user(); @endphp

    <div class="ml-[282px] mt-[50px] mb-[50px]">
        <p class="text-[36px] text-black font-bold">Your Account</p>
        <div class="flex">
            <p class="text-[17px] text-black/50 mr-1">{{ $customer->first_name }} {{ $customer->last_name }},</p>
            <p class="text-[17px] text-black/50">Email: {{ $customer->email }}</p>
        </div>
    </div>

    <div class="flex mx-[282px] mb-[80px]">
        @include('partials.account-nav', ['active' => 'security'])
        <div class="flex-1">
            <p class="text-[28px] font-bold mb-4">Login & Security</p>
            <hr class="mb-6 border-gray-300" />

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form action="{{ route('account.info.update') }}" method="POST" class="max-w-[500px]">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" required />
                </div>
                <div class="mb-4">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" required />
                </div>
                <div class="mb-4">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="tel" name="contact_num" value="{{ old('contact_num', $customer->contact_num) }}"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" required />
                </div>
                <div class="mb-4">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" required />
                </div>

                <hr class="my-6 border-gray-200" />
                <p class="font-bold text-[18px] mb-4">Change Password <span class="text-gray-400 font-normal text-[14px]">(optional)</span></p>

                <div class="mb-4">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" />
                </div>
                <div class="mb-4">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="new_password"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" />
                </div>
                <div class="mb-6">
                    <label class="block text-[16px] font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation"
                        class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" />
                </div>

                <button type="submit"
                    class="bg-[#FCAE42] text-black font-bold text-[16px] py-3 px-8 hover:bg-[#F54E4E] hover:text-white transition-colors">
                    Save Changes
                </button>
            </form>

            {{-- Delete account --}}
            <div class="mt-12 max-w-[500px]">
                <hr class="mb-6 border-gray-200" />
                <p class="font-bold text-[18px] text-[#ED1B24] mb-2">Danger Zone</p>
                <form action="{{ route('account.delete') }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <div class="mb-4">
                        <label class="block text-[16px] font-medium text-gray-700 mb-1">Confirm Password to Delete Account</label>
                        <input type="password" name="password"
                            class="w-full border border-[#ED1B24] px-4 py-2 rounded focus:outline-none text-[16px]" />
                    </div>
                    <button type="submit"
                        class="bg-[#ED1B24] text-white font-bold text-[16px] py-3 px-8 hover:bg-red-700 transition-colors">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>