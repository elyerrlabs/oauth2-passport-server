@extends('layouts.app')

@push('head')
    @vite(['resources/js/ecommerce.js'])
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        #footer {
            display: none;
        }
    </style>
@endpush

@section('content')
    @inertia
@endsection

@section('footer')
    <div id="footer">
        @include('layouts.parts.footer', ['hidden' => 'hidden'])
    </div>
@endsection
