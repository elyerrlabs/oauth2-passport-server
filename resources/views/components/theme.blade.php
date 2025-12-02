<div id="theme-toggle-app" class="relative inline-block text-left select-none">
    <!-- Toggle Button -->
    <button id="theme-toggle-btn"
        class="flex items-center justify-center w-10 h-10 rounded-xl
               bg-gray-100/80 dark:bg-gray-700/80 backdrop-blur-sm
               border border-gray-200/60 dark:border-gray-600/60
               shadow-sm hover:shadow-md transition-all duration-300
               hover:bg-gray-200/80 dark:hover:bg-gray-600/80
               hover:border-gray-300/60 dark:hover:border-gray-500/60
               focus:outline-none focus:ring-2 focus:ring-gray-400/50
               group relative overflow-hidden">

        <div class="absolute inset-0 bg-white/20 dark:bg-gray-800/20 backdrop-blur-sm rounded-xl"></div>

        <div class="relative w-5 h-5 z-10">
            <i id="icon-light"
                class="mdi mdi-white-balance-sunny absolute inset-0 transition-all duration-500 ease-out"></i>

            <i id="icon-dark"
                class="mdi mdi-moon-waning-crescent absolute inset-0 transition-all duration-500 ease-out"></i>

            <i id="icon-auto" class="mdi mdi-brightness-auto absolute inset-0 transition-all duration-500 ease-out"></i>
        </div>

        <div id="active-dot" class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-green-400 shadow-sm opacity-0">
        </div>
    </button>

    <!-- Dropdown -->
    <div id="theme-dropdown"
        class="hidden absolute right-0 mt-3 w-56 bg-white/95 dark:bg-gray-800/95 rounded-2xl shadow-xl
               ring-1 ring-black/10 dark:ring-white/10 backdrop-blur-md z-50 overflow-hidden
               border border-gray-200/50 dark:border-gray-700/50">

        <div
            class="px-5 py-4 border-b border-gray-100/50 dark:border-gray-700/50 
                   bg-linear-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-800/80 dark:to-gray-700/80">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-linear-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-palette-swatch text-white text-sm"></i>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Theme</span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Choose appearance</p>
                </div>
            </div>
        </div>

        <div class="p-2 space-y-1" id="theme-options"></div>

        <div class="px-5 py-3 border-t border-gray-100/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
            <div class="flex items-center justify-center space-x-2 text-xs">
                <i class="mdi mdi-update text-gray-400 text-xs"></i>
                <span class="text-gray-500 dark:text-gray-400">Changes apply instantly</span>
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

            dot.style.opacity = value !== "auto" ? "1" : "0";

            iconLight.classList.toggle("opacity-100", selectedTheme === "light");
            iconDark.classList.toggle("opacity-100", selectedTheme === "dark");
            iconAuto.classList.toggle("opacity-100", selectedTheme === "auto");
        }

        applyTheme(selectedTheme);

        // -----------------------------------------
        // Dropdown open/close
        // -----------------------------------------
        btn.addEventListener("click", () => {
            dropdown.classList.toggle("hidden");
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
        `;

            btn.dataset.value = t.value;

            btn.innerHTML = `
            <div class="absolute inset-0 bg-gradient-to-r 
                        from-gray-200 to-gray-300 
                        dark:from-gray-600 dark:to-gray-700 
                        opacity-0 group-hover:opacity-5 transition-opacity duration-300">
            </div>

            <div class="flex-shrink-0 relative">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 
                            shadow-sm border border-gray-200/50 dark:border-gray-600/50 ${t.inactiveBg}">
                    <i class="text-lg ${t.icon} ${t.inactiveColor}"></i>
                </div>
            </div>

            <div class="flex-1 text-left min-w-0">
                <div class="font-medium">${t.label}</div>
                <div class="text-xs opacity-70 mt-0.5 truncate">${t.description}</div>
            </div>

            <div class="check hidden">
                <div class="w-5 h-5 bg-gray-600 dark:bg-gray-400 rounded-full flex items-center justify-center">
                    <i class="mdi mdi-check text-white text-xs"></i>
                </div>
            </div>
        `;

            btn.addEventListener("click", () => {
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

                if (val === selectedTheme) {
                    el.classList.add(
                        "bg-gray-100/80",
                        "dark:bg-gray-700/80",
                        "text-gray-700",
                        "dark:text-gray-200",
                        "shadow-sm"
                    );
                    check.classList.remove("hidden");
                } else {
                    el.classList.remove(
                        "bg-gray-100/80",
                        "dark:bg-gray-700/80",
                        "text-gray-700",
                        "dark:text-gray-200",
                        "shadow-sm"
                    );
                    check.classList.add("hidden");
                }
            });
        }

        updateSelected();
    });
</script>
