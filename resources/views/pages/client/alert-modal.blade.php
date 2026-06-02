<div x-data="{ modalOpen: false }" x-on:open-alert-modal.window="modalOpen = true" x-on:close-alert-modal.window="modalOpen = false" x-cloak>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-90 flex items-center justify-center p-4">
            <div @click="modalOpen=false" class="absolute inset-0 bg-slate-900/50"></div>

            <div x-show="modalOpen" x-transition x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-950">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                            Payment Alerts for {{ $this->alertClient?->business_name ?? $this->alertClient?->name ?? 'Client' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Configure automated email reminders after a set interval.</p>
                    </div>
                    <button @click="modalOpen=false" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-900 dark:hover:text-slate-200">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-6 px-6 py-5">
                    <!-- Current Active Alerts Section -->
                    <div>
                        <h4 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Active Alerts</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            @forelse($clientAlerts as $alert)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/40">
                                    @if ($confirmingDeleteAlertId === $alert->id)
                                        <div class="flex w-full items-center justify-between">
                                            <span class="text-xs font-semibold text-rose-600 dark:text-rose-400">Delete this alert? This action is permanent.</span>
                                            <div class="flex items-center gap-2">
                                                <button 
                                                    type="button" 
                                                    wire:click="cancelDeleteAlert" 
                                                    class="rounded-lg bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                                                >
                                                    Cancel
                                                </button>
                                                <button 
                                                    type="button" 
                                                    wire:click="deleteAlert({{ $alert->id }})" 
                                                    class="rounded-lg bg-rose-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-rose-700"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                <i class="ri-notification-3-line text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                                    {{ $alert->service?->name ?? 'Unknown Service' }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    @if($alert->alert_type === 'interval_days')
                                                        Alert after <strong class="text-slate-700 dark:text-slate-300">{{ $alert->days_interval }} days</strong>
                                                    @else
                                                        Alert on <strong class="text-slate-700 dark:text-slate-300">{{ $alert->alert_date ? \Carbon\Carbon::parse($alert->alert_date)->format('M d, Y h:i A') : 'N/A' }}</strong>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button 
                                                type="button" 
                                                wire:click="editAlert({{ $alert->id }})" 
                                                class="rounded-lg p-1.5 text-blue-500 transition hover:bg-blue-50 dark:hover:bg-blue-950/40"
                                                title="Edit Alert"
                                            >
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="confirmDeleteAlert({{ $alert->id }})" 
                                                class="rounded-lg p-1.5 text-rose-500 transition hover:bg-rose-50 dark:hover:bg-rose-950/40"
                                                title="Delete Alert"
                                            >
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 py-6 text-center text-sm text-slate-400 dark:border-slate-800 dark:bg-slate-900/10">
                                    No payment alerts configured.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-800">

                    <!-- Configure New Alert Section -->
                    <div>
                        <h4 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            {{ $this->editingAlertId ? 'Edit Alert' : 'Add New Alert' }}
                        </h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Service <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model.live="alertServiceId" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                                    <option value="">Select Service</option>
                                    @foreach($this->services as $srv)
                                        <option value="{{ $srv->id }}">{{ $srv->name }}</option>
                                    @endforeach
                                </select>
                                @error('alertServiceId') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Alert Type <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model.live="alertType" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600">
                                    <option value="interval_days">Days Interval</option>
                                    <option value="specific_date">Specific Date</option>
                                </select>
                                @error('alertType') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            @if($alertType === 'interval_days')
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Days Interval <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            min="1" 
                                            wire:model.live="alertDaysInterval" 
                                            placeholder="e.g. 7" 
                                            class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-3 pr-16 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600"
                                        >
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-xs text-slate-400">Days</span>
                                        </div>
                                    </div>
                                    @error('alertDaysInterval') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>
                            @else
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Target Date & Time <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        wire:model.live="alertDate" 
                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100 dark:focus:border-slate-600"
                                    >
                                    @error('alertDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/40">
                    @if ($this->editingAlertId)
                        <button 
                            type="button"
                            wire:click="resetAlertForm" 
                            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900"
                        >
                            Cancel Edit
                        </button>
                    @endif
                    <button @click="modalOpen=false" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">Close</button>
                    <button 
                        wire:click="saveAlert" 
                        wire:loading.attr="disabled" 
                        class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-2 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-900"
                    >
                        <span wire:loading.remove wire:target="saveAlert">
                            {{ $this->editingAlertId ? 'Update Alert' : 'Add Alert' }}
                        </span>
                        <span wire:loading wire:target="saveAlert">
                            {{ $this->editingAlertId ? 'Updating...' : 'Adding...' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
