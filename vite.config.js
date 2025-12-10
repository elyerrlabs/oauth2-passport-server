/** @type {import('vite').UserConfig} */
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";
import monacoEditorPlugin from "vite-plugin-monaco-editor-esm";
import path from "path";
import dotenv from "dotenv";
dotenv.config();

export default defineConfig({
  server: {
    host: process.env.VITE_HOST,
    port: 5173,
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        "resources/js/app.js",
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
    minify: "esbuild",
    target: "esnext",
    chunkSizeWarningLimit: 1000,
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes("node_modules")) {
            if (id.includes("vue")) return "vendor-vue";
            return "vendor";
          }
        },
      },
    },
  },
});
