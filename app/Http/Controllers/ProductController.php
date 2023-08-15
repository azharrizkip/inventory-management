<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SerialNumber;
use App\Models\Product;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    public function index(Request $request)
    {
      // $query = SerialNumber::with('product');

      // if ($request->has('search')) {
      //     $search = $request->input('search');
      //     $query->where('serial_no', 'like', "%$search%")
      //           ->orWhereHas('product', function ($query) use ($search) {
      //               $query->where('product_name', 'like', "%$search%");
      //           });
      // }

      // $serialNumbers = $query->orderBy('created_at', 'desc')->paginate(10);

      // return view('products.index', compact('serialNumbers'));

      $search = $request->input('search');

      $query = Product::query();

      if ($search) {
          $query->where('product_name', 'LIKE', '%' . $search . '%')
              ->orWhere('model_no', 'LIKE', '%' . $search . '%');
      }

      $products = $query->orderBy('created_at','desc')->paginate(5);

      return view('products.index', compact('products'));
    }

    public function create()
    {
        $brands = Product::pluck('brand')->unique();
        return view('products.add', compact('brands'));
    }

    public function store(Request $request)
    {
      $request->validate([
        'product_name' => 'required',
        'brand' => 'required',
        'model_no' => 'required|unique:products',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
      ]);

      $requestData = $request->all();

      if ($request->input('brand') === 'new') {
          $brand = $request->input('new_brand');
          $requestData['brand'] = $brand;
      }

      Product::create($requestData);

      return redirect()->route('products')->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $brands = Product::pluck('brand')->unique();

        return view('products.edit', compact('product', 'brands'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'serial_no' => [
    //           'required',
    //           Rule::unique('serial_numbers')->ignore($id),
    //           'regex:/^SN-[A-Za-z0-9]{3}-[A-Za-z0-9]{4}$/'
    //         ],
    //         'warranty_start' => 'required|date',
    //         'prod_date' => 'required|date',
    //         'warranty_duration' => 'required|integer',
    //         'product_id' => 'required|exists:products,id', // Validasi bahwa product_id ada di tabel products
    //     ]);


    //     $serialNumber = SerialNumber::findOrFail($id);

    //     $updated = $serialNumber->update([
    //         'serial_no' => $request->serial_no,
    //         'warranty_start' => $request->warranty_start,
    //         'prod_date' => $request->prod_date,
    //         'warranty_duration' => $request->warranty_duration,
    //         'used' => 0,
    //         'product_id' => $request->product_id,
    //     ]);

    //     if ($updated) {
    //         return redirect()->route('products')->with('success', 'Serial Number berhasil diperbarui.');
    //     } else {
    //         return redirect()->route('products')->with('error', 'Gagal memperbarui Serial Number.');
    //     }
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'brand' => 'required',
            'model_no' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $requestData = $request->all();

        if ($request->input('brand') === 'new') {
          $brand = $request->input('new_brand');
          $requestData['brand'] = $brand;
        }


        $product = Product::findOrFail($id);
        $product->update([
            'product_name' => $request->product_name,
            'brand' => $requestData['brand'],
            'model_no' => $request->model_no,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
      $product = Product::findOrFail($id);
      // Hapus produk
      $product->delete();
      return redirect()->route('products')->with('success', 'Product has been deleted successfully.');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $serialNumbers = $product->serialNumbers()->orderBy('created_at', 'desc')->paginate(5);

        return view('products.detail', compact('product', 'serialNumbers'));
    }

}
