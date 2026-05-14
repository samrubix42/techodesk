
<div class="space-y-6 px-3 py-5 sm:px-4 sm:py-6 lg:px-6">
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-slate-100 p-6 shadow-sm dark:border-slate-800 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Invoice Settings</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Upload header and footer images and control their size in percent.</p>
    </div>

    <div class="flex flex-col gap-6">
        <section x-data="{ headerWidth: @entangle('headerWidth').live, headerHeight: @entangle('headerHeight').live }" class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-slate-100 blur-3xl dark:bg-slate-800/40"></div>
            <div class="relative">
                <div wire:loading wire:target="headerImage,saveHeader,deleteHeader" class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-white/70 backdrop-blur-sm dark:bg-slate-950/70">
                    <div class="flex items-center gap-3 rounded-full bg-white px-4 py-2 text-xs font-medium text-slate-600 shadow-sm dark:bg-slate-900 dark:text-slate-300">
                        <span class="h-3 w-3 animate-pulse rounded-full bg-slate-500"></span>
                        Uploading...
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Header Invoice</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">Image</span>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Upload</label>
                        <label class="mt-2 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center transition hover:border-slate-400 hover:bg-white dark:border-slate-700 dark:bg-slate-900/40 dark:hover:border-slate-600 dark:hover:bg-slate-900/60">
                            <input type="file" accept="image/*" wire:model="headerImage" class="sr-only">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm dark:bg-slate-900">
                                <span class="text-xl text-slate-500">+</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Drop image here or click to upload</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG up to 2MB</p>
                            </div>
                        </label>
                        @error('headerImage')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Width (%)</label>
                            <input
                                type="number"
                                min="1"
                                max="100"
                                step="0.1"
                                inputmode="decimal"
                                x-model.number="headerWidth"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100"
                            >
                            @error('headerWidth')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Height (%)</label>
                            <input
                                type="number"
                                min="1"
                                max="100"
                                step="0.1"
                                inputmode="decimal"
                                x-model.number="headerHeight"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100"
                            >
                            @error('headerHeight')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            @click="$wire.set('headerWidth', headerWidth); $wire.set('headerHeight', headerHeight); $wire.saveHeader()"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900"
                        >
                            Update
                        </button>
                        <button
                            type="button"
                            @click="$dispatch('open-setting-delete-modal'); $wire.confirmDelete('header')"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-900/40 dark:text-rose-300 dark:hover:bg-rose-900/30"
                            aria-label="Delete header image"
                        >
                            <i class="ri-delete-bin-6-line text-lg"></i>
                        </button>
                    </div>

                    @if ($headerImage)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-900/40">
                            <p class="mb-2 text-xs font-medium text-slate-500 dark:text-slate-400">Preview</p>
                            <img
                                src="{{ $headerImage->temporaryUrl() }}"
                                alt="Header invoice preview"
                                x-bind:style="`width: ${headerWidth || 100}%; transform: scaleY(${(headerHeight || 100) / 100}); transform-origin: top left;`"
                                class="max-w-full rounded-lg border border-slate-200 bg-white dark:border-slate-800"
                            >
                        </div>
                    @elseif ($headerImagePath)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-900/40">
                            <img
                                src="{{ asset('storage/' . $headerImagePath) }}"
                                alt="Header invoice"
                                x-bind:style="`width: ${headerWidth || 100}%; transform: scaleY(${(headerHeight || 100) / 100}); transform-origin: top left;`"
                                class="max-w-full rounded-lg border border-slate-200 bg-white dark:border-slate-800"
                            >
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section x-data="{ footerWidth: @entangle('footerWidth').live, footerHeight: @entangle('footerHeight').live }" class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="pointer-events-none absolute -left-10 -top-10 h-32 w-32 rounded-full bg-slate-100 blur-3xl dark:bg-slate-800/40"></div>
            <div class="relative">
                <div wire:loading wire:target="footerImage,saveFooter,deleteFooter" class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-white/70 backdrop-blur-sm dark:bg-slate-950/70">
                    <div class="flex items-center gap-3 rounded-full bg-white px-4 py-2 text-xs font-medium text-slate-600 shadow-sm dark:bg-slate-900 dark:text-slate-300">
                        <span class="h-3 w-3 animate-pulse rounded-full bg-slate-500"></span>
                        Uploading...
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Footer Invoice</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">Image</span>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Upload</label>
                        <label class="mt-2 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center transition hover:border-slate-400 hover:bg-white dark:border-slate-700 dark:bg-slate-900/40 dark:hover:border-slate-600 dark:hover:bg-slate-900/60">
                            <input type="file" accept="image/*" wire:model="footerImage" class="sr-only">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm dark:bg-slate-900">
                                <span class="text-xl text-slate-500">+</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Drop image here or click to upload</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG up to 2MB</p>
                            </div>
                        </label>
                        @error('footerImage')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Width (%)</label>
                            <input
                                type="number"
                                min="1"
                                max="100"
                                step="0.1"
                                inputmode="decimal"
                                x-model.number="footerWidth"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100"
                            >
                            @error('footerWidth')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Height (%)</label>
                            <input
                                type="number"
                                min="1"
                                max="100"
                                step="0.1"
                                inputmode="decimal"
                                x-model.number="footerHeight"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100"
                            >
                            @error('footerHeight')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            @click="$wire.set('footerWidth', footerWidth); $wire.set('footerHeight', footerHeight); $wire.saveFooter()"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900"
                        >
                            Update
                        </button>
                        <button
                            type="button"
                            @click="$dispatch('open-setting-delete-modal'); $wire.confirmDelete('footer')"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-900/40 dark:text-rose-300 dark:hover:bg-rose-900/30"
                            aria-label="Delete footer image"
                        >
                            <i class="ri-delete-bin-6-line text-lg"></i>
                        </button>
                    </div>

                    @if ($footerImage)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-900/40">
                            <p class="mb-2 text-xs font-medium text-slate-500 dark:text-slate-400">Preview</p>
                            <img
                                src="{{ $footerImage->temporaryUrl() }}"
                                alt="Footer invoice preview"
                                x-bind:style="`width: ${footerWidth || 100}%; transform: scaleY(${(footerHeight || 100) / 100}); transform-origin: top left;`"
                                class="max-w-full rounded-lg border border-slate-200 bg-white dark:border-slate-800"
                            >
                        </div>
                    @elseif ($footerImagePath)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-900/40">
                            <img
                                src="{{ asset('storage/' . $footerImagePath) }}"
                                alt="Footer invoice"
                                x-bind:style="`width: ${footerWidth || 100}%; transform: scaleY(${(footerHeight || 100) / 100}); transform-origin: top left;`"
                                class="max-w-full rounded-lg border border-slate-200 bg-white dark:border-slate-800"
                            >
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-slate-100 blur-3xl dark:bg-slate-800/40"></div>
            <div class="relative">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Bank Card & Details</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">Text</span>
                </div>

                <div class="mt-4 space-y-4">
                    <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-5 text-white shadow-lg ring-1 ring-white/10">
                        <div class="flex items-center justify-between">
                            <p class="text-xs uppercase tracking-[0.16em] text-slate-300">Bank Details</p>
                            <p class="text-xs font-medium text-emerald-300">Primary Account</p>
                        </div>
                        <p class="mt-4 text-lg font-semibold">{{ $accountHolderName ?: 'Account Holder Name' }}</p>
                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-[11px] uppercase tracking-[0.12em] text-slate-400">Account No.</p>
                                <p class="mt-1 font-medium">{{ $accountNumber ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-[0.12em] text-slate-400">IFSC</p>
                                <p class="mt-1 font-medium">{{ $ifscCode ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-[0.12em] text-slate-400">UPI ID</p>
                                <p class="mt-1 font-medium break-all">{{ $upiId ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-[0.12em] text-slate-400">Branch</p>
                                <p class="mt-1 font-medium">{{ $branch ?: '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Bank Details</p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Account Holder Name</label>
                            <input type="text" wire:model.live="accountHolderName" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('accountHolderName')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Account Number</label>
                            <input type="text" wire:model.live="accountNumber" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('accountNumber')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">IFSC Code</label>
                            <input type="text" wire:model.live="ifscCode" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('ifscCode')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">UPI ID</label>
                            <input type="text" wire:model.live="upiId" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('upiId')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Branch</label>
                        <input type="text" wire:model.live="branch" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                        @error('branch')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Tax Rates (%)</p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">IGST (%)</label>
                            <input type="number" min="0" max="100" step="0.01" wire:model.live="igst" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('igst')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">CGST (%)</label>
                            <input type="number" min="0" max="100" step="0.01" wire:model.live="cgst" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('cgst')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">SGST (%)</label>
                            <input type="number" min="0" max="100" step="0.01" wire:model.live="sgst" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('sgst')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Proforma Due Days</label>
                        <input type="number" min="0" max="3650" step="1" wire:model.live="proformaDueDays" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                        @error('proformaDueDays')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Company Location</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Company Address</label>
                        <textarea wire:model.live="companyAddress" rows="2" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100"></textarea>
                        @error('companyAddress')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">State</label>
                            <input type="text" wire:model.live="companyState" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('companyState')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Country</label>
                            <input type="text" wire:model.live="companyCountry" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-100">
                            @error('companyCountry')
                                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            @click="$dispatch('open-bank-save-confirm-modal')"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900"
                        >
                            Save Bank Details
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="pointer-events-none absolute -left-10 -top-10 h-32 w-32 rounded-full bg-slate-100 blur-3xl dark:bg-slate-800/40"></div>
            <div class="relative">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Invoice Notes</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">Editor</span>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Proforma Invoice Notes</label>
                        <div wire:ignore class="mt-2 rounded-xl border border-slate-300 bg-white p-2 dark:border-slate-800 dark:bg-slate-900/60">
                            <textarea id="proforma-notes-editor" class="min-h-[180px] w-full rounded-lg bg-white p-2 text-sm text-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ $proformaNotes }}</textarea>
                        </div>
                        @error('proformaNotes')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Tax Invoice Notes</label>
                        <div wire:ignore class="mt-2 rounded-xl border border-slate-300 bg-white p-2 dark:border-slate-800 dark:bg-slate-900/60">
                            <textarea id="general-notes-editor" class="min-h-[180px] w-full rounded-lg bg-white p-2 text-sm text-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ $generalNotes }}</textarea>
                        </div>
                        @error('generalNotes')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            wire:click="saveNotes"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900"
                        >
                            Save Notes
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div x-data="{ bankSaveConfirmOpen: false }" x-on:open-bank-save-confirm-modal.window="bankSaveConfirmOpen = true" x-on:close-bank-save-confirm-modal.window="bankSaveConfirmOpen = false" x-cloak>
        <template x-teleport="body">
            <div x-show="bankSaveConfirmOpen" class="fixed inset-0 z-95 flex items-center justify-center px-4">
                <div @click="bankSaveConfirmOpen = false" class="absolute inset-0 bg-slate-900/50"></div>

                <div x-show="bankSaveConfirmOpen" x-transition x-trap.inert.noscroll="bankSaveConfirmOpen" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Save Bank Details</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        Please confirm you want to save the updated bank details for invoices.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button @click="bankSaveConfirmOpen = false" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">Cancel</button>
                        <button wire:click="saveBankDetails" @click="bankSaveConfirmOpen = false" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">Confirm Save</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-data="{ deleteOpen: false }" x-on:open-setting-delete-modal.window="deleteOpen = true" x-on:close-setting-delete-modal.window="deleteOpen = false" x-cloak>
        <template x-teleport="body">
            <div x-show="deleteOpen" class="fixed inset-0 z-95 flex items-center justify-center px-4">
                <div @click="deleteOpen=false" class="absolute inset-0 bg-slate-900/50"></div>

                <div x-show="deleteOpen" x-transition x-trap.inert.noscroll="deleteOpen" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Delete Invoice Image</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        This will remove the
                        <span class="font-medium text-slate-900 dark:text-white">
                            {{ $deleteTarget === 'header' ? 'Header Invoice' : ($deleteTarget === 'footer' ? 'Footer Invoice' : 'selected') }}
                        </span>
                        image. This action cannot be undone.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button @click="deleteOpen=false" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">Cancel</button>
                        <button wire:click="deleteConfirmed" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">Delete</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

@script
<script>
    const tinyBaseUrl = "{{ asset('tinymce') }}";

    const initTinyEditor = (id, livewireField) => {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }

        const existing = tinymce.get(id);
        if (existing) {
            existing.remove();
        }

        tinymce.init({
            target: el,
            base_url: tinyBaseUrl,
            suffix: '.min',
            height: 260,
            menubar: false,
            promotion: false,
            plugins: 'lists link table code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | link table | code',
            setup: (editor) => {
                editor.on('init', () => {
                    $wire.set(livewireField, editor.getContent());
                });

                editor.on('change keyup', () => {
                    $wire.set(livewireField, editor.getContent());
                });
            }
        });
    };

    const bootEditors = () => {
        if (typeof tinymce === 'undefined') {
            return;
        }

        initTinyEditor('proforma-notes-editor', 'proformaNotes');
        initTinyEditor('general-notes-editor', 'generalNotes');
    };

    bootEditors();
    document.addEventListener('livewire:initialized', bootEditors);
    document.addEventListener('livewire:load', bootEditors);
    document.addEventListener('livewire:navigated', bootEditors);
</script>
@endscript
