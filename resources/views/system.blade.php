@extends('layouts.app')

@push('head')
    @vite('resources/js/system.js')
@endpush

@section('content')
    @inertia
@endsection
