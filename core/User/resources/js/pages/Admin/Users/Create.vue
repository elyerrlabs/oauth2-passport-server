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
    <!-- Create/Edit Button -->
    <button
        v-if="item?.id"
        @click="open"
        class="relative group w-12 h-12 gap-2 border border-blue-600 dark:border-blue-400 px-4 py-2 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
        :title="__('Edit user')"
    >
        <i class="mdi mdi-pencil text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-blue-600 dark:bg-blue-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Edit User") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-blue-600 dark:border-t-blue-500"
            ></div>
        </div>
    </button>

    <button
        v-else
        @click="open"
        class="flex items-center gap-2 px-4 py-2 border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
    >
        <i class="mdi mdi-plus-circle"></i>
        {{ __("New user") }}
    </button>

    <v-modal
        v-model="dialog"
        :title="item?.id ? __('Update User') : __('Create New User')"
        panel-class="w-full lg:w-6xl"
    >
        <template #body>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <v-input
                    v-model="form.name"
                    :label="__('Name')"
                    :error="form.errors.name"
                    :required="true"
                    :placeholder="__('Enter user name')"
                />

                <v-input
                    v-model="form.last_name"
                    :label="__('Last Name')"
                    :error="form.errors.last_name"
                    :required="true"
                    :placeholder="__('Enter last name')"
                />

                <v-input
                    :label="__('Email')"
                    v-model="form.email"
                    :error="form.errors.email"
                    :required="true"
                    :placeholder="__('Enter email address')"
                    :disabled="item?.id"
                />

                <v-select
                    :label="__('Country')"
                    v-model="form.country"
                    :error="form.errors.country"
                    :options="countries"
                    label-key="name_en"
                    value-key="name_en"
                    searchable
                >
                    <template #selected="{ option }">
                        <span class="text-gray-700 p-2 dark:text-gray-200">
                            {{
                                option
                                    ? `${option.emoji} - ${option.name_en}`
                                    : __("Select country")
                            }}
                        </span>
                    </template>
                    <template #option="{ option }">
                        <span
                            class="text-gray-700 p-2 dark:text-gray-200 block"
                        >
                            {{ option.emoji }} - {{ option.name_en }}
                        </span>
                    </template>
                </v-select>

                <v-input
                    :label="__('City')"
                    v-model="form.city"
                    :error="form.errors.city"
                    :placeholder="__('Enter city name')"
                />

                <v-select
                    :label="__('Dial Code')"
                    v-model="form.dial_code"
                    :options="dial_codes"
                    :error="form.errors.dial_code"
                    label-key="name_en"
                    value-key="dial_code"
                    searchable
                >
                    <template #selected="{ option }">
                        <span class="text-gray-700 p-2 dark:text-gray-200">
                            {{
                                option
                                    ? `${option.emoji} - ${option.name_en} ${option.dial_code}`
                                    : __("Select dial code")
                            }}
                        </span>
                    </template>
                    <template #option="{ option }">
                        <span
                            class="text-gray-700 p-2 dark:text-gray-200 block"
                        >
                            {{ option.emoji }} - {{ option.name_en }} -
                            {{ option.dial_code }}
                        </span>
                    </template>
                </v-select>

                <v-input
                    :label="__('Phone Number')"
                    v-model="form.phone"
                    :error="form.errors.phone"
                    :placeholder="__('Enter phone number')"
                />

                <v-switch
                    v-model="form.email_verified_at"
                    :label="__('Mark email as verified')"
                    :help-text="
                        __('User will not need to verify their email address')
                    "
                />

                <div>
                    <label
                        class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        <i
                            class="mdi mdi-cake-variant text-gray-500 dark:text-gray-400"
                        ></i>
                        {{ __("Birthday") }}
                    </label>
                    <VueDatePicker
                        v-model="form.birthday"
                        :enable-time-picker="false"
                        :max-date="new Date()"
                        format="yyyy-MM-dd"
                        model-type="format"
                        :placeholder="__('Select birthday')"
                        :dark="is_dark"
                        auto-apply
                        class="w-full"
                    />
                    <v-error :error="form.errors.birthday" class="mt-1" />
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <button
                    @click="close"
                    class="flex items-center gap-2 px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600 transition-colors duration-200"
                >
                    <i class="mdi mdi-close-circle"></i>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="createOrUpdate"
                    :disabled="form.processing"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                        form.processing
                            ? 'bg-blue-400 dark:bg-blue-600 cursor-not-allowed'
                            : 'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-blue-200 dark:focus:ring-blue-800',
                    ]"
                >
                    <i
                        v-if="form.processing"
                        class="mdi mdi-loading animate-spin"
                    ></i>
                    <i v-else class="mdi mdi-account-plus"></i>
                    <span>{{
                        form.processing
                            ? __(item?.id ? "Updating..." : "Creating...")
                            : item?.id
                            ? __("Update User")
                            : __("Create User")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue";
import VError from "@/components/VError.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const is_dark = ref(false);

// Emits
const emits = defineEmits(["created", "updated"]);
const props = defineProps({
    item: {
        type: Object,
        default: null,
    },
});

const page = usePage();

// State
const dialog = ref(false);
const form = useForm({
    name: null,
    last_name: null,
    email: null,
    country: null,
    dial_code: null,
    phone: null,
    birthday: null,
    email_verified_at: false,
});

const countries = ref([]);
const dial_codes = ref([]);

onMounted(() => {
    isDark();
    window.addEventListener("theme-change", isDark);
});

const isDark = () => {
    is_dark.value = localStorage.getItem("theme") == "light" ? false : true;
};

// Methods
const close = () => {
    form.reset();
    dialog.value = false;
};

const open = async () => {
    form.reset();

    if (props.item?.id) {
        form.name = props.item.name;
        form.last_name = props.item.last_name;
        form.email = props.item.email;
        form.country = props.item.country;
        form.dial_code = props.item.dial_code;
        form.phone = props.item.phone;
        form.birthday = props.item.birthday;
        form.email_verified_at = props.item.email_verified_at !== null;
    }

    dialog.value = true;
    await getCountries();
};

const createOrUpdate = () => {
    if (props.item?.id) {
        form.put(props.item.links.update, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                $notify.success(__("User updated successfully"));
                emits("updated");
                close();
            },
            onError: () => {
                $notify.error(__("Failed to update user"));
            },
        });
    } else {
        form.post(page.props.route, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                $notify.success(__("User created successfully"));
                emits("created");
                close();
            },
            onError: () => {
                $notify.error(__("Failed to create user"));
            },
        });
    }
};

const getCountries = async () => {
    try {
        const res = await $server.get("/api/public/countries", {
            params: { order_by: "name_en", order_type: "asc" },
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
</script>
 