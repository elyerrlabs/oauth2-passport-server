<template>
    <v-main-layout>
        <header
            class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-20 shadow-sm"
        >
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <!-- Search Bar -->
                    <div class="flex-1 max-w-xl mx-auto">
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <i
                                    class="mdi mdi-magnify text-slate-400 group-focus-within:text-indigo-500 dark:group-focus-within:text-indigo-400 text-base transition-colors duration-200"
                                ></i>
                            </div>
                            <input
                                v-model="searchTerm"
                                type="text"
                                :placeholder="__('Buscar...')"
                                class="w-full pl-9 pr-10 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 outline-none text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 hover:bg-white dark:hover:bg-slate-800"
                            />
                            <div
                                class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none"
                            >
                                <kbd
                                    class="text-[10px] text-slate-400 dark:text-slate-500 font-mono bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-600"
                                >
                                    ⌘K
                                </kbd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div
            class="px-4 sm:px-6 lg:px-8 py-6 bg-slate-50/50 dark:bg-slate-950/30 min-h-screen"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Contador de resultados -->
                <div
                    v-if="searchTerm"
                    class="mb-4 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400"
                >
                    <span
                        >{{ totalResults }}
                        {{ __("resultados encontrados") }}</span
                    >
                    <button
                        @click="searchTerm = ''"
                        class="text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                    >
                        {{ __("Limpiar") }}
                    </button>
                </div>

                <!-- Sección: Aplicaciones -->
                <div v-if="filtered_apps.length" class="mb-8">
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-1 h-4 bg-emerald-500 dark:bg-emerald-400 rounded-full"
                        ></div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                        >
                            {{ __("Aplicaciones") }}
                        </h3>
                        <span
                            class="text-[10px] font-normal text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800"
                        >
                            {{ __("User") }}
                        </span>
                        <span
                            class="text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-full"
                        >
                            {{ filtered_apps.length }}
                        </span>
                    </div>
                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3"
                    >
                        <a
                            v-for="app in filtered_apps"
                            :key="app.id"
                            :href="app.route"
                            class="group bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition-all duration-200 p-3 text-center relative"
                        >
                            <div class="absolute -top-1 -right-1">
                                <span
                                    class="text-[8px] font-bold uppercase bg-emerald-500 text-white px-1.5 py-0.5 rounded-full shadow-sm"
                                >
                                    {{ __("User") }}
                                </span>
                            </div>
                            <div
                                class="w-12 h-12 mx-auto bg-emerald-50 dark:bg-emerald-950/30 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors duration-200 mb-1.5"
                            >
                                <i
                                    :class="[
                                        app.icon || 'mdi mdi-application',
                                        'text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 text-2xl transition-colors duration-200',
                                    ]"
                                ></i>
                            </div>
                            <span
                                class="text-[11px] font-medium text-slate-700 dark:text-slate-300 leading-tight"
                                v-html="
                                    searchTerm
                                        ? highlightMatch(app.name, 'emerald')
                                        : app.name
                                "
                            ></span>
                        </a>
                    </div>
                </div>

                <!-- Sección: Administración (Nivel Medio) -->
                <div v-if="filtered_admin.length" class="mb-8">
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-1 h-4 bg-blue-500 dark:bg-blue-400 rounded-full"
                        ></div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                        >
                            {{ __("Administración") }}
                        </h3>
                        <span
                            class="text-[10px] font-normal text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/30 px-1.5 py-0.5 rounded-full border border-blue-200 dark:border-blue-800"
                        >
                            {{ __("Admin") }}
                        </span>
                        <span
                            class="text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-full"
                        >
                            {{ filtered_admin.length }}
                        </span>
                    </div>
                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-2"
                    >
                        <a
                            v-for="admin in filtered_admin"
                            :key="admin.id"
                            :href="admin.route"
                            class="group bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition-all duration-200 p-3 text-center relative"
                        >
                            <div class="absolute -top-1 -right-1">
                                <span
                                    class="text-[8px] font-bold uppercase bg-blue-500 text-white px-1.5 py-0.5 rounded-full shadow-sm"
                                >
                                    {{ __("Admin") }}
                                </span>
                            </div>
                            <div
                                class="w-12 h-12 mx-auto bg-blue-50 dark:bg-blue-950/30 rounded-xl flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors duration-200 mb-1.5"
                            >
                                <i
                                    :class="[
                                        admin.icon || 'mdi mdi-shield',
                                        'text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 text-2xl transition-colors duration-200',
                                    ]"
                                ></i>
                            </div>
                            <span
                                class="text-[11px] font-medium text-slate-700 dark:text-slate-300 leading-tight"
                                v-html="
                                    searchTerm
                                        ? highlightMatch(admin.name, 'blue')
                                        : admin.name
                                "
                            ></span>
                        </a>
                    </div>
                </div>

                <!-- Sección: Configuración -->
                <div v-if="filtered_settings.length" class="mb-8">
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-1 h-4 bg-purple-500 dark:bg-purple-400 rounded-full"
                        ></div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                        >
                            {{ __("Configuración") }}
                        </h3>
                        <span
                            class="text-[10px] font-normal text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/30 px-1.5 py-0.5 rounded-full border border-purple-200 dark:border-purple-800"
                        >
                            {{ __("Settings") }}
                        </span>
                        <span
                            class="text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-full"
                        >
                            {{ filtered_settings.length }}
                        </span>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2"
                    >
                        <a
                            v-for="setting in filtered_settings"
                            :key="setting.id"
                            :href="setting.route"
                            class="group bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-purple-300 dark:hover:border-purple-700 hover:shadow-md transition-all duration-200 p-3 flex items-center gap-3 relative"
                        >
                            <div class="absolute -top-1 -right-1">
                                <span
                                    class="text-[8px] font-bold uppercase bg-purple-500 text-white px-1.5 py-0.5 rounded-full shadow-sm"
                                >
                                    {{ __("Settings") }}
                                </span>
                            </div>
                            <div
                                class="w-10 h-10 bg-purple-50 dark:bg-purple-950/30 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/50 transition-colors duration-200"
                            >
                                <i
                                    :class="[
                                        setting.icon || 'mdi mdi-cog',
                                        'text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300 text-lg transition-colors duration-200',
                                    ]"
                                ></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span
                                    class="text-[11px] font-medium text-slate-700 dark:text-slate-300"
                                    v-html="
                                        searchTerm
                                            ? highlightMatch(
                                                  setting.name,
                                                  'purple',
                                              )
                                            : setting.name
                                    "
                                ></span>
                                <p
                                    class="text-[10px] text-slate-400 dark:text-slate-500 truncate"
                                >
                                    {{
                                        setting.description ||
                                        __("Configuración")
                                    }}
                                </p>
                            </div>
                            <i
                                class="mdi mdi-chevron-right text-slate-300 dark:text-slate-600 text-sm group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                            ></i>
                        </a>
                    </div>
                </div>

                <!-- NUEVA SECCIÓN: Panel de Administración Superior (Top Level Admin) -->
                <div v-if="filtered_top_admin.length" class="mb-8">
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-1 h-4 bg-rose-500 dark:bg-rose-400 rounded-full"
                        ></div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2"
                        >
                            <span>{{
                                __("Panel de Administración Superior")
                            }}</span>
                            <span
                                class="text-[10px] font-normal text-rose-500 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/30 px-1.5 py-0.5 rounded-full border border-rose-200 dark:border-rose-800"
                            >
                                {{ __("Root") }}
                            </span>
                        </h3>
                        <span
                            class="text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-full"
                        >
                            {{ filtered_top_admin.length }}
                        </span>
                    </div>
                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-8 gap-2"
                    >
                        <a
                            v-for="admin in filtered_top_admin"
                            :key="admin.id"
                            :href="admin.route"
                            class="group bg-white dark:bg-slate-900 rounded-xl border-2 border-rose-200 dark:border-rose-800 hover:border-rose-400 dark:hover:border-rose-600 hover:shadow-lg transition-all duration-200 p-3 text-center relative"
                        >
                            <!-- Insignia de nivel superior -->
                            <div class="absolute -top-1 -right-1">
                                <span
                                    class="text-[8px] font-bold uppercase bg-rose-500 text-white px-1.5 py-0.5 rounded-full shadow-sm"
                                >
                                    {{ __("Root") }}
                                </span>
                            </div>

                            <div
                                class="w-12 h-12 mx-auto bg-rose-50 dark:bg-rose-950/30 rounded-xl flex items-center justify-center group-hover:bg-rose-100 dark:group-hover:bg-rose-900/50 transition-colors duration-200 mb-1.5"
                            >
                                <i
                                    :class="[
                                        admin.icon || 'mdi mdi-shield-crown',
                                        'text-rose-600 dark:text-rose-400 group-hover:text-rose-700 dark:group-hover:text-rose-300 text-2xl transition-colors duration-200',
                                    ]"
                                ></i>
                            </div>
                            <span
                                class="text-[11px] font-medium text-slate-700 dark:text-slate-300 leading-tight"
                                v-html="
                                    searchTerm
                                        ? highlightMatch(admin.name, 'rose')
                                        : admin.name
                                "
                            ></span>
                            <p
                                v-if="admin.description"
                                class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 truncate"
                            >
                                {{ admin.description }}
                            </p>
                        </a>
                    </div>
                </div>

                <!-- Sin resultados -->
                <div
                    v-if="!totalResults && searchTerm"
                    class="text-center py-12"
                >
                    <div
                        class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-3"
                    >
                        <i
                            class="mdi mdi-magnify-off text-slate-400 dark:text-slate-500 text-2xl"
                        ></i>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __("No se encontraron resultados para") }}
                        <span
                            class="font-medium text-slate-700 dark:text-slate-300"
                            >"{{ searchTerm }}"</span
                        >
                    </p>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const searchTerm = ref("");

const user_routes = ref([]);
const admin_routes = ref([]);
const top_admin_routes = ref([]);
const user_settings = ref([]);
const admin_settings = ref([]);

const filtered_apps = computed(() => {
    if (!searchTerm.value.trim()) return user_routes.value;
    const term = searchTerm.value.toLowerCase();
    return user_routes.value.filter(
        (app) =>
            app.name.toLowerCase().includes(term) ||
            (app.description && app.description.toLowerCase().includes(term)),
    );
});

const filtered_admin = computed(() => {
    if (!searchTerm.value.trim()) return admin_routes.value;
    const term = searchTerm.value.toLowerCase();
    return admin_routes.value.filter(
        (admin) =>
            admin.name.toLowerCase().includes(term) ||
            (admin.description &&
                admin.description.toLowerCase().includes(term)),
    );
});

const filtered_top_admin = computed(() => {
    if (!searchTerm.value.trim()) return top_admin_routes.value;
    const term = searchTerm.value.toLowerCase();
    return top_admin_routes.value.filter(
        (admin) =>
            admin.name.toLowerCase().includes(term) ||
            (admin.description &&
                admin.description.toLowerCase().includes(term)),
    );
});

const filtered_settings = computed(() => {
    if (!searchTerm.value.trim())
        return [...user_settings.value, ...admin_settings.value];
    const term = searchTerm.value.toLowerCase();
    const allSettings = [...user_settings.value, ...admin_settings.value];
    return allSettings.filter(
        (setting) =>
            setting.name.toLowerCase().includes(term) ||
            (setting.description &&
                setting.description.toLowerCase().includes(term)),
    );
});

const totalResults = computed(() => {
    return (
        filtered_apps.value.length +
        filtered_admin.value.length +
        filtered_top_admin.value.length +
        filtered_settings.value.length
    );
});

onMounted(() => {
    user_routes.value = page.props.user_routes || [];
    admin_routes.value = page.props.admin_routes || [];
    top_admin_routes.value = page.props.admin_dashboard || [];
    user_settings.value = page.props.user_settings || [];
    admin_settings.value = page.props.admin_settings || [];
});

const highlightMatch = (text, color = "indigo") => {
    if (!searchTerm.value || !text) return text;
    const regex = new RegExp(`(${searchTerm.value})`, "gi");
    const colorMap = {
        emerald:
            "bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200",
        blue: "bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200",
        purple: "bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-200",
        rose: "bg-rose-100 dark:bg-rose-900/40 text-rose-800 dark:text-rose-200",
        indigo: "bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-200",
    };
    const highlightClass = colorMap[color] || colorMap.indigo;
    return text.replace(
        regex,
        `<mark class="${highlightClass} rounded px-0.5">$1</mark>`,
    );
};
</script>
