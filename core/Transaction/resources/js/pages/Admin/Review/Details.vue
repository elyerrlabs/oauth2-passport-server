<template>
    <v-account-layout>
        <div class="mb-6">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="min-w-0">
                    <h1
                        class="text-xl sm:text-2xl font-medium text-gray-900 dark:text-white truncate"
                    >
                        {{ __("Refund Review") }}
                    </h1>
                    <p
                        class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-400 truncate"
                    >
                        {{ __("Review and process refund request") }}
                        <span v-if="data?.transaction?.code">
                            # {{ data.transaction.code }}
                        </span>
                    </p>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <a
                        :href="data?.web?.index"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-xs sm:text-sm font-normal text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap"
                    >
                        <svg
                            class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        {{ __("Back to List") }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center h-64">
            <div
                class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
            ></div>
        </div>

        <!-- Main Content -->
        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Column: Refund Details -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Status Header with Timeline -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-5"
                >
                    <div
                        class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 sm:gap-4"
                    >
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <div
                                    :class="`px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs sm:text-sm font-semibold ${getStatusColorClass(
                                        data?.status
                                    )}`"
                                >
                                    {{ getStatusLabel(data?.status) }}
                                </div>
                                <div
                                    class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate"
                                >
                                    {{ __("Refund ID") }}:
                                    {{ truncateId(data?.id) }}
                                </div>
                            </div>
                            <h2
                                class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate"
                            >
                                {{ __("Refund Request for") }}
                                {{ data?.user?.name }}
                                {{ data?.user?.last_name }}
                            </h2>
                            <div
                                class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ __("Created") }}:
                                {{ data?.created }}
                            </div>
                        </div>
                        <div class="text-right mt-2 md:mt-0">
                            <div
                                class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ data?.amount }} {{ data?.currency }}
                            </div>
                            <div
                                class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1"
                            >
                                {{ __("Refund Requested") }}
                            </div>
                            <div
                                class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"
                            >
                                {{ __("Original Amount") }}:
                                {{ data?.transaction?.total }}
                                {{ data?.currency }}
                            </div>
                        </div>
                    </div>

                    <!-- Timeline - Mobile Optimized -->
                    <div
                        class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="flex items-center justify-between mb-3 sm:mb-4"
                        >
                            <h3
                                class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Status Timeline") }}
                            </h3>
                        </div>

                        <!-- Desktop Timeline -->
                        <div class="hidden sm:block relative">
                            <!-- Timeline Track -->
                            <div
                                class="absolute top-4 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 rounded-full"
                            ></div>

                            <!-- Timeline Points -->
                            <div class="flex justify-between relative z-10">
                                <div
                                    v-for="(
                                        statusPoint, index
                                    ) in timelinePoints"
                                    :key="statusPoint.status"
                                    class="flex flex-col items-center"
                                    :class="{
                                        'opacity-100': isStatusActiveOrPassed(
                                            statusPoint.status
                                        ),
                                        'opacity-40': !isStatusActiveOrPassed(
                                            statusPoint.status
                                        ),
                                    }"
                                >
                                    <!-- Status Point -->
                                    <div
                                        :class="`w-3 h-3 rounded-full border-2 border-white dark:border-gray-800 ${getTimelinePointClass(
                                            statusPoint.status
                                        )}`"
                                        :title="
                                            getStatusLabel(statusPoint.status)
                                        "
                                    ></div>

                                    <!-- Status Label -->
                                    <div
                                        class="mt-2 text-xs text-center max-w-[70px] px-1"
                                    >
                                        <div
                                            :class="`font-medium truncate ${
                                                data?.status ===
                                                statusPoint.status
                                                    ? 'text-blue-600 dark:text-blue-400'
                                                    : isStatusPassed(
                                                          statusPoint.status
                                                      )
                                                    ? 'text-gray-600 dark:text-gray-400'
                                                    : 'text-gray-400 dark:text-gray-500'
                                            }`"
                                        >
                                            {{
                                                getStatusLabelShort(
                                                    statusPoint.status
                                                )
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Timeline -->
                        <div class="sm:hidden">
                            <div class="relative pl-4">
                                <!-- Vertical Timeline Line -->
                                <div
                                    class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"
                                ></div>

                                <!-- Timeline Points -->
                                <div class="space-y-4">
                                    <div
                                        v-for="(
                                            statusPoint, index
                                        ) in timelinePoints"
                                        :key="statusPoint.status"
                                        class="relative"
                                        :class="{
                                            'opacity-100':
                                                isStatusActiveOrPassed(
                                                    statusPoint.status
                                                ),
                                            'opacity-40':
                                                !isStatusActiveOrPassed(
                                                    statusPoint.status
                                                ),
                                        }"
                                    >
                                        <!-- Status Point -->
                                        <div
                                            :class="`absolute left-0 transform -translate-x-1/2 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 ${getTimelinePointClass(
                                                statusPoint.status
                                            )}`"
                                            :title="
                                                getStatusLabel(
                                                    statusPoint.status
                                                )
                                            "
                                        ></div>

                                        <!-- Status Label -->
                                        <div class="ml-6">
                                            <div
                                                :class="`text-xs font-medium truncate ${
                                                    data?.status ===
                                                    statusPoint.status
                                                        ? 'text-blue-600 dark:text-blue-400'
                                                        : isStatusPassed(
                                                              statusPoint.status
                                                          )
                                                        ? 'text-gray-600 dark:text-gray-400'
                                                        : 'text-gray-400 dark:text-gray-500'
                                                }`"
                                            >
                                                {{
                                                    getStatusLabel(
                                                        statusPoint.status
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Possible States -->
                <div
                    v-if="availableStatusOptions.length > 0 && !isStatusLocked"
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-lg border border-blue-200 dark:border-blue-800 p-3 sm:p-4"
                >
                    <div class="flex items-center gap-2 sm:gap-3 mb-3">
                        <div
                            class="p-1.5 sm:p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg"
                        >
                            <svg
                                class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate"
                            >
                                {{ __("Next Possible Actions") }}
                            </h3>
                            <p
                                class="text-xs text-gray-600 dark:text-gray-400 truncate"
                            >
                                {{
                                    __(
                                        "Available status transitions from current state"
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                        <div
                            v-for="option in availableStatusOptions"
                            :key="option.value"
                            class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 py-1.5 sm:px-3 sm:py-2 bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-800 rounded-lg shadow-sm"
                        >
                            <div
                                :class="`w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full ${
                                    getStatusColorClass(option.value).split(
                                        ' '
                                    )[0]
                                }`"
                            ></div>
                            <span
                                class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate max-w-[100px] sm:max-w-none"
                            >
                                {{ option.label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Transaction Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Original Transaction Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-5"
                    >
                        <div
                            class="flex items-center justify-between mb-3 sm:mb-4"
                        >
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Original Transaction") }}
                            </h3>
                            <div
                                class="p-1.5 sm:p-2 bg-green-100 dark:bg-green-900/20 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Transaction ID") }}
                                </div>
                                <div
                                    class="text-xs sm:text-sm font-mono font-medium text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/30 p-2 rounded truncate"
                                >
                                    {{ data?.transaction?.code }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Payment Method") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white capitalize truncate"
                                >
                                    {{ data?.transaction?.payment_method }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Original Amount") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white truncate"
                                >
                                    {{ data?.transaction?.total }}
                                    {{ data?.currency }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Status") }}
                                </div>
                                <div
                                    :class="`inline-flex items-center px-2 py-1 rounded text-xs font-semibold ${getTransactionStatusColor(
                                        data?.transaction?.status
                                    )}`"
                                >
                                    {{
                                        getTransactionStatusLabel(
                                            data?.transaction?.status
                                        )
                                    }}
                                </div>
                            </div>
                            <div v-if="data?.transaction?.payment_url">
                                <a
                                    :href="data.transaction.payment_url"
                                    target="_blank"
                                    class="inline-flex items-center text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors truncate"
                                >
                                    <svg
                                        class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                        />
                                    </svg>
                                    <span class="truncate">{{
                                        __("View Payment Receipt")
                                    }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Refund Transaction Card (Only when exists) -->
                    <div
                        v-if="data?.transactionable"
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-5"
                    >
                        <div
                            class="flex items-center justify-between mb-3 sm:mb-4"
                        >
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Refund Transaction") }}
                            </h3>
                            <div
                                class="p-1.5 sm:p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Refund Transaction ID") }}
                                </div>
                                <div
                                    class="text-xs sm:text-sm font-mono font-medium text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/30 p-2 rounded truncate"
                                >
                                    {{ data.transactionable?.code }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Refund Amount") }}
                                </div>
                                <div
                                    class="text-sm font-medium text-amber-600 dark:text-amber-400 truncate"
                                >
                                    {{ data.transactionable?.total }}
                                    {{ data.currency }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Status") }}
                                </div>
                                <div
                                    :class="`inline-flex items-center px-2 py-1 rounded text-xs font-semibold ${getTransactionStatusColor(
                                        data.transactionable?.status
                                    )}`"
                                >
                                    {{
                                        getTransactionStatusLabel(
                                            data.transactionable?.status
                                        )
                                    }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Created") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white truncate"
                                >
                                    {{ data.transactionable?.created }}
                                </div>
                            </div>
                            <div v-if="data.transactionable?.payment_intent_id">
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Payment Intent") }}
                                </div>
                                <div
                                    class="text-xs font-mono text-gray-600 dark:text-gray-400 truncate"
                                >
                                    {{
                                        truncateId(
                                            data.transactionable
                                                .payment_intent_id
                                        )
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Card -->
                    <div
                        class="md:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-5"
                    >
                        <div
                            class="flex items-center justify-between mb-3 sm:mb-4"
                        >
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Customer Information") }}
                            </h3>
                            <div
                                class="p-1.5 sm:p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6"
                        >
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Full Name") }}
                                </div>
                                <div
                                    class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                >
                                    {{ data?.user?.name }}
                                    {{ data?.user?.last_name }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Email Address") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white truncate"
                                >
                                    {{ data?.user?.email }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Customer ID") }}
                                </div>
                                <div
                                    class="text-xs font-mono text-gray-600 dark:text-gray-400 truncate"
                                >
                                    {{ truncateId(data?.user?.id) }}
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Info -->
                        <div
                            class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >
                                        {{ __("Assigned To") }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-900 dark:text-white flex items-center gap-2 truncate"
                                    >
                                        <div
                                            class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-green-500 flex-shrink-0"
                                        ></div>
                                        <span v-if="data?.assigned_to?.name">
                                            {{ data.assigned_to?.name }}
                                            {{ data.assigned_to?.last_name }}
                                        </span>
                                        <span
                                            v-else
                                            class="text-gray-400 italic"
                                        >
                                            {{ __("Not assigned") }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >
                                        {{ __("Assigned By") }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-900 dark:text-white truncate"
                                    >
                                        <span v-if="data?.assigned_by?.name">
                                            {{ data.assigned_by?.name }}
                                            {{ data.assigned_by?.last_name }}
                                        </span>
                                        <span
                                            v-else
                                            class="text-gray-400 italic"
                                        >
                                            {{ __("Not assigned") }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Refund Details -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                >
                    <!-- Refund Reason -->
                    <div
                        class="p-4 sm:p-5 border-b border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="flex items-center justify-between mb-3 sm:mb-4"
                        >
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Refund Details") }}
                            </h3>
                            <div
                                class="p-1.5 sm:p-2 bg-amber-100 dark:bg-amber-900/20 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 dark:text-amber-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.5 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div
                                        class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        {{ __("Reason") }}
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 sm:p-4 border border-gray-200 dark:border-gray-700"
                                    >
                                        <div
                                            class="text-sm text-gray-900 dark:text-white font-medium break-words"
                                        >
                                            {{ data?.reason }}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div
                                        class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        {{ __("Refund Amount") }}
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 sm:p-4 border border-gray-200 dark:border-gray-700"
                                    >
                                        <div
                                            class="text-2xl font-bold text-amber-600 dark:text-amber-400"
                                        >
                                            {{ data?.amount }}
                                            {{ data?.currency }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                                        >
                                            {{ __("From original amount of") }}
                                            {{ data?.transaction?.total }}
                                            {{ data?.currency }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Description") }}
                                </div>
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 sm:p-4 border border-gray-200 dark:border-gray-700 min-h-[100px] sm:min-h-[120px]"
                                >
                                    <div
                                        class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap break-words"
                                    >
                                        {{ data?.description }}
                                    </div>
                                    <div
                                        class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-right"
                                    >
                                        {{
                                            data?.description?.length || 0
                                        }}/1000
                                        {{ __("characters") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files Section -->
                    <div v-if="data?.files?.length" class="p-4 sm:p-5">
                        <h4
                            class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mb-3 sm:mb-4 flex items-center gap-2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 flex-shrink-0"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path
                                    d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828L18 9.828m-4.828-2.828L6 14.172a4 4 0 105.657 5.657L21 10.485"
                                />
                            </svg>
                            <span class="truncate">{{
                                __("Supporting Evidence Documents")
                            }}</span>
                            <span
                                class="text-xs font-normal text-gray-500 dark:text-gray-400 whitespace-nowrap"
                            >
                                ({{ data.files.length }} {{ __("files") }})
                            </span>
                        </h4>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4"
                        >
                            <div
                                v-for="file in data.files"
                                :key="file.id"
                                class="rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden"
                            >
                                <div
                                    v-if="file.mime_type.startsWith('image/')"
                                    class="w-full h-32 sm:h-40 bg-gray-100 dark:bg-gray-800 overflow-hidden"
                                >
                                    <img
                                        :src="file.links.show"
                                        alt="Preview"
                                        class="w-full h-full object-cover"
                                    />
                                </div>

                                <div
                                    v-else
                                    class="w-full h-32 sm:h-40 flex items-center justify-center bg-gray-100 dark:bg-gray-800"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-8 h-8 sm:w-12 sm:h-12 text-gray-500"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <path
                                            d="M16.5 2h-9A2.5 2.5 0 005 4.5v15A2.5 2.5 0 007.5 22h9a2.5 2.5 0 002.5-2.5v-15A2.5 2.5 0 0016.5 2zm-1 14h-7v-2h7v2zm0-4h-7V10h7v2zm0-4h-7V6h7v2z"
                                        />
                                    </svg>
                                </div>

                                <div class="p-3 sm:p-4">
                                    <div
                                        class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate"
                                    >
                                        {{ file.original_name }}
                                    </div>

                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-300 mt-1"
                                    >
                                        {{ file.mime_type.split("/")[1] }} ·
                                        {{ Math.round(file.size / 1024) }}
                                        KB
                                    </div>

                                    <a
                                        :href="file.links.show"
                                        target="_blank"
                                        class="mt-2 sm:mt-3 inline-flex items-center gap-1 text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors truncate"
                                    >
                                        <span class="truncate">{{
                                            __("View Document")
                                        }}</span>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path
                                                d="M13 5h6v6h-2V8.414l-7.293 7.293-1.414-1.414L15.586 7H13V5z"
                                            />
                                            <path
                                                d="M5 5h5V3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5h-2v5H5V5z"
                                            />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Action Form -->
            <div class="lg:col-span-1">
                <!-- Status Update Form -->
                <div class="sticky top-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-5"
                    >
                        <div
                            class="flex items-center justify-between mb-4 sm:mb-5"
                        >
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Update Status") }}
                            </h3>
                            <div
                                class="p-1.5 sm:p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Status locked message -->
                        <div
                            v-if="isStatusLocked"
                            class="mb-4 p-3 sm:p-4 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div class="flex items-start">
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-amber-500 mr-2 mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                                <div>
                                    <div
                                        class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        {{ __("Status Locked") }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                    >
                                        {{
                                            __("This status cannot be modified")
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form
                            v-if="!isStatusLocked"
                            @submit.prevent="submitForm"
                        >
                            <!-- Current Status Display -->
                            <div
                                class="mb-4 p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-lg border border-blue-200 dark:border-blue-800"
                            >
                                <div
                                    class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-1.5"
                                >
                                    {{ __("Current Status") }}
                                </div>
                                <div class="flex items-center justify-between">
                                    <div
                                        :class="`px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs sm:text-sm font-semibold ${getStatusColorClass(
                                            data?.status
                                        )}`"
                                    >
                                        {{ getStatusLabel(data?.status) }}
                                    </div>
                                    <div
                                        class="text-xs text-blue-500 dark:text-blue-400 whitespace-nowrap"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        {{ __("Active") }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status Select -->
                            <div class="mb-4">
                                <label
                                    class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Update Status to") }}
                                    <span class="text-blue-500 ml-1">*</span>
                                </label>
                                <select
                                    v-model="form.status"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-white transition-all duration-200"
                                    :class="{
                                        'border-red-300 dark:border-red-700 focus:ring-red-500 focus:border-red-500':
                                            form.errors.status,
                                    }"
                                >
                                    <option value="" disabled>
                                        {{ __("Select new status...") }}
                                    </option>
                                    <option
                                        v-for="option in availableStatusOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.status"
                                    class="mt-2 flex items-center text-xs text-red-600 dark:text-red-400"
                                >
                                    <svg
                                        class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <span class="truncate">{{
                                        form.errors.status
                                    }}</span>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="space-y-2 sm:space-y-3">
                                <button
                                    type="submit"
                                    :disabled="
                                        form.processing ||
                                        form.status === data?.status ||
                                        !form.status
                                    "
                                    :class="`w-full flex justify-center items-center py-2.5 sm:py-3 px-4 border border-transparent rounded-lg text-xs sm:text-sm font-semibold text-white transition-all duration-200 ${
                                        form.processing
                                            ? 'bg-blue-400 cursor-not-allowed'
                                            : 'bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                                    } ${
                                        form.status === data?.status ||
                                        !form.status
                                            ? 'opacity-50 cursor-not-allowed'
                                            : ''
                                    }`"
                                >
                                    <svg
                                        v-if="form.processing"
                                        class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 sm:h-4 sm:w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="3"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    {{
                                        form.processing
                                            ? __("Updating...")
                                            : __("Update Status")
                                    }}
                                </button>

                                <button
                                    type="button"
                                    @click="resetForm"
                                    class="w-full py-2 sm:py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                                >
                                    {{ __("Reset Form") }}
                                </button>
                            </div>

                            <!-- Form Status -->
                            <div
                                v-if="form.recentlySuccessful"
                                class="mt-3 p-2.5 sm:p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 dark:text-green-400 mr-1.5 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    <span
                                        class="text-xs text-green-700 dark:text-green-300 truncate"
                                    >
                                        {{ __("Status updated successfully!") }}
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import VAccountLayout from "@/components/VAccountLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";

const page = usePage();
const data = ref({});
const loading = ref(true);

// Status workflow definition
const statusWorkflow = {
    pending: [{ value: "processing", label: __("Processing") }],
    processing: [{ value: "under_review", label: __("Under Review") }],
    under_review: [
        { value: "approved", label: __("Approved") },
        { value: "rejected", label: __("Rejected") },
    ],
    approved: [
        { value: "waiting_for_return", label: __("Waiting for Return") },
    ],
    waiting_for_return: [{ value: "refunding", label: __("Refunding") }],
    refunding: [{ value: "completed", label: __("Completed") }],
    completed: [],
    rejected: [],
};

// Timeline order for visual representation
const timelinePoints = [
    { status: "pending" },
    { status: "processing" },
    { status: "under_review" },
    { status: "approved" },
    { status: "waiting_for_return" },
    { status: "refunding" },
    { status: "completed" },
    { status: "rejected" }, // Alternative path
];

// Final statuses that cannot be modified
const finalStatuses = ["completed", "rejected"];

const form = useForm({
    status: "",
});

// Computed properties
const isStatusLocked = computed(() => {
    return finalStatuses.includes(data.value?.status);
});

const availableStatusOptions = computed(() => {
    const currentStatus = data.value?.status;
    if (!currentStatus || !statusWorkflow[currentStatus]) return [];
    return statusWorkflow[currentStatus];
});

onMounted(() => {
    if (page.props.data) {
        data.value = page.props.data;
        form.status = data.value.status;
    }
    loading.value = false;
});

// Methods
const submitForm = () => {
    if (!form.status || form.status === data.value?.status) return;

    form.put(data.value?.web?.update, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.data) {
                data.value = page.props.data;
            }
        },
    });
};

const resetForm = () => {
    form.reset();
    form.status = data.value?.status;
};

const truncateId = (id) => {
    if (!id) return "";
    if (id.length <= 8) return id;
    return id.substring(0, 8) + "...";
};

const getStatusLabel = (status) => {
    if (!status) return "";
    const statusMap = {
        pending: __("Pending"),
        processing: __("Processing"),
        under_review: __("Under Review"),
        approved: __("Approved"),
        waiting_for_return: __("Waiting for Return"),
        refunding: __("Refunding"),
        completed: __("Completed"),
        rejected: __("Rejected"),
    };
    return statusMap[status] || status;
};

// Short version for timeline display on small screens
const getStatusLabelShort = (status) => {
    if (!status) return "";
    const statusMap = {
        pending: __("Pending"),
        processing: __("Processing"),
        under_review: __("Review"),
        approved: __("Approved"),
        waiting_for_return: __("Waiting"),
        refunding: __("Refunding"),
        completed: __("Completed"),
        rejected: __("Rejected"),
    };
    return statusMap[status] || status;
};

const getStatusColorClass = (status) => {
    if (!status)
        return "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300";
    const colors = {
        pending:
            "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300",
        processing:
            "bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300",
        under_review:
            "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300",
        approved:
            "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300",
        waiting_for_return:
            "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300",
        refunding:
            "bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300",
        completed:
            "bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300",
        rejected:
            "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300",
    };
    return (
        colors[status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300"
    );
};

const getTransactionStatusColor = (status) => {
    if (!status)
        return "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300";
    const colors = {
        successful:
            "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300",
        pending:
            "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300",
        failed: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300",
        refunded:
            "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300",
    };
    return (
        colors[status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300"
    );
};

const getTransactionStatusLabel = (status) => {
    if (!status) return "";
    const statusMap = {
        successful: __("Successful"),
        pending: __("Pending"),
        failed: __("Failed"),
        refunded: __("Refunded"),
    };
    return statusMap[status] || status;
};

const getTimelinePointClass = (status) => {
    if (!isStatusActiveOrPassed(status)) {
        return "bg-gray-300 dark:bg-gray-600";
    }

    const colorMap = {
        pending: "bg-amber-500",
        processing: "bg-indigo-500",
        under_review: "bg-blue-500",
        approved: "bg-green-500",
        waiting_for_return: "bg-purple-500",
        refunding: "bg-cyan-500",
        completed: "bg-emerald-500",
        rejected: "bg-red-500",
    };

    return colorMap[status] || "bg-gray-500";
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    try {
        const date = new Date(dateString);
        return (
            date.toLocaleDateString() +
            " " +
            date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
        );
    } catch (error) {
        return dateString;
    }
};

const isStatusPassed = (status) => {
    const statusOrder = timelinePoints.map((s) => s.status);
    const currentIndex = statusOrder.indexOf(data.value?.status);
    const targetIndex = statusOrder.indexOf(status);

    if (currentIndex === -1 || targetIndex === -1) return false;

    // For rejected path
    if (
        status === "rejected" &&
        ["under_review", "processing", "pending"].includes(data.value?.status)
    ) {
        return false;
    }

    return targetIndex < currentIndex;
};

const isStatusActiveOrPassed = (status) => {
    if (status === data.value?.status) return true;
    return isStatusPassed(status);
};
</script>
