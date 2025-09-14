@extends('layouts.app')

@push('head')
    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
@endpush

@section('content')
    @inertia
@endsection
