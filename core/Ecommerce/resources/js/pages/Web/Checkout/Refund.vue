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
    <div>
        <button
            @click="toggle"
            class="px-6 py-3 bg-linear-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 border border-red-400 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer flex items-center group transform hover:scale-105 active:scale-95"
        >
            <i
                class="fas fa-undo-alt mr-3 group-hover:rotate-180 transition-transform duration-300"
            ></i>
            {{ __("Request Refund") }}
        </button>
        <v-modal
            :title="__('Request Refund')"
            v-model="dialog"
            panel-class="w-full lg:w-7xl"
        >
            <template #body>
                <!-- Informative Header -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6"
                >
                    <div class="flex items-start">
                        <i
                            class="fas fa-info-circle text-blue-500 mt-1 mr-3"
                        ></i>
                        <div>
                            <h3
                                class="font-semibold text-blue-800 dark:text-blue-300 text-sm mb-1"
                            >
                                {{ __("Refund Request Information") }}
                            </h3>
                            <p class="text-blue-700 dark:text-blue-400 text-xs">
                                {{
                                    __(
                                        "Please provide detailed information about your refund request. Clear and complete information helps us process your request faster. Ensure all supporting evidence is attached to avoid delays."
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-4">
                    <v-input
                        :label="__('Reason for Refund')"
                        v-model="form.reason"
                        :required="true"
                        :error="errors.reason"
                        :helper="
                            __(
                                'Select the main reason for your refund request (e.g., duplicate charge, service not provided, quality issues, damaged product, wrong item received)'
                            )
                        "
                    />

                    <div>
                        <v-input
                            :label="
                                __('Amount to request :currency', {
                                    ':currency': item?.transaction?.currency,
                                })
                            "
                            v-model="form.amount"
                            :required="true"
                            type="money"
                            :error="errors.amount"
                            :helper="
                                __(
                                    'You can request a partial or full refund. Enter the specific amount you wish to be refunded. If the entire transaction is problematic, request the full amount. For partial issues, specify the exact amount affected.'
                                )
                            "
                        />
                        <p
                            class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                        >
                            {{
                                __("Available balance: :amount :currency", {
                                    ":amount": formatMoney(
                                        item?.transaction?.cents
                                    ),
                                    ":currency": item?.transaction?.currency,
                                })
                            }}
                        </p>
                    </div>

                    <v-input
                        :label="__('Transaction Code')"
                        v-model="form.transaction_code"
                        required="true"
                        :disabled="true"
                        :error="errors.transaction_code"
                    />
                </div>

                <div class="mb-4">
                    <v-textarea
                        :label="__('Detailed Description')"
                        v-model="form.description"
                        :error="errors.description"
                        :helper="
                            __(
                                'Provide a detailed explanation of why you are requesting a refund. Include specific dates, service details, product information, and any relevant circumstances. The more specific you are, the faster we can assist you. Describe what went wrong and how it affected you.'
                            )
                        "
                        :rows="4"
                    />
                </div>

                <div class="mb-6">
                    <div class="mb-3">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            {{ __("Supporting Evidence") }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3 mb-3"
                        >
                            <div class="flex items-start">
                                <i
                                    class="fas fa-lightbulb text-yellow-500 mt-0.5 mr-2"
                                ></i>
                                <p
                                    class="text-yellow-700 dark:text-yellow-400 text-xs"
                                >
                                    {{
                                        __(
                                            "Tip: Upload clear photos, screenshots, or documents that support your claim. Useful evidence includes: receipt photos, bank statements, communication screenshots, product defect photos, service issue documentation, or video evidence of the problem."
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <v-upload-file
                        v-model="form.evidence"
                        :error="errors.evidence"
                    />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        {{
                            __(
                                "Supported formats: JPG, PNG, PDF, MP4, AVI, MOV files"
                            )
                        }}
                    </p>
                </div>

                <!-- Processing Time Info -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-3 mb-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-500 mr-2"></i>
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{
                                    __(
                                        "Typical processing time: 3-5 business days"
                                    )
                                }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-headset text-gray-500 mr-2"></i>
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ __("Need help? Contact our support team") }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Refund Policy Notice -->
                <div
                    class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-3 mb-6"
                >
                    <div class="flex items-start">
                        <i
                            class="fas fa-shield-alt text-purple-500 mt-0.5 mr-3"
                        ></i>
                        <div>
                            <p
                                class="text-purple-700 dark:text-purple-400 text-xs"
                            >
                                {{
                                    __(
                                        "By submitting this refund request, you acknowledge that all information provided is accurate and truthful. Refunds are subject to our terms of service and may require additional verification. Unauthorized or fraudulent claims may be rejected and could result in account suspension."
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-4 flex items-center justify-around gap-4">
                    <button
                        @click="requestRefund"
                        :disabled="loading"
                        class="px-8 py-3 bg-linear-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 group flex-1 max-w-xs transform hover:scale-105 active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <template v-if="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            {{ __("Processing Your Request...") }}
                        </template>

                        <template v-else>
                            <i
                                class="fas fa-check-circle group-hover:scale-110 transition-transform duration-300"
                            ></i>
                            {{ __("Submit Refund Request") }}
                        </template>
                    </button>

                    <button
                        @click="toggle"
                        class="px-8 py-3 bg-linear-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 group flex-1 max-w-xs transform hover:scale-105 active:scale-95"
                    >
                        <i
                            class="fas fa-times-circle group-hover:scale-110 transition-transform duration-300"
                        ></i>
                        {{ __("Cancel Request") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VUploadFile from "@Ecommerce/components/VFileUploader.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const emits = defineEmits(["created"]);

const loading = ref(false);
const dialog = ref(false);

const form = ref({
    reason: "",
    description: "",
    amount: "",
    transaction_code: "",
    evidence: [],
});

const errors = ref({});

const formatMoney = (cents) => {
    if (!cents) return "0.00";
    return (cents / 100).toFixed(2);
};

const toggle = () => {
    form.value.reason = "";
    form.value.description = "";
    form.value.amount = props?.item?.transaction?.cents || "";
    errors.value = {};
    dialog.value = !dialog.value;
};

onMounted(() => {
    form.value.transaction_code = props?.item?.transaction_code;
    form.value.amount = props?.item?.transaction?.cents || "";
});

const requestRefund = async () => {
    if (loading.value) return;

    loading.value = true;
    errors.value = {};

    const formData = new FormData();

    formData.append("reason", form.value.reason);
    formData.append("description", form.value.description);
    formData.append("amount", form.value.amount);
    formData.append("transaction_code", form.value.transaction_code);

    if (form.value.evidence && form.value.evidence.length > 0) {
        form.value.evidence.forEach((file) => {
            formData.append("evidence[]", file);
        });
    }

    try {
        const res = await $server.post(
            "/api/transaction/users/refunds",
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        if (res.status == 201) {
            $notify.success(__("Refund request submitted successfully!"));
            toggle();
            emits("created");
        }
    } catch (e) {
        if (e?.response?.status == 422) {
            errors.value = e.response.data.errors;
        }
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>
