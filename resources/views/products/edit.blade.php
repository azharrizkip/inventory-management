@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Product') }}</div>

                <div class="card-body">
                  @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif

                  <form method="POST" action="{{ route('products.update', $product->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                      <label for="product_name">{{ __('Product Name') }}</label>
                      <input id="product_name" type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" value="{{ old('product_name', $product->product_name) }}" required autofocus>
                      @error('product_name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="form-group">
                      <label for="brand">{{ __('Brand') }}</label>
                      <select id="brand" class="form-control @error('brand') is-invalid @enderror" name="brand" required>
                          <option value="">Select a brand...</option>
                          @foreach ($brands as $brand)
                              <option value="{{ $brand }}" {{ old('brand', $product->brand) == $brand ? 'selected' : '' }}>
                                  {{ $brand }}
                              </option>
                          @endforeach
                          <option value="new">Tambah Brand Baru</option>
                      </select>
                      @error('brand')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="form-group mb-2" id="newBrandField" style="display: none;">
                    <label for="new_brand">New Brand</label>
                    <input type="text" name="new_brand" id="new_brand" class="form-control">
                  </div>

                  <div class="form-group">
                      <label for="model_no">{{ __('Model No') }}</label>
                      <input id="model_no" type="text" class="form-control @error('model_no') is-invalid @enderror" name="model_no" value="{{ old('model_no', $product->model_no) }}" required>
                      @error('model_no')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="form-group">
                      <label for="price">{{ __('Price') }}</label>
                      <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" required>
                      @error('price')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="form-group">
                      <label for="stock">{{ __('Stock') }}</label>
                      <input id="stock" type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}" required>
                      @error('stock')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="form-group mb-0 mt-3">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update Product') }}
                    </button>
                </div>
                  </form>
                </div>
            </div>
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
