<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->
<template>
    <transition
        enter-active-class="transition-opacity duration-200 ease-out"
        leave-active-class="transition-opacity duration-150 ease-in"
        enter-from-class="opacity-0"
        leave-to-class="opacity-0"
    >
        <div
            v-if="loading"
            class="fixed inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center z-50"
        >
            <div
                class="text-center transform transition-all duration-300"
                :class="{ 'scale-105': loading }"
            >
                <!-- Loader con múltiples elementos para sensación de velocidad -->
                <div class="relative w-20 h-20 mx-auto mb-5">
                    <div class="absolute inset-0">
                        <div
                            class="w-full h-full border-4 border-purple-100 rounded-full"
                        ></div>
                    </div>
                    <div class="absolute inset-0 animate-spin">
                        <div
                            class="w-full h-full border-4 border-transparent border-t-purple-500 rounded-full"
                        ></div>
                    </div>
                    <div class="absolute inset-2">
                        <div
                            class="w-full h-full border-4 border-transparent border-b-purple-400 rounded-full animate-pulse"
                        ></div>
                    </div>
                    <div class="absolute inset-4">
                        <div
                            class="w-full h-full border-4 border-transparent border-l-purple-300 rounded-full animate-spin duration-700"
                        ></div>
                    </div>
                </div>
 
                <p class="text-gray-600 font-medium text-lg animate-pulse">
                    {{ __('Loading...') }}<span class="inline-block ml-1">
                        <span class="animate-bounce delay-75">.</span>
                        <span class="animate-bounce delay-150">.</span>
                        <span class="animate-bounce delay-300">.</span>
                    </span>
                </p>
 
                <div class="mt-4 flex justify-center space-x-1">
                    <div
                        class="w-2 h-2 bg-purple-400 rounded-full animate-bounce delay-100"
                    ></div>
                    <div
                        class="w-2 h-2 bg-purple-500 rounded-full animate-bounce delay-200"
                    ></div>
                    <div
                        class="w-2 h-2 bg-purple-600 rounded-full animate-bounce delay-300"
                    ></div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
});
</script>
