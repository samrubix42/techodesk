<div>
    <!-- Mobile Overlay -->
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        x-cloak
        @click="closeSidebar()"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"
    ></div>

    <!-- Sidebar -->
    <aside
        x-cloak
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 top-16 z-50 flex w-64 flex-col border-r border-slate-200 bg-white transition-all duration-300 dark:border-slate-800 dark:bg-slate-950 lg:static lg:translate-x-0"
    >

        <div class="flex h-full flex-col px-3 py-4">

            <!-- Navigation -->
            <nav class="space-y-1.5">

                <!-- Dashboard -->
                <a
                    href="{{ route('dashboard') }}"
                    wire:navigate
                    class="{{ request()->routeIs('dashboard')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('dashboard')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-home-5-line text-lg"></i>
                    </div>

                    <span>Dashboard</span>
                </a>

                <!-- Clients -->
                <a
                    href="{{ route('clients') }}"
                    wire:navigate
                    class="{{ request()->routeIs('clients')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('clients')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-group-line text-lg"></i>
                    </div>

                    <span>Clients</span>
                </a>

                <!-- Services -->
                <a
                    href="{{ route('services') }}"
                    wire:navigate
                    class="{{ request()->routeIs('services')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('services')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-briefcase-4-line text-lg"></i>
                    </div>

                    <span>Services</span>
                </a>

                <!-- Invoice -->
                <a
                    href="{{ route('invoiceing') }}"
                    wire:navigate
                    class="{{ request()->routeIs('invoiceing')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('invoiceing')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-file-list-3-line text-lg"></i>
                    </div>

                    <span>Invoice</span>
                </a>

                <!-- Proforma -->
                <a
                    href="{{ route('invoice-list.proforma') }}"
                    wire:navigate
                    class="{{ request()->routeIs('invoice-list.proforma')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('invoice-list.proforma')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-file-copy-2-line text-lg"></i>
                    </div>

                    <span>Proforma Invoice List</span>
                </a>
                <a
                    href="{{ route('invoice-list.general') }}"
                    wire:navigate
                    class="{{ request()->routeIs('invoice-list.general')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('invoice-list.general')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-file-copy-2-line text-lg"></i>
                    </div>

                    <span>Tax Invoice List</span>
                </a>

                <!-- Settings -->
                <a
                    href="{{ route('settings') }}"
                    wire:navigate
                    class="{{ request()->routeIs('settings')
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10 dark:bg-white dark:text-slate-900'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900'
                    }} group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium transition-all duration-200"
                >
                    <div class="{{ request()->routeIs('settings')
                        ? 'bg-white/10 dark:bg-slate-200'
                        : 'bg-slate-100 dark:bg-slate-900'
                    }} flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                        <i class="ri-settings-3-line text-lg"></i>
                    </div>

                    <span>Settings</span>
                </a>

            </nav>

 

        </div>

    </aside>
</div>