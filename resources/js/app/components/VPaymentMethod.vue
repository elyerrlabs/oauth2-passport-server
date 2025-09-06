<template>
    <div class="payment-method-selector">
        <div class="text-h5 q-mb-lg section-title">
            {{ __("Choose your payment method") }}
        </div>

        <div class="payment-methods-grid">
            <q-card
                v-for="(method, key) in filteredMethods"
                :key="key"
                @click="selectMethod(key)"
                flat
                bordered
                class="payment-method-card"
                v-show="method.enable"
                :class="{
                    'selected-method': selected_method === key,
                    'disabled-method': !method.enable,
                }"
            >
                <q-card-section class="card-content text-center">
                    <div class="method-icon-wrapper">
                        <q-icon
                            :name="method.icon"
                            size="32px"
                            class="method-icon"
                            :class="{ 'disabled-icon': !method.enable }"
                        />
                    </div>

                    <div class="method-name text-subtitle2 q-mt-sm">
                        {{ method.name }}
                    </div>

                    <div
                        v-if="!method.enable"
                        class="method-unavailable text-caption q-mt-xs"
                    >
                        {{ __("Currently unavailable") }}
                    </div>
                </q-card-section>

                <div v-if="selected_method === key" class="selected-indicator">
                    <q-icon name="mdi-check-circle" size="20px" />
                </div>
            </q-card>
        </div>

        <div
            v-if="selected_method !== -1"
            class="selected-method-display q-mt-lg"
        >
            <q-card flat bordered class="selected-card">
                <q-card-section class="row items-center">
                    <q-icon
                        :name="methods[selected_method]?.icon"
                        size="24px"
                        color="primary"
                        class="q-mr-md"
                    />
                    <div class="text-subtitle1">
                        {{ __("Selected method") }}:
                        <span class="text-weight-bold">{{
                            methods[selected_method]?.name
                        }}</span>
                    </div>
                </q-card-section>
            </q-card>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: [String, Number],
            default: -1,
        },
    },

    emits: ["update:modelValue"],

    data() {
        return {
            selected_method: this.modelValue,
            methods: [],
        };
    },

    computed: {
        filteredMethods() {
            return Object.entries(this.methods)
                .filter(([key, method]) => method)
                .reduce((obj, [key, method]) => {
                    obj[key] = method;
                    return obj;
                }, {});
        },
    },

    watch: {
        modelValue(newVal) {
            this.selected_method = this.methods[newVal].key;
        },

        selected_method(newVal) {
            this.$emit("update:modelValue", this.methods[newVal].key);
        },
    },

    created() {
        this.getPaymentMethods();
    },

    methods: {
        selectMethod(key) {
            if (this.methods[key] && this.methods[key].enable) {
                this.selected_method = key;
            }
        },

        async getPaymentMethods() {
            try {
                const res = await this.$server.get(
                    "/api/transaction/payments/methods"
                );

                if (res.status == 200) {
                    this.methods = res.data.data;
                }
            } catch (error) {}
        },
    },
};
</script>

<style>
/* Color variables for theming */
.payment-method-selector {
    --color-primary: #1976d2;
    --color-primary-light: #e3f2fd;
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
    --color-disabled: rgba(0, 0, 0, 0.38);
    --color-border: rgba(0, 0, 0, 0.12);
    --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-medium: 0 6px 16px rgba(0, 0, 0, 0.12);
    --border-radius: 12px;
}

.payment-method-selector {
    width: 100%;
}

.section-title {
    color: var(--color-text-primary);
    font-weight: 600;
}

.payment-methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 16px;
}

.payment-method-card {
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-soft);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.payment-method-card:hover:not(.disabled-method) {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.payment-method-card.selected-method {
    border: 2px solid var(--color-primary);
    background-color: var(--color-primary-light);
}

.payment-method-card.disabled-method {
    cursor: not-allowed;
    opacity: 0.6;
}

.card-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 16px;
}

.method-icon-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60px;
}

.method-icon {
    color: var(--color-primary);
}

.disabled-icon {
    color: var(--color-disabled) !important;
}

.selected-method .method-icon {
    color: var(--color-primary);
}

.method-name {
    color: var(--color-text-primary);
    font-weight: 500;
}

.selected-method .method-name {
    color: var(--color-primary);
    font-weight: 600;
}

.method-unavailable {
    color: var(--color-text-secondary);
    font-style: italic;
}

.selected-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    color: var(--color-positive);
    background: white;
    border-radius: 50%;
}

.selected-method-display {
    animation: fadeIn 0.5s ease;
}

.selected-card {
    border-radius: var(--border-radius);
    border-left: 4px solid var(--color-primary);
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .payment-methods-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
    }
}

@media (max-width: 599px) {
    .payment-methods-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .section-title {
        font-size: 1.25rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
