<header>
    <div class="flex items-center justify-between px-[261px]">
        <a href="/">
            <img src="{{ asset('images/Logo.png') }}" alt="Character portrait" />
        </a>

        <div class="relative">
            <form action="/catalog" method="GET" class="m-0">
                <input
                    type="text"
                    name="search"
                    placeholder="Search"
                    class="w-[908px] h-[36.74px] px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" />

                <button
                    type="submit"
                    class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.352 4.35a1 1 0 01-1.414 1.414l-4.352-4.35A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>

        <p class="ml-4 text-gray-700 font-bold">
            {{ Auth::guard('customer')->check() ? Auth::guard('customer')->user()->first_name : 'Guest' }}
        </p>

        @auth('customer')
            <div class="relative ml-4" id="notification-root">
                <button type="button" id="notification-toggle" class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a7 7 0 00-7 7v3.586l-.707.707A1 1 0 005 15h14a1 1 0 00.707-1.707L19 12.586V9a7 7 0 00-7-7zm0 20a3 3 0 01-2.83-2h5.66A3 3 0 0112 22z" />
                    </svg>
                    <span id="notification-badge" class="absolute -top-2 -right-2 hidden h-5 min-w-[20px] items-center justify-center rounded-full bg-red-600 px-1 text-xs font-bold text-white"></span>
                </button>

                <div id="notification-panel" class="hidden absolute right-0 mt-3 w-80 rounded-lg border border-gray-200 bg-white shadow-lg z-50">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-bold text-gray-700">Notifications</p>
                        <button type="button" id="notification-mark-all" class="text-xs font-semibold text-[#ED1B24] hover:underline">Mark all read</button>
                    </div>
                    <div id="notification-list" class="max-h-96 overflow-y-auto"></div>
                    <div id="notification-empty" class="px-4 py-6 text-center text-sm text-gray-500">No notifications yet.</div>
                </div>
            </div>
        @endauth

        <a href="{{ route('account.security') }}" class="ml-4">
            <img src="{{ asset('images/User.png') }}" alt="User profile" />
        </a>

        <a href="{{ url('/cart') }}" class="ml-4">
            <img src="{{ asset('images/cart.png') }}" alt="Shopping cart" />
        </a>
    </div>

    <div class="h-12 w-screen bg-[#FCAE42] px-[261px] flex items-center justify-between">
        <a href="/catalog" class="ml-7 text-black text-[20px] font-bold hover:opacity-80 transition">Books</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">E-Books</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">Best Sellers</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">New</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">Collections</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">Sale</a>
    </div>
</header>

@auth('customer')
    <script>
        (function () {
            const toggle = document.getElementById('notification-toggle');
            const panel = document.getElementById('notification-panel');
            const list = document.getElementById('notification-list');
            const empty = document.getElementById('notification-empty');
            const badge = document.getElementById('notification-badge');
            const markAll = document.getElementById('notification-mark-all');
            const csrfToken = '{{ csrf_token() }}';
            let isOpen = false;

            function formatTime(value) {
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return '';
                }
                return date.toLocaleString();
            }

            function renderNotifications(payload) {
                list.innerHTML = '';
                const notifications = payload.notifications || [];
                empty.classList.toggle('hidden', notifications.length > 0);

                notifications.forEach((item) => {
                    const data = item.data || {};
                    const wrapper = document.createElement('a');
                    wrapper.href = data.view_url || '{{ route('account.orders') }}';
                    wrapper.dataset.notificationId = item.id;
                    wrapper.className = 'block px-4 py-3 border-b border-gray-100 hover:bg-gray-50';

                    const title = document.createElement('p');
                    title.className = 'text-sm font-semibold text-gray-800';
                    title.textContent = data.title || 'Order Update';

                    const body = document.createElement('p');
                    body.className = 'text-xs text-gray-600 mt-1';
                    body.textContent = data.body || '';

                    const time = document.createElement('p');
                    time.className = 'text-[11px] text-gray-400 mt-2';
                    time.textContent = formatTime(data.created_at || item.created_at || '');

                    if (!item.read_at) {
                        wrapper.classList.add('bg-[#FFF6EB]');
                    }

                    wrapper.appendChild(title);
                    wrapper.appendChild(body);
                    wrapper.appendChild(time);
                    list.appendChild(wrapper);
                });
            }

            async function fetchNotifications() {
                const response = await fetch('{{ route('notifications.index') }}', {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                const unreadCount = payload.unread_count || 0;

                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }

                renderNotifications(payload);
            }

            async function markNotificationRead(id) {
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
            }

            async function markAllRead() {
                await fetch('{{ route('notifications.read-all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
                await fetchNotifications();
            }

            toggle.addEventListener('click', () => {
                isOpen = !isOpen;
                panel.classList.toggle('hidden', !isOpen);
                if (isOpen) {
                    fetchNotifications();
                }
            });

            document.addEventListener('click', (event) => {
                const root = document.getElementById('notification-root');
                if (!root.contains(event.target)) {
                    isOpen = false;
                    panel.classList.add('hidden');
                }
            });

            list.addEventListener('click', async (event) => {
                const target = event.target.closest('a[data-notification-id]');
                if (!target) {
                    return;
                }

                event.preventDefault();
                const id = target.dataset.notificationId;
                await markNotificationRead(id);
                window.location.href = target.href;
            });

            markAll.addEventListener('click', async () => {
                await markAllRead();
            });

            fetchNotifications();
            setInterval(fetchNotifications, 15000);
        })();
    </script>
@endauth