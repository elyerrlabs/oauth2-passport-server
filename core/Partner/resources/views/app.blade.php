@extends('layouts.app')

@push('css')
    <link nonce={{ $nonce }} href="{{ mix('css/app.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @inertia
@endsection

@push('js')
    <script nonce={{ $nonce }} src="{{ mix('js/core/partner/app.js') }}"></script>
@endpush
