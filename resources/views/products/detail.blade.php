@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ $product->product_name }} Detail
        </div>
        <div class="card-body">
            <h4>Product Information</h4>
            <p><strong>Product Name:</strong> {{ $product->product_name }}</p>
            <p><strong>Brand:</strong> {{ $product->brand }}</p>
            <p><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p><strong>Model No:</strong> {{ $product->model_no }}</p>
            <p><strong>Stock:</strong> {{ $product->stock }}</p>

            <h4>Serial Numbers</h4>
            @if ($serialNumbers->isEmpty())
              <p>No serial numbers are associated with this product.</p>
            @else
              <table class="table table-striped">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Serial Number</th>
                          <th>Production Date</th>
                          <th>Warranty Start</th>
                          <th>Warranty End</th>
                          <th>Used</th>
                          <th>Created At</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($serialNumbers as $index => $serialNumber)
                          <tr>
                              <td>{{ $serialNumbers->firstItem() + $index }}</td>
                              <td>{{ $serialNumber->serial_no }}</td>
                              <td>
                                {{ \Carbon\Carbon::parse($serialNumber->prod_date)->format('d-m-Y') }}
                              </td>
                              <td>
                                {{ \Carbon\Carbon::parse($serialNumber->warranty_start)->format('d-m-Y') }}
                              </td>
                              <td>
                                {{ \Carbon\Carbon::parse($serialNumber->warranty_start)->addDays($serialNumber->warranty_duration)->format('d-m-Y') }}
                              </td>
                              <td>{{ $serialNumber->used ? 'Yes' : 'No' }}</td>
                              <td>
                                {{ \Carbon\Carbon::parse($serialNumber->created_at)->format('d-m-Y') }}
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>

              <div class="pagination">
                {{ $serialNumbers->appends(request()->query())->links() }}
              </div>
            @endif
        </div>
    </div>
</div>
@endsection
