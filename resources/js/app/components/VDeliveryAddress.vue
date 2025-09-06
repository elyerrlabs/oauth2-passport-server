<template>
    <div class="address-selector-container">
        <!-- Select Address Button -->
        <q-btn
            color="primary"
            icon="mdi-map-marker-outline"
            :label="__('Choose delivery address')"
            @click="show_dialog = true"
            class="select-address-btn q-mb-md"
            unelevated
            rounded
        />

        <!-- Selected Address Display -->
        <div v-if="selected" class="selected-address-wrapper">
            <q-card class="selected-address-card" flat bordered>
                <div class="row items-center q-pa-md">
                    <div class="col-grow">
                        <div
                            class="text-subtitle2 text-uppercase text-weight-medium selected-label"
                        >
                            {{ __("Selected Delivery Address") }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <q-btn
                            flat
                            round
                            dense
                            icon="mdi-pencil"
                            color="primary"
                            @click="show_dialog = true"
                            size="sm"
                        />
                    </div>
                </div>

                <q-separator />

                <div class="q-pa-md address-details">
                    <div class="row q-col-gutter-md">
                        <div
                            v-if="selected.full_name"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("Full name") }}
                            </div>
                            <div
                                class="text-body1 text-weight-medium detail-value"
                            >
                                {{ selected.full_name }}
                            </div>
                        </div>

                        <div
                            v-if="selected.address || selected.address_line_2"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("Address") }}
                            </div>
                            <div class="detail-value">
                                {{ selected.address }}
                            </div>
                            <div
                                v-if="selected.address_line_2"
                                class="detail-value"
                            >
                                {{ selected.address_line_2 }}
                            </div>
                        </div>

                        <div
                            v-if="
                                selected.city ||
                                selected.state ||
                                selected.district
                            "
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("Location") }}
                            </div>
                            <div class="detail-value">
                                <span v-if="selected.city">
                                    {{ selected.city }}
                                </span>
                                <span v-if="selected.state"
                                    >, {{ selected.state }}</span
                                >
                                <span v-if="selected.district"
                                    >, {{ selected.district }}
                                </span>
                            </div>
                        </div>

                        <div
                            v-if="selected.country || selected.postal_code"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("Country") }} / {{ __("Postal Code") }}
                            </div>
                            <div class="detail-value">
                                <span v-if="selected.country">{{
                                    selected.country
                                }}</span>
                                <span v-if="selected.postal_code">
                                    - {{ selected.postal_code }}</span
                                >
                            </div>
                        </div>

                        <div
                            v-if="selected.phone"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("Primary phone") }}
                            </div>
                            <div class="detail-value">{{ selected.phone }}</div>
                        </div>

                        <div
                            v-if="selected.secondary_phone"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("Secondary phone") }}
                            </div>
                            <div class="detail-value">
                                {{ selected.secondary_phone }}
                            </div>
                        </div>

                        <div
                            v-if="selected.references"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div
                                class="text-caption text-uppercase text-weight-medium detail-label"
                            >
                                {{ __("References") }}
                            </div>
                            <div class="detail-value">
                                {{ selected.references }}
                            </div>
                        </div>
                    </div>
                </div>
            </q-card>
        </div>

        <!-- Address Selection Dialog -->
        <q-dialog v-model="show_dialog" persistent maximized>
            <q-card class="address-dialog">
                <q-bar class="dialog-header bg-primary text-white">
                    <div class="text-h6">{{ __("Delivery Addresses") }}</div>
                    <q-space />
                    <q-btn dense flat icon="mdi-close" v-close-popup>
                        <q-tooltip>{{ __("Close") }}</q-tooltip>
                    </q-btn>
                </q-bar>

                <q-card-section class="dialog-content q-pt-none">
                    <div class="row q-col-gutter-lg">
                        <!-- Add New Address Form -->
                        <div class="col-12 col-md-6">
                            <q-card class="form-card" flat bordered>
                                <q-card-section
                                    class="form-header bg-primary text-white"
                                >
                                    <div class="text-h6">
                                        {{ __("Add New Address") }}
                                    </div>
                                </q-card-section>

                                <q-card-section>
                                    <div class="column q-gutter-md">
                                        <q-input
                                            v-model="form.full_name"
                                            :label="__('Full Name')"
                                            filled
                                            dense
                                            :error="!!errors.full_name"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-account" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="errors.full_name"
                                                />
                                            </template>
                                        </q-input>

                                        <v-country
                                            v-model="form.country"
                                            :errors="errors"
                                            class="custom-input"
                                        />

                                        <v-phone
                                            v-model="form.phone"
                                            :errors="errors"
                                        />

                                        <q-input
                                            v-model="form.state"
                                            :label="__('State / Province')"
                                            filled
                                            dense
                                            :error="!!errors.state"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-map-marker" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="errors.state"
                                                />
                                            </template>
                                        </q-input>

                                        <q-input
                                            v-model="form.city"
                                            :label="__('City')"
                                            filled
                                            dense
                                            :error="!!errors.city"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-city" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error :error="errors.city" />
                                            </template>
                                        </q-input>

                                        <q-input
                                            v-model="form.district"
                                            :label="__('District')"
                                            filled
                                            dense
                                            :error="!!errors.district"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-home-city" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="errors.district"
                                                />
                                            </template>
                                        </q-input>

                                        <q-input
                                            v-model="form.address"
                                            :label="__('Address')"
                                            filled
                                            dense
                                            :error="!!errors.address"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-home" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="errors.address"
                                                />
                                            </template>
                                        </q-input>

                                        <q-input
                                            v-model="form.address_line_2"
                                            :label="__('Address Line 2')"
                                            filled
                                            dense
                                            :error="!!errors.address_line_2"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-home-plus" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="
                                                        errors.address_line_2
                                                    "
                                                />
                                            </template>
                                        </q-input>

                                        <q-input
                                            v-model="form.postal_code"
                                            :label="__('Postal Code')"
                                            filled
                                            dense
                                            :error="!!errors.postal_code"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon name="mdi-post" />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="errors.postal_code"
                                                />
                                            </template>
                                        </q-input>

                                        <q-input
                                            v-model="form.references"
                                            :label="__('References')"
                                            type="textarea"
                                            filled
                                            dense
                                            :error="!!errors.references"
                                            class="custom-input"
                                        >
                                            <template v-slot:prepend>
                                                <q-icon
                                                    name="mdi-information"
                                                />
                                            </template>
                                            <template v-slot:error>
                                                <v-error
                                                    :error="errors.references"
                                                />
                                            </template>
                                        </q-input>
                                    </div>
                                </q-card-section>

                                <q-card-actions class="q-px-md q-pb-md">
                                    <q-btn
                                        type="submit"
                                        color="primary"
                                        :label="
                                            form.id
                                                ? __('Update Address')
                                                : __('Save Address')
                                        "
                                        @click="saveAddress"
                                        class="full-width save-btn"
                                        unelevated
                                    />
                                </q-card-actions>
                            </q-card>
                        </div>

                        <!-- Existing Addresses List -->
                        <div class="col-12 col-md-6">
                            <q-card class="address-list-card" flat bordered>
                                <q-card-section
                                    class="list-header bg-primary text-white"
                                >
                                    <div class="text-h6">
                                        {{ __("Your Addresses") }}
                                    </div>
                                </q-card-section>

                                <q-card-section class="q-pa-none">
                                    <q-scroll-area class="address-scroll-area">
                                        <q-list class="address-list" separator>
                                            <q-item
                                                v-for="address in addresses"
                                                :key="address.id"
                                                class="address-item q-pa-md"
                                                :class="{
                                                    'selected-address-item':
                                                        selected?.id ===
                                                        address.id,
                                                }"
                                            >
                                                <q-item-section>
                                                    <div
                                                        class="row items-center"
                                                    >
                                                        <q-icon
                                                            name="mdi-map-marker"
                                                            size="20px"
                                                            class="q-mr-sm text-primary"
                                                            v-if="
                                                                selected?.id ===
                                                                address.id
                                                            "
                                                        />
                                                        <div
                                                            class="text-body1 text-weight-medium"
                                                        >
                                                            {{
                                                                address.full_name
                                                            }}
                                                            -
                                                            {{
                                                                address.country
                                                            }}
                                                            -
                                                            {{ address.city }} -
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-caption text-grey-7 q-mt-xs"
                                                    >
                                                        {{ address.address }}
                                                        <span
                                                            v-if="
                                                                address.address_line_2
                                                            "
                                                            >,
                                                            {{
                                                                address.address_line_2
                                                            }}</span
                                                        >
                                                    </div>

                                                    <div
                                                        class="row items-center q-mt-xs"
                                                    >
                                                        <q-icon
                                                            name="mdi-phone"
                                                            size="16px"
                                                            class="q-mr-xs text-grey-7"
                                                        />
                                                        <span
                                                            class="text-caption"
                                                            >{{
                                                                address.phone
                                                            }}</span
                                                        >
                                                    </div>
                                                </q-item-section>

                                                <q-item-section side top>
                                                    <div
                                                        class="column q-gutter-xs"
                                                    >
                                                        <q-btn
                                                            dense
                                                            flat
                                                            color="primary"
                                                            icon="mdi-check"
                                                            :label="
                                                                selected?.id ===
                                                                address.id
                                                                    ? __(
                                                                          'Selected'
                                                                      )
                                                                    : __(
                                                                          'Select'
                                                                      )
                                                            "
                                                            @click="
                                                                selectAddress(
                                                                    address
                                                                )
                                                            "
                                                            class="action-btn"
                                                            size="sm"
                                                        />

                                                        <q-btn
                                                            dense
                                                            flat
                                                            color="secondary"
                                                            icon="mdi-pencil"
                                                            :label="__('Edit')"
                                                            @click="
                                                                edit(address)
                                                            "
                                                            class="action-btn"
                                                            size="sm"
                                                        />

                                                        <q-btn
                                                            dense
                                                            flat
                                                            color="negative"
                                                            icon="mdi-delete"
                                                            :label="
                                                                __('Remove')
                                                            "
                                                            @click="
                                                                destroy(address)
                                                            "
                                                            class="action-btn"
                                                            size="sm"
                                                        />
                                                    </div>
                                                </q-item-section>
                                            </q-item>
                                        </q-list>
                                    </q-scroll-area>
                                </q-card-section>
                            </q-card>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="dialog-actions q-pa-md">
                    <q-btn
                        flat
                        :label="__('Close')"
                        color="grey"
                        v-close-popup
                        class="close-btn"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["selected"],

    data() {
        return {
            show_dialog: false,
            addresses: [],
            form: {},
            errors: {},
            selected: null,
        };
    },

    created() {
        this.open();
        this.getAddresses();
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
            this.errors = {};
        },

        selectAddress(item) {
            this.$q.notify({
                message: this.__(
                    "Delivery address has been selected successfully"
                ),
                color: "positive",
                position: "top-right",
                icon: "mdi-check-circle",
            });
            this.selected = item;
            this.show_dialog = false;
            this.$emit("selected", this.selected.id);
        },

        edit(item) {
            this.form = { ...item };
        },

        async destroy(item) {
            try {
                this.$q
                    .dialog({
                        title: this.__("Confirm Removal"),
                        message: this.__(
                            "Are you sure you want to remove this address?"
                        ),
                        cancel: true,
                        persistent: true,
                    })
                    .onOk(async () => {
                        const res = await this.$server.delete(
                            item.links.delete
                        );
                        if (res.status == 200) {
                            this.getAddresses();
                            this.$q.notify({
                                message: this.__(
                                    "Address removed successfully"
                                ),
                                color: "positive",
                                position: "top-right",
                                icon: "mdi-check-circle",
                            });

                            // If the removed address was selected, clear selection
                            if (this.selected?.id === item.id) {
                                this.selected = null;
                            }
                        }
                    });
            } catch (error) {
                console.error("Error removing address:", error);
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
            } catch (error) {
                console.error("Error fetching addresses:", error);
            }
        },

        async saveAddress() {
            try {
                const res = await this.$server.post(
                    this.$page.props.delivery_address.route,
                    this.form
                );

                if (res.status === 201 || res.status === 200) {
                    this.getAddresses();
                    this.$q.notify({
                        message: this.form.id
                            ? this.__("Delivery address updated successfully")
                            : this.__("Delivery address added successfully"),
                        color: "positive",
                        position: "top-right",
                        icon: "mdi-check-circle",
                    });

                    this.open();
                    this.errors = {};
                }
            } catch (error) {
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                }
            }
        },
    },
};
</script>

<style>
/* Color variables for theming */
.address-selector-container {
    --color-primary: #1976d2;
    --color-primary-dark: #1565c0;
    --color-secondary: #26a69a;
    --color-accent: #9c27b0;
    --color-positive: #21ba45;
    --color-negative: #c10015;
    --color-warning: #f2c037;
    --color-info: #31ccec;
    --color-dark: #1d1d1d;
    --color-light: #f5f5f5;
    --color-background: #fafafa;
    --color-card: #ffffff;
    --color-text-primary: rgba(0, 0, 0, 0.87);
    --color-text-secondary: rgba(0, 0, 0, 0.6);
    --color-border: rgba(0, 0, 0, 0.12);
    --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-medium: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.address-selector-container {
    width: 100%;
}

.select-address-btn {
    font-weight: 500;
    padding: 8px 16px;
}

.selected-address-wrapper {
    margin-top: 16px;
}

.selected-address-card {
    border-radius: 12px;
    box-shadow: var(--shadow-soft);
    transition: box-shadow 0.3s ease;
}

.selected-address-card:hover {
    box-shadow: var(--shadow-medium);
}

.selected-label {
    color: var(--color-primary);
    letter-spacing: 0.5px;
    font-size: 0.75rem;
}

.address-details .detail-label {
    color: var(--color-text-secondary);
    margin-bottom: 4px;
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 500;
}

.address-details .detail-value {
    color: var(--color-text-primary);
    word-break: break-word;
}

.address-dialog {
    border-radius: 12px;
    overflow: hidden;
}

.address-dialog .dialog-header {
    height: 60px;
}

.address-dialog .dialog-content {
    background-color: var(--color-background);
    padding: 24px;
}

.address-dialog .dialog-actions {
    border-top: 1px solid var(--color-border);
}

.form-card,
.address-list-card {
    border-radius: 12px;
    box-shadow: var(--shadow-soft);
}

.form-card .form-header,
.address-list-card .list-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 16px;
}

.custom-input .q-field__control {
    border-radius: 8px;
    height: 48px;
}

.custom-input .q-field__label {
    font-weight: 500;
}

.save-btn {
    border-radius: 8px;
    height: 48px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.address-scroll-area {
    height: 60vh;
}

.address-list .address-item {
    border-bottom: 1px solid var(--color-border);
    transition: background-color 0.2s ease;
}

.address-list .address-item:last-child {
    border-bottom: none;
}

.address-list .address-item:hover {
    background-color: rgba(25, 118, 210, 0.04);
}

.address-list .selected-address-item {
    background-color: rgba(25, 118, 210, 0.08);
    border-left: 4px solid var(--color-primary);
}

.action-btn {
    border-radius: 6px;
    padding: 4px 8px;
}

.close-btn {
    border-radius: 8px;
    padding: 8px 16px;
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .address-scroll-area {
        height: 40vh;
    }
}

@media (max-width: 767px) {
    .address-dialog .dialog-content {
        padding: 16px;
    }

    .address-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .address-item .q-item__section--side {
        margin-top: 12px;
        align-items: flex-start;
    }
}

@media (max-width: 599px) {
    .selected-address-card .row {
        flex-direction: column;
    }

    .form-card,
    .address-list-card {
        margin-bottom: 16px;
    }
}
</style>
