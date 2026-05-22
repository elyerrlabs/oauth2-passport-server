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
    <v-main-layout>
        <v-head :title="data?.id ? __('Update user') : __('Create new user')">
            <template #actions>
                <v-button
                    as="a"
                    :to="page.props.routes.users"
                    :label="__('Back to the users')"
                    variant="secondary"
                />
            </template>
        </v-head>
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
                :disabled="data?.id"
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
                    <span class="text-gray-700 p-2 dark:text-gray-200 block">
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
                    <span class="text-gray-700 p-2 dark:text-gray-200 block">
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

            <v-input
                :label="__('Birthday')"
                v-model="form.birthday"
                type="date"
                :error="form.errors.birthday"
            />
        </div>

        <div
            v-if="data?.id"
            class="flex justify-between text-xs text-gray-500 dark:text-gray-400"
        >
            <span
                v-if="form?.verified"
                class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full"
            >
                <i class="mdi mdi-check-circle text-xs"></i>
                {{ __("Verified") }}: {{ form.verified }}
            </span>
            <span class="text-gray-300 dark:text-gray-600">•</span>

            <span v-if="form?.created" class="inline-flex items-center gap-1">
                <i class="mdi mdi-calendar text-xs"></i>
                {{ __("Created") }}: {{ form?.created }}
            </span>
            <span class="text-gray-300 dark:text-gray-600">•</span>
            <span v-if="form?.updated" class="inline-flex items-center gap-1">
                <i class="mdi mdi-update text-xs"></i>
                {{ __("Updated") }}: {{ form?.updated }}
            </span>
        </div>

        <!-- Actions -->
        <div
            class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700"
        >
            <button
                @click="handled"
                :disabled="loading"
                :class="[
                    'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                    loading
                        ? 'bg-blue-400 dark:bg-blue-600 cursor-not-allowed'
                        : 'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-blue-200 dark:focus:ring-blue-800',
                ]"
            >
                <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                <i v-else class="mdi mdi-account-plus"></i>
                <span>{{
                    loading
                        ? __(data?.id ? "Updating..." : "Creating...")
                        : data?.id
                          ? __("Update User")
                          : __("Create User")
                }}</span>
            </button>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VSelect from "@/components/VSelect.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue"; 
import VButton from "@/components/VButton.vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VHead from "@/components/VHead.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const is_dark = ref(false);

// Emits
const emits = defineEmits(["created", "updated"]);

const props = defineProps({
    data: {
        type: Object,
        default: () => {},
    },
});

const page = usePage();

// State
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

const loading = ref(false);

const countries = ref([]);
const dial_codes = ref([]);

onMounted(async () => {
    
    isDark();
    window.addEventListener("theme-change", isDark);

    if (props.data?.id) {
        form.name = props.data.name;
        form.last_name = props.data.last_name;
        form.email = props.data.email;
        form.country = props.data.country;
        form.dial_code = props.data.dial_code;
        form.phone = props.data.phone;
        form.birthday = props.data.birthday;
        form.email_verified_at = props.data.email_verified_at;
    }

    await getCountries();
});

const isDark = () => {
    is_dark.value = localStorage.getItem("theme") == "light" ? false : true;
};

const createUser = () => {
    loading.value = true;

    form.post(page.props.routes.users, {
        preserveScroll: true,
        preserveState: true,
        forceFormData: true,
        onSuccess: (res) => {
            $notify.success(__("User created successfully"));
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const updateUser = () => {
    loading.value = true;

    form.put(props.data.links.update, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            $notify.success(__("User updated successfully"));
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const handled = () => {
    if (props.data?.id) {
        updateUser();
    } else {
        createUser();
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
