import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";
import monacoEditorPlugin from "vite-plugin-monaco-editor-esm";
import path from "path";

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        "resources/js/app.js",
        "resources/js/system.js",
        "resources/js/ecommerce.js",
        "resources/scss/app.scss",
        "resources/js/pages.js",
        "resources/scss/pages.scss",
      ],
      refresh: true,
    }),
    monacoEditorPlugin(),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "resources/js"),
    },
  },
  optimizeDeps: {
    include: ["monaco-editor"],
  },
  build: {
    chunkSizeWarningLimit: 2000,
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes("node_modules")) {
            if (id.includes("vue")) return "vendor-vue";
            if (id.includes("quasar")) return "vendor-quasar";
            if (id.includes("tailwind")) return "vendor-tailwind";
            return "vendor";
          }
        },
      },
    },
  },
});
