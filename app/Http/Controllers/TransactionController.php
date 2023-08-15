<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\SerialNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::orderByDesc('trans_date'); // Mengambil data berdasarkan urutan waktu terbaru

        if ($request->has('transaction_type') && $request->input('transaction_type') != '') {
            $transactionType = $request->input('transaction_type');
            $query->where('trans_type', $transactionType);
        }

        $transactions = $query->paginate(10); // Menampilkan 10 item per halaman

        return view('transactions.index', compact('transactions'));
    }

    public function report(Request $request)
    {
        $query = Transaction::with(['details.serialNumber.product'])->orderByDesc('trans_date'); // Mengambil data berdasarkan urutan waktu terbaru

        if ($request->has('transaction_type') && $request->input('transaction_type') != '') {
            $transactionType = $request->input('transaction_type');
            $query->where('trans_type', $transactionType);
        }

        $transactions = $query->paginate(10); // Menampilkan 10 item per halaman

        // Ambil data produk dan jumlah stoknya, diurutkan dari yang terbaru
        $products = Product::orderBy('created_at', 'desc')->paginate(5);

        $startDate = now()->subDays(30)->format('Y-m-d');
        $endDate = now()->addDays(1)->format('Y-m-d');

        // Ambil data profit harian selama 30 hari terakhir
        $profitData = DB::table('transaction_details')
            ->selectRaw('DATE(transactions.trans_date) AS date, SUM(CASE WHEN transactions.trans_type = "purchase" THEN transaction_details.discount - transaction_details.price ELSE transaction_details.price - transaction_details.discount END) AS profit')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.trans_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ambil data total penjualan dan pembelian selama 30 hari terakhir
        $salesPurchasesData = DB::table('transactions')
        ->selectRaw('DATE(transactions.trans_date) AS date, SUM(CASE WHEN transactions.trans_type = "sell" THEN transaction_details.price - transaction_details.discount ELSE 0 END) AS total_sales, SUM(CASE WHEN transactions.trans_type = "purchase" THEN transaction_details.price - transaction_details.discount ELSE 0 END) AS total_purchases')
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->whereBetween('transactions.trans_date', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Hitung total penjualan dan pembelian
        $totalSales = $salesPurchasesData->sum('total_sales');
        $totalPurchases = $salesPurchasesData->sum('total_purchases');


        return view('report', compact('transactions', 'products', 'profitData', 'salesPurchasesData', 'totalSales', 'totalPurchases'))->with('tab', $request->input('tab', 'transactions'));
    }

    public function showDetail($id)
    {
        $transaction = Transaction::with('details')->findOrFail($id);
        return view('transactions.detail', compact('transaction'));
    }

    public function create()
    {
        $products = Product::all();
        return view('transactions.add', compact('products'));
    }

    public function store(Request $request)
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $randomAlphanumeric = strtoupper(Str::random(6));
        $transNo = "TRX-$year-$month-$randomAlphanumeric";

        // Validasi form input sesuai kebutuhan
        $request->validate([
            'customer' => 'required|string|max:255',
            'trans_type' => 'required|in:sell,purchase',
            'product_id' => 'required|exists:products,id',
            'discount' => 'required|numeric|min:0',
        ]);

        $transaction = new Transaction();
        $transaction->trans_date = now();
        $transaction->trans_no = $transNo;
        $transaction->customer = $request->input('customer');
        $transaction->trans_type = $request->input('trans_type');
        $transaction->save();

        // $transaction->product_id = $request->input('product_id');
        // $transaction->discount = $request->input('discount');

        $transactionDetails = [];

        // Get the selected product
        $product = Product::findOrFail($request->input('product_id'));

        if ($transaction->trans_type == 'sell') {
            $request->validate([
                'serial_number' => 'required|exists:serial_numbers,id,product_id,' . $product->id,
            ]);

            $serialNumberId = $request->input('serial_number');
            $serialNumber = SerialNumber::findOrFail($serialNumberId);

            $transactionDetails[] = [
                'serial_number_id' => $serialNumberId,
                'price' => $serialNumber->product->price,
                'discount' => $request->input('discount'),
            ];

            // Proses pengurangan stock di table products
            $product->stock -= 1;
            $product->save();

            // Update status serial number
            $serialNumber->used = 1;
            $serialNumber->save();
        } elseif ($transaction->trans_type == 'purchase') {
            // Proses penambahan record pada table serial_numbers
            $request->validate([
                'production_date' => 'required|date',
                'warranty_start' => 'required|date',
                'warranty_duration' => 'required|numeric|min:0',
            ]);
            $serialNumber = new SerialNumber();
            $generatedSN = "SN-" . strtoupper(Str::random(3)) . "-" . strtoupper(Str::random(4));
            $serialNumber->serial_no = $generatedSN; // Fungsi untuk menghasilkan nomor seri
            $serialNumber->prod_date = $request->input('production_date');
            $serialNumber->warranty_start = $request->input('warranty_start');
            $serialNumber->warranty_duration = $request->input('warranty_duration');
            $serialNumber->used = 0;
            $serialNumber->price = $product->price;
            $serialNumber->product_id = $product->id;
            $serialNumber->save();

            $transactionDetails[] = [
                'serial_number_id' => $serialNumber->id,
                'price' => $serialNumber->product->price,
                'discount' => $request->input('discount'),
            ];

            $product->stock += 1;
            $product->save();
        }

        $transaction->details()->createMany($transactionDetails);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $serialNumber = SerialNumber::where('id', $transaction->details->first()->serial_number_id)->first();

        // Hapus record transaksi dan detail transaksi
        $transaction->details()->delete();
        $product = Product::findOrFail($transaction->details->first()->serialNumber->product->id);
        // Cek tipe transaksi
        if ($transaction->trans_type === 'sell') {
            // Proses pengembalian stok ke produk
            $product->stock += 1; // Kembalikan 1 stok

            // Update status serial number yang terjual
            if ($serialNumber) {
                $serialNumber->used = 0; // Kembalikan status used
                $serialNumber->save();
            }
        } elseif ($transaction->trans_type === 'purchase') {
            // Hapus serial number yang dibeli
            $product->stock -= 1; // Kurangi 1 stok

            if ($serialNumber) {
                $serialNumber->delete();
            }
        }

        $product->save();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $products = Product::all();

        $transactionDetail = $transaction->details->first();
        $transactionProduct = Product::findOrFail($transaction->details->first()->serialNumber->product->id);
        $serialNumber = $transaction->details->first()->serialNumber;
        return view('transactions.edit', compact('transaction', 'products', 'transactionDetail', 'transactionProduct', 'serialNumber'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'customer' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update transaction details
            $transactionDetails = [
                'discount' => $request->input('discount'),
            ];

            // Update transaction
            $transaction->update([
                'customer' => $request->input('customer'),
            ]);

            // Update transaction details
            $transaction->details()->update($transactionDetails);

            if ($transaction->trans_type == 'purchase') {
                // validate input for production date, warranty start, and warranty duration
                $request->validate([
                    'production_date' => 'required|date',
                    'warranty_start' => 'required|date',
                    'warranty_duration' => 'required|numeric|min:0',
                ]);

                // Update the purchased serial number if exists
                $serialNumberId = $transaction->details->first()->serial_number_id;
                $serialNumber = SerialNumber::findOrFail($serialNumberId);
                $serialNumber->update([
                    'prod_date' => $request->input('production_date'),
                    'warranty_start' => $request->input('warranty_start'),
                    'warranty_duration' => $request->input('warranty_duration'),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while updating the transaction: ' . $e->getMessage();

            // Rollback the transaction if an error occurs
            DB::rollback();

            return redirect()->back()->withErrors([$errorMessage]);
        }
    }


    public function getSerialNumbers($product_id)
    {
        $serialNumbers = SerialNumber::where('product_id', $product_id)
            ->where('used', 0) // Hanya ambil serial number yang belum digunakan
            ->get();

        return response()->json($serialNumbers);
    }
}