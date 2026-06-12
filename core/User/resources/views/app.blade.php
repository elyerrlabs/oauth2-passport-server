@extends('layouts.app')

@push('css')
    @vite(['resources/css/app.css', 'core/User/resources/js/app.js'])
@endpush

@push('head')
    @include('layouts.parts.title', [
        'title' => config('app.name', 'OAuth2 Passport Server'),
    ])
@endpush

@section('content')
    <x-inertia::app />
@endsection