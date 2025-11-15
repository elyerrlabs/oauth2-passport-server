<div id="theme-toggle-app" class="relative inline-block text-left select-none">
    <div v-cloak>
        <!-- Toggle Button - Diseño Neutral -->
        <button @click="isOpen = !isOpen"
            class="flex items-center justify-center w-10 h-10 rounded-xl
                   bg-gray-100/80 dark:bg-gray-700/80 backdrop-blur-sm
                   border border-gray-200/60 dark:border-gray-600/60
                   shadow-sm hover:shadow-md transition-all duration-300
                   hover:bg-gray-200/80 dark:hover:bg-gray-600/80
                   hover:border-gray-300/60 dark:hover:border-gray-500/60
                   focus:outline-none focus:ring-2 focus:ring-gray-400/50
                   group relative overflow-hidden">

            <!-- Glassmorphism Effect -->
            <div class="absolute inset-0 bg-white/20 dark:bg-gray-800/20 backdrop-blur-sm rounded-xl"></div>

            <!-- Icons Container -->
            <div class="relative w-5 h-5 z-10">
                <!-- Sun Icon (Light) -->
                <i class="mdi mdi-white-balance-sunny absolute inset-0 transition-all duration-500 ease-out"
                    :class="selectedTheme === 'light'
                        ?
                        'text-amber-600 opacity-100 rotate-0 scale-110' :
                        'text-gray-500 opacity-0 -rotate-45 scale-50'">
                </i>

                <!-- Moon Icon (Dark) -->
                <i class="mdi mdi-moon-waning-crescent absolute inset-0 transition-all duration-500 ease-out"
                    :class="selectedTheme === 'dark'
                        ?
                        'text-slate-400 opacity-100 rotate-0 scale-110' :
                        'text-gray-500 opacity-0 rotate-45 scale-50'">
                </i>

                <!-- Auto Icon -->
                <i class="mdi mdi-brightness-auto absolute inset-0 transition-all duration-500 ease-out"
                    :class="selectedTheme === 'auto'
                        ?
                        'text-gray-600 dark:text-gray-300 opacity-100 rotate-0 scale-110' :
                        'text-gray-500 opacity-0 rotate-180 scale-50'">
                </i>
            </div>

            <!-- Active Indicator Dot -->
            <div class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-green-400 shadow-sm"
                :class="selectedTheme !== 'auto' ? 'opacity-100' : 'opacity-0'">
            </div>
        </button>

        <!-- Dropdown Menu -->
        <transition enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in" enter-from-class="opacity-0 scale-95 translate-y-2"
            enter-to-class="opacity-100 scale-100 translate-y-0" leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-2">
            <div v-show="isOpen"
                class="absolute right-0 mt-3 w-56 bg-white/95 dark:bg-gray-800/95 rounded-2xl shadow-xl
                       ring-1 ring-black/10 dark:ring-white/10 backdrop-blur-md z-50 overflow-hidden
                       border border-gray-200/50 dark:border-gray-700/50">

                <!-- Header -->
                <div
                    class="px-5 py-4 border-b border-gray-100/50 dark:border-gray-700/50 
                           bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-800/80 dark:to-gray-700/80">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                            <i class="mdi mdi-palette-swatch text-white text-sm"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Theme</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Choose appearance</p>
                        </div>
                    </div>
                </div>

                <!-- Theme Options -->
                <div class="p-2 space-y-1">
                    <button v-for="theme in themes" :key="theme.value" @click="selectTheme(theme.value)"
                        class="w-full flex items-center space-x-4 px-4 py-3 rounded-xl text-sm transition-all duration-300 group relative overflow-hidden"
                        :class="selectedTheme === theme.value ?
                            'bg-gray-100/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-200 shadow-sm ring-1 ring-gray-200/50 dark:ring-gray-600/50' :
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-50/80 dark:hover:bg-gray-700/60'">

                        <!-- Background Effect -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 opacity-0 group-hover:opacity-5 transition-opacity duration-300">
                        </div>

                        <!-- Icon Container -->
                        <div class="flex-shrink-0 relative">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm border border-gray-200/50 dark:border-gray-600/50"
                                :class="selectedTheme === theme.value ?
                                    theme.activeBg + ' ring-1 ring-gray-300/50 dark:ring-gray-500/50' :
                                    theme.inactiveBg + ' group-hover:scale-105'">
                                <i class="text-lg transition-transform duration-300 group-hover:scale-110"
                                    :class="[
                                        selectedTheme === theme.value ? theme.activeColor : theme.inactiveColor,
                                        theme.icon
                                    ]"></i>
                            </div>
                        </div>

                        <!-- Text Content -->
                        <div class="flex-1 text-left min-w-0">
                            <div class="font-medium transition-colors duration-300">@{{ theme.label }}</div>
                            <div class="text-xs opacity-70 mt-0.5 truncate">@{{ theme.description }}</div>
                        </div>

                        <!-- Check Indicator -->
                        <div v-if="selectedTheme === theme.value" class="flex-shrink-0">
                            <div
                                class="w-5 h-5 bg-gray-600 dark:bg-gray-400 rounded-full flex items-center justify-center shadow-sm">
                                <i class="mdi mdi-check text-white text-xs"></i>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Footer -->
                <div
                    class="px-5 py-3 border-t border-gray-100/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="flex items-center justify-center space-x-2 text-xs">
                        <i class="mdi mdi-update text-gray-400 text-xs"></i>
                        <span class="text-gray-500 dark:text-gray-400">Changes apply instantly</span>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</div>

<!-- Vue CDN -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    isOpen: false,
                    selectedTheme: 'auto',
                    themes: [{
                            label: "Light",
                            value: "light",
                            icon: "mdi mdi-white-balance-sunny",
                            description: "Bright theme",
                            activeBg: "bg-amber-50/80 dark:bg-amber-900/20",
                            inactiveBg: "bg-gray-100/60 dark:bg-gray-700/60",
                            activeColor: "text-amber-600 dark:text-amber-400",
                            inactiveColor: "text-gray-500 dark:text-gray-400",
                        },
                        {
                            label: "Dark",
                            value: "dark",
                            icon: "mdi mdi-moon-waning-crescent",
                            description: "Dark theme",
                            activeBg: "bg-slate-100/80 dark:bg-slate-800/30",
                            inactiveBg: "bg-gray-100/60 dark:bg-gray-700/60",
                            activeColor: "text-slate-600 dark:text-slate-300",
                            inactiveColor: "text-gray-500 dark:text-gray-400",
                        },
                        {
                            label: "System",
                            value: "auto",
                            icon: "mdi mdi-brightness-auto",
                            description: "Auto system theme",
                            activeBg: "bg-gray-100/80 dark:bg-gray-700/30",
                            inactiveBg: "bg-gray-100/60 dark:bg-gray-700/60",
                            activeColor: "text-gray-600 dark:text-gray-300",
                            inactiveColor: "text-gray-500 dark:text-gray-400",
                        },
                    ],
                };
            },

            mounted() {
                this.loadTheme();
                this.setupEventListeners();
            },

            methods: {
                selectTheme(theme) {
                    this.selectedTheme = theme;
                    this.applyTheme(theme);
                    this.isOpen = false;
                },

                loadTheme() {
                    const savedTheme = localStorage.getItem("theme");
                    const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

                    if (savedTheme) {
                        this.selectedTheme = savedTheme;
                    } else {
                        this.selectedTheme = "auto";
                    }

                    this.applyTheme(this.selectedTheme);
                },

                applyTheme(theme) {
                    let effectiveTheme = theme;

                    if (theme === "auto") {
                        effectiveTheme = window.matchMedia("(prefers-color-scheme: dark)").matches ?
                            "dark" :
                            "light";
                    }

                    // Apply to document
                    document.documentElement.classList.remove("light", "dark");
                    document.documentElement.classList.add(effectiveTheme);

                    // Save preference
                    localStorage.setItem("theme", theme);

                    // Update meta theme color
                    this.updateMetaThemeColor(effectiveTheme);
                },

                updateMetaThemeColor(theme) {
                    const themeColor = theme === 'dark' ? '#1f2937' : '#f9fafb';
                    let metaTheme = document.querySelector('meta[name="theme-color"]');

                    if (!metaTheme) {
                        metaTheme = document.createElement('meta');
                        metaTheme.name = 'theme-color';
                        document.head.appendChild(metaTheme);
                    }

                    metaTheme.content = themeColor;
                },

                setupEventListeners() {
                    // System theme change listener
                    window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (
                    e) => {
                        if (this.selectedTheme === "auto") {
                            this.applyTheme("auto");
                        }
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener("click", (e) => {
                        if (!this.$el.contains(e.target)) {
                            this.isOpen = false;
                        }
                    });

                    // Close on escape key
                    document.addEventListener("keydown", (e) => {
                        if (e.key === 'Escape' && this.isOpen) {
                            this.isOpen = false;
                        }
                    });
                },
            },
        }).mount("#theme-toggle-app");
    });
</script>

<style>
    [v-cloak] {
        display: none !important;
    }

    /* Smooth transitions for all elements */
    #theme-toggle-app * {
        transition-property: color, background-color, border-color, transform, opacity;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
