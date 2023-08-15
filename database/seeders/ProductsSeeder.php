<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $products = [
            [
                'product_name' => 'Laptop Lenovo ThinkPad',
                'brand' => 'Lenovo',
                'price' => 12000000,
                'model_no' => 'TP-X230',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Smartphone Samsung Galaxy S21',
                'brand' => 'Samsung',
                'price' => 15000000,
                'model_no' => 'SG-S21',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'TV LG OLED 4K',
                'brand' => 'LG',
                'price' => 18000000,
                'model_no' => 'LG-4K-OLED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Laptop HP Envy',
                'brand' => 'HP',
                'price' => 13000000,
                'model_no' => 'HP-ENVY',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Smartphone Apple iPhone 13',
                'brand' => 'Apple',
                'price' => 20000000,
                'model_no' => 'iPhone-13',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Monitor Dell UltraSharp 27"',
                'brand' => 'Dell',
                'price' => 3000000,
                'model_no' => 'Dell-U2719D',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Camera Sony Alpha A7III',
                'brand' => 'Sony',
                'price' => 15000000,
                'model_no' => 'A7III',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Speaker JBL Flip 5',
                'brand' => 'JBL',
                'price' => 1200000,
                'model_no' => 'Flip-5',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Keyboard Mechanical Corsair K95',
                'brand' => 'Corsair',
                'price' => 2500000,
                'model_no' => 'K95',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_name' => 'Gaming Mouse Logitech G Pro X',
                'brand' => 'Logitech',
                'price' => 800000,
                'model_no' => 'G-Pro-X',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('products')->insert($products);
    }
}
