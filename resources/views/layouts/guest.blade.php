<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
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
<body class="h-full overflow-y-auto text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
    @include('components.toast')

    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>
