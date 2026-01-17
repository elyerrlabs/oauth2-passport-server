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
    <div class="w-full">
        <button
            @click="showDialog = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 shadow transition-colors duration-200"
        >
            <i class="mdi mdi-map-marker-outline"></i>
            <span>{{ __("Choose delivery address") }}</span>
        </button>

        <div v-if="selected" class="mt-4">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200"
            >
                <div
                    class="flex items-center justify-between px-4 py-3 border-b border-gray-300 dark:border-gray-600"
                >
                    <h3
                        class="text-sm uppercase font-medium text-blue-600 dark:text-blue-400"
                    >
                        {{ __("Selected Delivery Address") }}
                    </h3>
                    <button
                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
                        @click="showDialog = true"
                    >
                        <i class="mdi mdi-pencil"></i>
                    </button>
                </div>

                <div
                    class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm"
                >
                    <div v-if="selected.full_name">
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("Full name") }}
                        </div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            {{ selected.full_name }}
                        </div>
                    </div>

                    <div v-if="selected.address || selected.address_line_2">
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("Address") }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">
                            {{ selected.address }}
                        </div>
                        <div
                            v-if="selected.address_line_2"
                            class="text-gray-700 dark:text-gray-300"
                        >
                            {{ selected.address_line_2 }}
                        </div>
                    </div>

                    <div
                        v-if="
                            selected.city || selected.state || selected.district
                        "
                    >
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("Location") }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">
                            <span v-if="selected.city">{{
                                selected.city
                            }}</span>
                            <span v-if="selected.state"
                                >, {{ selected.state }}</span
                            >
                            <span v-if="selected.district"
                                >, {{ selected.district }}</span
                            >
                        </div>
                    </div>

                    <div v-if="selected.country || selected.postal_code">
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("Country") }} / {{ __("Postal Code") }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">
                            <span v-if="selected.country">{{
                                selected.country
                            }}</span>
                            <span v-if="selected.postal_code">
                                - {{ selected.postal_code }}</span
                            >
                        </div>
                    </div>

                    <div v-if="selected.phone">
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("Primary phone") }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">
                            {{ selected.phone }}
                        </div>
                    </div>

                    <div v-if="selected.secondary_phone">
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("Secondary phone") }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">
                            {{ selected.secondary_phone }}
                        </div>
                    </div>

                    <div v-if="selected.references">
                        <div
                            class="uppercase text-gray-500 dark:text-gray-400 text-xs mb-1"
                        >
                            {{ __("References") }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">
                            {{ selected.references }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <v-modal
            v-model="showDialog"
            panel-class="w-full xl:w-7xl"
            :title="__('Delivery Addresses')"
        >
            <template #body>
                <div class="p-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Add New Address Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="bg-blue-600 text-white px-4 py-3 rounded-t-xl"
                        >
                            <h4 class="text-base">
                                {{ __("Add New Address") }}
                            </h4>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <v-input
                                    v-model="form.full_name"
                                    :error="errors.full_name"
                                    :label="__('Full Name')"
                                    :placeholder="__('John Doe')"
                                />
                            </div>

                            <div>
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
                                        <span v-else>
                                            {{ __("Select country") }}</span
                                        >
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
                            </div>

                            <div>
                                <v-input
                                    v-model="form.state"
                                    :error="errors.state"
                                    :label="__('State')"
                                    :placeholder="__('Enter your state')"
                                />
                            </div>
                            <div>
                                <v-input
                                    v-model="form.city"
                                    :error="errors.city"
                                    :label="__('City')"
                                    :required="true"
                                    :placeholder="__('Enter your city')"
                                />
                            </div>

                            <div>
                                <v-input
                                    v-model="form.zip"
                                    :error="errors.zip"
                                    :label="__('Zip Code')"
                                    :placeholder="__('Enter your zip code')"
                                />
                            </div>

                            <div>
                                <v-input
                                    v-model="form.district"
                                    :error="errors.district"
                                    :label="__('District')"
                                    :placeholder="__('Enter your district')"
                                />
                            </div>

                            <div>
                                <v-input
                                    v-model="form.address"
                                    :error="errors.address"
                                    :label="__('Address')"
                                    :required="true"
                                    :placeholder="__('Enter your address')"
                                />
                            </div>

                            <div>
                                <v-input
                                    v-model="form.address_line_2"
                                    :error="errors.address_line_2"
                                    :label="__('Address line 2')"
                                    :placeholder="
                                        __('Apt, suite, etc (optional)')
                                    "
                                />
                            </div>

                            <div>
                                <v-input
                                    v-model="form.postal_code"
                                    :error="errors.postal_code"
                                    :label="__('Postal Code')"
                                    :placeholder="__('Enter your postal code')"
                                />
                            </div>

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
                                                    {{
                                                        __("Select code")
                                                    }}</span
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
                                                    {{
                                                        __("Select code")
                                                    }}</span
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
                                            v-model="secondary_phone.number"
                                        />
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
                                @click="saveAddress"
                                class="w-full bg-blue-600 cursor-pointer hover:bg-blue-700 text-white py-3 rounded-lg transition-colors duration-200"
                            >
                                {{
                                    form.id
                                        ? __("Update Address")
                                        : __("Save Address")
                                }}
                            </button>
                        </div>
                    </div>

                    <!-- Your Addresses Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="bg-blue-600 text-white px-4 py-3 rounded-t-xl"
                        >
                            <h4 class="text-base">
                                {{ __("Your Addresses") }}
                            </h4>
                        </div>
                        <div
                            class="p-4 max-h-[60vh] overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <div
                                v-for="address in addresses"
                                :key="address.id"
                                class="py-3 flex justify-between items-start transition-colors duration-200"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-600 dark:border-blue-500':
                                        selected?.id === address.id,
                                }"
                            >
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ address.full_name }}
                                        -
                                        {{ address.country }} -
                                        {{ address.city }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        {{ address.address }}
                                        <span v-if="address.address_line_2"
                                            >,
                                            {{ address.address_line_2 }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center text-sm text-gray-600 dark:text-gray-400 mt-1"
                                    >
                                        <i class="mdi mdi-phone mr-1"></i>
                                        {{ address.phone }}
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-1 ml-4">
                                    <button
                                        class="text-blue-600 dark:text-blue-400 hover:underline text-sm transition-colors"
                                        @click="selectAddress(address)"
                                    >
                                        {{
                                            selected?.id === address.id
                                                ? __("Selected")
                                                : __("Select")
                                        }}
                                    </button>
                                    <button
                                        class="text-gray-600 dark:text-gray-400 hover:underline text-sm transition-colors"
                                        @click="edit(address)"
                                    >
                                        {{ __("Edit") }}
                                    </button>
                                    <button
                                        class="text-red-600 dark:text-red-400 hover:underline text-sm transition-colors"
                                        @click="destroy(address)"
                                    >
                                        {{ __("Remove") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="flex justify-end px-6 py-4 border-t border-gray-200 dark:border-gray-700"
                >
                    <button
                        class="px-4 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                        @click="showDialog = false"
                    >
                        {{ __("Close") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VInput from "@/components/VInput.vue";
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import VError from "@/components/VError.vue";

export default {
    components: {
        VInput,
        VModal,
        VSelect,
        VError,
    },

    data() {
        return {
            showDialog: false,
            addresses: [],
            selected: null,
            errors: {},
            countries: [],
            primary_phone: {},
            secondary_phone: {},
            form: {
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
            },
        };
    },

    created() {
        this.open();
        this.getAddresses();
        this.getCountries();
    },

    methods: {
        open() {
            this.form = {
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
            };
            this.primary_phone = {};
            this.secondary_phone = {};
            this.errors = {};
        },

        selectAddress(item) {
            $notify.success(
                __("Delivery address has been selected successfully")
            );

            this.selected = item;
            this.showDialog = false;
            this.open();
            this.$emit("selected", this.selected.id);
        },

        edit(item) {
            this.form = { ...item };

            // Primary phone
            if (item.phone) {
                const [dial_code, ...numberParts] = item.phone.split(" ");
                this.primary_phone = {
                    dial_code: dial_code || "",
                    number: numberParts.join(" ") || "",
                };
            } else {
                this.primary_phone = { dial_code: "", number: "" };
            }

            // Secondary phone
            if (item.secondary_phone) {
                const [dial_code, ...numberParts] =
                    item.secondary_phone.split(" ");
                this.secondary_phone = {
                    dial_code: dial_code || "",
                    number: numberParts.join(" ") || "",
                };
            } else {
                this.secondary_phone = { dial_code: "", number: "" };
            }

            this.errors = {};
        },

        async destroy(item) {
            const confirm = await this.$swal.fire({
                title: __("Confirm Removal"),
                text: __("Are you sure you want to remove this address?"),
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonText: __("Yes, remove it!"),
                cancelButtonText: __("Cancel"),
            });

            if (confirm) {
                try {
                    const res = await this.$server.delete(item.links.delete);
                    if (res.status == 200) {
                        this.getAddresses();

                        $notify.success(
                            __("Delivery address removed successfully")
                        );

                        // If the removed address was selected, clear selection
                        if (this.selected?.id === item.id) {
                            this.selected = null;
                        }
                    }
                } catch (e) {
                    if (e?.response?.data?.message) {
                        $notify.success(e?.response?.data?.message);
                    }
                }
            }
        },

        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: {
                        order_by: "name_en",
                        order_type: "asc",
                    },
                });
                if (res.status === 200) {
                    this.countries = res.data;
                    this.dial_codes = res.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getAddresses() {
            try {
                const res = await this.$server.get(
                    this.$page.props.delivery_address.route,
                    {
                        params: {
                            per_page: 50,
                        },
                    }
                );

                if (res.status == 200) {
                    this.addresses = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$swal.fire({
                        icon: "error",
                        title: __("Error"),
                        text: e.response.data.message,
                    });
                }
            }
        },

        async saveAddress() {
            this.form.phone =
                this.primary_phone?.dial_code && this.primary_phone?.number
                    ? `${this.primary_phone.dial_code} ${this.primary_phone.number}`
                    : null;

            this.form.secondary_phone =
                this.secondary_phone?.dial_code && this.secondary_phone?.number
                    ? `${this.secondary_phone.dial_code} ${this.secondary_phone.number}`
                    : null;

            try {
                const res = await this.$server.post(
                    this.$page.props.delivery_address.route,
                    this.form
                );

                if (res.status === 201 || res.status === 200) {
                    this.getAddresses();
                    this.$swal.fire({
                        icon: "success",
                        title: __("Success"),
                        text: this.form.id
                            ? __("Delivery address updated successfully")
                            : __("Delivery address added successfully"),
                        color: "positive",
                        position: "top-right",
                        icon: "mdi-check-circle",
                    });

                    this.open();
                    this.errors = {};
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$swal.fire({
                        icon: "error",
                        title: __("Error"),
                        text: e.response.data.message,
                    });
                }

                if (e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
