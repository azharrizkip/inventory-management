@extends('layouts.dashboard')

@section('content')
    <div class="container">
      <h2>Product List</h2>
      @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
      @endif

      <div class="mb-3">
        <a href="{{ route('products.add') }}" class="btn btn-success">Add Product</a>
      </div>
      <div class="mb-3">
        <form action="{{ route('products') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by Product Name or Model Number" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
      </div>

      <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Model No</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                <tr>
                   <td>{{ $products->firstItem() + $index }}</td>
                    <td><a href="{{ route('products.show', $product->id) }}">{{ $product->product_name }}</a></td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ $product->model_no }}</td>
                    <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                      <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal{{ $product->id }}">
                        Delete
                     </button>
                  </td>
                </tr>
                <div class="modal fade" id="confirmDeleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              Are you sure you want to delete {{ $product->product_name }}?
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                              <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger">Delete</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
            @endforeach
        </tbody>
      </table>
      <div class="pagination">
        {{ $products->appends(request()->query())->links() }}
      </div>
      {{-- <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
              <th>No</th>
              <th>Product Name</th>
              <th>Brand</th>
              <th>Serial Number</th>
              <th>Price</th>
              <th>Production Date</th>
              <th>Warranty Start</th>
              <th>Warranty End</th>
              <th>Used</th>
              <th>Actions</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($serialNumbers as $index => $serialNumber)
                <tr>
                    <td>{{ $serialNumbers->firstItem() + $index }}</td>
                    <td>{{ $serialNumber->product->product_name }}</td>
                    <td>{{ $serialNumber->product->brand }}</td>
                    <td>{{ $serialNumber->serial_no }}</td>
                    <td>Rp. {{ number_format($serialNumber->price, 0, ',', '.') }}</td>
                    <td>{{ $serialNumber->prod_date }}</td>
                    <td>{{ $serialNumber->warranty_start }}</td>
                    <td>
                      {{ \Carbon\Carbon::parse($serialNumber->warranty_start)->addDays($serialNumber->warranty_duration)->format('Y-m-d') }}
                    </td>
                    <td>{{ $serialNumber->used ? 'Yes' : 'No' }}</td>
                    <td>
                      @if (!$serialNumber->used)
                          <a href="{{ route('products.edit', $serialNumber->id) }}" class="btn btn-sm btn-primary">Edit</a>
                      @endif
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal{{ $serialNumber->id }}">
                        Delete
                    </button>
                  </td>
                </tr>
                <div class="modal fade" id="confirmDeleteModal{{ $serialNumber->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              Are you sure you want to delete this serial number?
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                              <form action="{{ route('products.destroy', $serialNumber->id) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger">Delete</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
            @endforeach
        </tbody>
      </table> --}}

      {{-- <div class="pagination">
        {{ $serialNumbers->appends(request()->query())->links() }}
      </div> --}}


    </div>

    @push('scripts')
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @endpush
@endsection
