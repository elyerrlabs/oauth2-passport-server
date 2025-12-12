/** @type {import('vite').UserConfig} */
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";
import monacoEditorPlugin from "vite-plugin-monaco-editor-esm";
import path from "path";
import fs from "fs";
import dotenv from "dotenv";
dotenv.config();

/**
 * Resolve components
 * @returns 
 */
function LoadCoreModules() {
  const corePath = path.resolve(__dirname, "core");
  const alias = {};

  if (fs.existsSync(corePath)) {
    const modules = fs.readdirSync(corePath);

    modules.forEach((module) => {
      const jsPath = path.join(corePath, module, "resources/js");

      if (fs.existsSync(jsPath)) {
        alias[`@${module}`] = jsPath;
      }
    });
  }
  return alias;
}


/**
 * Resolve asset to compile
 * @returns 
 */
function loadModuleAssets() {
  const corePath = path.resolve(__dirname, "core");
  const inputs = [];

  if (!fs.existsSync(corePath)) return inputs;

  const modules = fs.readdirSync(corePath);

  modules.forEach((module) => {
    const jsDir = path.join(corePath, module, "resources/js");
    const scssDir = path.join(corePath, module, "resources/scss");

    if (fs.existsSync(jsDir)) {
      fs.readdirSync(jsDir)
        .filter((file) => file.endsWith(".js"))
        .forEach((file) => {
          inputs.push(path.join(jsDir, file));
        });
    }

    if (fs.existsSync(scssDir)) {
      fs.readdirSync(scssDir)
        .filter((file) => file.endsWith(".scss"))
        .forEach((file) => {
          inputs.push(path.join(scssDir, file));
        });
    }
  });
  return inputs;
}


export default defineConfig({
  server: {
    host: process.env.VITE_HOST,
    port: 5173,
  },
  resolve: {
    alias: {
      "@css": path.resolve(__dirname, "resources/css"),
      "@": path.resolve(__dirname, "resources/js"),
      ...LoadCoreModules(),
    },
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        "resources/js/app.js",
        "resources/scss/app.scss",
        "resources/js/pages.js",
        "resources/scss/pages.scss",
        ...loadModuleAssets()
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
