import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        "resources/js/app.js",
        "resources/scss/app.scss",
        "resources/js/pages.js",
        "resources/scss/pages.scss",
      ],
      refresh: true,
    }),
    vue(),
  ],
  build: {
    chunkSizeWarningLimit: 1000,
  },
});
