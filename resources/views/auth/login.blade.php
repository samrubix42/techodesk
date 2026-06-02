@extends('layouts.guest')

@section('content')
<div class="w-full max-w-md">
    <div class="flex flex-col items-center mb-8">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold uppercase text-white dark:bg-white dark:text-slate-900">TD</span>
        <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Welcome back</h2>
        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Sign in to your Techodesk account to continue.</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                    Email address
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    placeholder="e.g. test@example.com"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-slate-600"
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-slate-600"
                >
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:ring-offset-slate-950 dark:focus:ring-slate-600"
                >
                <label for="remember" class="ml-2 block text-sm text-slate-600 dark:text-slate-400 selection:bg-transparent">
                    Remember me
                </label>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
            >
                Sign in
            </button>
        </form>
    </div>
</div>
@endsection
