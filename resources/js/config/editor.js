import { Jodit } from "jodit";
import "jodit/es2021/jodit.min.css";
import "jodit/esm/plugins/all.js";
window.Jodit = Jodit;

import "monaco-editor/esm/vs/editor/editor.all.js";

//import 'monaco-editor/esm/vs/editor/standalone/browser/accessibilityHelp/accessibilityHelp.js';

import * as monaco from "monaco-editor";
window.monaco = monaco;

// Import individual workers
import editorWorker from "monaco-editor/esm/vs/editor/editor.worker?worker";
import jsonWorker from "monaco-editor/esm/vs/language/json/json.worker?worker";
import cssWorker from "monaco-editor/esm/vs/language/css/css.worker?worker";
import htmlWorker from "monaco-editor/esm/vs/language/html/html.worker?worker";
import tsWorker from "monaco-editor/esm/vs/language/typescript/ts.worker?worker";
self.MonacoEnvironment = {
  getWorker(_, label) {
    if (label === "json") return new jsonWorker();
    if (label === "css" || label === "scss" || label === "less")
      return new cssWorker();
    if (label === "html" || label === "handlebars" || label === "razor")
      return new htmlWorker();
    if (label === "typescript" || label === "javascript") return new tsWorker();
    return new editorWorker();
  },
};

// --------------------- MONACO ---------------------

// Global default configuration for Monaco
window.monacoConfigDefault = {
  theme: "vs-dark",
  fontSize: 14,
  automaticLayout: true,
  minimap: { enabled: false },
};

// Reusable function to create Monaco editors
window.createMonacoEditor = (element, options = {}) => {
  return monaco.editor.create(element, options);
};

// --------------------- JODIT ---------------------

// Global default configuration for Jodit
window.joditConfigDefault = {
  language: (navigator.language || navigator.userLanguage || "en").split(
    "-"
  )[0],
  uploader: {
    insertImageAsBase64URI: true,
  },
  toolbarSticky: true,
  toolbarAdaptive: true,
};

// Reusable function to create Jodit editors
window.createJoditEditor = (element, options = {}) => {
  const config = {
    ...window.joditConfigDefault, // apply default values
    ...options, // override with instance-specific options
  };
  return new Jodit(element, config);
};

// --------------------- EXAMPLE USAGE ---------------------
// You can use it in any Blade or component
document.addEventListener("DOMContentLoaded", () => {
  // Create Monaco editor
  /*const monacoEditor = window.createMonacoEditor(
    document.getElementById("editor-monaco"),
    { value: "<h1>Hello World</h1>", language: "html" }
  );

  monacoEditor.onDidChangeModelContent(() => {
    console.log("Monaco changed:", monacoEditor.getValue());
  });*/
  // Create Jodit editor
  /*const joditEditor = window.createJoditEditor(
    document.getElementById("editor-jodit"),
    { buttons: ["bold", "italic", "underline"] } // override buttons
  );

  joditEditor.events.on("blur", () => {
    console.log("Jodit lost focus:", joditEditor.value);
  });*/
});
