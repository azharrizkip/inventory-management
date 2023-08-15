<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SerialNumbersSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $oneMonthAgo = Carbon::now()->subMonth();
        $warrantyStart = Carbon::today();

        $serialNumbers = [];

        $products = DB::table('products')->select('id', 'price')->get();

        foreach ($products as $product) {
            for ($i = 1; $i <= 10; $i++) {
                $serialNumbers[] = [
                    'product_id' => $product->id,
                    'serial_no' => 'SN-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(4)),
                    'price' => $product->price,
                    'prod_date' => $oneMonthAgo,
                    'warranty_start' => $warrantyStart,
                    'warranty_duration' => 365,
                    'used' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('serial_numbers')->insert($serialNumbers);
    }
}
