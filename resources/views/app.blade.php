@extends('layouts.app')

@push('head')
    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
@endpush

@section('content')
    @inertia
@endsection
