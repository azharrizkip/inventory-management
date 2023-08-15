@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Add Serial Number Product
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="product_id">Product Name</label>
                        <select name="product_id" id="product_id" class="form-control">
                            <option value="">Select a product...</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="serial_no">Serial Number</label>
                        <input type="text" name="serial_no" id="serial_no" class="form-control" placeholder="format SN-XXX-XXXX">
                    </div>
                    <div class="form-group mb-2">
                        <label for="prod_date">Production Date</label>
                        <input type="date" name="prod_date" id="prod_date" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label for="warranty_start">Warranty Start</label>
                        <input type="date" name="warranty_start" id="warranty_start" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label for="warranty_duration">Warranty Duration (in days)</label>
                        <input type="number" name="warranty_duration" id="warranty_duration" class="form-control" placeholder="Lama garansi">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
      <script>
        document.addEventListener("DOMContentLoaded", function() {
            var serialNumberInput = document.getElementById("serial_no");

            serialNumberInput.addEventListener("input", function() {
                this.value = this.value.toUpperCase();
            });
        });
    </script>
    @endpush
@endsection
