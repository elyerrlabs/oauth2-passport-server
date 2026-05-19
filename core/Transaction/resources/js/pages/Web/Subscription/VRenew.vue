<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <div>
        <v-button
            :label="__('Extend expiration date')"
            icon="mdi mdi-account-credit-card-outline"
            @click="dialog = true"
        />
        <v-modal
            :title="__('Extend expiration date')"
            v-model="dialog"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <div
                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4"
                >
                    <div v-for="item in payment_methods" :key="item.key">
                        <button
                            @click="selected_method = item.key"
                            class="w-full p-4 cursor-pointer flex flex-col items-center gap-2 rounded-xl border-2 transition-all"
                            :class="[
                                selected_method === item.key
                                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                    : 'border-gray-200 hover:border-gray-300 dark:border-gray-700',
                            ]"
                        >
                            <i :class="['text-2xl mdi', item.icon]"></i>
                            <span class="text-sm">{{ item.name }}</span>
                        </button>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-4">
                    <v-button
                        :label="
                            loading
                                ? __('Processing...')
                                : __('Continue payment')
                        "
                        variant="success"
                        @click="extendPayment"
                        :disabled="!selected_method || loading"
                        :loading="loading"
                    />
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";

const page = usePage();

const props = defineProps({
    payment_methods: {
        type: Object,
        default: () => {},
    },
    package: {
        type: Object,
        default: () => null,
    },
});

const dialog = ref(false);
const selected_method = ref(null);
const loading = ref(false);

const extendPayment = async () => {
    if (!selected_method.value || loading.value) {
        return;
    }

    loading.value = true;

    try {
        const res = await $server.post(page.props.routes.renew, {
            package: props.package.id,
            billing_period: props.package.billing_period,
            payment_method: selected_method.value,
        });

        if (res.status == 201) {
            window.location.href = res.data.data.redirect_to;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
        console.log(e);
        loading.value = false;
    }
};
</script>
