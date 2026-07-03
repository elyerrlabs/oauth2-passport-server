<template>
    <v-main-layout>
        <div class="max-w-6xl mx-auto p-4 md:p-6">
            <!-- Profile Header -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4"
            >
                <div class="flex-1">
                    <h1
                        class="text-3xl font-semibold text-gray-900 dark:text-white mb-1"
                    >
                        {{ __("User Profile") }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __("View and manage user account information") }}
                    </p>
                </div>
                <div class="flex justify-around gap-4 shrink-0">
                    <v-button
                        as="a"
                        :to="user?.links?.index"
                        :label="__('Back to the users')"
                        variant="secondary"
                    />
                    <v-button
                        as="a"
                        :to="user?.links?.edit"
                        :label="__('Edit Profile')"
                    />

                    <v-button
                        as="a"
                        :to="user?.links?.scopes"
                        :label="__('Manage scopes')"
                        variant="warning"
                    />
                </div>
            </div>

            <!-- Personal Information Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden"
            >
                <div
                    class="flex justify-between items-center px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        {{ __("Personal Information") }}
                    </h3>
                    <span
                        class="text-xs px-3 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                    >
                        {{ __("Verified") }}:
                        {{ user.email_verified_at ? __("Yes") : __("No") }}
                    </span>
                </div>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6"
                >
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Full Name") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.name }} {{ user.last_name }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Email Address") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.email }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Country") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.country || __("Not specified") }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("City") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.city || __("Not specified") }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Address") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.address || __("Not specified") }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Phone Number") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.full_phone?.trim() || __("Not specified") }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Birthday") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.birthday || __("Not specified") }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide"
                        >
                            {{ __("Language") }}
                        </label>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ user.lang?.toUpperCase() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Account Status Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden"
            >
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        {{ __("Account Status") }}
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                    <div
                        class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700"
                    >
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Email Verified") }}
                        </span>
                        <span
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-medium',
                                user.email_verified_at
                                    ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                    : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                            ]"
                        >
                            {{
                                user.email_verified_at
                                    ? __("Verified")
                                    : __("Unverified")
                            }}
                        </span>
                    </div>
                    <div
                        class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700"
                    >
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Account Status") }}
                        </span>
                        <span
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-medium',
                                user.disabled
                                    ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
                                    : 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                            ]"
                        >
                            {{ user.disabled ? __("Disabled") : __("Active") }}
                        </span>
                    </div>
                    <div
                        class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700"
                    >
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Member Since") }}
                        </span>
                        <span
                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ formatDate(user.created) }}
                        </span>
                    </div>
                    <div
                        class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700"
                    >
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Last Updated") }}
                        </span>
                        <span
                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ formatDate(user.updated) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions Card -->
            <div
                v-if="user.scopes?.length"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
            >
                <div
                    class="flex justify-between items-center px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        {{ __("Roles & Permissions") }}
                    </h3>
                    <span
                        class="text-xs px-3 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                    >
                        {{ user.scopes.length }} {{ __("Scope(s)") }}
                    </span>
                </div>
                <div class="p-6 space-y-4">
                    <div
                        v-for="scope in user.scopes"
                        :key="scope.id"
                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 dark:hover:shadow-gray-900"
                    >
                        <div class="flex flex-wrap gap-3 items-center mb-3">
                            <span
                                class="font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-3 py-1 rounded text-sm"
                            >
                                {{ scope.scope?.service?.name }}
                            </span>
                            <span
                                class="font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded text-sm"
                            >
                                {{ scope.scope?.role?.name }}
                            </span>
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    scope.status === 'unlimited'
                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                        : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                ]"
                            >
                                {{
                                    scope.status === "unlimited"
                                        ? __("Unlimited")
                                        : __("Limited")
                                }}
                            </span>
                        </div>
                        <div
                            class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400"
                        >
                            <div class="flex gap-1">
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-300"
                                    >{{ __("Group") }}:</span
                                >
                                <span>{{
                                    scope.scope?.service?.group?.name
                                }}</span>
                            </div>
                            <div class="flex gap-1">
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-300"
                                    >{{ __("Permission") }}:</span
                                >
                                <span>{{
                                    scope.scope?.role?.description ||
                                    __("Full access")
                                }}</span>
                            </div>
                            <div v-if="scope.end_date" class="flex gap-1">
                                <span
                                    class="font-medium text-gray-700 dark:text-gray-300"
                                    >{{ __("Expires") }}:</span
                                >
                                <span>{{ formatDate(scope.end_date) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import VMainLayout from "@/components/VMainLayout.vue";
import VButton from "@/components/VButton.vue";

const page = usePage();
const user = ref({});

const formatDate = (dateString) => {
    if (!dateString) return __("Not specified");
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

onMounted(() => {
    user.value = page.props.data;
});
</script>
