<nav x-data="{ open: false }" class=" border-b border-white" style="background: linear-gradient(to bottom, #a97851, #6b4c33)">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.index')">
                        {{ __('Books') }}
                    </x-nav-link>
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role == 'user')
                            <x-nav-link :href="route('user.borrowed')" :active="request()->routeIs('user.borrowed')">
                                {{ __('Borrowed Books') }}
                            </x-nav-link>
                            @elseif(Auth::user()->role == 'librarian')
                            <x-nav-link :href="route('books.create')" :active="request()->routeIs('books.create')">
                                {{ __('Add A New Book') }}
                            </x-nav-link>
                            <x-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.index')">
                                {{ __('Loans') }}
                            </x-nav-link>
                            <x-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.index')">
                                {{ __('Reservations') }}
                            </x-nav-link>
                            <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
                                {{ __('Members') }}
                            </x-nav-link>
                            @elseif(Auth::user()->role == 'admin')
                                <x-nav-link :href="route('librarians.index')" :active="request()->routeIs('librarians.index')">
                                    {{ __('Librarians') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')">
                                    {{ __('Statistics') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin_settings.index')" :active="request()->routeIs('admin_settings.index')">
                                    {{ __('Library settings') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.device-detections.index')" :active="request()->routeIs('admin.device-detections.index')">
                                    {{ __('Device Detection') }}
                                </x-nav-link>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
            @auth
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-5 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:bg-orange-300 focus:outline-none transition ease-in-out duration-150">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center px-3 py-5 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:bg-orange-300 focus:outline-none transition ease-in-out duration-150">
                            Register
                        </a>
                    @endif
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-black hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.index')">
                {{ __('Books') }}
            </x-responsive-nav-link>
            @auth
                @if(Auth::user()->role == 'user')
            <x-responsive-nav-link :href="route('user.borrowed')" :active="request()->routeIs('user.borrowed')">
                {{ __('Borrowed Books') }}
            </x-responsive-nav-link>
                @elseif(Auth::user()->role == 'librarian')
                <x-responsive-nav-link :href="route('books.create')" :active="request()->routeIs('books.create')">
                    {{ __('Add A New Book') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.index')">
                    {{ __('Loans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.index')">
                    {{ __('Reservations') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.index')">
                    {{ __('Members') }}
                </x-responsive-nav-link>
                @elseif(Auth::user()->role == 'admin')
                <x-responsive-nav-link :href="route('librarians.index')" :active="request()->routeIs('librarians.index')">
                    {{ __('Librarians') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')">
                    {{ __('Statistics') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin_settings.index')" :active="request()->routeIs('admin_settings.index')">
                    {{ __('Library settings') }}
                </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.device-detections.index')" :active="request()->routeIs('admin.device-detections.index')">
                        {{ __('Library settings') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-orange-300">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-orange-200">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        Log in
                    </x-responsive-nav-link>

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            Register
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
