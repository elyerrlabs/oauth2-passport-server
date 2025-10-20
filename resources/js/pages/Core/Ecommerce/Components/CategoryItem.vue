<template>
    <li>
        <!-- Menu Item -->
        <div
            class="flex items-center px-4 pb-1 hover:bg-gray-100 rounded-md transition-colors cursor-pointer"
        >
            <div
                class="flex items-center flex-1"
                @click="goTo(item.links?.index)"
            >
                <i
                    :class="[
                        'mdi',
                        item.icon?.icon || 'mdi-folder-outline',
                        'mr-3 text-primary-600 text-lg',
                    ]"
                ></i>

                <span class="text-gray-700 text-sm">{{ item.name }}</span>
            </div>

            <!-- Toggle arrow -->

            <i
                v-if="hasChildren"
                @click.stop="toggle = !toggle"
                :class="[
                    'mdi font-bold  ',
                    toggle ? 'mdi-chevron-down' : 'mdi-chevron-right',
                    'ml-2 text-gray-500 text-2xl  cursor-pointer transition-transform duration-200',
                ]"
            ></i>
        </div>

        <!-- Recursive Subcategories -->
        <transition name="fade">
            <ul
                v-show="toggle && hasChildren"
                class="ml-6 border-l border-gray-200 pl-3 space-y-1"
            >
                <CategoryItem
                    v-for="child in item.children"
                    :key="child.id"
                    :item="child"
                />
            </ul>
        </transition>
    </li>
</template>

<script>
export default {
    name: "CategoryItem",
    emits: ["blur"],
    props: {
        item: { type: Object, required: true },
    },
    data() {
        return {
            toggle: true,
        };
    },
    computed: {
        hasChildren() {
            return (
                Array.isArray(this.item.children) &&
                this.item.children.length > 0
            );
        },
    },

    methods: {
        setBlur() {
            this.$emit("blur", false);
        },

        goTo(url) {
            if (url) window.location.href = url;
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
