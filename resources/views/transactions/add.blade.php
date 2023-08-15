<!-- resources/views/transactions/add.blade.php -->
@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Add Transaction</h1>
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

        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf
            <div class="form-group mb-2">
              <label for="product_id">Product</label>
              <select id="product_id" name="product_id" class="form-control" required>
                  <option value="">Select a product...</option>
                  @foreach ($products as $product)
                      <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->product_name }}</option>
                  @endforeach
              </select>
            </div>
            <div class="form-group mb-2">
              <label for="trans_type">Transaction Type</label>
              <select name="trans_type" id="trans_type" class="form-control" required>
                  <option value="">Select a transaction type...</option>
                  <option value="sell">Penjualan</option>
                  <option value="purchase">Pembelian</option>
              </select>
            </div>
            <div id="sale-fields" style="display: none;">
                <div class="form-group mb-2">
                    <label for="serial_number">Serial Number</label>
                    <select name="serial_number" id="serial_number" class="form-control">
                        <option value="">-- Select Serial Number --</option>
                        <!-- Loop untuk menampilkan pilihan serial number -->
                    </select>
                </div>
            </div>
            <div class="form-group mb-2">
                <label for="customer">Customer Name</label>
                <input type="text" name="customer" class="form-control" required>
            </div>
            <div id="purchase-fields" style="display: none;">
              <div class="form-group mb-2">
                  <label for="production_date">Production Date</label>
                  <input type="date" name="production_date" id="production_date" class="form-control">
              </div>
              <div class="form-group mb-2">
                  <label for="warranty_start">Warranty Start</label>
                  <input type="date" name="warranty_start" id="warranty_start" class="form-control">
              </div>
              <div class="form-group mb-2">
                  <label for="warranty_duration">Warranty Duration (in days)</label>
                  <input type="number" name="warranty_duration" id="warranty_duration" class="form-control">
              </div>
            </div>
            <div class="form-group mb-2">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" value="" readonly>
            </div>
            <div class="form-group mb-2">
                <label for="discount">Discount</label>
                <input type="number" name="discount" id="discount" class="form-control" value="0" required>
            </div>
            <div class="form-group mb-2">
              <label for="total_price">Total Price</label>
              <span id="total_price">0.00</span>
          </div>

            <button type="submit" class="btn btn-primary mt-3">Save Transaction</button>
        </form>
    </div>

    @push('scripts')
      <script>
        function updatePrice() {
            var productSelect = document.getElementById('product_id');
            var priceInput = document.getElementById('price');
            var selectedProduct = productSelect.options[productSelect.selectedIndex];
            priceInput.value = selectedProduct.getAttribute('data-price');
        }

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

        function populateSerialNumbers() {
            var productId = document.getElementById('product_id').value; // Ambil ID produk yang dipilih
            var serialNumberSelect = document.getElementById('serial_number');

            serialNumberSelect.innerHTML = '<option value="">-- Select Serial Number --</option>';

            fetch(`/get-serial-numbers/${productId}`)
              .then(response => response.json())
              .then(data => {
                  data.forEach(serialNumber => {
                      var option = document.createElement('option');
                      option.value = serialNumber.id;
                      option.textContent = serialNumber.serial_no;
                      serialNumberSelect.appendChild(option);
                  });
              })
              .catch(error => console.error('Error fetching serial numbers:', error));
        }

        document.addEventListener("DOMContentLoaded", function() {
            updatePrice();
        });

        document.getElementById('product_id').addEventListener('change', function(){
          updatePrice();
          calculateTotalPrice();
          if(document.getElementById('trans_type').value === 'sell'){
            populateSerialNumbers();
          }
        });
        document.getElementById('trans_type').addEventListener('change', function () {
            var purchaseFields = document.getElementById('purchase-fields');
            var saleFields = document.getElementById('sale-fields');

            if (this.value === 'purchase') {
                purchaseFields.style.display = 'block';
                saleFields.style.display = 'none';
            } else {
                saleFields.style.display = 'block';
                purchaseFields.style.display = 'none';
                populateSerialNumbers();
            }
        });
        document.getElementById('price').addEventListener('input', calculateTotalPrice);
        document.getElementById('discount').addEventListener('input', calculateTotalPrice);
    </script>
    @endpush
@endsection
