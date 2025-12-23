<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-950 text-gray-100"
    >
        <div class="text-center space-y-6">
            <h1 class="text-7xl font-extrabold text-red-500">404</h1>

            <p class="text-xl text-gray-300">Page not found</p>

            <p class="text-sm text-gray-400 max-w-md mx-auto">
                The page you are trying to access does not exist or was moved.
            </p>

            <button
                @click="goBack"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition font-semibold"
            >
                Go back
            </button>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        goBack() {
            window.history.length > 1
                ? window.history.back()
                : (window.location.href = "/");
        },
    },
};
</script>
