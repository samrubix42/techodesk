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
                'address_1' => '123 Acme St',
                'address_2' => 'Suite 400',
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
                'address_1' => '456 BlueWave Way',
                'address_2' => null,
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'business_name' => 'BlueWave Technologies LLC',
                'gst_number' => null,
            ],
            [
                'name' => 'Techonika Solutions',
                'email' => 'info@techonika.com',
                'phone' => '+91 98765 43210',
                'address_1' => 'Sector 62',
                'address_2' => 'Noida',
                'city' => 'Noida',
                'state' => 'Uttar Pradesh',
                'country' => 'India',
                'business_name' => 'Techonika Solutions Pvt Ltd',
                'gst_number' => '09AAACT0000A1Z5',
            ],
            [
                'name' => 'Digital Dreams',
                'email' => 'contact@digitaldreams.in',
                'phone' => '+91 88888 77777',
                'address_1' => 'MG Road',
                'address_2' => null,
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'country' => 'India',
                'business_name' => 'Digital Dreams Agency',
                'gst_number' => '29AAACT0000A1Z5',
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
