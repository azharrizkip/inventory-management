@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Add Product
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="product_id">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                      <label for="brand">Brand</label>
                      <select name="brand" id="brand" class="form-control">
                          <option value="">Select a brand...</option>
                          @foreach ($brands as $brand)
                              <option value="{{ $brand }}">{{ $brand }}</option>
                          @endforeach
                          <option value="new">Tambah Brand Baru</option>
                      </select>
                    </div>
                    <div class="form-group mb-2" id="newBrandField" style="display: none;">
                      <label for="new_brand">New Brand</label>
                      <input type="text" name="new_brand" id="new_brand" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                      <label for="model_no">Model No</label>
                      <input type="text" name="model_no" id="model_no" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="price">Price</label>
                        <input type="text" name="price" id="price" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
      <script>
          document.addEventListener("DOMContentLoaded", function() {
              var brandInput = document.getElementById("brand");
              var newBrandField = document.getElementById("newBrandField");

              brandInput.addEventListener("change", function() {
                  console.log(this.value);
                  if (this.value === "new") {
                      newBrandField.style.display = "block";
                  } else {
                      newBrandField.style.display = "none";
                  }
                });
          });
      </script>
    @endpush
@endsection
