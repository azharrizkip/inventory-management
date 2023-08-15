<!-- resources/views/report.blade.php -->

@extends('layouts.dashboard')

@push('styles')
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
  <h2>Transaction Report</h2>
    @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
    @endif

    <div class="mb-3">
      <a href="{{ route('transactions.add') }}" class="btn btn-success">Add Transaction</a>
    </div>
    <form action="{{ route('transactions.index') }}" method="GET">
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
                <th>Transaction Date</th>
                <th>Customer</th>
                <th>Transaction Type</th>
                <th>Action</th>
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
                  <span class="local-time">
                    {{ \Carbon\Carbon::parse($transaction->trans_date)->toIso8601String() }}
                  </span>
                </td>
                <td>{{ $transaction->customer }}</td>
                <td>{{ $transaction->trans_type }}</td>
                <td>
                    <a href="{{ route('transactions.edit', ['id' => $transaction->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal{{ $transaction->id }}">
                      Delete
                   </button>
                </td>
            </tr>
            <div class="modal fade" id="confirmDeleteModal{{ $transaction->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          Are you sure you want to delete this transaction ({{ $transaction->trans_no }})?
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                          <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
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
      {{ $transactions->appends(request()->except('page'))->links() }}
    </div>
</div>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const localTimeElements = document.getElementsByClassName('local-time');
        // loop over all local-time elements
        for (let localTimeElement of localTimeElements) {
            const utcTime = new Date(localTimeElement.textContent.trim());
            const localTime = new Date(utcTime);

            // convert to localTime with format DD-MM-YYYY HH:mm:ss
            const localTimeFormatted = localTime.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
            // remove comma in the string and replace / with -
            const localTimeFormattedWithoutComma = localTimeFormatted.replace(',', '').replace(/\//g, '-').replace('.', ':')  ;
            localTimeElement.textContent = localTimeFormattedWithoutComma;
        }
    </script>
@endpush
@endsection
