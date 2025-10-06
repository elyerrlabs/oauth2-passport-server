@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => $title])
@endsection


@section('content')
    <div class="min-h-screen bg-gray-50 flex justify-center items-start py-10">
        <!-- Main Content Area -->
        <main class="w-full max-w-5xl px-4">
            <!-- Content Box -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <!-- #endregion -->
                <div class="min-h-[400px] p-8 prose max-w-none">
                    {!! $content !!}
                </div>
            </div>
        </main>
    </div>
@endsection
