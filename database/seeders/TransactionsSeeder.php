<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $transactions = [];

        for ($i = 1; $i <= 100; $i++) {
            $transNo = 'TRX-' . Carbon::now()->format('Y-m') . '-' . strtoupper(Str::random(7));
            $transType = $i <= 50 ? 'purchase' : 'sell'; // 50 purchase, 50 sell
            $customer = $this->generateRandomName() . ' ' . rand(1, 100);
            $transDate = Carbon::now()->subDays(rand(0, 30))->format('Y-m-d H:i:s');

            $transactions[] = [
                'trans_no' => $transNo,
                'trans_type' => $transType,
                'customer' => $customer,
                'trans_date' => $transDate,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('transactions')->insert($transactions);
    }

    private function generateRandomName()
    {
        $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'David', 'Sophia', 'Daniel', 'Olivia'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia'];

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];

        return $firstName . ' ' . $lastName;
    }
}
