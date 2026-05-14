<div x-data="{ open: @entangle('confirmOpen') }" x-cloak>
    <template x-teleport="body">
        <div x-show="open" style="position:fixed; inset:0; z-index:120; display:grid; place-items:center; padding:16px;">
            <div @click="open = false" style="position:absolute; inset:0; background:rgba(15,23,42,0.65);"></div>
            <div x-show="open" style="position:relative; width:100%; max-width:860px; max-height:92vh; overflow:auto; background:#f4f4f4; border-radius:10px; padding:18px; box-shadow:0 20px 45px rgba(0,0,0,.35);">
                <div style="width:794px; min-height:1123px; margin:0 auto; background:#f2f2f2; padding:20px 26px 28px 26px; box-sizing:border-box; border:1px solid #c8c8c8; color:#1b1b1b;">
                @if ($headerImagePath)
                    <img src="{{ asset('storage/' . $headerImagePath) }}" alt="Header" style="display:block; width:{{ $headerWidth ?? 100 }}%; transform:scaleY({{ ($headerHeight ?? 100) / 100 }}); transform-origin:top left; margin:0 auto 12px auto;">
                @endif

                <div style="text-align:center;">
                    <h3 style="font-size:31px; font-weight:700; margin:8px 0 14px 0;">{{ $invoiceType === 'general' ? 'Tax Invoice' : 'Proforma Invoice' }}</h3>
                </div>

                <div style="margin-top:8px; display:grid; grid-template-columns:1fr 1fr; gap:16px; font-size:14px;">
                    <div style="line-height:1.45;">
                        <p style="font-weight:600;">To:</p>
                        <p>{{ $this->selectedClient?->name }}</p>
                        <p>{{ $this->selectedClient?->address }}</p>
                        <p>{{ $this->selectedClient?->city }} {{ $this->selectedClient?->state }}</p>
                        @if (!empty($this->selectedClient?->gst_number))
                            <p>GST No.: {{ $this->selectedClient?->gst_number }}</p>
                        @endif
                    </div>
                    <div style="text-align:right; line-height:1.5;">
                        <p><span style="font-weight:600;">Date:</span> {{ now()->format('d/m/Y') }}</p>
                        <p><span style="font-weight:600;">Invoice No.:</span> {{ $this->previewInvoiceNumber }}</p>
                        <p><span style="font-weight:600;">Status:</span> {{ strtoupper($status) }}</p>
                    </div>
                </div>

                <div style="margin-top:14px; overflow:hidden; border:1px solid #111;">
                    <table style="width:100%; border-collapse:collapse; font-size:12.5px; line-height:1.2;">
                        <thead style="background:#efefef;">
                            <tr>
                                <th style="border:1px solid #111; padding:4px; text-align:left;">Sl. No.</th>
                                <th style="border:1px solid #111; padding:4px; text-align:left;">Service Details</th>
                                <th style="border:1px solid #111; padding:4px; text-align:left;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoiceItems as $idx => $item)
                                <tr>
                                    <td style="border:1px solid #111; padding:4px;">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                                    <td style="border:1px solid #111; padding:4px; white-space:pre-line;">{{ $item['service_details'] }}</td>
                                    <td style="border:1px solid #111; padding:4px;">INR {{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="border:1px solid #111; padding:4px; text-align:right;">Total (Excluding GST)</td>
                                <td style="border:1px solid #111; padding:4px;">INR {{ number_format($this->subTotal, 2) }}</td>
                            </tr>
                            @if ($this->taxPercent > 0)
                                <tr>
                                    <td colspan="2" style="border:1px solid #111; padding:4px; text-align:right;">{{ $this->taxLabel }}</td>
                                    <td style="border:1px solid #111; padding:4px;">INR {{ number_format($this->taxAmount, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2" style="border:1px solid #111; padding:4px; text-align:right; font-weight:600;">Total {{ $this->taxPercent > 0 ? '(Including GST)' : '' }}</td>
                                <td style="border:1px solid #111; padding:4px; font-weight:600;">INR {{ number_format($this->totalAmount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="prose prose-sm mt-4 max-w-none dark:prose-invert">
                    {!! $this->currentNotes !!}
                </div>

                @if ($invoiceType === 'proforma')
                    <div class="mt-4 text-sm">
                        <p class="font-medium">Bank account details for payment:</p>
                        <p>Account Holder Name: {{ $accountHolderName ?: '-' }}</p>
                        <p>Account Number: {{ $accountNumber ?: '-' }}</p>
                        <p>IFSC Code: {{ $ifscCode ?: '-' }}</p>
                        <p>Branch: {{ $branch ?: '-' }}</p>
                        <p>UPI ID: {{ $upiId ?: '-' }}</p>
                    </div>
                @endif

                <div style="margin-top:10px; font-size:13px;">
                    <p>Address: {{ $companyAddress ?: '-' }}</p>
                    <p>State: {{ $companyState ?: '-' }}</p>
                    <p>Country: {{ $companyCountry ?: '-' }}</p>
                </div>

                @if ($footerImagePath)
                    <img src="{{ asset('storage/' . $footerImagePath) }}" alt="Footer" style="display:block; width:{{ $footerWidth ?? 100 }}%; transform:scaleY({{ ($footerHeight ?? 100) / 100 }}); transform-origin:top left; margin:20px auto 0 auto;">
                @endif
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="open = false" wire:click="closeConfirm" class="rounded-xl border border-slate-300 px-4 py-2 text-sm dark:border-slate-700">Cancel</button>
                    <button type="button" wire:click="saveInvoice" wire:loading.attr="disabled" wire:target="saveInvoice,saveInvoiceAndDownload" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white">Save</button>
                    <button type="button" wire:click="saveInvoiceAndDownload" wire:loading.attr="disabled" wire:target="saveInvoice,saveInvoiceAndDownload" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white">Save & Download PDF</button>
                </div>
                <p wire:loading wire:target="saveInvoice,saveInvoiceAndDownload" class="mt-2 text-right text-xs text-slate-500">Processing invoice, please wait...</p>
            </div>
        </div>
    </template>
</div>
