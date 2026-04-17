@extends('admin.policies.policies')

@section('form')
    <div class="space-y-6">
        <h2 class="text-2xl font-bold dark:text-white text-gray-800">
            {{ __($title) }}
        </h2>

        <p class="text-gray-600 dark:text-gray-300">
            {{ __($description) }}
        </p>

        <x-editor name="{{ $name }}" content="{{ $content }}" label="Contenido" required="{{ true }}"
            monaco="{{ true }}" jodit="{{ true }}" preview="{{ true }}" lang="html" />
    </div>
@endsection
