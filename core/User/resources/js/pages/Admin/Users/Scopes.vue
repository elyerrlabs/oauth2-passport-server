<template>
    <v-main-layout>
        <v-head :title="__('Manage access scopes')">
            <template #actions>
                <v-button
                    as="a"
                    :to="user?.links?.index"
                    variant="success"
                    :label="__('Back to the users')"
                />

                <v-button
                    as="a"
                    :to="user?.links?.show"
                    variant="primary"
                    :label="__('Show User detail')"
                />
            </template>
            <template #bottom>
                <div
                    class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-t-lg"
                >
                    <div class="flex items-center gap-3">
                        <i
                            class="mdi mdi-shield-account text-blue-600 dark:text-blue-400 text-xl"
                        ></i>
                        <div>
                            <span
                                class="text-sm text-gray-500 dark:text-gray-400"
                                >{{ __("User") }}:</span
                            >
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white ml-1"
                            >
                                {{ user.name }} {{ user.last_name }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-folder-multiple-outline mr-1"></i>
                            {{ totalGroups }} {{ __("groups") }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-check-circle-outline mr-1"></i>
                            {{ assignedScopesCount }}/{{ totalRoles }}
                            {{ __("assigned") }}
                        </span>
                    </div>
                </div>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <v-select
                        :label="__('Groups')"
                        v-model="search.group_id"
                        :options="groups"
                        label-key="name"
                        value-key="id"
                        searchable
                        @search="getGroups"
                        @change="searching"
                    />

                    <v-select
                        :label="__('Services')"
                        v-model="search.service_id"
                        :options="services"
                        label-key="name"
                        value-key="id"
                        searchable
                        @search="getServices"
                        @change="searching"
                    />

                    <v-select
                        :label="__('Roles')"
                        v-model="search.role_id"
                        :options="roles"
                        label-key="name"
                        value-key="id"
                        searchable
                        @search="getRoles"
                        @change="searching"
                    />

                    <v-select
                        :label="__('Choose pagination')"
                        v-model="search.per_page"
                        @change="getScopes"
                        :options="[
                            { name: 15, id: 15 },
                            { name: 50, id: 50 },
                            { name: 100, id: 100 },
                            { name: 150, id: 150 },
                            { name: 200, id: 200 },
                            { name: 300, id: 300 },
                            { name: 300, id: 300 },
                        ]"
                    />

                    <v-button
                        :label="__('Reset filters')"
                        @click="resetFilters"
                        icon="mdi mdi-relad"
                    />
                </div>
            </template>
        </v-head>

        <div>
            <!-- Content -->
            <div class="p-6">
                <!-- Loading -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-12"
                >
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg
                            class="animate-spin h-5 w-5 text-blue-600"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <span class="text-sm">{{ __("Loading...") }}</span>
                    </div>
                </div>

                <!-- Empty -->
                <div
                    v-else-if="!showSkeleton && scopes.length === 0"
                    class="text-center py-12"
                >
                    <i
                        class="mdi mdi-shield-off-outline text-gray-400 text-3xl mb-3"
                    ></i>
                    <p class="text-sm text-gray-500">
                        {{ __("No permissions available") }}
                    </p>
                </div>

                <!-- Groups -->
                <div v-else class="space-y-4 overflow-y-auto pr-1">
                    <div
                        v-for="(group, groupIndex) in groupedScopes"
                        :key="groupIndex"
                        class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800 shadow-sm"
                    >
                        <!-- Group Header - Nivel 1 -->
                        <div
                            @click="toggleGroup(groupIndex)"
                            class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors bg-gray-50 dark:bg-gray-800/50"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="flex items-center justify-center w-8 h-8 rounded-md bg-blue-100 dark:bg-blue-900/30"
                                >
                                    <i
                                        class="mdi mdi-folder-account text-blue-600 dark:text-blue-400 text-lg"
                                    ></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider"
                                        >
                                            {{ __("Group") }}
                                        </span>
                                        <span
                                            class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                        >
                                            {{ __(group.name) }}
                                        </span>
                                    </div>
                                    <p
                                        v-if="group.description"
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                    >
                                        {{ __(group.description) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700"
                                >
                                    <i
                                        class="mdi mdi-check-circle-outline text-green-600 dark:text-green-400 text-xs"
                                    ></i>
                                    <span
                                        class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        {{ getAssignedCountForGroup(group) }}/{{
                                            getTotalRolesCountForGroup(group)
                                        }}
                                    </span>
                                </div>
                                <i
                                    class="mdi text-gray-400 text-xl transition-transform duration-200 shrink-0"
                                    :class="
                                        expandedGroups[groupIndex]
                                            ? 'mdi-chevron-up'
                                            : 'mdi-chevron-down'
                                    "
                                ></i>
                            </div>
                        </div>

                        <!-- Progress bar -->
                        <div class="h-1 bg-gray-100 dark:bg-gray-700">
                            <div
                                class="h-full bg-linear-to-r from-green-500 to-green-400 transition-all duration-300"
                                :style="{
                                    width: `${getGroupProgress(group)}%`,
                                }"
                            ></div>
                        </div>

                        <!-- Services - Nivel 2 -->
                        <transition name="slide">
                            <div
                                v-show="expandedGroups[groupIndex]"
                                class="border-t border-gray-100 dark:border-gray-700"
                            >
                                <div
                                    class="p-3 space-y-3 bg-gray-50/30 dark:bg-gray-900/20"
                                >
                                    <div
                                        v-for="(
                                            service, serviceIndex
                                        ) in group.services"
                                        :key="serviceIndex"
                                        class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800 shadow-sm"
                                    >
                                        <!-- Service Header -->
                                        <div
                                            @click="
                                                toggleService(
                                                    groupIndex,
                                                    serviceIndex,
                                                )
                                            "
                                            class="flex items-center justify-between px-3 py-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors bg-gray-50/50 dark:bg-gray-800/30"
                                        >
                                            <div
                                                class="flex items-center gap-3 min-w-0"
                                            >
                                                <div
                                                    class="flex items-center justify-center w-7 h-7 rounded-md bg-purple-100 dark:bg-purple-900/30"
                                                >
                                                    <i
                                                        class="mdi mdi-cog text-purple-600 dark:text-purple-400 text-sm"
                                                    ></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <span
                                                            class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider"
                                                        >
                                                            {{ __("Service") }}
                                                        </span>
                                                        <span
                                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"
                                                        >
                                                            {{
                                                                __(service.name)
                                                            }}
                                                        </span>
                                                    </div>
                                                    <p
                                                        v-if="
                                                            service.description
                                                        "
                                                        class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                                    >
                                                        {{
                                                            __(
                                                                service.description,
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    {{
                                                        getAssignedCountForService(
                                                            service,
                                                        )
                                                    }}/{{
                                                        service.roles.length
                                                    }}
                                                </span>
                                                <i
                                                    class="mdi text-gray-400 text-lg transition-transform duration-200 shrink-0"
                                                    :class="
                                                        expandedServices[
                                                            `${groupIndex}-${serviceIndex}`
                                                        ]
                                                            ? 'mdi-chevron-up'
                                                            : 'mdi-chevron-down'
                                                    "
                                                ></i>
                                            </div>
                                        </div>

                                        <!-- Roles - Nivel 3 -->
                                        <transition name="slide">
                                            <div
                                                v-show="
                                                    expandedServices[
                                                        `${groupIndex}-${serviceIndex}`
                                                    ]
                                                "
                                                class="border-t border-gray-100 dark:border-gray-700"
                                            >
                                                <div
                                                    class="p-3 bg-gray-50/20 dark:bg-gray-900/10"
                                                >
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-2 gap-2"
                                                    >
                                                        <div
                                                            v-for="role in service.roles"
                                                            :key="role.id"
                                                            class="flex items-center justify-between p-2 rounded-lg transition-all duration-200"
                                                            :class="{
                                                                'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 shadow-sm':
                                                                    findScope(
                                                                        role
                                                                            ?.scope
                                                                            ?.id,
                                                                    ),
                                                                'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md':
                                                                    !findScope(
                                                                        role
                                                                            ?.scope
                                                                            ?.id,
                                                                    ),
                                                            }"
                                                        >
                                                            <div
                                                                class="min-w-0 flex-1 mr-2"
                                                            >
                                                                <div
                                                                    class="flex items-center gap-2"
                                                                >
                                                                    <i
                                                                        class="mdi mdi-shield-account-outline text-gray-400 text-sm"
                                                                    ></i>
                                                                    <span
                                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                                                    >
                                                                        {{
                                                                            __(
                                                                                role.name,
                                                                            )
                                                                        }}
                                                                    </span>
                                                                </div>
                                                                <p
                                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1"
                                                                >
                                                                    {{
                                                                        __(
                                                                            role.description,
                                                                        )
                                                                    }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="shrink-0"
                                                            >
                                                                <v-add-scope
                                                                    :item="role"
                                                                    @created="
                                                                        UserScopes
                                                                    "
                                                                    v-if="
                                                                        !findScope(
                                                                            role
                                                                                ?.scope
                                                                                ?.id,
                                                                        )
                                                                    "
                                                                />
                                                                <v-delete-scope
                                                                    @deleted="
                                                                        UserScopes
                                                                    "
                                                                    :item="
                                                                        findScope(
                                                                            role
                                                                                ?.scope
                                                                                ?.id,
                                                                        )
                                                                    "
                                                                    v-else
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="flex items-center justify-between px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg"
            >
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __("Click on groups or services to expand/collapse") }}
                </div>
                <v-button
                    @click="toggleAll"
                    variant="ghost"
                    size="xs"
                    :icon="
                        allExpanded
                            ? 'mdi mdi-arrow-collapse-all'
                            : 'mdi mdi-arrow-expand-all'
                    "
                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                >
                    {{ allExpanded ? __("Collapse All") : __("Expand All") }}
                </v-button>
            </div>

            <v-paginate
                v-model="search.page"
                :total-pages="pages.total_pages"
                @change="getScopes"
            />
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VButton from "@/components/VButton.vue";
import VDeleteScope from "./VDeleteScope.vue";
import VAddScope from "./VAddScope.vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VHead from "@/components/VHead.vue";
import VPaginate from "@/components/VPaginate.vue";
import VSelect from "@/components/VSelect.vue";

const props = defineProps({
    user: { type: Object, required: true },
    data: { type: Object, required: true },
});

const page = usePage();

const loading = ref(false);
const showSkeleton = ref(true);
const scopes = ref([]);
const roles = ref([]);
const groups = ref([]);
const services = ref([]);
const user_scopes = ref([]);
const expandedGroups = ref({});
const expandedServices = ref({});
const search = ref({
    page: 1,
    per_page: 50,
    role_id: "",
    service_id: "",
    group_id: "",
});

const pages = ref({
    total_pages: 0,
});
const form = useForm({});

const totalGroups = computed(() => groupedScopes.value.length);

const totalRoles = computed(() => {
    return groupedScopes.value.reduce(
        (acc, group) =>
            acc +
            group.services.reduce(
                (sAcc, service) => sAcc + service.roles.length,
                0,
            ),
        0,
    );
});

const assignedScopesCount = computed(() => user_scopes.value.length);

const allExpanded = computed(() => {
    const groupsExpanded = Object.values(expandedGroups.value).every((v) => v);
    const servicesExpanded = Object.values(expandedServices.value).every(
        (v) => v,
    );
    return groupsExpanded && servicesExpanded && groupedScopes.value.length > 0;
});

onMounted(async () => {
    loading.value = true;

    await getScopes();
    await getRoles();
    await getServices();
    await getGroups();

    user_scopes.value = page.props.data.data;

    // Expandir todos los grupos por defecto
    if (groupedScopes.value.length > 0) {
        groupedScopes.value.forEach((_, index) => {
            expandedGroups.value[index] = true;
            // También expandir el primer servicio de cada grupo
            if (groupedScopes.value[index].services.length > 0) {
                groupedScopes.value[index].services.forEach(
                    (_, serviceIndex) => {
                        expandedServices.value[`${index}-${serviceIndex}`] =
                            true;
                    },
                );
            }
        });
    }

    loading.value = false;
    showSkeleton.value = false;
});

const toggleGroup = (groupIndex) => {
    expandedGroups.value[groupIndex] = !expandedGroups.value[groupIndex];
};

const toggleService = (groupIndex, serviceIndex) => {
    const key = `${groupIndex}-${serviceIndex}`;
    expandedServices.value[key] = !expandedServices.value[key];
};

const toggleAll = () => {
    const newState = !allExpanded.value;
    groupedScopes.value.forEach((group, index) => {
        expandedGroups.value[index] = newState;
        group.services.forEach((_, serviceIndex) => {
            expandedServices.value[`${index}-${serviceIndex}`] = newState;
        });
    });
};

watch(
    () => search.value.group_id,
    async (group_id) => {
        await getServices();
    },
);

const resetFilters = async () => {
    search.value.page = 1;
    search.value.per_page = 50;
    search.value.role_id = "";
    search.value.service_id = "";
    search.value.group_id = "";

    await getScopes();
};

const searching = async () => {
    search.page = 1;

    await getScopes();
};

const UserScopes = () => {
    form.get(page.props.routes.scopes, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            user_scopes.value = res.props.data.data;
        },
        onError: (e) => {
            console.log(e);
        },
    });
};

const getScopes = async () => {
    try {
        const res = await $server.get(page.props.api.scopes, {
            params: search.value,
        });
        if (res.status === 200) {
            const values = res.data;
            scopes.value = values.data;
            pages.value = values.meta.pagination;
        }
    } catch (e) {
        console.log(e);
    }
};

const getRoles = async (name = "") => {
    try {
        const res = await $server.get(page.props.api.roles, {
            params: {
                per_page: 500,
                name: name,
            },
        });
        if (res.status === 200) {
            roles.value = res.data.data;
        }
    } catch (e) {
        console.log(e);
    }
};

const getServices = async (name = "") => {
    try {
        const res = await $server.get(page.props.api.services, {
            params: {
                per_page: 500,
                name: name,
                group_id: search.value.group_id,
            },
        });
        if (res.status === 200) {
            services.value = res.data.data;
        }
    } catch (e) {
        console.log(e);
    }
};

const getGroups = async (name = "") => {
    try {
        const res = await $server.get(page.props.api.groups, {
            params: {
                per_page: 500,
                name: name,
            },
        });
        if (res.status === 200) {
            groups.value = res.data.data;
        }
    } catch (e) {
        console.log(e);
    }
};

const findScope = (scopeId) => {
    if (!user_scopes.value.length) return null;
    return user_scopes.value.find((item) => item.scope.id == scopeId);
};

const getAssignedCountForGroup = (group) => {
    let count = 0;
    group.services.forEach((service) => {
        service.roles.forEach((role) => {
            if (findScope(role?.scope?.id)) count++;
        });
    });
    return count;
};

const getAssignedCountForService = (service) => {
    return service.roles.filter((role) => findScope(role?.scope?.id)).length;
};

const getTotalRolesCountForGroup = (group) => {
    return group.services.reduce(
        (acc, service) => acc + service.roles.length,
        0,
    );
};

const getGroupProgress = (group) => {
    const assigned = getAssignedCountForGroup(group);
    const total = getTotalRolesCountForGroup(group);
    return total > 0 ? (assigned / total) * 100 : 0;
};

const groupedScopes = computed(() => {
    const grouped = {};

    scopes.value.forEach((item) => {
        const { service, role } = item;
        const group = service.group;
        const groupName = group.name;
        const serviceName = service.name;

        const scopeData = {
            id: item.id,
            gsr_id: item.gsr_id,
            public: item.public,
            active: item.active,
            web: item.web,
            api_key: item.api_key,
            links: { assign: props.user.links.scopes },
        };

        const roleData = {
            id: role.id,
            name: role.name,
            slug: role.slug,
            description: role.description,
            scope: scopeData,
        };

        if (!grouped[groupName]) {
            grouped[groupName] = {
                name: groupName,
                description: group.description,
                services: {},
            };
        }

        if (!grouped[groupName].services[serviceName]) {
            grouped[groupName].services[serviceName] = {
                name: service.name,
                description: service.description,
                roles: [],
            };
        }

        grouped[groupName].services[serviceName].roles.push(roleData);
    });

    return Object.values(grouped).map((group) => ({
        ...group,
        services: Object.values(group.services),
    }));
});
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.2s ease;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
