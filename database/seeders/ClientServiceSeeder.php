<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientServiceSeeder extends Seeder
{
    /**
     * Seed the application's clients and services.
     */
    public function run(): void
    {
        $services = [
            'Web Development',
            'Mobile App Development',
            'UI/UX Design',
            'SEO Optimization',
            'Digital Marketing',
            'Cloud Hosting',
        ];

        foreach ($services as $serviceName) {
            Service::updateOrCreate(
                ['slug' => Str::slug($serviceName)],
                ['name' => $serviceName]
            );
        }

        $clients = [
            [
                'name' => 'Acme Corporation',
                'email' => 'hello@acme.com',
                'phone' => '+1-202-555-0101',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'business_name' => 'Acme Corp',
                'gst_number' => null,
            ],
            [
                'name' => 'BlueWave Technologies',
                'email' => 'contact@bluewave.io',
                'phone' => '+1-202-555-0112',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'business_name' => 'BlueWave Technologies LLC',
                'gst_number' => null,
            ],
            [
                'name' => 'Sunrise Retail',
                'email' => 'support@sunriseretail.com',
                'phone' => '+1-202-555-0123',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'business_name' => 'Sunrise Retail Pvt Ltd',
                'gst_number' => null,
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['email' => $client['email']],
                $client
            );
        }
    }
}
