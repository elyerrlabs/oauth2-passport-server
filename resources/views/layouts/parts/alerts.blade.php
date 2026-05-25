<script nonce="{{ $nonce }}">
    document.addEventListener("DOMContentLoaded", function() {

        @if (session('status'))
            $notify.open({
                type: 'warning',
                message: "{{ session('status') }}"
            });
        @endif

        @if (session('success'))
            $notify.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            $notify.error("{{ session('error') }}");
        @endif
    });
</script>
