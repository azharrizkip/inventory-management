<!-- resources/views/report.blade.php -->

@extends('layouts.dashboard')

@push('styles')
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
  <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link{{ !request('tab') || request('tab') === 'transactions' ? ' active' : '' }}" href="{{ route('report', ['tab' => 'transactions']) }}" role="tab" aria-controls="transactions" aria-selected="true">Transactions</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link{{ request('tab') === 'products' ? ' active' : '' }}" href="{{ route('report', ['tab' => 'products']) }}" role="tab" aria-controls="products" aria-selected="false">Products</a>
    </li>
  </ul>

  <div class="tab-content" id="reportTabsContent">
    <div class="tab-pane fade{{ $tab === 'transactions' ? ' show active' : '' }}" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
      <h2>Transaction Report</h2>

      <div class="card mb-3">
        <div class="card-header">
            Grafik Informasi Penjualan dan Pembelian
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div id="salesPurchasesChart"></div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Hasil Penjualan dan Pembelian Selama 30 Hari Terakhir</h5>
                            <p class="card-text">Total Penjualan: Rp. {{ number_format($totalSales, 2, ',', '.') }}</p>
                            <p class="card-text">Total Pembelian: Rp. {{ number_format($totalPurchases, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
            Profit Harian Selama 30 Hari Terakhir
        </div>
        <div class="card-body">
            <div id="profitChart"></div>
        </div>
      </div>

      <form action="{{ route('report') }}" method="GET">
          <div class="mb-3">
              <label for="transaction_type" class="form-label">Transaction Type:</label>
              <select name="transaction_type" id="transaction_type" class="form-select">
                  <option value="">All</option>
                  <option value="purchase" {{ request('transaction_type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                  <option value="sell" {{ request('transaction_type') === 'sell' ? 'selected' : '' }}>Sell</option>
              </select>
          </div>
          <button type="submit" class="btn btn-primary">Apply Filter</button>
      </form>
      <table class="table table-striped">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Transaction Number</th>
                  <th>Product</th>
                  <th>Transaction Date</th>
                  <th>Customer</th>
                  <th>Price</th>
                  <th>Transaction Type</th>
              </tr>
          </thead>
          <tbody>
              @foreach($transactions as $transaction)
              <tr>
                  <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                  <td>
                    <a href={{ route('transactions.detail', ['id' => $transaction->id]) }}>
                      {{ $transaction->trans_no }}
                    </a>
                  </td>
                  <td>
                    @foreach ($transaction->details as $detail)
                        {{ $detail->serialNumber->product->product_name }}
                    @endforeach
                </td>
                  <td>{{ $transaction->trans_date }}</td>
                  <td>{{ $transaction->customer }}</td>
                  <td>
                    @foreach ($transaction->details as $detail)
                        Rp. {{ number_format($detail->price - $detail->discount, 0, ',', '.') }}
                    @endforeach
                  </td>
                  <td>{{ $transaction->trans_type }}</td>
              </tr>
              @endforeach
          </tbody>
      </table>

      <div class="pagination">
        {{ $transactions->appends(request()->except('page'))->links() }}
      </div>
    </div>
    <div class="tab-pane fade{{ $tab === 'products' ? ' show active' : '' }}" id="products" role="tabpanel" aria-labelledby="products-tab">
        <!-- Konten Tabel Produk -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Model No</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->model_no }}</td>
                    <td>{{ $product->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $products->appends(request()->except('page'))->links() }}
        </div>
    </div>
  </div>
</div>
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.2/dist/js/bootstrap.min.js"></script>
  <script>
      var reportTabs = new bootstrap.Tab(document.getElementById('reportTabs'));

      document.getElementById('transactions-tab').addEventListener('click', function() {
          reportTabs.show('transactions');
      });

      document.getElementById('products-tab').addEventListener('click', function() {
          reportTabs.show('products');
      });
  </script>
  <script>
    var profitData = @json($profitData);
    var salesPurchasesData = @json($salesPurchasesData);

    var options = {
        chart: {
            height: 350,
            type: 'line',
        },
        series: [{
            name: 'Profit',
            data: profitData.map(item => item.profit),
        }],
        xaxis: {
            categories: profitData.map(item => item.date),
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                },
            },
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                },
            },
        },
    };

    var salesPurchasesOptions = {
        chart: {
            height: 350,
            type: 'area',
        },
        series: [
            { name: 'Penjualan', data: salesPurchasesData.map(item => item.total_sales) },
            { name: 'Pembelian', data: salesPurchasesData.map(item => item.total_purchases) },
        ],
        xaxis: {
            categories: salesPurchasesData.map(item => item.date),
        },
        dataLabels: {
            enabled: false
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                },
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#profitChart"), options);
    var salesPurchasesChart = new ApexCharts(document.querySelector("#salesPurchasesChart"), salesPurchasesOptions);

    chart.render();
    salesPurchasesChart.render();
   </script>

@endpush
@endsection
