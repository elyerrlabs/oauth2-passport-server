<div id="theme-toggle-app" class="relative inline-block text-left select-none">

    <!-- Toggle Button -->
    <button id="theme-toggle-btn"
        class="flex items-center justify-center w-11 h-11 rounded-xl
               bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-300
               border border-gray-300 dark:border-gray-700
               shadow-sm hover:shadow-md
               transition-all duration-300
               hover:bg-gray-50 dark:hover:bg-gray-700/90
               focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-2
               group relative overflow-hidden">

        <!-- Background gradient that changes with theme -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent 
                    to-gray-100/50 dark:to-gray-900/30 rounded-xl">
        </div>

        <div class="relative w-6 h-6 z-10">
            <!-- Sun icon for light mode -->
            <i id="icon-light"
                class="mdi mdi-white-balance-sunny absolute inset-0 
                       opacity-0 scale-0 group-hover:scale-110
                       transition-all duration-300 ease-out
                       text-amber-500"></i>

            <!-- Moon icon for dark mode -->
            <i id="icon-dark"
                class="mdi mdi-moon-waning-crescent absolute inset-0 
                       opacity-0 scale-0 group-hover:scale-110
                       transition-all duration-300 ease-out
                       text-blue-400"></i>

            <!-- Auto icon -->
            <i id="icon-auto"
                class="mdi mdi-brightness-auto absolute inset-0 
                       opacity-0 scale-0 group-hover:scale-110
                       transition-all duration-300 ease-out
                       text-gray-500 dark:text-gray-400"></i>
        </div>

        <!-- Active indicator -->
        <div id="active-dot"
            class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full 
                   bg-gradient-to-r from-amber-400 to-amber-500 dark:from-blue-500 dark:to-blue-600
                   shadow-md ring-2 ring-white dark:ring-gray-900
                   opacity-0 transition-all duration-300 transform scale-0 group-hover:scale-100">
        </div>

        <!-- Ripple effect on hover -->
        <span
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent 
                     -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
        </span>
    </button>

    <!-- Dropdown -->
    <div id="theme-dropdown"
        class="hidden absolute right-0 mt-3 w-64 
               bg-white dark:bg-gray-900 backdrop-blur-xl
               rounded-2xl border border-gray-200 dark:border-gray-800
               shadow-xl shadow-gray-500/10 dark:shadow-black/50
               ring-1 ring-gray-900/5 dark:ring-gray-700/50 z-50 overflow-hidden
               animate-in slide-in-from-top-2 duration-200">

        <!-- Header -->
        <div
            class="px-5 py-4 border-b border-gray-100 dark:border-gray-800
                   bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">

            <div class="flex items-center space-x-3">
                <div
                    class="w-9 h-9 bg-gradient-to-br from-amber-500 to-amber-600 
                           dark:from-blue-600 dark:to-blue-800
                           rounded-lg flex items-center justify-center shadow-sm">
                    <i class="mdi mdi-palette-swatch text-white text-base"></i>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Theme') }}</span>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ __('Choose appearance') }}</p>
                </div>
            </div>
        </div>

        <!-- Options -->
        <div class="p-2 space-y-1" id="theme-options"></div>

        <!-- Footer -->
        <div
            class="px-5 py-3 border-t border-gray-100 dark:border-gray-800
                   bg-gray-50/80 dark:bg-gray-900/80">
            <div class="flex items-center justify-center space-x-2 text-xs text-gray-600 dark:text-gray-400">
                <i class="mdi mdi-update text-gray-500 dark:text-gray-500"></i>
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
                description: "Bright and clean theme",
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
                description: "Easy on the eyes",
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
                description: "Follow system settings",
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

        // -----------------------------------------
        // Apply Theme
        // -----------------------------------------
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

            // Update dot visibility and color
            dot.style.opacity = value !== "auto" ? "1" : "0";
            dot.classList.toggle("scale-100", value !== "auto");
            dot.classList.toggle("scale-0", value === "auto");

            // Show correct icon with animation
            iconLight.classList.toggle("opacity-100", selectedTheme === "light");
            iconLight.classList.toggle("scale-100", selectedTheme === "light");
            iconLight.classList.toggle("scale-0", selectedTheme !== "light");

            iconDark.classList.toggle("opacity-100", selectedTheme === "dark");
            iconDark.classList.toggle("scale-100", selectedTheme === "dark");
            iconDark.classList.toggle("scale-0", selectedTheme !== "dark");

            iconAuto.classList.toggle("opacity-100", selectedTheme === "auto");
            iconAuto.classList.toggle("scale-100", selectedTheme === "auto");
            iconAuto.classList.toggle("scale-0", selectedTheme !== "auto");

            // Update button border color based on theme
            btn.classList.remove(
                'border-amber-300', 'border-blue-300', 'border-gray-300',
                'dark:border-amber-700', 'dark:border-blue-700', 'dark:border-gray-700'
            );

            if (selectedTheme === 'light') {
                btn.classList.add('border-amber-300', 'dark:border-amber-700');
            } else if (selectedTheme === 'dark') {
                btn.classList.add('border-blue-300', 'dark:border-blue-700');
            } else {
                btn.classList.add('border-gray-300', 'dark:border-gray-700');
            }
        }

        // Initial theme application
        applyTheme(selectedTheme);

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (selectedTheme === 'auto') {
                applyTheme('auto');
            }
        });

        // -----------------------------------------
        // Dropdown open/close
        // -----------------------------------------
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

        // -----------------------------------------
        // Render Theme Options
        // -----------------------------------------
        themes.forEach((t) => {
            const btn = document.createElement("button");

            btn.className = `
            w-full flex items-center space-x-4 px-4 py-3 rounded-xl text-sm 
            transition-all duration-300 group relative overflow-hidden
            hover:shadow-md hover:scale-[1.02] active:scale-[0.98]
        `;

            btn.dataset.value = t.value;

            btn.innerHTML = `
            <!-- Hover background -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/50 to-transparent 
                        dark:from-gray-800/50 opacity-0 group-hover:opacity-100 
                        transition-opacity duration-300">
            </div>

            <div class="flex-shrink-0 relative z-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 
                            shadow-sm ${t.borderColor} ${t.inactiveBg} group-hover:scale-110">
                    <i class="text-lg ${t.icon} ${t.iconColor}"></i>
                </div>
            </div>

            <div class="flex-1 text-gray-800 dark:text-gray-100 text-left min-w-0 z-10">
                <div class="font-medium">${t.label}</div>
                <div class="text-xs opacity-70 mt-0.5 truncate">${t.description}</div>
            </div>

            <div class="check hidden z-10">
                <div class="w-5 h-5 bg-gradient-to-r from-amber-500 to-amber-600 
                           dark:from-blue-500 dark:to-blue-600 rounded-full flex items-center justify-center 
                           shadow-sm transform scale-0 group-hover:scale-100 transition-transform duration-300">
                    <i class="mdi mdi-check text-white text-xs"></i>
                </div>
            </div>
        `;

            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                applyTheme(t.value);
                dropdown.classList.add("hidden");
                updateSelected();
            });

            optionsContainer.appendChild(btn);
        });

        function updateSelected() {
            [...optionsContainer.children].forEach((el) => {
                const val = el.dataset.value;
                const check = el.querySelector(".check");
                const iconBox = el.querySelector("div > div");
                const theme = themes.find(t => t.value === val);

                if (val === selectedTheme) {
                    // Apply active styles
                    el.classList.add("shadow-sm", "scale-[1.01]");
                    el.classList.remove("hover:scale-[1.02]");

                    // Update background
                    const bgDiv = iconBox;
                    bgDiv.className = `w-10 h-10 rounded-xl flex items-center justify-center 
                                     transition-all duration-300 shadow-sm ${theme.borderColor} ${theme.activeBg}`;

                    // Update icon color
                    const icon = bgDiv.querySelector('i');
                    icon.className = `text-lg ${theme.icon} ${theme.activeColor}`;

                    // Show checkmark
                    check.classList.remove("hidden");
                    check.querySelector('div').classList.remove("scale-0");
                    check.querySelector('div').classList.add("scale-100");

                    // Update text colors
                    const textDiv = el.querySelector('div:nth-child(3)');
                    textDiv.classList.add(theme.activeColor);
                    textDiv.classList.remove('text-gray-800', 'dark:text-gray-100');
                } else {
                    // Reset to inactive styles
                    el.classList.remove("shadow-sm", "scale-[1.01]");
                    el.classList.add("hover:scale-[1.02]");

                    // Update background
                    const bgDiv = iconBox;
                    bgDiv.className = `w-10 h-10 rounded-xl flex items-center justify-center 
                                     transition-all duration-300 shadow-sm ${theme.borderColor} ${theme.inactiveBg}`;

                    // Update icon color
                    const icon = bgDiv.querySelector('i');
                    icon.className = `text-lg ${theme.icon} ${theme.inactiveColor}`;

                    // Hide checkmark
                    check.classList.add("hidden");
                    check.querySelector('div').classList.add("scale-0");
                    check.querySelector('div').classList.remove("scale-100");

                    // Reset text colors
                    const textDiv = el.querySelector('div:nth-child(3)');
                    textDiv.classList.remove(theme.activeColor, 'text-gray-800', 'dark:text-gray-100');
                    textDiv.classList.add('text-gray-800', 'dark:text-gray-100');
                }
            });
        }

        updateSelected();
    });
</script>
