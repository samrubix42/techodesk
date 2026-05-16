<div class="space-y-6 px-3 py-5 sm:px-4 sm:py-6 lg:px-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Create Invoice</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Step-wise invoice creation for Proforma and Tax Invoice types.</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="mb-5 flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step === 1 ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300' }}">1</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Client & Type</span>
            </div>
            <span class="h-px flex-1 bg-slate-200 dark:bg-slate-800"></span>
            <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step === 2 ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300' }}">2</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Invoice Details</span>
            </div>
        </div>

        @if ($step === 1)
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Invoice Type</label>
                    @if ($invoiceTypeLocked)
                        <div class="mt-2 inline-flex rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white dark:bg-white dark:text-slate-900">
                            {{ $invoiceType === 'general' ? 'Tax Invoice' : 'Proforma Invoice' }}
                        </div>
                    @else
                        <select wire:model.live="invoiceType" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                            <option value="proforma">Proforma Invoice</option>
                            <option value="general">Tax Invoice</option>
                        </select>
                    @endif
                    @error('invoiceType') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Search Client</label>
                    <input type="text" wire:model.live.debounce.300ms="clientSearch" placeholder="Search by name, business, email, phone" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                </div>

                <div class="space-y-2 rounded-xl border border-slate-200 p-3 dark:border-slate-800">
                    @forelse ($this->clients as $client)
                        <button type="button" wire:click="selectClient({{ $client->id }})" class="w-full rounded-lg border px-3 py-2 text-left text-sm transition {{ $selectedClientId === $client->id ? 'border-slate-900 bg-slate-900 text-white dark:border-white dark:bg-white dark:text-slate-900' : 'border-slate-200 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-900' }}">
                            <p class="font-medium">{{ $client->name }}</p>
                            <p class="text-xs opacity-80">{{ $client->business_name ?: 'No business name' }} | {{ $client->email ?: 'No email' }}</p>
                        </button>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">No clients found.</p>
                    @endforelse
                </div>
                @error('selectedClientId') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror

                <div class="flex justify-end">
                    <button type="button" wire:click="nextStep" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-slate-900">Continue</button>
                </div>
            </div>
        @endif

        @if ($step === 2)
            <div class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white p-4 text-sm dark:border-slate-800 dark:from-slate-900 dark:to-slate-950">
                    <p><span class="font-medium">Client:</span> {{ $this->selectedClient?->name }}</p>
                    <p><span class="font-medium">Invoice Type:</span> {{ $invoiceType === 'general' ? 'Tax Invoice' : 'Proforma Invoice' }}</p>
                    <p><span class="font-medium">Preview Invoice No:</span> {{ $this->previewInvoiceNumber }}</p>
                </div>

                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Copy From Existing Invoice</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Copy items, prices, and status from old invoices.</p>
                        </div>
                        
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            {{-- Source Filter Toggle --}}
                            <div class="inline-flex rounded-lg bg-slate-100 p-1 dark:bg-slate-900">
                                <button type="button" wire:click="$set('copySourceFilter', 'client')" class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $copySourceFilter === 'client' ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-800 dark:text-white' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                                    Selected Client
                                </button>
                                <button type="button" wire:click="$set('copySourceFilter', 'all')" class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $copySourceFilter === 'all' ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-800 dark:text-white' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                                    Global Search
                                </button>
                            </div>

                            <div class="flex gap-2">
                                <select wire:model.live="copyInvoiceType" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs dark:border-slate-800 dark:bg-slate-900/60">
                                    <option value="all">All Types</option>
                                    <option value="proforma">Proforma</option>
                                    <option value="general">Tax Inv</option>
                                </select>
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="copyInvoiceSearch" placeholder="Search no, client or service..." class="w-full rounded-lg border border-slate-300 bg-white pl-8 pr-3 py-1.5 text-xs lg:w-64 dark:border-slate-800 dark:bg-slate-900/60">
                                    <svg class="absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 md:grid-cols-2">
                        @forelse ($this->copySourceInvoices as $source)
                            <div class="group relative flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3 transition hover:border-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-white">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if ($source['type'] === 'general')
                                                <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400">TAX</span>
                                            @else
                                                <span class="rounded bg-amber-50 px-1.5 py-0.5 text-[10px] font-bold text-amber-700 ring-1 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400">PROFORMA</span>
                                            @endif
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $source['invoice_number'] }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $source['invoice_date'] }}</span>
                                        </div>
                                        <div class="mt-1 truncate">
                                            <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-300">{{ $source['client_name'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex shrink-0 gap-1.5">
                                        <button type="button" wire:click="previewCopyInvoice('{{ $source['type'] }}', {{ $source['id'] }})" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-900">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                        <button type="button" wire:click="copyFromInvoice('{{ $source['type'] }}', {{ $source['id'] }})" class="rounded-lg bg-slate-900 px-2.5 py-1.5 text-[11px] font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                                            Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-100 pt-2 dark:border-slate-800/50">
                                    <span class="text-[10px] text-slate-500">{{ $source['item_count'] }} item(s)</span>
                                    <span class="text-[11px] font-bold text-slate-900 dark:text-white">INR {{ number_format($source['total_price'], 0) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 rounded-xl border border-dashed border-slate-200 p-8 text-center dark:border-slate-800">
                                <p class="text-sm text-slate-500 dark:text-slate-400">No invoices found matching your criteria.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Invoice Service (Optional)</label>
                    <select wire:model.live="selectedServiceId" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                        <option value="">Select service (nullable)</option>
                        @foreach ($this->services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedServiceId') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Services & Prices</label>
                        <button type="button" wire:click="addItem" class="rounded-lg border border-slate-300 px-3 py-1 text-xs dark:border-slate-700">+ Add Row</button>
                    </div>

                    @foreach ($invoiceItems as $index => $item)
                        <div class="grid grid-cols-1 gap-2 rounded-xl border border-slate-200 bg-slate-50/60 p-3 md:grid-cols-12 dark:border-slate-800 dark:bg-slate-900/30">
                            <div class="md:col-span-9">
                                <label class="text-xs text-slate-500">Service Details</label>
                                <textarea wire:model.live="invoiceItems.{{ $index }}.service_details" rows="2" class="mt-1 w-full rounded-lg border border-slate-300 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-900/60"></textarea>
                                @error('invoiceItems.' . $index . '.service_details') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs text-slate-500">Price</label>
                                <input type="number" wire:model.live="invoiceItems.{{ $index }}.price" min="0.01" step="0.01" class="mt-1 w-full rounded-lg border border-slate-300 px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-900/60">
                                @error('invoiceItems.' . $index . '.price') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-end md:col-span-1">
                                <button type="button" wire:click="removeItem({{ $index }})" class="w-full rounded-lg border border-rose-300 px-2 py-2 text-xs text-rose-600">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Invoice Date</label>
                        <input type="date" wire:model.live="invoiceDate" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                        @error('invoiceDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    @if ($invoiceType === 'proforma')
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
                            <select wire:model.live="status" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                                <option value="unpaid">Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Payment Due Date</label>
                            <input type="date" wire:model.live="paymentDueDay" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                            @error('paymentDueDay') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div class="rounded-xl border border-slate-200 p-3 text-sm dark:border-slate-800 {{ $invoiceType === 'proforma' ? 'col-span-1' : 'col-span-3' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Subtotal:</span>
                            <span class="font-medium">INR {{ number_format($this->subTotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">{{ $this->taxLabel }}:</span>
                            <span class="font-medium">INR {{ number_format($this->taxAmount, 2) }}</span>
                        </div>
                        <div class="mt-1 flex items-center justify-between border-t border-slate-200 pt-1 dark:border-slate-800">
                            <span class="font-semibold text-slate-900 dark:text-white">Total:</span>
                            <span class="font-bold text-slate-900 dark:text-white">INR {{ number_format($this->totalAmount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button" wire:click="backStep" class="rounded-xl border border-slate-300 px-4 py-2 text-sm dark:border-slate-700">Back</button>
                    <button type="button" wire:click="openConfirm" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-slate-900">Preview & Confirm</button>
                </div>
            </div>
        @endif
    </div>

    @include('pages.invoiceing.confirm-modal')

    <div x-data="{ open: @entangle('copyPreviewOpen') }" x-cloak>
        <template x-teleport="body">
            <div x-show="open" style="position:fixed; inset:0; z-index:125; display:grid; place-items:center; padding:16px;">
                <div @click="$wire.closeCopyPreview()" style="position:absolute; inset:0; background:rgba(15,23,42,0.65);"></div>
                <div x-show="open" class="relative w-full max-w-3xl rounded-xl bg-white p-5 shadow-2xl dark:bg-slate-950">
                    @if ($copyPreviewInvoice)
                        <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 md:flex-row md:items-start md:justify-between dark:border-slate-800">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $copyPreviewInvoice['type_label'] }}</p>
                                <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $copyPreviewInvoice['invoice_number'] }}</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $copyPreviewInvoice['client_name'] }}</span> 
                                    | {{ $copyPreviewInvoice['invoice_date'] }} 
                                    | {{ $copyPreviewInvoice['status'] ?: 'N/A' }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" wire:click="copyFromInvoice('{{ $copyPreviewInvoice['type'] }}', {{ $copyPreviewInvoice['id'] }})" class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white dark:bg-white dark:text-slate-900">Copy Details</button>
                                <button type="button" wire:click="closeCopyPreview" class="rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-700">Close</button>
                            </div>
                        </div>

                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50 text-left dark:bg-slate-900">
                                        <th class="border border-slate-300 px-2 py-2 dark:border-slate-700">Sl. No.</th>
                                        <th class="border border-slate-300 px-2 py-2 dark:border-slate-700">Service Details</th>
                                        <th class="border border-slate-300 px-2 py-2 dark:border-slate-700">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($copyPreviewInvoice['items'] as $idx => $item)
                                        <tr>
                                            <td class="border border-slate-300 px-2 py-2 align-top dark:border-slate-700">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                                            <td class="border border-slate-300 px-2 py-2 align-top dark:border-slate-700 whitespace-pre-line">{{ $item['service_details'] }}</td>
                                            <td class="border border-slate-300 px-2 py-2 align-top dark:border-slate-700">INR {{ number_format((float) $item['price'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="border border-slate-300 px-2 py-4 text-center text-slate-500 dark:border-slate-700">No service rows found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </template>
    </div>
</div>
