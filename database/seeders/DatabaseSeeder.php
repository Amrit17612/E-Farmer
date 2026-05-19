<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Crop;
use App\Models\Order;
use App\Models\MarketPrice;
use App\Models\Scheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('admin123'),
            'phone'     => '9876543212',
            'location'  => 'India',
            'role'      => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        // Create market prices
        $marketData = [
            ['crop_name' => 'Wheat',    'category' => 'Grains',     'min_price' => 2100, 'max_price' => 2400, 'modal_price' => 2250, 'unit' => 'quintal', 'market_name' => 'Ludhiana APMC',  'state' => 'Punjab',      'district' => 'Ludhiana',   'trend' => 'up'],
            ['crop_name' => 'Rice',     'category' => 'Grains',     'min_price' => 2900, 'max_price' => 3300, 'modal_price' => 3100, 'unit' => 'quintal', 'market_name' => 'Karnal APMC',    'state' => 'Haryana',     'district' => 'Karnal',     'trend' => 'stable'],
            ['crop_name' => 'Tomato',   'category' => 'Vegetables', 'min_price' => 35,   'max_price' => 60,   'modal_price' => 48,   'unit' => 'kg',      'market_name' => 'Nashik APMC',    'state' => 'Maharashtra', 'district' => 'Nashik',     'trend' => 'up'],
            ['crop_name' => 'Onion',    'category' => 'Vegetables', 'min_price' => 20,   'max_price' => 45,   'modal_price' => 32,   'unit' => 'kg',      'market_name' => 'Lasalgaon APMC', 'state' => 'Maharashtra', 'district' => 'Nashik',     'trend' => 'down'],
            ['crop_name' => 'Potato',   'category' => 'Vegetables', 'min_price' => 15,   'max_price' => 30,   'modal_price' => 22,   'unit' => 'kg',      'market_name' => 'Agra APMC',      'state' => 'UP',          'district' => 'Agra',       'trend' => 'stable'],
            ['crop_name' => 'Cotton',   'category' => 'Others',     'min_price' => 6500, 'max_price' => 7500, 'modal_price' => 7000, 'unit' => 'quintal', 'market_name' => 'Rajkot APMC',    'state' => 'Gujarat',     'district' => 'Rajkot',     'trend' => 'up'],
            ['crop_name' => 'Soybean',  'category' => 'Oilseeds',   'min_price' => 4200, 'max_price' => 4800, 'modal_price' => 4500, 'unit' => 'quintal', 'market_name' => 'Indore APMC',    'state' => 'MP',          'district' => 'Indore',     'trend' => 'stable'],
            ['crop_name' => 'Mustard',  'category' => 'Oilseeds',   'min_price' => 5000, 'max_price' => 5600, 'modal_price' => 5300, 'unit' => 'quintal', 'market_name' => 'Jaipur APMC',    'state' => 'Rajasthan',   'district' => 'Jaipur',     'trend' => 'up'],
            ['crop_name' => 'Maize',    'category' => 'Grains',     'min_price' => 1700, 'max_price' => 2000, 'modal_price' => 1850, 'unit' => 'quintal', 'market_name' => 'Davangere APMC', 'state' => 'Karnataka',   'district' => 'Davangere',  'trend' => 'down'],
            ['crop_name' => 'Chilli',   'category' => 'Spices',     'min_price' => 8000, 'max_price' => 12000,'modal_price' => 10000,'unit' => 'quintal', 'market_name' => 'Guntur APMC',    'state' => 'AP',          'district' => 'Guntur',     'trend' => 'up'],
            ['crop_name' => 'Turmeric', 'category' => 'Spices',     'min_price' => 7000, 'max_price' => 9000, 'modal_price' => 8000, 'unit' => 'quintal', 'market_name' => 'Nizamabad APMC', 'state' => 'Telangana',   'district' => 'Nizamabad',  'trend' => 'stable'],
            ['crop_name' => 'Moong Dal','category' => 'Pulses',     'min_price' => 7500, 'max_price' => 8500, 'modal_price' => 8000, 'unit' => 'quintal', 'market_name' => 'Kota APMC',      'state' => 'Rajasthan',   'district' => 'Kota',       'trend' => 'up'],
        ];

        foreach ($marketData as $data) {
            MarketPrice::create(array_merge($data, ['price_date' => now()->subDays(rand(0, 3))]));
        }

        // Create government schemes
        $schemes = [
            [
                'title'       => 'PM-KISAN Samman Nidhi',
                'description' => 'Direct income support of ₹6,000 per year to all farmer families across the country to supplement their financial needs.',
                'eligibility' => 'Small and marginal farmer families with combined landholding up to 2 hectares.',
                'benefits'    => '₹6,000 per year in three equal instalments of ₹2,000 each every four months.',
                'ministry'    => 'Ministry of Agriculture & Farmers Welfare',
                'category'    => 'Subsidy',
                'apply_link'  => 'https://pmkisan.gov.in',
                'is_active'   => true,
                'documents_required' => ['Aadhaar Card', 'Land Records', 'Bank Account Details', 'Mobile Number'],
            ],
            [
                'title'       => 'Pradhan Mantri Fasal Bima Yojana',
                'description' => 'Comprehensive crop insurance scheme providing financial support to farmers suffering crop loss due to unforeseen events.',
                'eligibility' => 'All farmers growing notified crops in a notified area.',
                'benefits'    => 'Insurance coverage and financial support for crop failure. Premium: 2% for Kharif, 1.5% for Rabi crops.',
                'ministry'    => 'Ministry of Agriculture & Farmers Welfare',
                'category'    => 'Insurance',
                'apply_link'  => 'https://pmfby.gov.in',
                'is_active'   => true,
                'documents_required' => ['Aadhaar Card', 'Land Records', 'Bank Account', 'Crop Details'],
            ],
            [
                'title'       => 'Kisan Credit Card (KCC)',
                'description' => 'Provides farmers with timely access to credit for agricultural and allied activities at concessional interest rates.',
                'eligibility' => 'All farmers including tenant farmers, oral lessees, sharecroppers.',
                'benefits'    => 'Credit limit up to ₹3 lakh at 7% interest rate (with 3% subvention = effective 4%).',
                'ministry'    => 'Ministry of Finance / NABARD',
                'category'    => 'Loan',
                'apply_link'  => 'https://www.nabard.org',
                'is_active'   => true,
                'documents_required' => ['Aadhaar Card', 'PAN Card', 'Land Documents', 'Bank Account'],
            ],
            [
                'title'       => 'Soil Health Card Scheme',
                'description' => 'Provides soil health cards to farmers with crop-wise recommendations of nutrients and fertilizers for soil.',
                'eligibility' => 'All farmers across India.',
                'benefits'    => 'Free soil testing and personalized fertilizer recommendations to improve productivity.',
                'ministry'    => 'Ministry of Agriculture & Farmers Welfare',
                'category'    => 'Training',
                'apply_link'  => 'https://soilhealth.dac.gov.in',
                'is_active'   => true,
                'documents_required' => ['Land Records', 'Aadhaar Card'],
            ],
            [
                'title'       => 'Sub-Mission on Agricultural Mechanization',
                'description' => 'Promotes farm mechanization by providing financial assistance for purchase of agricultural machinery and equipment.',
                'eligibility' => 'Individual farmers, FPOs, cooperative societies.',
                'benefits'    => 'Subsidy of 25%-50% on agricultural machinery and equipment purchase.',
                'ministry'    => 'Ministry of Agriculture & Farmers Welfare',
                'category'    => 'Equipment',
                'apply_link'  => 'https://agrimachinery.nic.in',
                'is_active'   => true,
                'documents_required' => ['Aadhaar Card', 'Land Records', 'Bank Account', 'Quotation from Manufacturer'],
            ],
            [
                'title'       => 'National Mission for Sustainable Agriculture',
                'description' => 'Makes agriculture more productive, sustainable, remunerative and climate resilient through adoption of water use efficiency.',
                'eligibility' => 'Farmers in rainfed and other vulnerable areas.',
                'benefits'    => 'Financial assistance for water use efficiency, soil health management, and sustainable practices.',
                'ministry'    => 'Ministry of Agriculture & Farmers Welfare',
                'category'    => 'Seeds & Fertilizers',
                'apply_link'  => 'https://nmsa.dac.gov.in',
                'is_active'   => true,
                'documents_required' => ['Aadhaar Card', 'Land Records', 'Bank Account'],
            ],
        ];

        foreach ($schemes as $scheme) {
            Scheme::create($scheme);
        }

    }
}
