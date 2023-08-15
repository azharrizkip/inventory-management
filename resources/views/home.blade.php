@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome, {{ $user->username }}!</div>

                <div class="card-body">
                    <p>Glad to see you here. Have a great day!</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Local Time</div>

                <div class="card-body">
                    <p>The current local time is: <span id="local-time">{{ $currentTime }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const utcTimeString = "{{ $currentTime }}";
        const localTime = new Date(utcTimeString);

        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZoneName: 'short'
        };

        const localTimeString = localTime.toLocaleString('id-ID', options);

        const localTimeElement = document.getElementById('local-time');
        localTimeElement.textContent = localTimeString;
    </script>
@endpush
@endsection
