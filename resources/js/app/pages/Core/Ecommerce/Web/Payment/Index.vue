<template>
    <v-ecommerce>
        <div class="text-h5 text-primary q-mb-md">Orders History</div>

        <q-list bordered class="rounded-borders shadow-2">
            <q-expansion-item
                v-for="order in orders"
                :key="order.id"
                :label="'Order #' + order.id"
                :caption="'Total: $' + order.total"
                expand-separator
                icon="shopping_cart"
            >
                <q-list separator>
                    <q-item v-for="item in order.items" :key="item.id">
                        <q-item-section avatar>
                            <q-img
                                :src="item.image"
                                :alt="item.name"
                                style="width: 60px; height: 60px"
                                class="rounded-borders"
                            />
                        </q-item-section>

                        <q-item-section>
                            <q-item-label>{{ item.name }}</q-item-label>
                            <q-item-label caption>
                                Quantity: {{ item.quantity }} × ${{
                                    item.unitPrice
                                }}
                            </q-item-label>
                        </q-item-section>

                        <q-item-section side top>
                            <div class="text-bold">
                                ${{ item.quantity * item.unitPrice }}
                            </div>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-expansion-item>
        </q-list>
    </v-ecommerce>
</template>

<script>
export default {
    data() {
        return {
            orders: [
                {
                    id: "1001",
                    total: 59.97,
                    items: [
                        {
                            id: "p1",
                            name: "Wireless Mouse",
                            quantity: 1,
                            unitPrice: 19.99,
                            image: "https://via.placeholder.com/60",
                        },
                        {
                            id: "p2",
                            name: "Mechanical Keyboard",
                            quantity: 2,
                            unitPrice: 19.99,
                            image: "https://via.placeholder.com/60",
                        },
                    ],
                },
                {
                    id: "1002",
                    total: 39.99,
                    items: [
                        {
                            id: "p3",
                            name: "Gaming Headset",
                            quantity: 1,
                            unitPrice: 39.99,
                            image: "https://via.placeholder.com/60",
                        },
                    ],
                },
            ],
        };
    },
};
</script>
