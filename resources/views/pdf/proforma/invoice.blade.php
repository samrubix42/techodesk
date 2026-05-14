<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; margin: 0; }
        .page { padding: 22px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #111; padding: 4px; font-size: 12px; }
        .no-border td { border: none; padding: 0; }
        .mt-8 { margin-top: 8px; }
        .mt-14 { margin-top: 14px; }
        .text-right { text-align: right; }
        .title { text-align: center; font-size: 24px; font-weight: 700; margin: 10px 0; }
    </style>
</head>
<body>
<div class="page">
    @if (!empty($settings['invoice_header_image_path']))
        <img
            src="{{ public_path('storage/' . $settings['invoice_header_image_path']) }}"
            style="width: {{ (float) ($settings['invoice_header_image_width'] ?? 100) }}%; transform: scaleY({{ ((float) ($settings['invoice_header_image_height'] ?? 100)) / 100 }}); transform-origin: top left;"
            alt="Header"
        >
    @endif

    <div class="title">Proforma Invoice</div>

    <table class="no-border">
        <tr>
            <td style="width:65%;">
                <strong>To:</strong><br>
                {{ $invoice->client?->name }}<br>
                {{ $invoice->client?->address }}<br>
                {{ $invoice->client?->city }}{{ $invoice->client?->city && $invoice->client?->state ? ', ' : '' }}{{ $invoice->client?->state }}<br>
                @if (!empty($invoice->client?->gst_number))
                    GST No.: {{ $invoice->client?->gst_number }}
                @endif
            </td>
            <td class="text-right" style="width:35%;">
                <strong>Date:</strong> {{ now()->format('d/m/Y') }}<br><br>
                <strong>Invoice No.:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Status:</strong> {{ strtoupper($invoice->status) }}
            </td>
        </tr>
    </table>

    <div class="mt-14">
        <strong>Product & services details:</strong>
        <table class="mt-8">
            <thead>
                <tr>
                    <th style="width:12%;">Sl. No.</th>
                    <th style="width:63%;">Service Details</th>
                    <th style="width:25%;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->serviceAndPrices as $index => $item)
                    <tr>
                        <td>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                        <td>{{ $item->service_details }}</td>
                        <td>INR {{ number_format((float) $item->price, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right">Total (Excluding GST)</td>
                    <td>INR {{ number_format($subTotal, 2) }}</td>
                </tr>
                @if ($taxPercent > 0)
                    <tr>
                        <td colspan="2" class="text-right">{{ (float) ($settings['tax_igst'] ?? 0) > 0 ? 'IGST' : 'GST' }} ({{ number_format($taxPercent, 2) }}%)</td>
                        <td>INR {{ number_format($taxAmount, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2" class="text-right">Total {{ $taxPercent > 0 ? '(Including GST)' : '' }}</td>
                    <td>INR {{ number_format($total, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-14">
        <strong>NOTE:</strong>
        {!! $settings['invoice_proforma_notes'] ?? '' !!}
    </div>

    <div class="mt-14">
        <strong>Bank account details for payment:</strong><br><br>
        Account Holder Name: {{ $settings['bank_account_holder_name'] ?? '-' }}<br>
        Account Number: {{ $settings['bank_account_number'] ?? '-' }}<br>
        IFSC Code: {{ $settings['bank_ifsc_code'] ?? '-' }}<br>
        Branch: {{ $settings['bank_branch'] ?? '-' }}<br>
        UPI ID: {{ $settings['bank_upi_id'] ?? '-' }}<br><br>
        Address: {{ $settings['company_address'] ?? '-' }}<br>
        State: {{ $settings['company_state'] ?? '-' }}<br>
        Country: {{ $settings['company_country'] ?? '-' }}
    </div>

    @if (!empty($settings['invoice_footer_image_path']))
        <div class="mt-14">
            <img
                src="{{ public_path('storage/' . $settings['invoice_footer_image_path']) }}"
                style="width: {{ (float) ($settings['invoice_footer_image_width'] ?? 100) }}%; transform: scaleY({{ ((float) ($settings['invoice_footer_image_height'] ?? 100)) / 100 }}); transform-origin: top left;"
                alt="Footer"
            >
        </div>
    @endif
</div>
</body>
</html>
