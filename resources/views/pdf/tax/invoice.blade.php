<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>

        @page {
            margin: 0;
            size: A4 portrait;
        }

        body {
            margin: 0;
            background: #fff;
            color: #111;
            font-family: Calibri, Arial, Helvetica, sans-serif;
            font-size: 11.5px;
            line-height: 1.25;
            font-weight: 400;
        }

        .page {
            position: relative;
            padding: 30px 68px 95px 68px;
            box-sizing: border-box;
            background: #fff;
        }

        /* HEADER */

        .header {
            display: block;
            margin: 0 auto 18px auto;
            height: auto;
        }

        /* FOOTER */

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10;
        }

        .footer img {
            display: block;
            width: 100%;
            height: auto;
        }

        /* TITLE */

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            margin: 6px 0 18px 0;
            letter-spacing: 0.2px;
        }

        /* META */

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .meta td {
            border: 0;
            padding: 0;
            vertical-align: top;
        }

        .meta p {
            margin: 0 0 4px 0;
            line-height: 1.3;
        }

        .left-meta {
            width: 68%;
        }

        .right-meta {
            width: 32%;
            text-align: right;
        }

        .label {
            font-weight: 700;
        }

        /* SECTION */

        .details-title {
            margin: 12px 0 3px 0;
            font-weight: 700;
            font-size: 11.5px;
        }

        /* TABLE */

        .items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 11.5px;
        }

        .items th {
            border: 1px solid #222;
            padding: 7px 10px;
            text-align: left;
            font-weight: 700;
            background: #fff;
            line-height: 1.25;
        }

        .items td {
            border: 1px solid #222;
            padding: 7px 10px;
            vertical-align: top;
            line-height: 1.3;
        }

        .center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .totals-row td {
            font-weight: 400;
        }

        /* NOTE */

        .note {
            margin-top: 16px;
            font-size: 11.5px;
            line-height: 1.45;
        }

        .note-title {
            font-weight: 700;
            margin-bottom: 7px;
        }

        /* BANK */

        .bank {
            margin-top: 24px;
            font-size: 11.5px;
            line-height: 1.45;
        }

        .bank-title {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .bank p {
            margin: 0 0 4px 0;
        }

        .due-date {
            margin-bottom: 16px !important;
        }

    </style>

</head>

<body>

<div class="page">

    {{-- HEADER --}}
    @if (!empty($settings['invoice_header_image_path']))
        <img
            class="header"
            src="{{ public_path('storage/' . $settings['invoice_header_image_path']) }}"
            style="
                width: {{ (float) ($settings['invoice_header_image_width'] ?? 100) }}%;
                transform: scaleY({{ ((float) ($settings['invoice_header_image_height'] ?? 100)) / 100 }});
                transform-origin: top left;
            "
            alt="Header"
        >
    @endif

    {{-- TITLE --}}
    <div class="title">
        Tax Invoice
    </div>

    {{-- META --}}
    <table class="meta">

        <tr>

            <td class="left-meta">

                <p class="label">To:</p>

                <p>{{ $invoice->client?->name }}</p>

                @if ($invoice->client?->address)
                    <p>{{ $invoice->client?->address }}</p>
                @endif

                <p>
                    {{ trim(($invoice->client?->city ? $invoice->client?->city . ', ' : '') . ($invoice->client?->state ?? '')) }}
                </p>

                @if (!empty($invoice->client?->gst_number))
                    <p style="margin-top:8px;">
                        GST No.: {{ $invoice->client?->gst_number }}
                    </p>
                @endif

                <p style="margin-top:12px;">
                    <span class="label">Invoice No.:</span>
                    {{ $invoice->invoice_number }}
                </p>

                @if (!empty($invoice->status))
                    <p style="margin-top:6px;">
                        <span class="label">Status:</span>
                        {{ strtoupper($invoice->status) }}
                    </p>
                @endif

            </td>

            <td class="right-meta">

                <p>
                    <span class="label">Date:</span>
                    {{ $invoiceDate }}
                </p>

            </td>

        </tr>

    </table>

    {{-- SECTION TITLE --}}
    <div class="details-title">
        Product &amp; services details:
    </div>

    {{-- TABLE --}}
    <table class="items">

        <thead>

            <tr>
                <th style="width:10%;">Sl. No.</th>
                <th style="width:64%;">Service Details</th>
                <th style="width:26%;">Price</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($invoice->serviceAndPrices as $index => $item)

                <tr>

                    <td>
                        {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.
                    </td>

                    <td>
                        {{ $item->service_details }}
                    </td>

                    <td>
                        INR {{ number_format((float) $item->price, 2) }}
                    </td>

                </tr>

            @endforeach

            <tr class="totals-row">

                <td colspan="2" class="center">
                    Total (Excluding GST)
                </td>

                <td>
                    INR {{ number_format($subTotal, 2) }}
                </td>

            </tr>

            @if ($taxPercent > 0)

                <tr class="totals-row">

                    <td colspan="2" class="center">

                        {{ (float) ($settings['tax_igst'] ?? 0) > 0 ? 'IGST' : 'GST' }}

                        ({{ number_format($taxPercent, 2) }}%)

                    </td>

                    <td>
                        INR {{ number_format($taxAmount, 2) }}
                    </td>

                </tr>

            @endif

            <tr class="totals-row">

                <td colspan="2" class="center">
                    Total {{ $taxPercent > 0 ? '(Including GST)' : '' }}
                </td>

                <td>
                    INR {{ number_format($total, 2) }}
                </td>

            </tr>

        </tbody>

    </table>

    {{-- NOTE --}}
    <div class="note">

        <div class="note-title">
            NOTE:
        </div>

        {!! $settings['invoice_general_notes'] ?? $settings['invoice_proforma_notes'] ?? '' !!}

    </div>

    {{-- FOOTER --}}
    @if (!empty($settings['invoice_footer_image_path']))

        <div class="footer">

            <img
                src="{{ public_path('storage/' . $settings['invoice_footer_image_path']) }}"
                style="
                    width: {{ (float) ($settings['invoice_footer_image_width'] ?? 100) }}%;
                    transform: scaleY({{ ((float) ($settings['invoice_footer_image_height'] ?? 100)) / 100 }});
                    transform-origin: bottom left;
                "
                alt="Footer"
            >

        </div>

    @endif

</div>

</body>

</html>