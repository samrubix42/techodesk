
<div class="space-y-5 px-3 py-5 sm:space-y-6 sm:px-4 sm:py-6 lg:px-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-2xl">Service Management</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Create, update, and manage services.</p>
        </div>

        <button
            type="button"
            @click="$dispatch('open-service-modal'); $wire.resetForm()"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 sm:w-auto dark:bg-white dark:text-slate-900"
        >
            <i class="ri-add-line text-base"></i>
            Add Service
        </button>
    </div>

    <div class="relative w-full sm:w-96">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search services..."
            class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-4 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600"
        >
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-900/60 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Slug</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($this->services as $service)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-900 dark:text-white">{{ $service->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $service->slug }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" @click="$dispatch('open-service-modal'); $wire.openEditModal({{ $service->id }})" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">Edit</button>
                                    <button type="button" @click="$dispatch('open-service-delete-modal'); $wire.confirmDelete({{ $service->id }})" class="rounded-md bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 transition hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-200 dark:hover:bg-rose-900/50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-400">No services found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-3 md:hidden">
            @forelse ($this->services as $service)
                <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-900 dark:text-white">{{ $service->name }}</p>
                            <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">{{ $service->slug }}</p>
                        </div>
                        <button type="button" @click="$dispatch('open-service-modal'); $wire.openEditModal({{ $service->id }})" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 dark:bg-slate-900 dark:text-slate-200">Edit</button>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="truncate">{{ $service->slug }}</span>
                        <button type="button" @click="$dispatch('open-service-delete-modal'); $wire.confirmDelete({{ $service->id }})" class="rounded-md bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 dark:bg-rose-900/30 dark:text-rose-200">Delete</button>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 py-10 text-center text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40">No services found.</div>
            @endforelse
        </div>

        <div class="border-t border-slate-100 px-4 py-3 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:px-6">
            {{ $this->services->total() }} services
        </div>
    </div>

    @if ($this->services->hasPages())
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
            {{ $this->services->links() }}
        </div>
    @endif

    @include('pages.service.form-modal')
    @include('pages.service.delete-modal')
</div>