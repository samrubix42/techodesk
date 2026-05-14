<?php

namespace App\Http\Controllers;

use App\Models\GeneralInvoice;
use App\Models\ProformaInvoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class InvoicePdfController extends Controller
{
    public function download(string $type, int $invoice)
    {
        abort_unless(in_array($type, ['general', 'proforma'], true), 404);

        $isGeneral = $type === 'general';
        $model = $isGeneral ? GeneralInvoice::class : ProformaInvoice::class;
        $record = $model::with(['client', 'service', 'serviceAndPrices'])->findOrFail($invoice);

        $settings = Setting::whereIn('key', [
            'invoice_header_image_path',
            'invoice_header_image_width',
            'invoice_header_image_height',
            'invoice_footer_image_path',
            'invoice_footer_image_width',
            'invoice_footer_image_height',
            'bank_account_holder_name',
            'bank_account_number',
            'bank_ifsc_code',
            'bank_branch',
            'bank_upi_id',
            'tax_igst',
            'tax_cgst',
            'tax_sgst',
            'invoice_proforma_notes',
            'invoice_general_notes',
            'company_address',
            'company_state',
            'company_country',
        ])->pluck('value', 'key');

        $subTotal = (float) $record->serviceAndPrices->sum('price');
        $igst = (float) ($settings['tax_igst'] ?? 0);
        $taxPercent = $igst > 0
            ? $igst
            : ((float) ($settings['tax_cgst'] ?? 0) + (float) ($settings['tax_sgst'] ?? 0));
        $taxAmount = round($subTotal * ($taxPercent / 100), 2);
        $total = round($subTotal + $taxAmount, 2);

        $view = $isGeneral ? 'pdf.tax.invoice' : 'pdf.proforma.invoice';

        $pdf = Pdf::loadView($view, [
            'invoice' => $record,
            'settings' => $settings,
            'subTotal' => $subTotal,
            'taxPercent' => $taxPercent,
            'taxAmount' => $taxAmount,
            'total' => $total,
            'invoiceDate' => $record->created_at?->format('d/m/Y') ?? now()->format('d/m/Y'),
        ])->setPaper('a4', 'portrait');

        $fileName = preg_replace('/[^A-Za-z0-9_-]/', '_', $record->invoice_number) . '.pdf';
        $folder = $isGeneral ? 'tax' : 'proforma';
        $directory = storage_path('app/public/invoices/' . $folder);
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;

        File::ensureDirectoryExists($directory);
        $pdf->save($path);

        return response()->download($path, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
