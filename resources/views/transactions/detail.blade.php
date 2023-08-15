@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Transaction Detail</h1>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transaction Information</h4>
                    <p><strong>Transaction Number:</strong> {{ $transaction->trans_no }}</p>
                    <p><strong>Transaction Date:</strong> {{ $transaction->trans_date }}</p>
                    <p><strong>Customer:</strong> {{ $transaction->customer }}</p>
                    <p><strong>Transaction Type:</strong> {{ $transaction->trans_type }}</p>
                    <!-- ... Add more transaction-related information as needed -->

                    <h4 class="card-title">Transaction Details</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Serial Number</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $detail)
                                <tr>
                                    <td>{{ $detail->serialNumber->serial_no }}</td>
                                    <td>{{ $detail->serialNumber->product->product_name }}</td>
                                    <td>Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($detail->discount, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($detail->price - $detail->discount, 0, ',', '.') }}</td>

                                  </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
