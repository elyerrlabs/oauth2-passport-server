@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => $title])
@endsection

@section('content')
    <div
        class="min-h-screen bg-gray-50 dark:bg-gray-900 flex justify-center items-start py-10 transition-colors duration-300">
        <!-- Main Content Area -->
        <main class="w-full max-w-5xl px-4">
            <!-- Content Box -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                <!-- Content Area -->
                <div class="min-h-[400px] p-8 prose max-w-none dark:prose-invert">
                    {!! $content !!}
                </div>
            </div>
        </main>
    </div>
@endsection
