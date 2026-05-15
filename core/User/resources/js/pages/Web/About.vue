<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
  <v-main-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900">
      <div class="px-6 py-8 lg:px-10">
        <div class="max-w-6xl mx-auto">
          <!-- Header Section with User Profile -->
          <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 dark:from-slate-200 dark:to-white rounded-xl flex items-center justify-center shadow-lg">
                  <span class="text-white dark:text-slate-900 font-semibold text-base">{{ userInitials }}</span>
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-900"></div>
              </div>
              <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ user.name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</p>
              </div>
            </div>
            <div class="flex items-center gap-6 text-sm">
              <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                <i class="mdi mdi-clock-outline text-lg"></i>
                <span>{{ user.last_connected }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ __('Online') }}</span>
              </div>
            </div>
          </div>

          <!-- Command Bar / Search -->
          <div class="mb-10">
            <div class="relative max-w-2xl mx-auto">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <i class="mdi mdi-magnify text-slate-400 text-xl"></i>
              </div>
              <input
                v-model="searchTerm"
                type="text"
                @input="filterApplications"
                :placeholder="__('Search applications, settings, or type a command...')"
                class="w-full pl-12 pr-32 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-300 focus:border-transparent transition-all duration-200 shadow-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 outline-none"
              />
              <div class="absolute inset-y-0 right-0 pr-4 flex items-center gap-2">
                <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">⌘</kbd>
                <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">K</kbd>
              </div>
            </div>
            <p v-if="searchTerm && filtered_apps.length" class="text-center text-xs text-slate-500 dark:text-slate-400 mt-2">
              {{ filtered_apps.length }} {{ __('result') }}{{ filtered_apps.length !== 1 ? 's' : '' }} {{ __('for') }} "{{ searchTerm }}"
            </p>
          </div>

          <!-- Tab Navigation -->
          <div class="border-b border-slate-200 dark:border-slate-800 mb-8">
            <nav class="flex gap-1">
              <button
                @click="activeTab = 'apps'"
                class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200"
                :class="activeTab === 'apps'
                  ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-x border-t border-slate-200 dark:border-slate-800'
                  : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
              >
                {{ __('Applications') }}
                <span class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ user_routes.length }}</span>
              </button>
              <button
                v-if="admin_routes.length"
                @click="activeTab = 'admin'"
                class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200"
                :class="activeTab === 'admin'
                  ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-x border-t border-slate-200 dark:border-slate-800'
                  : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
              >
                {{ __('Administration') }}
                <span class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ admin_routes.length }}</span>
              </button>
              <button
                @click="activeTab = 'settings'"
                class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200"
                :class="activeTab === 'settings'
                  ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-x border-t border-slate-200 dark:border-slate-800'
                  : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
              >
                {{ __('Settings') }}
                <span class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ totalSettings }}</span>
              </button>
            </nav>
          </div>

          <!-- Applications Tab -->
          <div v-if="activeTab === 'apps'" class="space-y-2">
            <div v-if="filtered_apps.length" class="grid gap-2">
              <a
                v-for="app in filtered_apps"
                :key="app.id"
                :href="app.route"
                class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
              >
                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors">
                  <i :class="[app.icon || 'mdi mdi-application', 'text-slate-500 dark:text-slate-400 group-hover:text-white dark:group-hover:text-slate-900 text-lg transition-colors']"></i>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-slate-900 dark:text-white" v-html="searchTerm ? highlightMatch(app.name) : app.name"></h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400">{{ app.description || __('Application') }}</p>
                </div>
                <div class="flex items-center gap-2 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                  <span class="text-sm">{{ __('Open') }}</span>
                  <i class="mdi mdi-arrow-right"></i>
                </div>
              </a>
            </div>
            <div v-else class="text-center py-16 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
              <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="mdi mdi-magnify text-slate-400 text-2xl"></i>
              </div>
              <p class="text-slate-500 dark:text-slate-400">{{ __('No applications found matching') }} "{{ searchTerm }}"</p>
            </div>
          </div>

          <!-- Administration Tab -->
          <div v-if="activeTab === 'admin' && admin_routes.length" class="grid gap-2">
            <a
              v-for="admin in admin_routes"
              :key="admin.id"
              :href="admin.route"
              class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
            >
              <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors">
                <i :class="[admin.icon || 'mdi mdi-shield', 'text-slate-500 dark:text-slate-400 group-hover:text-white dark:group-hover:text-slate-900 text-lg transition-colors']"></i>
              </div>
              <div class="flex-1">
                <h3 class="font-semibold text-slate-900 dark:text-white">{{ admin.name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ admin.description || __('Administration section') }}</p>
              </div>
              <div class="flex items-center gap-2 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                <span class="text-sm">{{ __('Access') }}</span>
                <i class="mdi mdi-arrow-right"></i>
              </div>
            </a>
          </div>

          <!-- Settings Tab -->
          <div v-if="activeTab === 'settings'" class="space-y-8">
            <!-- User Preferences -->
            <div v-if="user_settings.length">
              <div class="flex items-center gap-3 mb-4">
                <i class="mdi mdi-account-outline text-slate-400"></i>
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('User Preferences') }}</h2>
                <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
              </div>
              <div class="grid gap-2">
                <a
                  v-for="setting in user_settings"
                  :key="setting.id"
                  :href="setting.route"
                  class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
                >
                  <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                    <i :class="[setting.icon, 'text-slate-500 dark:text-slate-400 text-lg']"></i>
                  </div>
                  <div class="flex-1">
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ setting.name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ setting.description || __('Setting') }}</p>
                  </div>
                  <i class="mdi mdi-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-slate-900 dark:group-hover:text-white"></i>
                </a>
              </div>
            </div>

            <!-- System Administration -->
            <div v-if="admin_settings.length">
              <div class="flex items-center gap-3 mb-4">
                <i class="mdi mdi-cog-outline text-slate-400"></i>
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('System Administration') }}</h2>
                <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
              </div>
              <div class="grid gap-2">
                <a
                  v-for="setting in admin_settings"
                  :key="setting.id"
                  :href="setting.route"
                  class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
                >
                  <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                    <i :class="[setting.icon, 'text-slate-500 dark:text-slate-400 text-lg']"></i>
                  </div>
                  <div class="flex-1">
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ setting.name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ setting.description || __('System setting') }}</p>
                  </div>
                  <i class="mdi mdi-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-slate-900 dark:group-hover:text-white"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="mt-12 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-slate-400 dark:text-slate-500">
            <div class="flex items-center gap-6">
              <span class="flex items-center gap-1.5"><i class="mdi mdi-apps"></i> {{ user_routes.length }} {{ __('Applications') }}</span>
              <span class="hidden sm:inline text-slate-300 dark:text-slate-700">•</span>
              <span class="flex items-center gap-1.5"><i class="mdi mdi-tune"></i> {{ totalSettings }} {{ __('Settings') }}</span>
              <span v-if="admin_routes.length" class="hidden sm:inline text-slate-300 dark:text-slate-700">•</span>
              <span v-if="admin_routes.length" class="flex items-center gap-1.5"><i class="mdi mdi-shield-account"></i> {{ admin_routes.length }} {{ __('Admin') }}</span>
            </div>
            <div class="flex items-center gap-4">
              <span class="font-mono text-xs">{{ app_name }}</span>
              <span class="text-slate-300 dark:text-slate-700">|</span>
              <span class="text-xs">{{ __('OAuth2 Passport Server') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-main-layout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import VMainLayout from '@/components/VMainLayout.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const searchTerm = ref('');
const activeTab = ref('apps');

const user = ref({});
const user_routes = ref([]);
const admin_routes = ref([]);
const user_settings = ref([]);
const admin_settings = ref([]);
const userInitials = ref('');
const filtered_apps = ref([]);
const app_name = ref('');

const totalSettings = computed(() => user_settings.value.length + admin_settings.value.length);

onMounted(() => {
  user.value = page.props.user || {};
  user_routes.value = page.props.user_routes || [];
  filtered_apps.value = [...user_routes.value];
  admin_routes.value = page.props.admin_routes || [];
  user_settings.value = page.props.user_settings || [];
  admin_settings.value = page.props.admin_settings || [];
  app_name.value = page.props.app_name || '';
  userInitials.value = `${user.value.name?.[0] || ''}${user.value.last_name?.[0] || ''}`.toUpperCase();
});

function filterApplications() {
  if (!searchTerm.value.trim()) {
    filtered_apps.value = [...user_routes.value];
    return;
  }
  const term = searchTerm.value.toLowerCase();
  filtered_apps.value = user_routes.value.filter(app =>
    app.name.toLowerCase().includes(term) ||
    (app.description && app.description.toLowerCase().includes(term))
  );
}

function highlightMatch(text) {
  if (!searchTerm.value || !text) return text;
  const regex = new RegExp(`(${searchTerm.value})`, 'gi');
  return text.replace(regex, '<mark class="bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200 rounded px-0.5">$1</mark>');
}
</script>