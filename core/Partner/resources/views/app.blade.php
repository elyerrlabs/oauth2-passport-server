@extends('layouts.app')

@push('head')
    @include('layouts.parts.title', [
        'title' => config('app.name', 'OAuth2 Passport Server'),
    ])
@endpush

@push('css')
    @vite(['resources/css/app.css', 'core/Partner/resources/js/app.js'])
@endpush

@section('content')
    <x-inertia::app />
@endsection
