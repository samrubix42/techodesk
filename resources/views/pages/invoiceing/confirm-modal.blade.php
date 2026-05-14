<div x-data="{ open: @entangle('confirmOpen') }" x-cloak>
    <template x-teleport="body">
        <div x-show="open" style="position:fixed; inset:0; z-index:120; display:grid; place-items:center; padding:16px;">
            <div @click="open = false" style="position:absolute; inset:0; background:rgba(15,23,42,0.65);"></div>
            <div x-show="open" style="position:relative; width:100%; max-width:900px; max-height:94vh; overflow:auto; background:#ffffff; border-radius:8px; padding:16px; box-shadow:0 20px 45px rgba(0,0,0,.35);">
                <div style="width:794px; min-height:1123px; margin:0 auto; background:#ffffff; padding:34px 82px 92px 82px; box-sizing:border-box; color:#111; font-family:Calibri, Arial, Helvetica, sans-serif; font-size:11px; line-height:1.28; position:relative;">
                    @if ($headerImagePath)
                        <img src="{{ asset('storage/' . $headerImagePath) }}" alt="Header" style="display:block; width:{{ $headerWidth ?? 100 }}%; height:auto; transform:scaleY({{ ($headerHeight ?? 100) / 100 }}); transform-origin:top left; margin:0 auto 20px auto;">
                    @endif

                    <h3 style="font-size:15px; font-weight:700; text-align:center; margin:10px 0 16px 0;">{{ $invoiceType === 'general' ? 'Tax Invoice' : 'Proforma Invoice' }}</h3>

                    <div style="display:grid; grid-template-columns:1fr 170px; gap:18px; margin-top:6px;">
                        <div>
                            <p style="margin:0 0 2px 0;">To:</p>
                            <p style="margin:0;">{{ $this->selectedClient?->name }}</p>
                            @if ($this->selectedClient?->address)
                                <p style="margin:0;">{{ $this->selectedClient?->address }}</p>
                            @endif
                            <p style="margin:0;">{{ trim(($this->selectedClient?->city ? $this->selectedClient?->city . ', ' : '') . ($this->selectedClient?->state ?? '')) }}</p>
                            @if (!empty($this->selectedClient?->gst_number))
                                <p style="margin:8px 0 0 0;">GST No.: {{ $this->selectedClient?->gst_number }}</p>
                            @endif
                            <p style="margin:12px 0 0 0;"><strong>Invoice No.:</strong> {{ $this->previewInvoiceNumber }}</p>
                            @if ($invoiceType === 'proforma')
                                <p style="margin:8px 0 0 0;"><strong>Status:</strong> {{ strtoupper($status) }}</p>
                            @endif
                        </div>
                        <div style="text-align:left;">
                            <p style="margin:0;"><strong>Date:</strong>&nbsp; {{ \Illuminate\Support\Carbon::parse($invoiceDate)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div style="margin-top:18px;">
                        <p style="margin:0 0 2px 0; font-weight:700;">Product &amp; services details:</p>
                        <table style="width:100%; border-collapse:collapse; table-layout:fixed; font-size:11px; line-height:1.2;">
                            <thead>
                                <tr>
                                    <th style="width:12%; border:1px solid #111; padding:3px 5px; text-align:left;">Sl. No.</th>
                                    <th style="width:58%; border:1px solid #111; padding:3px 5px; text-align:left;">Service Details</th>
                                    <th style="width:30%; border:1px solid #111; padding:3px 5px; text-align:left;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoiceItems as $idx => $item)
                                    <tr>
                                        <td style="border:1px solid #111; padding:4px 5px; vertical-align:top;">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                                        <td style="border:1px solid #111; padding:4px 5px; vertical-align:top; white-space:pre-line;">{{ $item['service_details'] }}</td>
                                        <td style="border:1px solid #111; padding:4px 5px; vertical-align:top;">INR {{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" style="border:1px solid #111; padding:3px 5px; text-align:center;">Total (Excluding GST)</td>
                                    <td style="border:1px solid #111; padding:3px 5px;">INR {{ number_format($this->subTotal, 2) }}</td>
                                </tr>
                                @if ($this->taxPercent > 0)
                                    <tr>
                                        <td colspan="2" style="border:1px solid #111; padding:3px 5px; text-align:center;">{{ $this->taxLabel }}</td>
                                        <td style="border:1px solid #111; padding:3px 5px;">INR {{ number_format($this->taxAmount, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" style="border:1px solid #111; padding:3px 5px; text-align:center;">Total {{ $this->taxPercent > 0 ? '(Including GST)' : '' }}</td>
                                    <td style="border:1px solid #111; padding:3px 5px;">INR {{ number_format($this->totalAmount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:14px; font-size:11px;">
                        <p style="margin:0 0 8px 0;">NOTE:</p>
                        <div>{!! $this->currentNotes !!}</div>
                    </div>

                    @if ($invoiceType === 'proforma')
                        <div style="margin-top:18px; font-size:11px; line-height:1.45;">
                            @if ($this->previewDueDate)
                                <p style="margin:0 0 10px 0; font-weight:700;">Due Date for Payment: {{ $this->previewDueDate }}</p>
                            @endif
                            <p style="margin:0 0 8px 0; font-weight:700;">Bank account details for payment:</p>
                            <p style="margin:0;">Account Holder Name: {{ $accountHolderName ?: '-' }}</p>
                            <p style="margin:0;">Account Number: {{ $accountNumber ?: '-' }}</p>
                            <p style="margin:0;">IFSC Code: {{ $ifscCode ?: '-' }}</p>
                            <p style="margin:0;">Branch: {{ $branch ?: '-' }}</p>
                            <p style="margin:12px 0 0 0;">UPI ID: {{ $upiId ?: '-' }}</p>
                        </div>
                    @endif

                    @if ($footerImagePath)
                        <img src="{{ asset('storage/' . $footerImagePath) }}" alt="Footer" style="position:absolute; left:0; bottom:0; display:block; width:{{ $footerWidth ?? 100 }}%; height:auto; transform:scaleY({{ ($footerHeight ?? 100) / 100 }}); transform-origin:bottom left;">
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
