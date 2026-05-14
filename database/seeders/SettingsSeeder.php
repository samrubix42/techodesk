<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the application's settings (excluding address and header/footer image settings).
     */
    public function run(): void
    {
        $settings = [
            'bank_account_holder_name' => 'GrowthVault Innovations Pvt. Ltd.',
            'bank_account_number' => '070163300005200',
            'bank_ifsc_code' => 'YESB0000701',
            'bank_branch' => 'Yes Bank, Noida Sector 50, U.P.',
            'bank_upi_id' => 'growthvault@yesbank',
            'company_state' => 'Uttar Pradesh',
            'company_country' => 'India',
            'tax_igst' => '18',
            'tax_cgst' => null,
            'tax_sgst' => null,
            'invoice_proforma_notes' => '<ul><li>This is a proforma invoice and not a tax invoice.</li><li>Any further addition of requirements will be chargeable as per our pricing standard.</li></ul>',
            'invoice_general_notes' => '<ul><li>Any further addition of requirements will be chargeable as per standard pricing.</li></ul>',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
