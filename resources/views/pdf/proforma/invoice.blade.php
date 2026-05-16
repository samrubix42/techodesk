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
            background: #ffffff;
            color: #222;
            font-family: Calibri, Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.25;
            font-weight: 400;
        }

        .page {
            padding: 28px 34px 80px 34px;
            box-sizing: border-box;
            position: relative;
        }

        /* HEADER */

        .header {
            display: block;
            margin: 0 auto 12px auto;
        }

        /* TITLE */

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            margin-top: 5px;
            margin-bottom: 22px;
        }

        /* META SECTION */

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .meta-table td {
            vertical-align: top;
            padding: 0;
        }

        .left-meta {
            width: 72%;
        }

        .right-meta {
            width: 28%;
            text-align: right;
        }

        .meta-table p {
            margin: 0 0 5px 0;
            font-size: 12px;
        }

        .label {
            font-weight: 700;
        }

        .space-top {
            margin-top: 10px;
        }

        /* SECTION TITLE */

        .details-title {
            font-weight: 700;
            margin-top: 14px;
            margin-bottom: 4px;
            font-size: 12px;
        }

        /* TABLE */

        .items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
        }

        .items th {
            border: 1px solid #444;
            padding: 6px 9px;
            text-align: left;
            font-weight: 700;
            background: #fff;
            line-height: 1.35;
        }

        .items td {
            border: 1px solid #444;
            padding: 6px 9px;
            vertical-align: top;
            line-height: 1.35;
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
            font-size: 12px;
        }

        .note p {
            margin: 0 0 6px 0;
        }

        .note ul {
            margin: 8px 0 0 18px;
            padding-left: 14px;
        }

        .note li {
            margin-bottom: 12px;
            line-height: 1.4;
        }

        /* BANK */

        .bank {
            margin-top: 24px;
            font-size: 12px;
            line-height: 1.4;
        }

        .bank p {
            margin: 0 0 4px 0;
        }

        .bank-title {
            font-weight: 700;
            margin-bottom: 10px !important;
        }

        .due-date {
            margin-bottom: 18px !important;
        }

        /* FOOTER */

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .footer img {
            display: block;
            width: 100%;
        }

    </style>
</head>

<body>

<div class="page">

    {{-- HEADER IMAGE --}}
    @if (!empty($settings['invoice_header_image_path']))
        <img
            class="header"
            src="{{ public_path('storage/' . $settings['invoice_header_image_path']) }}"
            style="width: {{ (float) ($settings['invoice_header_image_width'] ?? 100) }}%;"
            alt="Header"
        >
    @endif

    {{-- TITLE --}}
    <div class="title">
        Proforma Invoice
    </div>

    {{-- META SECTION --}}
    <table class="meta-table">

        <tr>

            <td class="left-meta">

                <p class="label">To:</p>

                <p>{{ $invoice->client?->business_name ?: $invoice->client?->name }}</p>

                @if ($invoice->client?->address_1)
                    <p>{{ $invoice->client?->address_1 }}</p>
                @endif
                @if ($invoice->client?->address_2)
                    <p>{{ $invoice->client?->address_2 }}</p>
                @endif

                <p>
                    {{ trim(($invoice->client?->city ? $invoice->client?->city . ', ' : '') . ($invoice->client?->state ?? '')) }}
                </p>

                @if (!empty($invoice->client?->gst_number))
                    <p class="space-top">
                        GST No.: {{ $invoice->client?->gst_number }}
                    </p>
                @endif

                <p class="space-top">
                    <span class="label">Invoice No.:</span>
                    {{ $invoice->invoice_number }}
                </p>

                <p class="space-top">
                    <span class="label">Status:</span>
                    {{ strtoupper($invoice->status) }}
                </p>

            </td>

            <td class="right-meta">

                <p>
                    <span class="label">Date:</span>
                    {{ $invoiceDate }}
                </p>

            </td>

        </tr>

    </table>

    {{-- PRODUCT TITLE --}}
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
                    Total (Excluding Tax)
                </td>

                <td>
                    INR {{ number_format($subTotal, 2) }}
                </td>

            </tr>

            @if ($taxPercent > 0)
                @if ($taxType === 'gst')
                    <tr class="totals-row">
                        <td colspan="2" class="center">
                            CGST ({{ number_format($taxPercent / 2, 2) }}%)
                        </td>
                        <td>
                            INR {{ number_format($taxAmount / 2, 2) }}
                        </td>
                    </tr>
                    <tr class="totals-row">
                        <td colspan="2" class="center">
                            SGST ({{ number_format($taxPercent / 2, 2) }}%)
                        </td>
                        <td>
                            INR {{ number_format($taxAmount / 2, 2) }}
                        </td>
                    </tr>
                @else
                    <tr class="totals-row">
                        <td colspan="2" class="center">
                            IGST ({{ number_format($taxPercent, 2) }}%)
                        </td>
                        <td>
                            INR {{ number_format($taxAmount, 2) }}
                        </td>
                    </tr>
                @endif
            @endif

            <tr class="totals-row">

                <td colspan="2" class="center">
                    Total {{ $taxPercent > 0 ? '(Including Tax)' : '' }}
                </td>

                <td>
                    INR {{ number_format($total, 2) }}
                </td>

            </tr>

        </tbody>

    </table>

    {{-- NOTES --}}
    <div class="note">

        <p class="label">NOTE:</p>

        {!! $settings['invoice_proforma_notes'] ?? '' !!}

    </div>

    {{-- BANK DETAILS --}}
    <div class="bank">

        @if ($dueDate)

            <p class="due-date">
                <span class="label">Due Date for Payment:</span>
                {{ $dueDate }}
            </p>

        @endif

        <p class="bank-title">
            Bank account details for payment:
        </p>

        <p>
            Account Holder Name:
            {{ $settings['bank_account_holder_name'] ?? '-' }}
        </p>

        <p>
            Account Number:
            {{ $settings['bank_account_number'] ?? '-' }}
        </p>

        <p>
            IFSC Code:
            {{ $settings['bank_ifsc_code'] ?? '-' }}
        </p>

        <p>
            Branch:
            {{ $settings['bank_branch'] ?? '-' }}
        </p>

        <p style="margin-top:12px;">
            UPI ID:
            {{ $settings['bank_upi_id'] ?? '-' }}
        </p>

    </div>

    {{-- FOOTER IMAGE --}}
    @if (!empty($settings['invoice_footer_image_path']))

        <div class="footer">

            <img
                src="{{ public_path('storage/' . $settings['invoice_footer_image_path']) }}"
                alt="Footer"
            >

        </div>

    @endif

</div>

</body>

</html>