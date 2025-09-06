<template>
    <v-user-layout>
        <div class="container-wrapper">
            <q-card class="product-detail-card shadow-10">
                <!-- Encabezado mejorado -->
                <q-card-section
                    class="card-header bg-primary text-white q-pa-lg"
                >
                    <div class="row items-center">
                        <q-icon
                            name="mdi-certificate"
                            size="28px"
                            class="q-mr-md"
                        />
                        <div class="text-h5">{{ __("Plan Details") }}</div>
                        <q-space />
                        <q-badge
                            :color="
                                item?.status === 'successful' ? 'green' : 'red'
                            "
                            class="status-badge text-white"
                        >
                            {{ item?.status?.toUpperCase() }}
                        </q-badge>
                    </div>
                    <q-separator dark class="q-my-md" />
                    <div class="text-h6">{{ item?.meta?.name }}</div>
                    <div
                        class="text-subtitle2 opacity-80"
                        v-html="item?.meta?.description"
                    ></div>
                </q-card-section>

                <!-- Información principal en grid -->
                <q-card-section class="q-pa-lg">
                    <div class="text-h6 text-primary q-mb-md">
                        {{ __("Plan Information") }}
                    </div>

                    <div class="info-grid q-gutter-md">
                        <!-- Columna izquierda -->
                        <div class="info-column">
                            <div class="info-item">
                                <q-icon
                                    name="mdi-package-variant"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Plan Name") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.meta?.name }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <q-icon
                                    name="mdi-cash-sync"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Transaction Code") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.transaction?.code }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <q-icon
                                    name="mdi-calendar-start"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Start Date") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.start_at }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item" v-if="item?.last_renewal_at">
                                <q-icon
                                    name="mdi-calendar-refresh"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Last Renewal") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.last_renewal_at }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-column">
                            <div class="info-item">
                                <q-icon
                                    name="mdi-currency-usd"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Price") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.transaction?.total }}
                                        {{ item?.transaction?.currency }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <q-icon
                                    name="mdi-calendar-range"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Billing Period") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.transaction?.billing_period }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <q-icon
                                    name="mdi-calendar-end"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("End Date") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.end_at }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item" v-if="item?.cancellation_at">
                                <q-icon
                                    name="mdi-calendar-remove"
                                    color="primary"
                                    class="q-mr-sm"
                                />
                                <div class="info-content">
                                    <div class="info-label">
                                        {{ __("Cancellation") }}
                                    </div>
                                    <div class="info-value">
                                        {{ item?.cancellation_at }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row q-mt-lg">
                        <div class="col-xs-12 col-sm-6 q-pa-xs">
                            <q-item class="additional-info">
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-credit-card-outline"
                                        color="primary"
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label caption>
                                        {{ __("Payment Method") }}
                                    </q-item-label>
                                    <q-item-label class="text-weight-medium">
                                        {{ item?.transaction?.payment_method }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </div>
                        <div class="col-xs-12 col-sm-6 q-pa-xs">
                            <q-item class="additional-info">
                                <q-item-section avatar>
                                    <q-icon
                                        :name="
                                            item?.is_recurring
                                                ? 'mdi-autorenew'
                                                : 'mdi-pause-circle-outline'
                                        "
                                        :color="
                                            item?.is_recurring
                                                ? 'positive'
                                                : 'grey'
                                        "
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label caption>
                                        {{ __("Recurring") }}
                                    </q-item-label>
                                    <q-item-label class="text-weight-medium">
                                        {{
                                            item?.is_recurring
                                                ? __("Yes")
                                                : __("No")
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </div>
                    </div>
                </q-card-section>

                <q-separator />

                <!-- Servicios incluidos -->
                <q-card-section class="q-pa-lg">
                    <div class="text-h6 text-primary q-mb-md">
                        {{ __("Included Services") }}
                    </div>
                    <div class="services-grid">
                        <q-card
                            v-for="scope in item?.meta?.scopes"
                            :key="scope.id"
                            class="service-card"
                            flat
                            bordered
                        >
                            <q-card-section class="q-pa-md">
                                <div class="text-subtitle1 text-weight-bold">
                                    {{ __(scope.role.name) }}
                                </div>
                                <div class="text-caption text-grey-7 q-mt-xs">
                                    {{ __(scope.role.description) }}
                                </div>
                                <q-chip
                                    size="sm"
                                    color="blue-1"
                                    text-color="primary"
                                    class="q-mt-sm"
                                >
                                    <q-icon
                                        name="mdi-cube"
                                        size="16px"
                                        class="q-mr-xs"
                                    />
                                    {{ scope.service.name }}
                                </q-chip>
                            </q-card-section>
                        </q-card>
                    </div>
                </q-card-section>

                <q-separator />

                <!-- Transacciones -->
                <q-card-section class="q-pa-lg">
                    <div class="text-h6 text-primary q-mb-md">
                        {{ __("Transactions") }}
                    </div>

                    <div class="transactions-container">
                        <q-card
                            v-for="(tx, index) in item?.transactions"
                            :key="index"
                            class="transaction-card q-mb-md"
                            :class="{
                                'transaction-successful':
                                    tx.status === 'successful',
                                'transaction-pending': tx.status === 'pending',
                                'transaction-failed': tx.status === 'failed',
                            }"
                        >
                            <q-card-section class="q-pa-md">
                                <div class="row items-center justify-between">
                                    <div class="col">
                                        <div class="row items-center">
                                            <q-icon
                                                :name="
                                                    tx.status === 'successful'
                                                        ? 'mdi-check-circle'
                                                        : tx.status ===
                                                          'pending'
                                                        ? 'mdi-timer-sand'
                                                        : 'mdi-close-circle'
                                                "
                                                :color="
                                                    tx.status === 'successful'
                                                        ? 'positive'
                                                        : tx.status ===
                                                          'pending'
                                                        ? 'warning'
                                                        : 'negative'
                                                "
                                                size="24px"
                                                class="q-mr-sm"
                                            />
                                            <div>
                                                <div class="text-weight-bold">
                                                    {{ __("Transaction #")
                                                    }}{{ tx.code }}
                                                </div>
                                                <div
                                                    class="text-caption text-grey"
                                                >
                                                    {{ tx.created }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <q-badge
                                            :color="
                                                tx.status === 'successful'
                                                    ? 'green'
                                                    : tx.status === 'pending'
                                                    ? 'orange'
                                                    : 'red'
                                            "
                                            class="status-badge"
                                        >
                                            {{ __(tx.status) }}
                                        </q-badge>
                                    </div>
                                </div>

                                <q-separator class="q-my-sm" />

                                <div class="row q-col-gutter-md">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="text-caption text-grey">
                                            {{ __("Total") }}
                                        </div>
                                        <div class="text-weight-medium">
                                            {{ tx.total }} {{ tx.currency }}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="text-caption text-grey">
                                            {{ __("Payment Method") }}
                                        </div>
                                        <div class="text-weight-medium">
                                            {{ tx.payment_method }}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="text-caption text-grey">
                                            {{ __("Billing Period") }}
                                        </div>
                                        <div class="text-weight-medium">
                                            {{ tx.billing_period || __("N/A") }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="row q-mt-sm"
                                    v-if="tx.session_id || tx.payment_intent_id"
                                >
                                    <div class="col-12">
                                        <q-expansion-item
                                            :label="__('Technical Details')"
                                            header-class="text-primary"
                                            expand-icon-class="text-primary"
                                        >
                                            <q-card>
                                                <q-card-section
                                                    class="bg-grey-2"
                                                >
                                                    <div
                                                        v-if="tx.session_id"
                                                        class="q-mb-xs"
                                                    >
                                                        <span
                                                            class="text-caption text-grey"
                                                            >{{
                                                                __(
                                                                    "Session ID:"
                                                                )
                                                            }}</span
                                                        >
                                                        <div
                                                            class="text-copyable"
                                                        >
                                                            {{ tx.session_id }}
                                                        </div>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            tx.payment_intent_id
                                                        "
                                                    >
                                                        <span
                                                            class="text-caption text-grey"
                                                            >{{
                                                                __(
                                                                    "Payment Intent:"
                                                                )
                                                            }}</span
                                                        >
                                                        <div
                                                            class="text-copyable"
                                                        >
                                                            {{
                                                                tx.payment_intent_id
                                                            }}
                                                        </div>
                                                    </div>
                                                </q-card-section>
                                            </q-card>
                                        </q-expansion-item>
                                    </div>
                                </div>

                                <div
                                    class="row items-center justify-around q-mt-md"
                                >
                                    <v-cancel
                                        v-if="tx.status === 'pending'"
                                        :item="tx"
                                    />

                                    <v-activate
                                        @updated="getPackages"
                                        v-if="check(tx)"
                                        :item="tx"
                                    />

                                    <q-btn
                                        v-if="
                                            tx.status === 'successful' &&
                                            tx.payment_url
                                        "
                                        :href="tx.payment_url"
                                        target="_blank"
                                        :label="__('View Invoice')"
                                        icon="mdi-receipt"
                                        size="sm"
                                        color="primary"
                                        outline
                                        class="q-ml-sm"
                                    />
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                </q-card-section>

                <!-- Acciones -->
                <q-card-actions class="q-pa-lg bg-grey-2">
                    <v-subscription
                        :period="item?.meta?.price"
                        :plan="item"
                        :label="__('Extend or renew package')"
                        :buy="false"
                        :package="item"
                        class="full-width"
                    />
                </q-card-actions>
            </q-card>
        </div>
    </v-user-layout>
</template>

<script>
import VCancel from "./Cancel.vue";
import VActivate from "../../Admin/Transaction/Activate.vue";

export default {
    components: {
        VCancel,
        VActivate,
    },

    data() {
        return {
            dialog: false,
            item: {},
        };
    },

    created() {
        this.getPackages();
    },

    methods: {
        check(item) {
            return item.status == "pending" || item.status == "failed";
        },
        async getPackages() {
            try {
                const res = await this.$server.get(window.location.href);

                if (res.status === 200) {
                    this.item = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },
    },
};
</script>

<style scoped>
.container-wrapper {
    height: 100vh;
    overflow-y: auto;
    padding: 16px;
    background-color: #f5f5f5;
}

.product-detail-card {
    border-radius: 12px;
    overflow: hidden;
    max-width: 1000px;
    margin: 0 auto;
}

.card-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.status-badge {
    border-radius: 16px;
    padding: 4px 12px;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-content {
    margin-left: 8px;
}

.info-label {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 2px;
}

.info-value {
    font-weight: 500;
}

.additional-info {
    padding: 8px 0;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
}

.service-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: 8px;
}

.service-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.transaction-card {
    border-radius: 8px;
    border-left: 4px solid #ccc;
}

.transaction-successful {
    border-left-color: #4caf50;
}

.transaction-pending {
    border-left-color: #ff9800;
}

.transaction-failed {
    border-left-color: #f44336;
}

.text-copyable {
    font-family: monospace;
    background-color: #f5f5f5;
    padding: 4px 8px;
    border-radius: 4px;
    word-break: break-all;
    cursor: pointer;
}

.text-copyable:hover {
    background-color: #e8e8e8;
}

/* Estilos para mejorar el scroll */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@media (max-width: 768px) {
    .container-wrapper {
        padding: 8px;
        height: 100vh;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .services-grid {
        grid-template-columns: 1fr;
    }

    .product-detail-card {
        min-width: unset;
    }
}
</style>
