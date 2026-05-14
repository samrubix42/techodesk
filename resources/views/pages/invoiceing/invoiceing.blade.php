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
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Copy From Existing Invoice</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Copy service, status, and price rows from this client's old invoices. Client, date, and invoice number stay new.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:w-[420px]">
                            <div>
                                <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Show</label>
                                <select wire:model.live="copyInvoiceType" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                                    <option value="all">All invoices</option>
                                    <option value="proforma">Proforma only</option>
                                    <option value="general">Tax only</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Search</label>
                                <input type="text" wire:model.live.debounce.300ms="copyInvoiceSearch" placeholder="Invoice no or service" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        @forelse ($this->copySourceInvoices as $source)
                            <div class="flex flex-col gap-3 rounded-lg border border-slate-200 bg-slate-50/70 p-3 text-sm md:flex-row md:items-center md:justify-between dark:border-slate-800 dark:bg-slate-900/30">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200 dark:bg-slate-950 dark:text-slate-200 dark:ring-slate-800">{{ $source['type_label'] }}</span>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $source['invoice_number'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $source['invoice_date'] }}</p>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $source['item_count'] }} item(s) | {{ $source['status'] }} | INR {{ number_format($source['total_price'], 2) }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="previewCopyInvoice('{{ $source['type'] }}', {{ $source['id'] }})" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium dark:border-slate-700">Preview</button>
                                    <button type="button" wire:click="copyFromInvoice('{{ $source['type'] }}', {{ $source['id'] }})" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white dark:bg-white dark:text-slate-900">Copy Details</button>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-lg border border-dashed border-slate-300 p-3 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No previous invoices found for this client.</p>
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

                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Invoice Date</label>
                        <input type="date" wire:model.live="invoiceDate" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                        @error('invoiceDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
                        <select wire:model.live="status" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-3 text-sm dark:border-slate-800">
                        <p>Subtotal: INR {{ number_format($this->subTotal, 2) }}</p>
                        <p>Tax: {{ $this->taxLabel }}</p>
                        <p>Tax Amt: INR {{ number_format($this->taxAmount, 2) }}</p>
                        <p class="font-semibold">Total: INR {{ number_format($this->totalAmount, 2) }}</p>
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
                                <p class="mt-1 text-sm text-slate-500">{{ $copyPreviewInvoice['client_name'] }} | {{ $copyPreviewInvoice['invoice_date'] }} | {{ $copyPreviewInvoice['status'] }}</p>
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
