<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <v-header />

        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <i
                                class="fas fa-shopping-bag text-purple-600 text-xl"
                            ></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                {{ __("Order History") }}
                            </h1>
                            <p class="text-gray-500 mt-1">
                                {{ __("Review your past purchases") }}
                            </p>
                        </div>
                    </div>
                    <button
                        v-if="orders.length > 0"
                        @click="getCheckouts"
                        class="p-3 bg-white rounded-xl border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300 shadow-sm"
                        :disabled="loading"
                        :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                        <i
                            class="fas fa-sync-alt text-purple-600"
                            :class="{ 'animate-spin': loading }"
                        ></i>
                        <span class="sr-only">{{ __("Refresh orders") }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Empty State -->
            <div
                v-if="orders.length === 0 && !loading"
                class="text-center bg-white rounded-2xl shadow-sm p-8 max-w-md mx-auto border border-gray-100"
            >
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mb-6"
                >
                    <i class="fas fa-box-open text-3xl text-purple-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ __("No orders yet") }}
                </h3>
                <p class="text-gray-500 mb-6">
                    {{
                        __(
                            "Your order history will appear here once you make a purchase"
                        )
                    }}
                </p>
                <a
                    :href="$page.props.routes.search"
                    class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5"
                >
                    <i class="fas fa-shopping-cart mr-2"></i>
                    {{ __("Start Shopping") }}
                </a>
            </div>

            <!-- Orders -->
            <div v-else-if="orders.length > 0" class="space-y-5">
                <div
                    v-for="order in orders"
                    :key="order.id"
                    class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100"
                >
                    <!-- Order Header -->
                    <button
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer transition-colors duration-200 hover:bg-gray-50"
                        :class="{
                            'border-l-4 border-emerald-500':
                                order.transaction.status === 'successful',
                            'border-l-4 border-amber-500':
                                order.transaction.status === 'pending',
                            'border-l-4 border-rose-500':
                                order.transaction.status === 'failed',
                        }"
                        @click="
                            expandedOrder =
                                expandedOrder === order.id ? null : order.id
                        "
                    >
                        <div class="flex items-center space-x-5">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm"
                                >
                                    {{ orderNumberIcon(order.code) }}
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white border-2 border-white flex items-center justify-center"
                                    :class="{
                                        'bg-emerald-500':
                                            order.transaction.status ===
                                            'successful',
                                        'bg-amber-500':
                                            order.transaction.status ===
                                            'pending',
                                        'bg-rose-500':
                                            order.transaction.status ===
                                            'failed',
                                    }"
                                >
                                    <i
                                        class="text-xs text-white"
                                        :class="{
                                            'fas fa-check':
                                                order.transaction.status ===
                                                'successful',
                                            'fas fa-clock':
                                                order.transaction.status ===
                                                'pending',
                                            'fas fa-times':
                                                order.transaction.status ===
                                                'failed',
                                        }"
                                    ></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ __("Order") }} #{{ order.code }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{
                                        formatCompactDate(
                                            order.transaction.created
                                        )
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right flex items-center space-x-4">
                            <div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-emerald-100 text-emerald-800':
                                            order.transaction.status ===
                                            'successful',
                                        'bg-amber-100 text-amber-800':
                                            order.transaction.status ===
                                            'pending',
                                        'bg-rose-100 text-rose-800':
                                            order.transaction.status ===
                                            'failed',
                                    }"
                                >
                                    {{ formatStatus(order.transaction.status) }}
                                </span>
                                <p class="font-bold text-gray-800 text-lg mt-1">
                                    {{ order.transaction.total }}
                                    {{ order.transaction.currency }}
                                </p>
                            </div>
                            <i
                                class="fas text-gray-400 transition-transform duration-300"
                                :class="
                                    expandedOrder === order.id
                                        ? 'fa-chevron-up'
                                        : 'fa-chevron-down'
                                "
                            ></i>
                        </div>
                    </button>

                    <!-- Expanded Content -->
                    <transition
                        enter-active-class="transition-all duration-300 ease-out"
                        leave-active-class="transition-all duration-200 ease-in"
                        enter-class="opacity-0 max-h-0"
                        enter-to-class="opacity-100 max-h-96"
                        leave-class="opacity-100 max-h-96"
                        leave-to-class="opacity-0 max-h-0"
                    >
                        <div
                            v-if="expandedOrder === order.id"
                            class="p-6 border-t border-gray-100 space-y-6 bg-gray-50"
                        >
                            <!-- Transaction -->
                            <section class="bg-white rounded-xl p-5 shadow-sm">
                                <h4
                                    class="flex items-center text-base font-semibold text-gray-800 mb-4"
                                >
                                    <div
                                        class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3"
                                    >
                                        <i
                                            class="fas fa-receipt text-purple-600"
                                        ></i>
                                    </div>
                                    {{ __("Transaction Details") }}
                                </h4>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-5"
                                >
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <span
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1"
                                            >Status</span
                                        >
                                        <p
                                            class="font-semibold"
                                            :class="{
                                                'text-emerald-600':
                                                    order.transaction.status ===
                                                    'successful',
                                                'text-amber-600':
                                                    order.transaction.status ===
                                                    'pending',
                                                'text-rose-600':
                                                    order.transaction.status ===
                                                    'failed',
                                            }"
                                        >
                                            {{
                                                formatStatus(
                                                    order.transaction.status
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <span
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1"
                                            >{{ __("Payment Method") }}</span
                                        >
                                        <p class="font-semibold text-gray-800">
                                            {{
                                                formatPaymentMethod(
                                                    order.transaction
                                                        .payment_method
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <span
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1"
                                            >{{ __("Total Amount") }}</span
                                        >
                                        <p
                                            class="font-bold text-lg text-purple-600"
                                        >
                                            {{ order.transaction.total }}
                                            {{ order.transaction.currency }}
                                        </p>
                                    </div>
                                </div>
                            </section>

                            <!-- Delivery -->
                            <section class="bg-white rounded-xl p-5 shadow-sm">
                                <h4
                                    class="flex items-center text-base font-semibold text-gray-800 mb-4"
                                >
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3"
                                    >
                                        <i
                                            class="fas fa-location-dot text-blue-600"
                                        ></i>
                                    </div>
                                    {{ __("Delivery Address") }}
                                </h4>
                                <div
                                    class="bg-blue-50 rounded-xl p-5 flex justify-between items-start border border-blue-100"
                                >
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{
                                                order.delivery_address.full_name
                                            }}
                                        </p>
                                        <p class="text-gray-700 mt-1">
                                            {{ order.delivery_address.address }}
                                        </p>
                                        <p class="text-gray-600 text-sm mt-1">
                                            {{ order.delivery_address.city }},
                                            {{ order.delivery_address.country }}
                                        </p>
                                        <p
                                            class="flex items-center text-gray-600 text-sm mt-2"
                                        >
                                            <i
                                                class="fas fa-phone text-xs mr-2 text-gray-500"
                                            ></i>
                                            {{ order.delivery_address.phone }}
                                        </p>
                                    </div>
                                    <a
                                        v-if="order.delivery_address.whatsapp"
                                        :href="order.delivery_address.whatsapp"
                                        target="_blank"
                                        class="p-3 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl transition-colors duration-200"
                                        title="Contact via WhatsApp"
                                    >
                                        <i class="fab fa-whatsapp text-xl"></i>
                                    </a>
                                </div>
                            </section>

                            <!-- Items -->
                            <section class="bg-white rounded-xl p-5 shadow-sm">
                                <h4
                                    class="flex items-center text-base font-semibold text-gray-800 mb-4"
                                >
                                    <div
                                        class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3"
                                    >
                                        <i
                                            class="fas fa-box text-amber-600"
                                        ></i>
                                    </div>
                                    {{ __("Order Items") }} ({{
                                        order.orders.length
                                    }})
                                </h4>
                                <div class="space-y-3">
                                    <div
                                        v-for="item in order.orders"
                                        :key="item.id"
                                        class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors duration-200"
                                    >
                                        <img
                                            :src="item.images[0]?.url"
                                            :alt="item.meta.name"
                                            class="w-16 h-16 rounded-lg object-cover shadow-sm mr-4 border border-gray-200"
                                        />
                                        <div class="flex-1">
                                            <p
                                                class="font-semibold text-gray-800"
                                            >
                                                {{ item.meta.name }}
                                            </p>
                                            <p
                                                class="text-sm text-gray-500 mt-1"
                                            >
                                                {{ __("Quantity") }}:
                                                {{ item.quantity }}
                                            </p>
                                        </div>
                                        <p
                                            class="font-bold text-purple-600 text-lg"
                                        >
                                            {{ item.currency }}
                                            {{ item.format_price }}
                                        </p>
                                    </div>
                                </div>
                            </section>

                            <!-- Actions -->
                            <div
                                class="flex flex-wrap gap-3 pt-5 border-t border-gray-200"
                            >
                                <a
                                    v-if="order.transaction.payment_url"
                                    :href="order.transaction.payment_url"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm"
                                >
                                    <i
                                        class="fas fa-receipt mr-2 text-purple-500"
                                    ></i>
                                    {{ __("View Receipt") }}
                                </a>
                                <button
                                    v-if="canReorder(order)"
                                    @click="reorder(order)"
                                    class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm"
                                >
                                    <i
                                        class="fas fa-reply mr-2 text-blue-500"
                                    ></i>
                                    {{ __("Reorder") }}
                                </button>
                                <button
                                    @click="copyOrderId(order.code)"
                                    class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm"
                                >
                                    <i
                                        class="fas fa-copy mr-2 text-gray-500"
                                    ></i>
                                    {{ __("Copy Order ID") }}
                                </button>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </main>

        <!-- Loading -->
        <div
            v-if="loading"
            class="fixed inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50"
        >
            <div class="text-center">
                <div
                    class="w-16 h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin mb-4"
                ></div>
                <p class="text-gray-700 font-medium">
                    {{ __("Loading orders...") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import VHeader from "../../Components/VHeader.vue";

export default {
    components: {
        VHeader,
    },

    data() {
        return {
            orders: [],
            loading: false,
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            expandedOrder: null,
        };
    },

    created() {
        this.getCheckouts();
    },

    watch: {
        "search.page"(value) {
            this.getCheckouts();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getCheckouts();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        async getCheckouts() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.checkouts,
                    {
                        params: this.search,
                    }
                );
                if (res.status === 200) {
                    this.orders = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.current_page =
                        res.data.meta.pagination.current_page;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        },

        formatCompactDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
            });
        },

        orderNumberIcon(code) {
            // Get the last character of order code for avatar
            return code.slice(-1).toUpperCase();
        },

        formatStatus(status) {
            const statusMap = {
                successful: "Successful",
                failed: "Failed",
                pending: "Pending",
                refunded: "Refunded",
                cancelled: "Cancelled",
            };
            return statusMap[status] || status;
        },

        formatPaymentMethod(method) {
            const methodMap = {
                offline: "Offline",
                card: "Credit Card",
                paypal: "PayPal",
                bank_transfer: "Bank Transfer",
            };
            return methodMap[method] || method;
        },

        canReorder(order) {
            return (
                order.transaction.status === "successful" ||
                order.transaction.status === "completed"
            );
        },

        reorder(order) {
            this.$q.notify({
                message: `Adding items from order ${order.code} to cart`,
                color: "positive",
                icon: "add_shopping_cart",
                timeout: 2000,
            });
            // Implementation for reorder functionality
        },

        copyOrderId(orderCode) {
            navigator.clipboard.writeText(orderCode);
            this.$swal.fire({
                title: "Success",
                text: "Order ID copied to clipboard",
                icon: "success",
                timer: 1500,
                showConfirmButton: false,
            });
        },
    },
};
</script>
