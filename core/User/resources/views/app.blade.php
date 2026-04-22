@extends('layouts.app')

@push('css')
    <link nonce={{ $nonce }} href="{{ mix('css/app.css') }}" rel="stylesheet" />
@endpush

@push('head')
    @include('layouts.parts.title', [
        'title' => config('app.name', 'OAuth2 Passport Server'),
    ])
@endpush

@section('content')
    @inertia
@endsection

@push('js')
    <script nonce={{ $nonce }} src="{{ mix('js/core/user/app.js') }}"></script>
@endpush
