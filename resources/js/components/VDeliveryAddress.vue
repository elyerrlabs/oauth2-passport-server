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
    <div class="w-full">
        <button
            @click="showDialog = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 shadow hover:bg-blue-700 transition"
        >
            <i class="mdi mdi-map-marker-outline"></i>
            <span>{{ __("Choose delivery address") }}</span>
        </button>

        <div v-if="selected" class="mt-4">
            <div
                class="bg-white rounded-xl border border-gray-300 shadow-sm hover:shadow-md transition"
            >
                <div
                    class="flex items-center justify-between px-4 py-3 border-b border-b-gray-300"
                >
                    <h3 class="text-sm uppercase font-medium text-blue-600">
                        {{ __("Selected Delivery Address") }}
                    </h3>
                    <button
                        class="text-blue-600 hover:text-blue-800"
                        @click="showDialog = true"
                    >
                        <i class="mdi mdi-pencil"></i>
                    </button>
                </div>

                <div
                    class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm"
                >
                    <div v-if="selected.full_name">
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("Full name") }}
                        </div>
                        <div class="font-medium">{{ selected.full_name }}</div>
                    </div>

                    <div v-if="selected.address || selected.address_line_2">
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("Address") }}
                        </div>
                        <div>{{ selected.address }}</div>
                        <div v-if="selected.address_line_2">
                            {{ selected.address_line_2 }}
                        </div>
                    </div>

                    <div
                        v-if="
                            selected.city || selected.state || selected.district
                        "
                    >
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("Location") }}
                        </div>
                        <div>
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
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("Country") }} / {{ __("Postal Code") }}
                        </div>
                        <div>
                            <span v-if="selected.country">{{
                                selected.country
                            }}</span>
                            <span v-if="selected.postal_code">
                                - {{ selected.postal_code }}</span
                            >
                        </div>
                    </div>

                    <div v-if="selected.phone">
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("Primary phone") }}
                        </div>
                        <div>{{ selected.phone }}</div>
                    </div>

                    <div v-if="selected.secondary_phone">
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("Secondary phone") }}
                        </div>
                        <div>{{ selected.secondary_phone }}</div>
                    </div>

                    <div v-if="selected.references">
                        <div class="uppercase text-gray-500 text-xs mb-1">
                            {{ __("References") }}
                        </div>
                        <div>{{ selected.references }}</div>
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
                <div class="p-1 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="bg-white rounded-xl shadow-sm">
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
                                        <span class="text-gray-700 p-4">
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
                                    :required="true"
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
                                <span class="text-gray-700">
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
                                                <span class="text-gray-700 p-4">
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
                                <span class="text-gray-700">
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
                                                <span class="text-gray-700 p-4">
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
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ __("References") }}
                                </label>
                                <textarea
                                    v-model="form.references"
                                    :error="errors.references"
                                    type="text"
                                    class="mt-1 py-2 px-3 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                ></textarea>
                            </div>
                        </div>
                        <div class="px-4 py-3">
                            <button
                                @click="saveAddress"
                                class="w-full bg-blue-600 cursor-pointer hover:bg-blue-700 text-white py-3 rounded-lg"
                            >
                                {{
                                    form.id
                                        ? __("Update Address")
                                        : __("Save Address")
                                }}
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <div
                            class="bg-blue-600 text-white px-4 py-3 rounded-t-xl"
                        >
                            <h4 class="text-base">
                                {{ __("Your Addresses") }}
                            </h4>
                        </div>
                        <div class="p-4 max-h-[60vh] overflow-y-auto divide-y">
                            <div
                                v-for="address in addresses"
                                :key="address.id"
                                class="py-3 flex justify-between items-start"
                                :class="{
                                    'bg-blue-50 border-l-4 border-blue-600':
                                        selected?.id === address.id,
                                }"
                            >
                                <div>
                                    <div class="font-medium">
                                        {{ address.full_name }}
                                        -
                                        {{ address.country }} -
                                        {{ address.city }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ address.address }}
                                        <span v-if="address.address_line_2"
                                            >,
                                            {{ address.address_line_2 }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center text-sm text-gray-600 mt-1"
                                    >
                                        <i class="mdi mdi-phone mr-1"></i>
                                        {{ address.phone }}
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <button
                                        class="text-blue-600 hover:underline text-sm"
                                        @click="selectAddress(address)"
                                    >
                                        {{
                                            selected?.id === address.id
                                                ? __("Selected")
                                                : __("Select")
                                        }}
                                    </button>
                                    <button
                                        class="text-gray-600 hover:underline text-sm"
                                        @click="edit(address)"
                                    >
                                        {{ __("Edit") }}
                                    </button>
                                    <button
                                        class="text-red-600 hover:underline text-sm"
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
                <div class="flex justify-end px-6 py-4">
                    <button
                        class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100"
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
import VCountry from "@/components/VCountry.vue";
import VPhone from "@/components/VPhone.vue";
import VInput from "@/components/VInput.vue";
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import VError from "@/components/VError.vue";

export default {
    components: {
        VCountry,
        VPhone,
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
            this.$swal.fire({
                icon: "success",
                title: __("Success"),
                text: __("Delivery address has been selected successfully"),
            });
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
                        this.$swal.fire({
                            icon: "success",
                            title: __("Success"),
                            text: __("Delivery address removed successfully"),
                        });

                        // If the removed address was selected, clear selection
                        if (this.selected?.id === item.id) {
                            this.selected = null;
                        }
                    }
                } catch (e) {
                    if (e?.response?.data?.message) {
                        this.$swal.fire({
                            icon: "error",
                            title: __("Error"),
                            text: __("Delivery address removal failed"),
                        });
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
