<!-- ========================= -->
<!-- MAIN LAYOUT -->
<!-- ========================= -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Remix Icon -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css"
    >

    <!-- TinyMCE -->
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>

    <!-- Theme -->
    <script>
        function applyTheme() {
            const theme = localStorage.getItem('theme');

            if (
                theme === 'dark' ||
                (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        applyTheme();

        document.addEventListener('livewire:navigated', () => {
            applyTheme();
        });
    </script>

    @livewireStyles
</head>

<body
    x-data="{
        sidebarOpen: false,

        toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');

            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        },

        openSidebar() {
            this.sidebarOpen = true
        },

        closeSidebar() {
            this.sidebarOpen = false
        }
    }"
    class="min-h-screen overflow-hidden bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100"
>

    @include('components.toast')

    <!-- Header -->
    <livewire:public.header />

    <!-- Layout -->
    <div class="flex h-[calc(100vh-64px)] overflow-hidden">

        <!-- Sidebar -->
        <livewire:public.sidebar />

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

    </div>

    @livewireScripts
</body>
</html>