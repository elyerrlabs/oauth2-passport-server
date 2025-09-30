@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => $title])
@endsection


@section('content')
    <div class="min-h-screen bg-gray-50 flex justify-center items-start py-10">
        <!-- Main Content Area -->
        <main class="w-full max-w-4xl px-4">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="/"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Home') }}
                </a>
            </div>

            <!-- Content Box -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="ql-container ql-snow">
                    <div class="ql-editor min-h-[400px] p-8 prose max-w-none">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
