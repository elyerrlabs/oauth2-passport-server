@extends('layouts.app')

@push('head')
    @if (Route::has('transaction.plans.index') && Route::currentRouteName() == 'transaction.plans.index')
        {!! config('seo.plans') !!}
    @endif
@endpush

@push('css')
    <link nonce={{ $nonce }} href="{{ mix('css/app.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @inertia
@endsection

@push('js')
    <script nonce={{ $nonce }} src="{{ mix('js/core/transaction/app.js') }}"></script>
@endpush
