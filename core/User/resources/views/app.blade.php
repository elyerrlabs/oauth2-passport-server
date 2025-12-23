@extends('layouts.app')

@push('css')
    <link nonce={{ $nonce }} href="{{ asset('css/app.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @inertia
@endsection

@push('js')
    <script nonce={{ $nonce }} src="{{ asset('js/core/user/app.js') }}"></script>
@endpush
