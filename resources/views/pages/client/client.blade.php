
<div class="space-y-5 px-3 py-5 sm:space-y-6 sm:px-4 sm:py-6 lg:px-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-2xl">Client Management</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Create, update, and manage client records.</p>
        </div>

        <button
            type="button"
            @click="$dispatch('open-client-modal'); $wire.resetForm()"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 sm:w-auto dark:bg-white dark:text-slate-900"
        >
            <i class="ri-add-line text-base"></i>
            Add Client
        </button>
    </div>

    <div class="relative w-full sm:w-96">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search clients..."
            class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-4 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600"
        >
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-900/60 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Phone</th>
                        <th class="px-6 py-4 text-left">Business</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($this->clients as $client)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-900 dark:text-white">{{ $client->name }}</p>
                                @if ($client->city || $client->state)
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $client->city }} {{ $client->state }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $client->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $client->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $client->business_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" @click="$dispatch('open-alert-modal'); $wire.openAlertModal({{ $client->id }})" class="rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-200 dark:hover:bg-blue-900/50">Alerts</button>
                                    <button type="button" @click="$dispatch('open-client-modal'); $wire.openEditModal({{ $client->id }})" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">Edit</button>
                                    <button type="button" @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $client->id }})" class="rounded-md bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 transition hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-200 dark:hover:bg-rose-900/50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">No clients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-3 md:hidden">
            @forelse ($this->clients as $client)
                <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-900 dark:text-white">{{ $client->name }}</p>
                            <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">{{ $client->email ?? '-' }}</p>
                        </div>
                        <button type="button" @click="$dispatch('open-client-modal'); $wire.openEditModal({{ $client->id }})" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 dark:bg-slate-900 dark:text-slate-200">Edit</button>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>{{ $client->phone ?? '-' }}</span>
                        <span>{{ $client->business_name ?? '-' }}</span>
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="$dispatch('open-alert-modal'); $wire.openAlertModal({{ $client->id }})" class="rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-200">Alerts</button>
                            <button type="button" @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $client->id }})" class="rounded-md bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 dark:bg-rose-900/30 dark:text-rose-200">Delete</button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 py-10 text-center text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40">No clients found.</div>
            @endforelse
        </div>

        <div class="border-t border-slate-100 px-4 py-3 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:px-6">
            {{ $this->clients->total() }} clients
        </div>
    </div>

    @if ($this->clients->hasPages())
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
            {{ $this->clients->links() }}
        </div>
    @endif

    @include('pages.client.form-modal')
    @include('pages.client.delete-modal')
    @include('pages.client.alert-modal')
</div>