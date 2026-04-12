<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Login & Security - NCB</title>
</head>
<body class="bg-gray-50">
    @include('partials.header')
    @php $customer = Auth::guard('customer')->user(); @endphp

    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Your Account</h1>
            <p class="text-gray-600">Welcome back, <span class="font-semibold">{{ $customer->first_name }} {{ $customer->last_name }}</span></p>
        </div>

        <div class="flex flex-col xl:flex-row gap-8">
            {{-- Sidebar Navigation --}}
            @include('partials.account-nav', ['active' => 'security'])

            {{-- Main Content --}}
            <div class="flex-1 min-w-0 space-y-6">
                {{-- Personal Information --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information</h2>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 space-y-1">
                            @foreach($errors->all() as $error)
                                <p class="text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('account.info.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Name Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" required />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" required />
                            </div>
                        </div>

                        {{-- Contact & Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                                <input type="tel" name="contact_num" value="{{ old('contact_num', $customer->contact_num) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" required />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" required />
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="bg-[#ED1B24] text-white font-semibold px-8 py-3 rounded-lg hover:bg-red-700 transition">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Change Password --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Change Password</h2>
                    <p class="text-gray-600 text-sm mb-6">Leave blank to keep your current password</p>

                    <form action="{{ route('account.info.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" name="new_password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="bg-[#ED1B24] text-white font-semibold px-8 py-3 rounded-lg hover:bg-red-700 transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Delete Account --}}
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-red-700 mb-2">Delete Account</h2>
                    <p class="text-red-600 text-sm mb-6">Deleting your account will remove all your data permanently and cannot be undone.</p>

                    <form action="{{ route('account.delete') }}" method="POST"
                          onsubmit="return confirm('Are you absolutely sure? This cannot be undone.')">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Enter Password to Confirm</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" required />
                        </div>

                        <div class="flex justify-end pt-4 border-t border-red-200 mt-6">
                            <button type="submit"
                                class="bg-red-600 text-white font-semibold px-8 py-3 rounded-lg hover:bg-red-700 transition">
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