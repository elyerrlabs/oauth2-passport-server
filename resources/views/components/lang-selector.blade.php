<div class="flex justify-end">
    <select name="locale" id="locale" data-url="true"
        class="appearance-none rounded-lg border border-gray-300 bg-white px-3 py-2 pr-8 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @foreach (config('app.langs') as $code => $lang)
            <option value="{{ request()->fullUrlWithQuery(['lang' => $code]) }}" @selected(app()->getLocale() === $code)>
                {{ $lang['flag'] }} {{ __($lang['title']) }}
            </option>
        @endforeach
    </select>

    <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
        <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                clip-rule="evenodd" />
        </svg>
    </div>
</div>

<script nonce="{{ $nonce }}">
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('locale').addEventListener('change', function() {
            window.location.href = this.value;
        });
    })
</script>
