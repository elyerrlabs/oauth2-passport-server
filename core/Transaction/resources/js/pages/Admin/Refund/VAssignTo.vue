<template>
    <div>
        <button
            @click="toggle"
            class="px-4 py-2 bg-linear-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-lg transition-all duration-200 cursor-pointer flex items-center shadow-sm hover:shadow"
            :disabled="loading"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
        >
            <i class="fas fa-user-plus mr-2"></i>
            {{ __("Assign Reviewer") }}
            <i v-if="loading" class="fas fa-spinner fa-spin ml-2"></i>
        </button>

        <v-modal
            v-model="dialog"
            :title="__('Assign Review Responsibility')"
            panel-class="w-full lg:w-2xl"
            :loading="loading"
        >
            <template #body>
                <div class="p-6 space-y-6">
                    <!-- Context Information -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700"
                    >
                        <div class="flex items-start gap-3">
                            <i
                                class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5"
                            ></i>
                            <div class="text-sm">
                                <p
                                    class="font-medium text-blue-800 dark:text-blue-300 mb-1"
                                >
                                    {{ __("What does this do?") }}
                                </p>
                                <p class="text-blue-700 dark:text-blue-400">
                                    {{
                                        __(
                                            "Assigning a user will make them responsible for reviewing and processing this refund request. They will receive notifications and have full access to manage this case."
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Assignment Status -->
                    <div
                        v-if="props.item?.assigned_to"
                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Currently Assigned To") }}
                            </span>
                            <span
                                class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded"
                            >
                                {{ __("Assigned") }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-user text-blue-600 dark:text-blue-400"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ props.item.assigned_to.name }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ props.item.assigned_to.email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- User Selection -->
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                {{ __("Select Team Member") }}
                                <span class="text-red-500">*</span>
                            </label>
                            <v-select
                                v-model="form.assigned_to"
                                :options="users"
                                searchable
                                @search="listUsers"
                                :error="form.errors.assigned_to"
                                clearable
                                label-key="name"
                                label-value="id"
                                :placeholder="__('Search by name ...')"
                            />
                            <p
                                v-if="form.errors.assigned_to"
                                class="mt-1 text-sm text-red-600 dark:text-red-400"
                            >
                                {{ form.errors.assigned_to }}
                            </p>
                            <p
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{
                                    __(
                                        "Select a team member to assign responsibility for this refund review"
                                    )
                                }}
                            </p>
                        </div>

                        <!-- User Count -->
                        <div
                            v-if="users.length > 0"
                            class="text-xs text-gray-500 dark:text-gray-400"
                        >
                            {{ __("Showing") }} {{ users.length }}
                            {{ __("available team members") }}
                        </div>
                        <div
                            v-else-if="searching"
                            class="text-xs text-gray-500 dark:text-gray-400"
                        >
                            <i class="fas fa-spinner fa-spin mr-1"></i>
                            {{ __("Searching for team members...") }}
                        </div>
                        <div
                            v-else
                            class="text-xs text-gray-500 dark:text-gray-400"
                        >
                            {{
                                __(
                                    "No team members found. Try a different search."
                                )
                            }}
                        </div>
                    </div>

                    <!-- Unassign Option -->
                    <div
                        v-if="props.item?.assigned_to"
                        class="border-t border-gray-200 dark:border-gray-700 pt-4"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                >
                                    {{ __("Need to remove assignment?") }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        __(
                                            "Leave the field empty and click 'Update Assignment' to unassign."
                                        )
                                    }}
                                </p>
                            </div>
                            <button
                                @click="form.assigned_to = ''"
                                class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors cursor-pointer"
                            >
                                {{ __("Clear Selection") }}
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button
                            @click="assignTo"
                            :disabled="loading || form.processing"
                            class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 cursor-pointer flex items-center justify-center flex-1 shadow-sm hover:shadow"
                            :class="{
                                'opacity-50 cursor-not-allowed':
                                    loading || form.processing,
                            }"
                        >
                            <i class="fas fa-user-check mr-2"></i>
                            {{ __("Update Assignment") }}
                            <i
                                v-if="form.processing"
                                class="fas fa-spinner fa-spin ml-2"
                            ></i>
                        </button>

                        <button
                            @click="dialog = false"
                            :disabled="loading || form.processing"
                            class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center flex-1"
                        >
                            <i class="fas fa-times mr-2"></i>
                            {{ __("Cancel") }}
                        </button>
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    item: Object,
});

const emits = defineEmits(["assigned"]);

const dialog = ref(false);
const loading = ref(false);
const searching = ref(false);
const users = ref([]);

const form = useForm({
    assigned_to: "",
});

const toggle = async () => {
    dialog.value = !dialog.value;
    if (dialog.value) {
        await listUsers();
        form.assigned_to = props.item?.assigned_to?.id || "";
    }
};

const assignTo = () => {
    if (form.processing) return;

    // Validate if selection has changed
    const currentUserId = props.item?.assigned_to?.id;
    const newUserId = form.assigned_to;

    if (currentUserId === newUserId) {
        $notify.info(
            __(
                "No changes detected. Please select a different user or clear the assignment."
            )
        );
        return;
    }

    form.put(props.item.web.assignTo, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            if (form.assigned_to) {
                $notify.success(
                    __("Refund request successfully assigned to team member!")
                );
            } else {
                $notify.success(__("Assignment successfully removed!"));
            }
            emits("assigned");
            dialog.value = false;
            form.reset();
        },
        onError: () => {
            $notify.error(__("Failed to update assignment. Please try again."));
        },
    });
};

const listUsers = async (text = "") => {
    searching.value = true;
    try {
        const res = await $server.get("/transaction/admin/refunds/list/users", {
            params: {
                name: text,
                per_page: 20,
            },
        });

        if (res.status == 200) {
            users.value = res.data.data;
        }
    } catch (err) {}
};
</script>

<style scoped>
/* Custom styles if needed */
button:disabled {
    cursor: not-allowed;
}
</style>
