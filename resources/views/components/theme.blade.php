<div id="theme-toggle-app" class="relative inline-block text-left select-none">

    <!-- Toggle Button -->
    <button id="theme-toggle-btn"
        class="flex items-center justify-center w-9 h-9 rounded-lg
               bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-300
               border border-gray-200 dark:border-gray-700
               shadow-sm hover:shadow-md
               transition-all duration-300
               hover:bg-gray-50 dark:hover:bg-gray-700/90
               focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-2
               group relative overflow-hidden">

        <!-- Background gradient -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent 
                    to-gray-100/50 dark:to-gray-900/30 rounded-lg">
        </div>

        <div class="relative w-4 h-4 z-10">
            <!-- Sun icon for light mode -->
            <i id="icon-light"
                class="mdi mdi-white-balance-sunny absolute inset-0 text-sm
                       opacity-0 scale-0 group-hover:scale-110
                       transition-all duration-300 ease-out
                       text-amber-500"></i>

            <!-- Moon icon for dark mode -->
            <i id="icon-dark"
                class="mdi mdi-moon-waning-crescent absolute inset-0 text-sm
                       opacity-0 scale-0 group-hover:scale-110
                       transition-all duration-300 ease-out
                       text-blue-400"></i>

            <!-- Auto icon -->
            <i id="icon-auto"
                class="mdi mdi-brightness-auto absolute inset-0 text-sm
                       opacity-0 scale-0 group-hover:scale-110
                       transition-all duration-300 ease-out
                       text-gray-500 dark:text-gray-400"></i>
        </div>

        <!-- Active indicator -->
        <div id="active-dot"
            class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full 
                   bg-gradient-to-r from-amber-400 to-amber-500 dark:from-blue-500 dark:to-blue-600
                   shadow-md ring-2 ring-white dark:ring-gray-900
                   opacity-0 transition-all duration-300 transform scale-0">
        </div>

        <!-- Ripple effect -->
        <span
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent 
                     -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
        </span>
    </button>

    <!-- Dropdown -->
    <div id="theme-dropdown"
        class="hidden absolute right-0 mt-2 w-56 
               bg-white dark:bg-gray-900 backdrop-blur-xl
               rounded-xl border border-gray-200 dark:border-gray-800
               shadow-lg shadow-gray-500/10 dark:shadow-black/50
               ring-1 ring-gray-900/5 dark:ring-gray-700/50 z-50 overflow-hidden
               animate-in slide-in-from-top-2 duration-200">

        <!-- Header -->
        <div
            class="px-4 py-3 border-b border-gray-100 dark:border-gray-800
                   bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">

            <div class="flex items-center space-x-2">
                <div
                    class="w-7 h-7 bg-gradient-to-br from-amber-500 to-amber-600 
                           dark:from-blue-600 dark:to-blue-800
                           rounded-md flex items-center justify-center shadow-sm">
                    <i class="mdi mdi-palette-swatch text-white text-xs"></i>
                </div>

                <div>
                    <span class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ __('Theme') }}</span>
                    <p class="text-[11px] text-gray-600 dark:text-gray-400 mt-0.5">{{ __('Choose appearance') }}</p>
                </div>
            </div>
        </div>

        <!-- Options -->
        <div class="p-1.5 space-y-0.5" id="theme-options"></div>

        <!-- Footer -->
        <div
            class="px-4 py-2 border-t border-gray-100 dark:border-gray-800
                   bg-gray-50/80 dark:bg-gray-900/80">
            <div class="flex items-center justify-center space-x-1.5 text-[10px] text-gray-600 dark:text-gray-400">
                <i class="mdi mdi-update text-gray-500 dark:text-gray-500 text-[10px]"></i>
                <span>{{ __('Changes apply instantly') }}</span>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $nonce }}">
    document.addEventListener("DOMContentLoaded", () => {

        const themes = [{
                label: "Light",
                value: "light",
                icon: "mdi mdi-white-balance-sunny",
                description: "Bright theme",
                activeBg: "bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-900/10",
                inactiveBg: "bg-gray-100 dark:bg-gray-800/60",
                activeColor: "text-amber-600 dark:text-amber-400",
                inactiveColor: "text-gray-500 dark:text-gray-400",
                borderColor: "border border-amber-200/50 dark:border-amber-800/30",
                iconColor: "text-amber-500 dark:text-amber-400"
            },
            {
                label: "Dark",
                value: "dark",
                icon: "mdi mdi-moon-waning-crescent",
                description: "Dark theme",
                activeBg: "bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20",
                inactiveBg: "bg-gray-100 dark:bg-gray-800/60",
                activeColor: "text-blue-600 dark:text-blue-400",
                inactiveColor: "text-gray-500 dark:text-gray-400",
                borderColor: "border border-blue-200/50 dark:border-blue-800/30",
                iconColor: "text-blue-500 dark:text-blue-400"
            },
            {
                label: "System",
                value: "auto",
                icon: "mdi mdi-brightness-auto",
                description: "Auto detect",
                activeBg: "bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800/30 dark:to-gray-800/20",
                inactiveBg: "bg-gray-100 dark:bg-gray-800/60",
                activeColor: "text-gray-700 dark:text-gray-300",
                inactiveColor: "text-gray-500 dark:text-gray-400",
                borderColor: "border border-gray-200/50 dark:border-gray-700/30",
                iconColor: "text-gray-500 dark:text-gray-400"
            },
        ];

        let selectedTheme = localStorage.getItem("theme") || "auto";

        const btn = document.getElementById("theme-toggle-btn");
        const dropdown = document.getElementById("theme-dropdown");
        const optionsContainer = document.getElementById("theme-options");
        const dot = document.getElementById("active-dot");

        const iconLight = document.getElementById("icon-light");
        const iconDark = document.getElementById("icon-dark");
        const iconAuto = document.getElementById("icon-auto");

        // Helper function to safely update classes
        function updateElementClasses(element, classesToAdd = [], classesToRemove = []) {
            const removeClasses = [];
            classesToRemove.forEach(cls => {
                if (cls && typeof cls === 'string' && cls.trim()) {
                    removeClasses.push(...cls.split(' ').filter(c => c && c.trim()));
                }
            });

            const uniqueRemoveClasses = [...new Set(removeClasses.filter(c => c))];
            uniqueRemoveClasses.forEach(cls => {
                element.classList.remove(cls);
            });

            const addClasses = [];
            classesToAdd.forEach(cls => {
                if (cls && typeof cls === 'string' && cls.trim()) {
                    addClasses.push(...cls.split(' ').filter(c => c && c.trim()));
                }
            });

            const uniqueAddClasses = [...new Set(addClasses.filter(c => c))];
            uniqueAddClasses.forEach(cls => {
                element.classList.add(cls);
            });
        }

        // Apply Theme
        function applyTheme(value) {
            selectedTheme = value;

            let effective = value;
            if (value === "auto") {
                effective = window.matchMedia("(prefers-color-scheme: dark)").matches ?
                    "dark" :
                    "light";
            }

            document.documentElement.classList.remove("light", "dark");
            document.documentElement.classList.add(effective);

            localStorage.setItem("theme", value);

            // Update dot visibility
            dot.style.opacity = value !== "auto" ? "1" : "0";
            dot.classList.toggle("scale-100", value !== "auto");
            dot.classList.toggle("scale-0", value === "auto");

            // Show correct icon
            iconLight.classList.toggle("opacity-100", selectedTheme === "light");
            iconLight.classList.toggle("scale-100", selectedTheme === "light");
            iconLight.classList.toggle("scale-0", selectedTheme !== "light");

            iconDark.classList.toggle("opacity-100", selectedTheme === "dark");
            iconDark.classList.toggle("scale-100", selectedTheme === "dark");
            iconDark.classList.toggle("scale-0", selectedTheme !== "dark");

            iconAuto.classList.toggle("opacity-100", selectedTheme === "auto");
            iconAuto.classList.toggle("scale-100", selectedTheme === "auto");
            iconAuto.classList.toggle("scale-0", selectedTheme !== "auto");

            // Update button border
            updateElementClasses(btn,
                selectedTheme === 'light' ? ['border-amber-300', 'dark:border-amber-700'] :
                selectedTheme === 'dark' ? ['border-blue-300', 'dark:border-blue-700'] : ['border-gray-300',
                    'dark:border-gray-700'
                ],
                [
                    'border-amber-300', 'border-blue-300', 'border-gray-300',
                    'dark:border-amber-700', 'dark:border-blue-700', 'dark:border-gray-700'
                ]
            );
        }

        applyTheme(selectedTheme);

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (selectedTheme === 'auto') {
                applyTheme('auto');
            }
        });

        // Dropdown toggle
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdown.classList.toggle("hidden");
            dropdown.classList.toggle("animate-in");
        });

        document.addEventListener("click", (e) => {
            if (!document.getElementById("theme-toggle-app").contains(e.target)) {
                dropdown.classList.add("hidden");
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") dropdown.classList.add("hidden");
        });

        // Render Theme Options
        themes.forEach((t) => {
            const optionBtn = document.createElement("button");

            optionBtn.className = `
                w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-xs 
                transition-all duration-200 group relative overflow-hidden
                hover:bg-gray-50 dark:hover:bg-gray-800/50
            `;

            optionBtn.dataset.value = t.value;

            optionBtn.innerHTML = `
                <div class="flex-shrink-0 relative z-10">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center transition-all duration-200 
                                ${t.borderColor} ${t.inactiveBg} group-hover:scale-105">
                        <i class="text-sm ${t.icon} ${t.iconColor}"></i>
                    </div>
                </div>

                <div class="flex-1 text-gray-800 dark:text-gray-100 text-left min-w-0 z-10">
                    <div class="font-medium text-xs">${t.label}</div>
                    <div class="text-[10px] opacity-70 mt-0.5 truncate">${t.description}</div>
                </div>

                <div class="check hidden z-10">
                    <div class="w-4 h-4 bg-gradient-to-r from-amber-500 to-amber-600 
                               dark:from-blue-500 dark:to-blue-600 rounded-full flex items-center justify-center 
                               shadow-sm transform scale-0 group-hover:scale-100 transition-transform duration-200">
                        <i class="mdi mdi-check text-white text-[8px]"></i>
                    </div>
                </div>
            `;

            optionBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                applyTheme(t.value);
                dropdown.classList.add("hidden");
                updateSelected();
            });

            optionsContainer.appendChild(optionBtn);
        });

        function updateSelected() {
            [...optionsContainer.children].forEach((el) => {
                const val = el.dataset.value;
                const check = el.querySelector(".check");
                const iconBox = el.querySelector("div > div");
                const theme = themes.find(t => t.value === val);
                const textDiv = el.querySelector('div:nth-child(2)');

                if (val === selectedTheme) {
                    // Active styles
                    el.classList.add("bg-gray-50", "dark:bg-gray-800/50");
                    el.classList.remove("hover:bg-gray-50", "dark:hover:bg-gray-800/50");

                    // Update background
                    updateElementClasses(iconBox,
                        [theme.activeBg, theme.borderColor],
                        [theme.inactiveBg]
                    );

                    // Update icon color
                    const icon = iconBox.querySelector('i');
                    icon.className = `text-sm ${theme.icon} ${theme.activeColor}`;

                    // Show checkmark
                    check.classList.remove("hidden");
                    const checkIcon = check.querySelector('div');
                    checkIcon.classList.remove("scale-0");
                    checkIcon.classList.add("scale-100");

                    // Update text colors
                    updateElementClasses(textDiv,
                        [theme.activeColor],
                        ['text-gray-800', 'dark:text-gray-100', theme.inactiveColor]
                    );
                } else {
                    // Inactive styles
                    el.classList.remove("bg-gray-50", "dark:bg-gray-800/50");
                    el.classList.add("hover:bg-gray-50", "dark:hover:bg-gray-800/50");

                    // Update background
                    updateElementClasses(iconBox,
                        [theme.inactiveBg, theme.borderColor],
                        [theme.activeBg]
                    );

                    // Update icon color
                    const icon = iconBox.querySelector('i');
                    icon.className = `text-sm ${theme.icon} ${theme.inactiveColor}`;

                    // Hide checkmark
                    check.classList.add("hidden");
                    const checkIcon = check.querySelector('div');
                    checkIcon.classList.add("scale-0");
                    checkIcon.classList.remove("scale-100");

                    // Reset text colors
                    updateElementClasses(textDiv,
                        ['text-gray-800', 'dark:text-gray-100'],
                        [theme.activeColor, theme.inactiveColor]
                    );
                }
            });
        }

        updateSelected();
    });
</script>
