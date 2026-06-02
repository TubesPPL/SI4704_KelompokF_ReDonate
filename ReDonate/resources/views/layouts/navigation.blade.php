<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-bold text-xl text-gray-900">Re<span class="text-teal-600">Donate</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Beranda
                    </x-nav-link>
                    <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                        Katalog
                    </x-nav-link>
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        Event
                    </x-nav-link>
                    <x-nav-link :href="route('wishlist-requests.index')" :active="request()->routeIs('wishlist-requests.*')">
                        Barang Dibutuhkan
                    </x-nav-link>
                    <x-nav-link href="#how-it-works">
                        Cara Kerja
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side (Auth / Guest) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Chat Icon -->
                    <x-chat-dropdown />

                    <!-- Notifications -->
                    <x-notification-dropdown />

                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 border border-transparent text-sm font-medium rounded-full text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 p-1 pr-3 shadow-sm hover:shadow">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ Auth::user()->name }}" />
                                <div>{{ explode(' ', Auth::user()->name)[0] }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                Dashboard
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil Saya
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('items.create')">
                                Donasikan Barang
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('recipient.dashboard')">
                                Riwayat Klaim
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('wishlist.index')">
                                Wishlist Saya
                            </x-dropdown-link>

                            @if(Auth::user()->role === 'admin')
                                <div class="border-t border-gray-100 my-1"></div>
                                <x-dropdown-link :href="route('admin.dashboard')" class="text-purple-700 font-semibold">
                                    ⚙️ Panel Admin
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-teal-600 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 px-4 py-2 rounded-md transition shadow-sm">Daftar</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Beranda
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                Katalog
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                Event
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#how-it-works">
                Cara Kerja
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4 flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ Auth::user()->name }}" />
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profil Saya
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('items.create')">
                        Donasikan Barang
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('recipient.dashboard')">
                        Riwayat Klaim
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Keluar
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">Masuk</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">Daftar</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
