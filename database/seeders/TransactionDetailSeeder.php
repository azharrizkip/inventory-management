<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionDetailSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $transactionDetails = [];
        $transactions = DB::table('transactions')->get();
        $serialNumbers = DB::table('serial_numbers')->inRandomOrder()->get();

        foreach ($transactions as $transaction) {
            $transactionId = $transaction->id;

            $serialNumber = $serialNumbers->shift(); // Mengambil dan menghapus elemen pertama dari array
            $price = $serialNumber->price;


            $discount = $transactionId % 10 === 0 ? rand(100000, 300000) : 0;

            $transactionDetails[] = [
                'transaction_id' => $transactionId,
                'serial_number_id' => $serialNumber->id,
                'price' => $price,
                'discount' => $discount,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('transaction_details')->insert($transactionDetails);
    }
}
