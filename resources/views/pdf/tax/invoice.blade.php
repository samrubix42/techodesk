<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; size: A4 portrait; }
        body { margin: 0; background: #fff; color: #111; font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; line-height: 1.35; }
        .page { position: relative; min-height: 1122px; padding: 38px 82px 120px 82px; box-sizing: border-box; background: #fff; }
        .header { display: block; margin: 0 auto 20px auto; height: auto; }
        .footer { position: absolute; left: 0; bottom: 0; display: block; height: auto; transform-origin: bottom left; }
        .title { text-align: center; font-size: 15px; font-weight: 700; margin: 10px 0 16px 0; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .meta td { border: 0; padding: 0; vertical-align: top; }
        .details-title { margin: 18px 0 2px 0; font-weight: 700; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 10.5px; line-height: 1.2; }
        .items th, .items td { border: 1px solid #111; padding: 3px 5px; vertical-align: top; }
        .items th { text-align: left; font-weight: 700; }
        .center { text-align: center; }
        .note { margin-top: 14px; font-size: 10.5px; }
        p { margin: 0; }
    </style>
</head>
<body>
<div class="page">
    @if (!empty($settings['invoice_header_image_path']))
        <img
            class="header"
            src="{{ public_path('storage/' . $settings['invoice_header_image_path']) }}"
            style="width: {{ (float) ($settings['invoice_header_image_width'] ?? 100) }}%; transform: scaleY({{ ((float) ($settings['invoice_header_image_height'] ?? 100)) / 100 }}); transform-origin: top left;"
            alt="Header"
        >
    @endif

    <div class="title">Tax Invoice</div>

    <table class="meta">
        <tr>
            <td style="width:68%;">
                <p>To:</p>
                <p>{{ $invoice->client?->name }}</p>
                @if ($invoice->client?->address)
                    <p>{{ $invoice->client?->address }}</p>
                @endif
                <p>{{ trim(($invoice->client?->city ? $invoice->client?->city . ', ' : '') . ($invoice->client?->state ?? '')) }}</p>
                @if (!empty($invoice->client?->gst_number))
                    <p style="margin-top:8px;">GST No.: {{ $invoice->client?->gst_number }}</p>
                @endif
                <p style="margin-top:12px;"><strong>Invoice No.:</strong> {{ $invoice->invoice_number }}</p>
            </td>
            <td style="width:32%;">
                <p><strong>Date:</strong>&nbsp; {{ $invoiceDate }}</p>
            </td>
        </tr>
    </table>

    <p class="details-title">Product &amp; services details:</p>
    <table class="items">
        <thead>
            <tr>
                <th style="width:12%;">Sl. No.</th>
                <th style="width:58%;">Service Details</th>
                <th style="width:30%;">Price</th>
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
                <td colspan="2" class="center">Total (Excluding GST)</td>
                <td>INR {{ number_format($subTotal, 2) }}</td>
            </tr>
            @if ($taxPercent > 0)
                <tr>
                    <td colspan="2" class="center">{{ (float) ($settings['tax_igst'] ?? 0) > 0 ? 'IGST' : 'GST' }} ({{ number_format($taxPercent, 2) }}%)</td>
                    <td>INR {{ number_format($taxAmount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="2" class="center">Total {{ $taxPercent > 0 ? '(Including GST)' : '' }}</td>
                <td>INR {{ number_format($total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="note">
        <p style="margin-bottom:8px;">NOTE:</p>
        {!! $settings['invoice_general_notes'] ?? '' !!}
    </div>

    @if (!empty($settings['invoice_footer_image_path']))
        <img
            class="footer"
            src="{{ public_path('storage/' . $settings['invoice_footer_image_path']) }}"
            style="width: {{ (float) ($settings['invoice_footer_image_width'] ?? 100) }}%; transform: scaleY({{ ((float) ($settings['invoice_footer_image_height'] ?? 100)) / 100 }});"
            alt="Footer"
        >
    @endif
</div>
</body>
</html>
