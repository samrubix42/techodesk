<div class="space-y-6 px-3 py-5 sm:px-4 sm:py-6 lg:px-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Edit Proforma Invoice</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $invoice->invoice_number }}</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Client</label>
                <select wire:model.live="selectedClientId" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                    <option value="">Select client</option>
                    @foreach ($this->clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}{{ $client->business_name ? ' - ' . $client->business_name : '' }}</option>
                    @endforeach
                </select>
                @error('selectedClientId') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Invoice Service</label>
                <select wire:model.live="selectedServiceId" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                    <option value="">Select service (nullable)</option>
                    @foreach ($this->services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
                @error('selectedServiceId') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

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
                @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 space-y-3">
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

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 p-3 text-sm dark:border-slate-800">
                <p>Subtotal: INR {{ number_format($this->subTotal, 2) }}</p>
                <p>Tax: {{ number_format($this->taxPercent, 2) }}%</p>
                <p>Tax Amt: INR {{ number_format($this->taxAmount, 2) }}</p>
                <p class="font-semibold">Total: INR {{ number_format($this->totalAmount, 2) }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('invoice-list.proforma') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm dark:border-slate-700">Back</a>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('invoice.preview', ['type' => 'proforma', 'invoice' => $invoice->id]) }}" target="_blank" class="rounded-xl border border-slate-300 px-4 py-2 text-sm dark:border-slate-700">Preview</a>
                <button type="button" wire:click="save" wire:loading.attr="disabled" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-slate-900">Save Changes</button>
            </div>
        </div>
    </div>
</div>
