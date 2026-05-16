<?php

namespace App\Http\Controllers;

use App\Models\GeneralInvoice;
use App\Models\ProformaInvoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class InvoicePdfController extends Controller
{
    public function download(string $type, int $invoice)
    {
        [$path, $fileName] = $this->storePdf($type, $invoice);

        return response()->download($path, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function preview(string $type, int $invoice)
    {
        [$path, $fileName] = $this->storePdf($type, $invoice);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    private function storePdf(string $type, int $invoice): array
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
            'invoice_proforma_due_days',
            'company_address',
            'company_state',
            'company_country',
        ])->pluck('value', 'key');

        $subTotal = (float) $record->serviceAndPrices->sum('price');
        
        $clientState = $record->client?->state;
        $clientCountry = $record->client?->country;
        $companyState = $settings['company_state'] ?? null;

        $isIndia = strtolower($clientCountry ?? '') === 'india';
        $isSameState = $isIndia && $clientState && $companyState && (strtolower(trim($clientState)) === strtolower(trim($companyState)));
        
        $taxPercent = 0;
        $taxType = 'none';

        if (!$isIndia) {
            $taxPercent = 0;
            $taxType = 'none';
        } elseif ($isSameState) {
            $taxPercent = (float) ($settings['tax_cgst'] ?? 0) + (float) ($settings['tax_sgst'] ?? 0);
            $taxType = 'gst';
        } else {
            $taxPercent = (float) ($settings['tax_igst'] ?? 0);
            $taxType = 'igst';
        }

        $taxAmount = round($subTotal * ($taxPercent / 100), 2);
        $total = round($subTotal + $taxAmount, 2);

        $view = $isGeneral ? 'pdf.tax.invoice' : 'pdf.proforma.invoice';

        $pdf = Pdf::loadView($view, [
            'invoice' => $record,
            'settings' => $settings,
            'subTotal' => $subTotal,
            'taxPercent' => $taxPercent,
            'taxType' => $taxType,
            'taxAmount' => $taxAmount,
            'total' => $total,
            'invoiceDate' => $record->invoice_date?->format('d/m/Y') ?? $record->created_at?->format('d/m/Y') ?? now()->format('d/m/Y'),
            'dueDate' => $isGeneral ? null : $this->calculateDueDate($record, $settings),
        ])->setPaper('a4', 'portrait');

        $fileName = preg_replace('/[^A-Za-z0-9_-]/', '_', $record->invoice_number) . '.pdf';
        $folder = $isGeneral ? 'tax' : 'proforma';
        $directory = storage_path('app/public/invoices/' . $folder);
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;

        File::ensureDirectoryExists($directory);
        $pdf->save($path);

        return [$path, $fileName];
    }

    private function calculateDueDate(GeneralInvoice|ProformaInvoice $record, $settings): ?string
    {
        if ($record instanceof ProformaInvoice && $record->payment_due_day) {
            return Carbon::parse($record->payment_due_day)->format('d/m/Y');
        }

        $days = $settings['invoice_proforma_due_days'] ?? null;

        if (!is_numeric($days)) {
            return null;
        }

        $baseDate = $record->invoice_date ?? $record->created_at ?? now();

        return $baseDate->copy()->addDays((int) $days)->format('d/m/Y');
    }
}
