
<div>
    <div
        class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        x-cloak
        @click="closeSidebar()"
        aria-hidden="true"
    ></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col border-r border-slate-200/70 bg-white/95 p-4 text-sm text-slate-700 transition-transform duration-300 dark:border-slate-800/80 dark:bg-slate-950/95 dark:text-slate-200 lg:static lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        x-cloak
    >
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Workspace</p>
                <p class="text-base font-semibold text-slate-900 dark:text-white">Operations Hub</p>
            </div>
            <button
                type="button"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-100 dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-900 lg:hidden"
                @click="closeSidebar()"
                aria-label="Close sidebar"
            >
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6l-12 12" />
                </svg>
            </button>
        </div>

   

        <nav class="mt-5 flex-1 space-y-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Primary</p>
                <div class="mt-2 space-y-1">
                    <a class="flex items-center gap-3 rounded-lg bg-slate-900 px-3 py-2 text-white" href="#">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white/10">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" d="M3 12l9-9 9 9" />
                                <path stroke-linecap="round" d="M9 21V9h6v12" />
                            </svg>
                        </span>
                        Dashboard
                    </a>
                    <!-- <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-900" href="#">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" d="M4 7h16M4 12h10M4 17h7" />
                            </svg>
                        </span>
                        Projects
                    </a> -->
                    <a href="{{route('clients')}}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-900" href="#">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" d="M16 3H8a2 2 0 0 0-2 2v14l6-3 6 3V5a2 2 0 0 0-2-2Z" />
                            </svg>
                        </span>
                        Clients
                    </a>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Manage</p>
                <div class="mt-2 space-y-1">
                    <a class="flex items-center justify-between rounded-lg px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-900" href="#">
                        <span class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" d="M4 6h16M4 10h16M4 14h10" />
                                </svg>
                            </span>
                            Invoices
                        </span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-slate-900 dark:text-slate-400">24</span>
                    </a>
                    <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-900" href="#">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" d="M5 6h14v12H5z" />
                                <path stroke-linecap="round" d="M8 4v4M16 4v4" />
                            </svg>
                        </span>
                        Calendar
                    </a>
                    <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-900" href="#">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" d="M7 7h10M7 12h10M7 17h7" />
                            </svg>
                        </span>
                        Reports
                    </a>
                </div>
            </div>
        </nav>

    </aside>
</div>