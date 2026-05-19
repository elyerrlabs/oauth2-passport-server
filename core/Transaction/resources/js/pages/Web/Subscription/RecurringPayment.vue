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
    <v-button
        @click="dialog = true"
        :title="buttonLabel"
        :label="buttonLabel"
        icon="mdi mdi-reload"
        :variant="item?.is_recurring ? 'danger' : 'success'"
    />

    <v-modal
        v-model="dialog"
        :title="__(dialogTitle)"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <!-- Content -->
            <div class="p-6">
                <p class="text-gray-700 dark:text-white">
                    {{ __(dialogMessage) }}
                </p>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-between p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700 rounded-b-2xl"
            >
                <v-button
                    @click="dialog = false"
                    :label="__('Cancel')"
                    variant="secondary"
                />
                <v-button @click="recurringPayment" :label="__('Confirm')" />
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const emits = defineEmits(["success"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
        default: () => ({}),
    },
});

const form = useForm({});

const dialog = ref(false);

const buttonLabel = computed(() => {
    return props.item?.is_recurring
        ? "Cancel recurring payment"
        : "Enable recurring payment";
});

const dialogTitle = computed(() => {
    return props.item?.is_recurring
        ? "Cancel recurring payment"
        : "Enable recurring payment";
});

const dialogMessage = computed(() => {
    return props.item?.is_recurring
        ? "Are you sure you want to cancel the recurring payment?"
        : "Do you want to enable recurring payment for this item?";
});

const recurringPayment = async () => {
    form.put(props.item.links.recurring, {
        preserveState: true,
        onSuccess: () => {
            emits("success");
        },
        onError: (errors) => {
            if (errors?.message) {
                $notify.error(errors.message);
            }
        },
        onFinish: () => {
            dialog.value = false;
        },
    });
};
</script>
