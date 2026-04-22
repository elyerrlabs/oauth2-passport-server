@extends('layouts.app')

@push('head')
    @include('layouts.parts.title', [
        'title' => config('app.name', 'OAuth2 Passport Server'),
    ])
@endpush

@push('css')
    <link nonce={{ $nonce }} href="{{ mix('css/app.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @inertia
@endsection

@push('js')
    <script nonce={{ $nonce }} src="{{ mix('js/core/partner/app.js') }}"></script>
@endpush
