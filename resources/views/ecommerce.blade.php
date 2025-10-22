@extends('layouts.app')

@push('head')
    @vite(['resources/js/ecommerce.js'])
@endpush

@section('content')
    @inertia
@endsection

@section('footer')
    @include('layouts.parts.footer', ['hidden' => 'hidden'])
@endsection
