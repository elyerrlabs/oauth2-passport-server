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
        <button
            v-if="item?.id"
            @click="open"
            class="p-1 rounded-full text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200"
        >
            <span class="mdi mdi-pencil text-xl"></span>
        </button>

        <button
            v-else
            @click="open"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 shadow transition-colors duration-200"
        >
            <i class="mdi mdi-map-marker-outline"></i>
            <span>{{ __("Add new address") }}</span>
        </button>

        <v-modal
            v-model="dialog"
            panel-class="w-full lg:w-4xl"
            :title="__('Delivery Addresses')"
        >
            <template #body>
                <div class="grid row-cols-1 md:row-cols-2">
                    <v-input
                        v-model="form.full_name"
                        :error="errors.full_name"
                        :label="__('Full Name')"
                        :placeholder="__('John Doe')"
                        required
                    />

                    <v-select
                        :label="__('Country')"
                        v-model="form.country"
                        :error="errors.country"
                        :options="countries"
                        :required="true"
                        label-key="name_en"
                        value-key="name_en"
                        searchable
                    >
                        <template #selected="{ option }">
                            <span v-if="option">
                                {{ option.emoji }} -
                                {{ option.name_en }}
                            </span>
                            <span v-else> {{ __("Select country") }}</span>
                        </template>

                        <template #option="{ option }">
                            <span
                                class="text-gray-700 block dark:text-gray-300 p-4"
                            >
                                {{ option.emoji }} -
                                {{ option.name_en }}
                            </span>
                        </template>
                    </v-select>

                    <v-input
                        v-model="form.state"
                        :error="errors.state"
                        :label="__('State')"
                        :placeholder="__('Enter your state')"
                        :required="true"
                    />

                    <v-input
                        v-model="form.city"
                        :error="errors.city"
                        :label="__('City')"
                        :required="true"
                        :placeholder="__('Enter your city')"
                    />

                    <v-input
                        v-model="form.district"
                        :error="errors.district"
                        :label="__('District')"
                        :required="true"
                        :placeholder="__('Enter your district')"
                    />

                    <v-input
                        v-model="form.address"
                        :error="errors.address"
                        :label="__('Address')"
                        :required="true"
                        :placeholder="__('Enter your address')"
                    />

                    <v-input
                        v-model="form.address_line_2"
                        :error="errors.address_line_2"
                        :label="__('Address line 2')"
                        :placeholder="__('Apt, suite, etc (optional)')"
                    />

                    <v-input
                        v-model="form.postal_code"
                        :error="errors.postal_code"
                        :required="true"
                        :label="__('Postal Code')"
                        :placeholder="__('Enter your postal code')"
                    />

                    <div>
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ __("Primary phone") }}
                            <i class="text-red-500">*</i>
                        </span>
                        <div class="flex gap-2">
                            <div class="w-60">
                                <v-select
                                    v-model="primary_phone.dial_code"
                                    :options="countries"
                                    :required="true"
                                    label-key="name_en"
                                    value-key="dial_code"
                                    searchable
                                >
                                    <template #selected="{ option }">
                                        <span v-if="option">
                                            {{ option.emoji }}
                                            {{ option.dial_code }}
                                        </span>
                                        <span v-else>
                                            {{ __("Select code") }}</span
                                        >
                                    </template>

                                    <template #option="{ option }">
                                        <span
                                            class="text-gray-700 block dark:text-gray-300 p-2"
                                        >
                                            {{ option.emoji }} -
                                            {{ option.name_en }}
                                        </span>
                                    </template>
                                </v-select>
                            </div>
                            <div class="flex-1">
                                <v-input
                                    v-model="primary_phone.number"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <v-error :error="errors.phone" />
                    </div>

                    <div>
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ __("Secondary phone") }}
                        </span>
                        <div class="flex gap-2">
                            <div class="w-60">
                                <v-select
                                    v-model="secondary_phone.dial_code"
                                    :options="countries"
                                    label-key="name_en"
                                    value-key="dial_code"
                                    searchable
                                >
                                    <template #selected="{ option }">
                                        <span v-if="option">
                                            {{ option.emoji }}

                                            {{ option.dial_code }}
                                        </span>
                                        <span v-else>
                                            {{ __("Select code") }}</span
                                        >
                                    </template>

                                    <template #option="{ option }">
                                        <span
                                            class="text-gray-700 block dark:text-gray-300 p-2"
                                        >
                                            {{ option.emoji }} -
                                            {{ option.name_en }}
                                        </span>
                                    </template>
                                </v-select>
                            </div>
                            <div class="flex-1">
                                <v-input v-model="secondary_phone.number" />
                            </div>
                        </div>
                        <v-error :error="errors.secondary_phone" />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ __("References") }}
                        </label>
                        <textarea
                            v-model="form.references"
                            :error="errors.references"
                            type="text"
                            class="mt-1 py-2 px-3 block w-full rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white"
                        ></textarea>
                    </div>
                </div>
                <div class="px-4 py-3">
                    <button
                        @click="handle"
                        class="w-full bg-blue-600 cursor-pointer hover:bg-blue-700 text-white py-3 rounded-lg transition-colors duration-200"
                    >
                        {{
                            form.id ? __("Update Address") : __("Save Address")
                        }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VInput from "@/components/VInput.vue";
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import VError from "@/components/VError.vue";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    item: {
        type: Object,
        default: () => {},
    },
});

const emits = defineEmits(["created", "updated"]);
const page = usePage();
const dialog = ref(false);
const errors = ref({});
const countries = ref([]);
const dial_codes = ref([]);
const primary_phone = ref({
    dial_code: "",
    number: "",
});
const secondary_phone = ref({
    dial_code: "",
    number: "",
});
const form = ref({
    id: "",
    full_name: "",
    country: "",
    state: "",
    city: "",
    district: "",
    address: "",
    address_line_2: "",
    postal_code: "",
    phone: "",
    secondary_phone: "",
    references: "",
});

const open = async () => {
    edit();
    dialog.value = true;
    errors.value = {};
    await getCountries();
};

const getCountries = async () => {
    try {
        const res = await $server.get(page.props.api.countries, {
            params: {
                order_by: "name_en",
                order_type: "asc",
            },
        });
        if (res.status === 200) {
            countries.value = res.data;
            dial_codes.value = res.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const edit = () => {
    form.value = { ...props.item };

    // Primary phone
    if (props.item?.phone) {
        const [dial_code, ...numberParts] = props.item.phone.split(" ");
        primary_phone.value.dial_code = dial_code || "";
        primary_phone.value.number = numberParts.join(" ") || "";
    } else {
        primary_phone.value.dial_code = "";
        primary_phone.value.dial_code = "";
    }

    // Secondary phone
    if (props.item?.secondary_phone) {
        const [dial_code, ...numberParts] =
            props.item.secondary_phone.split(" ");
        secondary_phone.value.dial_code = dial_code || "";
        secondary_phone.value.number = numberParts.join(" ") || "";
    } else {
        secondary_phone.value.dial_code = "";
        secondary_phone.value.number = "";
    }

    errors.value = {};
};

const handle = async () => {
    form.value.phone =
        primary_phone.value.dial_code && primary_phone.value.number
            ? `${primary_phone.value.dial_code} ${primary_phone.value.number}`
            : null;

    form.value.secondary_phone =
        secondary_phone.value?.dial_code && secondary_phone.value.number
            ? `${secondary_phone.value.dial_code} ${secondary_phone.value.number}`
            : null;

    if (props.item?.id) {
        await update();
    } else {
        await saveAddress();
    }
};

const saveAddress = async () => {
    try {
        const res = await $server.post(page.props.api.address, form.value);

        if (res.status === 201) {
            $notify.success(__("Delivery address added successfully"));
            emits("created");
            open();
            errors.value = {};
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }

        if (e.response.status == 422) {
            errors.value = e.response.data.errors;
        }
    }
};

const update = async () => {
    try {
        const res = await $server.put(props.item.links.update, form.value);

        if (res.status === 200) {
            $notify.success(__("Delivery address updated successfully"));
            emits("updated");
            open();
            errors.value = {};
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }

        if (e.response.status == 422) {
            errors.value = e.response.data.errors;
        }
    }
};
</script>
