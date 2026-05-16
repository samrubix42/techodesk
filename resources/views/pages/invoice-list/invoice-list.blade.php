
<div class="space-y-6 px-3 py-5 sm:px-4 sm:py-6 lg:px-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $this->pageTitle }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                View and download {{ strtolower($this->typeLabel) }} invoices.
            </p>
        </div>
        <div class="flex gap-2">
            <button type="button" wire:click="resetFilters" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium dark:border-slate-700 dark:text-slate-300">Reset Filters</button>
            <a href="{{ route('invoiceing') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-slate-900">+ Create New</a>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Invoice # or client..." class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Client</label>
                <select wire:model.live="filterClient" class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                    <option value="">All Clients</option>
                    @foreach($this->clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Service</label>
                <select wire:model.live="filterService" class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
                    <option value="">All Services</option>
                    @foreach($this->services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Date</label>
                <input type="date" wire:model.live="filterDate" class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
            </div>
        </div>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left dark:border-slate-800">
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">Sl. No.</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">Invoice No</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">Date</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">Client</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">Service</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">Total Amount</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->invoices as $index => $invoice)
                        <tr class="border-b border-slate-50 transition hover:bg-slate-50/50 dark:border-slate-900 dark:hover:bg-slate-900/50">
                            <td class="px-4 py-3 text-slate-500">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $invoice->invoice_number }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</td>
                            <td class="px-4 py-3 text-slate-900 dark:text-white">{{ $invoice->client?->name ?: '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $invoice->service?->name ?: 'Multiple' }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">INR {{ number_format((float) $invoice->total_price, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('invoice.preview', ['type' => $this->pdfType, 'invoice' => $invoice->id]) }}" target="_blank" class="inline-flex rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">
                                        Preview
                                    </a>
                                    <a href="{{ $this->type === 'proforma' ? route('invoice.proforma.edit', ['invoice' => $invoice->id]) : route('invoice.tax.edit', ['invoice' => $invoice->id]) }}" class="inline-flex rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">
                                        Edit
                                    </a>
                                    <a href="{{ route('invoice.pdf', ['type' => $this->pdfType, 'invoice' => $invoice->id]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-600/20 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                    <p class="mt-2">No {{ strtolower($this->typeLabel) }} invoices found matching your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
