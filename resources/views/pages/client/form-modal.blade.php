<div x-data="{ modalOpen: false }" x-on:open-client-modal.window="modalOpen = true" x-on:close-client-modal.window="modalOpen = false" x-cloak>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-90 flex items-center justify-center p-4">
            <div @click="modalOpen=false" class="absolute inset-0 bg-slate-900/50"></div>

            <div x-show="modalOpen" x-transition x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-950">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $clientId ? 'Edit Client' : 'Add Client' }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Save client details and contact info.</p>
                    </div>
                    <button @click="modalOpen=false" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-900 dark:hover:text-slate-200">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="space-y-5 px-6 py-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Name</label>
                            <input wire:model.live="name" placeholder="e.g. Samir Khan" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Email</label>
                            <input wire:model.live="email" placeholder="email@example.com" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Phone</label>
                            <input wire:model.live="phone" placeholder="+91 98765 43210" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Business</label>
                            <input wire:model.live="business_name" placeholder="Business name" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('business_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">GST Number</label>
                            <input wire:model.live="gst_number" placeholder="GSTIN" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('gst_number') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Address</label>
                            <input wire:model.live="address" placeholder="Street, area" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">City</label>
                            <input wire:model.live="city" placeholder="City" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('city') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">State</label>
                            <input wire:model.live="state" placeholder="State" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('state') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Postal Code</label>
                            <input wire:model.live="postal_code" placeholder="Postal code" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('postal_code') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Country</label>
                            <input wire:model.live="country" placeholder="Country" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                            @error('country') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
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
