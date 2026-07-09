<template>
    <v-main-layout>
        <!-- Header con diseño empresarial -->
        <header
            class="bg-linear-to-r from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 border-b border-slate-200/60 dark:border-slate-800/60 sticky top-0 z-20 backdrop-blur-sm bg-white/90 dark:bg-slate-950/90"
        >
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Brand -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/25"
                            >
                                <i
                                    class="mdi mdi-vector-square text-white text-sm"
                                ></i>
                            </div>
                            <span
                                class="text-sm font-semibold text-slate-700 dark:text-slate-200 hidden sm:block"
                                >{{ __('Dashboard') }}</span
                            >
                        </div>
                    </div>

                    <!-- Search Bar unificada -->
                    <div class="flex-1 max-w-2xl mx-4">
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <i
                                    class="mdi mdi-magnify text-slate-400 group-focus-within:text-indigo-500 dark:group-focus-within:text-indigo-400 text-base transition-all duration-300"
                                ></i>
                            </div>
                            <input
                                v-model="searchTerm"
                                type="text"
                                :placeholder="
                                    __(
                                        'Search applications, settings, administration...',
                                    )
                                "
                                class="w-full pl-11 pr-12 py-2.5 bg-white dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/80 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-300 outline-none text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 shadow-sm hover:shadow-md"
                                @keyup.escape="searchTerm = ''"
                                @input="handleSearch"
                            />
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1.5"
                            >
                                <kbd
                                    class="text-[10px] text-slate-400 dark:text-slate-500 font-mono bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-600"
                                >
                                    ⌘K
                                </kbd>
                                <button
                                    v-if="searchTerm"
                                    @click="clearSearch"
                                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
                                >
                                    <i class="mdi mdi-close text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="flex items-center gap-2"></div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div
            class="px-4 sm:px-6 lg:px-8 py-8 bg-linear-to-b from-slate-50/50 to-white dark:from-slate-950/30 dark:to-slate-950 min-h-screen"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Results counter -->
                <div
                    v-if="searchTerm"
                    class="mb-6 flex items-center justify-between bg-white dark:bg-slate-900/50 rounded-xl px-4 py-3 border border-slate-200/60 dark:border-slate-800/60 shadow-sm"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-indigo-50 dark:bg-indigo-950/30 rounded-lg flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-file-search-outline text-indigo-600 dark:text-indigo-400 text-sm"
                            ></i>
                        </div>
                        <div>
                            <span
                                class="text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                {{ totalResults }} {{ __("results found") }}
                            </span>
                            <span
                                v-if="searchTerm"
                                class="text-xs text-slate-400 dark:text-slate-500 ml-2"
                            >
                                {{ __("for") }} "{{ searchTerm }}"
                            </span>
                        </div>
                    </div>
                    <button
                        @click="clearSearch"
                        class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition-all"
                    >
                        <i class="mdi mdi-close-circle-outline text-sm"></i>
                        {{ __("Clear search") }}
                    </button>
                </div>

                <!-- Renderizado dinámico de secciones -->
                <div
                    v-for="section in filteredSections"
                    :key="section.key"
                    class="mb-10"
                >
                    <!-- Solo mostrar sección si tiene items -->
                    <template v-if="section.items.length > 0">
                        <!-- Encabezado de sección -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-1.5 h-6 rounded-full"
                                    :class="`bg-linear-to-b ${section.color.from} ${section.color.to}`"
                                ></div>
                                <h3
                                    class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                                >
                                    {{ __(section.title) }}
                                </h3>
                                <span
                                    class="text-[10px] font-medium px-2.5 py-0.5 rounded-full border"
                                    :class="section.badge.class"
                                >
                                    {{ __(section.badge.text) }} 
                                </span>
                            </div>
                            <span
                                class="text-xs text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-full font-medium"
                            >
                                {{ section.items.length }}
                            </span>
                        </div>

                        <!-- Grid de items -->
                        <div class="grid gap-4" :class="section.gridClass">
                            <a
                                v-for="item in section.items"
                                :key="item.id"
                                :href="item.route"
                                class="group bg-white dark:bg-slate-900/80 rounded-2xl border border-slate-200/60 dark:border-slate-800/60 transition-all duration-300 relative"
                                :class="section.itemClass"
                            >
                                <!-- Badge flotante -->
                                <div
                                    class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                >
                                    <span
                                        class="text-[8px] font-bold uppercase text-white px-2 py-0.5 rounded-full shadow-lg"
                                        :class="section.badge.bg"
                                    >
                                        {{ __(section.badge.text) }}
                                    </span>
                                </div>

                                <!-- Contenido según tipo -->
                                <div
                                    v-if="section.type === 'grid'"
                                    class="p-4 text-center"
                                >
                                    <div
                                        class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:shadow-lg transition-all duration-300 mb-2"
                                        :class="section.iconBg"
                                    >
                                        <i
                                            :class="[
                                                item.icon ||
                                                    section.defaultIcon,
                                                'text-3xl transition-all duration-300',
                                                section.iconColor,
                                            ]"
                                        ></i>
                                    </div>
                                    <span
                                        class="text-xs font-medium text-slate-700 dark:text-slate-300 leading-tight transition-colors"
                                        :class="section.textHover"
                                        v-html="
                                            searchTerm
                                                ? highlightMatch(
                                                      __(item.name),
                                                      section.colorName,
                                                  )
                                                : __(item.name)
                                        "
                                    >
                                    </span>
                                    <div
                                        class="mt-1.5 w-full h-0.5 bg-linear-to-r from-transparent via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                        :class="`group-hover:via-${section.colorName}-200 dark:group-hover:via-${section.colorName}-800`"
                                    ></div>
                                </div>

                                <div v-else class="p-4 flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:shadow-lg transition-all duration-300"
                                        :class="section.iconBg"
                                    >
                                        <i
                                            :class="[
                                                item.icon ||
                                                    section.defaultIcon,
                                                'text-xl transition-all duration-300',
                                                section.iconColor,
                                            ]"
                                        ></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span
                                            class="text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors"
                                            :class="section.textHover"
                                            v-html="
                                                searchTerm
                                                    ? highlightMatch(
                                                          __(item.name),
                                                          section.colorName,
                                                      )
                                                    : __(item.name)
                                            "
                                        >
                                        </span>
                                    </div>
                                    <i
                                        class="mdi mdi-chevron-right text-slate-300 dark:text-slate-600 text-lg transition-all duration-300 group-hover:translate-x-1"
                                        :class="section.chevronColor"
                                    ></i>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>

                <!-- No results -->
                <div
                    v-if="!totalResults && searchTerm"
                    class="text-center py-16"
                >
                    <div
                        class="w-20 h-20 mx-auto bg-linear-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 rounded-2xl flex items-center justify-center mb-4 shadow-inner"
                    >
                        <i
                            class="mdi mdi-magnify-off text-slate-400 dark:text-slate-500 text-3xl"
                        ></i>
                    </div>
                    <h4
                        class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-1"
                    >
                        {{ __("No results found") }}
                    </h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __("We couldn't find any results for") }}
                        <span
                            class="font-medium text-slate-700 dark:text-slate-300"
                            >"{{ searchTerm }}"</span
                        >
                    </p>
                    <button
                        @click="clearSearch"
                        class="mt-4 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium flex items-center gap-2 mx-auto px-4 py-2 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition-all"
                    >
                        <i class="mdi mdi-arrow-left"></i>
                        {{ __("Clear search and go back") }}
                    </button>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const searchTerm = ref("");
const allItems = ref([]);

// Configuración de secciones
const sectionConfigs = {
    applications: {
        key: "applications",
        title: "Applications",
        type: "grid",
        colorName: "emerald",
        color: { from: "from-emerald-400", to: "to-emerald-600" },
        badge: {
            text: "User",
            class: "text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800",
            bg: "bg-emerald-500",
        },
        gridClass: "grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6",
        itemClass:
            "hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-xl hover:shadow-emerald-500/10 dark:hover:shadow-emerald-500/5",
        iconBg: "bg-linear-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-950/30 dark:to-emerald-900/20",
        iconColor:
            "text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300",
        textHover:
            "group-hover:text-emerald-700 dark:group-hover:text-emerald-300",
        defaultIcon: "mdi mdi-application",
    },
    administration: {
        key: "administration",
        title: "Administration",
        type: "grid",
        colorName: "blue",
        color: { from: "from-blue-400", to: "to-blue-600" },
        badge: {
            text: "Admin",
            class: "text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/40 border-blue-200 dark:border-blue-800",
            bg: "bg-blue-500",
        },
        gridClass: "grid-cols-2 sm:grid-cols-3 lg:grid-cols-6",
        itemClass:
            "hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-xl hover:shadow-blue-500/10 dark:hover:shadow-blue-500/5",
        iconBg: "bg-linear-to-br from-blue-50 to-blue-100/50 dark:from-blue-950/30 dark:to-blue-900/20",
        iconColor:
            "text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300",
        textHover: "group-hover:text-blue-700 dark:group-hover:text-blue-300",
        defaultIcon: "mdi mdi-shield",
    },
    userSettings: {
        key: "userSettings",
        title: "User Settings",
        type: "list",
        colorName: "purple",
        color: { from: "from-purple-400", to: "to-purple-600" },
        badge: {
            text: "Settings",
            class: "text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800",
            bg: "bg-purple-500",
        },
        gridClass: "grid-cols-1 sm:grid-cols-2 lg:grid-cols-4",
        itemClass:
            "hover:border-purple-300 dark:hover:border-purple-700 hover:shadow-xl hover:shadow-purple-500/10 dark:hover:shadow-purple-500/5",
        iconBg: "bg-linear-to-br from-purple-50 to-purple-100/50 dark:from-purple-950/30 dark:to-purple-900/20",
        iconColor:
            "text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300",
        textHover:
            "group-hover:text-purple-700 dark:group-hover:text-purple-300",
        chevronColor:
            "group-hover:text-purple-600 dark:group-hover:text-purple-400",
        defaultIcon: "mdi mdi-cog",
    },
    adminSettings: {
        key: "adminSettings",
        title: "Admin Settings",
        type: "list",
        colorName: "amber",
        color: { from: "from-amber-400", to: "to-amber-600" },
        badge: {
            text: "Admin Settings",
            class: "text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800",
            bg: "bg-amber-500",
        },
        gridClass: "grid-cols-1 sm:grid-cols-2 lg:grid-cols-4",
        itemClass:
            "hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-xl hover:shadow-amber-500/10 dark:hover:shadow-amber-500/5",
        iconBg: "bg-linear-to-br from-amber-50 to-amber-100/50 dark:from-amber-950/30 dark:to-amber-900/20",
        iconColor:
            "text-amber-600 dark:text-amber-400 group-hover:text-amber-700 dark:group-hover:text-amber-300",
        textHover: "group-hover:text-amber-700 dark:group-hover:text-amber-300",
        chevronColor:
            "group-hover:text-amber-600 dark:group-hover:text-amber-400",
        defaultIcon: "mdi mdi-cog-box",
    },
    topAdmin: {
        key: "topAdmin",
        title: "Top Level Admin Panel",
        type: "grid",
        colorName: "rose",
        color: { from: "from-rose-400", to: "to-rose-600" },
        badge: {
            text: "Root",
            class: "text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800",
            bg: "bg-rose-500",
        },
        gridClass: "grid-cols-2 sm:grid-cols-3 lg:grid-cols-6",
        itemClass:
            "border-2 border-rose-200/60 dark:border-rose-800/60 hover:border-rose-400 dark:hover:border-rose-600 hover:shadow-2xl hover:shadow-rose-500/20 dark:hover:shadow-rose-500/10",
        iconBg: "bg-linear-to-br from-rose-50 to-rose-100/50 dark:from-rose-950/30 dark:to-rose-900/20",
        iconColor:
            "text-rose-600 dark:text-rose-400 group-hover:text-rose-700 dark:group-hover:text-rose-300",
        textHover: "group-hover:text-rose-700 dark:group-hover:text-rose-300",
        defaultIcon: "mdi mdi-shield-crown",
    },
};

// Función de búsqueda mejorada - busca en todo el texto
const searchInText = (text, searchTerm) => {
    if (!text || !searchTerm) return false;
    const normalizedText = text.toLowerCase().trim();
    const normalizedSearch = searchTerm.toLowerCase().trim();
    return normalizedText.includes(normalizedSearch);
};

// Función de filtrado mejorada - busca en múltiples campos
const filterItems = (items, term) => {
    if (!term.trim() || !items) return items || [];
    const normalizedTerm = term.toLowerCase().trim();

    return items.filter((item) => {
        // Buscar en nombre
        if (item.name && searchInText(item.name, normalizedTerm)) {
            return true;
        }
        // Buscar en descripción
        if (
            item.description &&
            searchInText(item.description, normalizedTerm)
        ) {
            return true;
        }
        // Buscar en tags
        if (item.tags && Array.isArray(item.tags)) {
            return item.tags.some((tag) => searchInText(tag, normalizedTerm));
        }
        // Buscar en cualquier propiedad de texto del objeto
        for (const key in item) {
            if (
                typeof item[key] === "string" &&
                searchInText(item[key], normalizedTerm)
            ) {
                return true;
            }
        }
        return false;
    });
};

// Secciones filtradas - computada reactiva
const filteredSections = computed(() => {
    const sections = [];
    const term = searchTerm.value;

    // Recorrer cada sección configurada
    Object.keys(sectionConfigs).forEach((key) => {
        const config = sectionConfigs[key];
        const items = allItems.value[key] || [];

        // Filtrar items según búsqueda
        const filteredItems = filterItems(items, term);

        // Crear sección solo si tiene items o no hay búsqueda
        if (filteredItems.length > 0 || !term) {
            sections.push({
                ...config,
                items: filteredItems,
                key: key,
            });
        }
    });

    return sections;
});

// Total de resultados
const totalResults = computed(() => {
    return filteredSections.value.reduce(
        (total, section) => total + section.items.length,
        0,
    );
});

// Limpiar búsqueda
const clearSearch = () => {
    searchTerm.value = "";
    // Enfocar el input después de limpiar
    const searchInput = document.querySelector('input[type="text"]');
    if (searchInput) {
        searchInput.focus();
    }
};

// Manejar búsqueda en tiempo real
const handleSearch = () => {
    // Forzar actualización reactiva
    // No es necesario hacer nada más, Vue se encarga automáticamente
};

// Keyboard shortcut
const handleKeyboard = (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === "k") {
        e.preventDefault();
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    if (e.key === "Escape" && searchTerm.value) {
        clearSearch();
    }
};

// Cargar datos desde props
onMounted(() => {
    allItems.value = {
        applications: page.props.user_routes || [],
        administration: page.props.admin_routes || [],
        topAdmin: page.props.admin_dashboard || [],
        userSettings: page.props.user_settings || [],
        adminSettings: page.props.admin_settings || [],
    };

    document.addEventListener("keydown", handleKeyboard);
});

// Watcher para debug
watch(searchTerm, (newVal) => {
    console.log("Buscando:", newVal);
    console.log(
        "Resultados:",
        filteredSections.value.map((s) => ({
            section: s.key,
            count: s.items.length,
            items: s.items.map((i) => i.name),
        })),
    );
});

// Highlight mejorado
const highlightMatch = (text, color = "indigo") => {
    if (!searchTerm.value || !text) return text;
    const regex = new RegExp(
        `(${searchTerm.value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")})`,
        "gi",
    );
    const colorMap = {
        emerald:
            "bg-emerald-100/80 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-200 font-semibold px-0.5 rounded",
        blue: "bg-blue-100/80 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 font-semibold px-0.5 rounded",
        purple: "bg-purple-100/80 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200 font-semibold px-0.5 rounded",
        rose: "bg-rose-100/80 dark:bg-rose-900/50 text-rose-800 dark:text-rose-200 font-semibold px-0.5 rounded",
        amber: "bg-amber-100/80 dark:bg-amber-900/50 text-amber-800 dark:text-amber-200 font-semibold px-0.5 rounded",
        indigo: "bg-indigo-100/80 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-200 font-semibold px-0.5 rounded",
    };
    const highlightClass = colorMap[color] || colorMap.indigo;
    return text.replace(regex, `<mark class="${highlightClass}">$1</mark>`);
};
</script>

<style scoped>
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.group:hover {
    transform: translateY(-2px);
}

::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark ::-webkit-scrollbar-thumb {
    background: #475569;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>
