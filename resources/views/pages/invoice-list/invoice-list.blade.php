<div class="space-y-6 px-3 py-5 sm:px-4 sm:py-6 lg:px-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $this->pageTitle }}</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            View and download general invoices.
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Search</label>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Invoice number or client name" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-900/60">
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <h2 class="mb-3 text-lg font-semibold text-slate-900 dark:text-white">General Invoices</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left dark:border-slate-800">
                        <th class="px-2 py-2">Invoice No</th>
                        <th class="px-2 py-2">Client</th>
                        <th class="px-2 py-2">Total</th>
                        <th class="px-2 py-2">Download</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->generalInvoices as $invoice)
                        <tr class="border-b border-slate-100 dark:border-slate-900">
                            <td class="px-2 py-2">{{ $invoice->invoice_number }}</td>
                            <td class="px-2 py-2">{{ $invoice->client?->name ?: '-' }}</td>
                            <td class="px-2 py-2">INR {{ number_format((float) $invoice->total_price, 2) }}</td>
                            <td class="px-2 py-2">
                                <a href="{{ route('invoice.pdf', ['type' => 'general', 'invoice' => $invoice->id]) }}" class="inline-flex rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white dark:bg-white dark:text-slate-900">
                                    PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-2 py-4 text-slate-500">No general invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
