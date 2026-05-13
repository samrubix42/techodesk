<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css">

        <script>
            (() => {
                const stored = localStorage.getItem("theme");
                const isDark = stored
                    ? stored === "dark"
                    : window.matchMedia("(prefers-color-scheme: dark)").matches;
                document.documentElement.classList.toggle("dark", isDark);
            })();
        </script>

        <script>
            window.layoutState = () => ({
                isDark: document.documentElement.classList.contains("dark"),
                sidebarOpen: false,
                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem("theme", this.isDark ? "dark" : "light");
                    document.documentElement.classList.toggle("dark", this.isDark);
                },
                openSidebar() {
                    this.sidebarOpen = true;
                },
                closeSidebar() {
                    this.sidebarOpen = false;
                },
            });
        </script>

        @livewireStyles
    </head>
    <body
        class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100"
        x-data="layoutState()"
    >
        @include('components.toast')

        <livewire:public.header />

        <div class="flex w-full min-h-screen gap-4">
            <livewire:public.sidebar />

            <main class="min-w-0 flex-1">
                <div class="mx-auto w-full max-w-[1200px] px-3 sm:px-4 lg:px-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
    </body>
</html>
