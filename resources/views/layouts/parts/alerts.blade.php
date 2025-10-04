<script nonce="{{ $nonce }}">
    document.addEventListener("DOMContentLoaded", function() {

        @if (session('status'))
            $notify.success("{{ session('status') }}");
        @endif

        @if (session('error'))
            $notify.error("{{ session('error') }}");
        @endif
    });
</script>
