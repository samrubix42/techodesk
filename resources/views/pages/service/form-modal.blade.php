<div x-data="{ modalOpen: false }" x-on:open-service-modal.window="modalOpen = true" x-on:close-service-modal.window="modalOpen = false" x-cloak>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-90 flex items-center justify-center p-4">
            <div @click="modalOpen=false" class="absolute inset-0 bg-slate-900/50"></div>

            <div x-show="modalOpen" x-transition x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-950">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $serviceId ? 'Edit Service' : 'Add Service' }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Save service details.</p>
                    </div>
                    <button @click="modalOpen=false" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-900 dark:hover:text-slate-200">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="space-y-5 px-6 py-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Name</label>
                            <input wire:model.live="name" placeholder="Service name" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Slug</label>
                            <input wire:model.live="slug" placeholder="service-slug" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('slug') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/40">
                    <button @click="modalOpen=false" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-2 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-900">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
