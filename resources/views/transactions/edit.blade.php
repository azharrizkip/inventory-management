@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Edit Transaction</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group mb-2">
              <label for="product_id">Product</label>
              <input class="form-control" type="text" name="product_id" value="{{ $transactionProduct->product_name }}" readonly>
            </div>
            <div class="form-group mb-2">
              <label for="trans_type">Transaction Type</label>
              <input class="form-control" type="text" name="trans_type" value="{{ $transaction->trans_type == 'sell' ? 'Penjualan' : 'Pembelian' }}" readonly>
            </div>
            <div class="form-group mb-2">
                <label for="serial_number">Serial Number</label>
                <input name="serial_number" id="serial_number" value="{{ $serialNumber->serial_no }}" class="form-control" readonly />
            </div>

            <div class="form-group mb-2">
                <label for="customer">Customer Name</label>
                <input type="text" name="customer" class="form-control" value="{{ $transaction->customer }}" required>
            </div>
            <div id="purchase-fields" style="display: none;">
              <div class="form-group mb-2">
                  <label for="production_date">Production Date</label>
                  <input type="date" name="production_date" id="production_date" class="form-control" value="{{ \Carbon\Carbon::parse($serialNumber->prod_date)->format('Y-m-d') }}">
              </div>
              <div class="form-group mb-2">
                  <label for="warranty_start">Warranty Start</label>
                  <input type="date" name="warranty_start" id="warranty_start" class="form-control" value="{{ \Carbon\Carbon::parse($serialNumber->warranty_start)->format('Y-m-d') }}">
              </div>
              <div class="form-group mb-2">
                  <label for="warranty_duration">Warranty Duration (in days)</label>
                  <input type="number" name="warranty_duration" id="warranty_duration" class="form-control" value="{{ $serialNumber->warranty_duration }}">
              </div>
            </div>
            <div class="form-group mb-2">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ $transactionDetail->price }}" readonly>
            </div>
            <div class="form-group mb-2">
                <label for="discount">Discount</label>
                <input type="number" name="discount" id="discount" class="form-control" value="{{ $transactionDetail->discount }}" required>
            </div>
            <div class="form-group mb-2">
              <label for="total_price">Total Price</label>
              <span id="total_price">{{ $transactionDetail->price - $transactionDetail->discount }}</span>
          </div>

            <button type="submit" class="btn btn-primary mt-3">Update Transaction</button>
        </form>
    </div>

    @push('scripts')
      <script>
         var transType = "{{ $transaction->trans_type }}";

        function calculateTotalPrice() {
          var price = parseFloat(document.getElementById('price').value);
          var discount = parseFloat(document.getElementById('discount').value);
          var total = price - discount;

          var formattedTotal = formatRupiah(total);
          document.getElementById('total_price').textContent = formattedTotal;
        }

        function formatRupiah(number) {
            var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            return formatter.format(number);
        }

        function checkAdditionalFields(){
          var purchaseFields = document.getElementById('purchase-fields');

          if (transType === 'purchase') {
              purchaseFields.style.display = 'block';
          }
        }

        document.addEventListener("DOMContentLoaded", function() {
            calculateTotalPrice();
            checkAdditionalFields();
        });

        document.getElementById('price').addEventListener('input', calculateTotalPrice);
        document.getElementById('discount').addEventListener('input', calculateTotalPrice);
      </script>
    @endpush
@endsection
