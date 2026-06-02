
<header class="sticky top-0 z-30 w-full border-b border-slate-200/70 bg-white/80 backdrop-blur dark:border-slate-800/80 dark:bg-slate-950/70">
    <div class="mx-auto flex h-16 items-center gap-4 px-6 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-700 transition hover:bg-slate-100 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-900 lg:hidden"
                @click="openSidebar()"
                aria-label="Open sidebar"
            >
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h10" />
                </svg>
            </button>

            <a class="flex items-center gap-2" href="#">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-xs font-semibold uppercase text-white dark:bg-white dark:text-slate-900">TD</span>
                <span class="text-base font-semibold text-slate-900 dark:text-white">Techodesk</span>
            </a>
        </div>

        <div class="hidden flex-1 ml-20 lg:flex">
            <div class="relative w-full max-w-xl">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" d="m21 21-4.3-4.3" />
                        <circle cx="11" cy="11" r="6.5" />
                    </svg>
                </span>
                <input
                    type="search"
                    placeholder="Search workspace"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white/70 pl-10 pr-4 text-sm text-slate-700 outline-none transition focus:border-slate-300 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-200 dark:focus:border-slate-700"
                />
            </div>
        </div>

        <div class="ml-auto flex items-center gap-2">
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-700 transition hover:bg-slate-100 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-900"
                @click="toggleTheme()"
                aria-label="Toggle theme"
            >
                <i x-show="!isDark" class="ri-sun-line text-lg"></i>
                <i x-show="isDark" x-cloak class="ri-moon-line text-lg"></i>
            </button>

            <button
                type="button"
                class="hidden h-9 items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm text-slate-700 transition hover:bg-slate-100 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-900 sm:inline-flex"
            >
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Online
            </button>

            <button
                type="button"
                class="inline-flex h-9 items-center gap-2 rounded-lg bg-slate-900 px-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900"
            >
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                </svg>
                New
            </button>

            <!-- Profile Dropdown -->
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button
                    @click="dropdownOpen = !dropdownOpen"
                    @click.outside="dropdownOpen = false"
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-xs font-semibold text-white transition hover:opacity-90 dark:bg-white dark:text-slate-900"
                    aria-label="Open profile"
                >
                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'US' }}
                </button>
                <div 
                    x-show="dropdownOpen" 
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="opacity-0 scale-95" 
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    x-cloak
                    class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl border border-slate-200 bg-white py-1 shadow-lg outline-none dark:border-slate-800 dark:bg-slate-950"
                >
                    <div class="border-b border-slate-100 px-4 py-2.5 dark:border-slate-800">
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Signed in as</p>
                        <p class="mt-1 truncate text-sm font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name ?? 'Guest User' }}</p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <div class="p-1">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/20"
                            >
                                <i class="ri-logout-box-r-line"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>