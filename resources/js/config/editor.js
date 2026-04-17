import { Jodit } from "jodit";
import "jodit/esm/plugins/all.js";
window.Jodit = Jodit;

import "monaco-editor/esm/vs/editor/editor.main.js";

import * as monaco from "monaco-editor";
window.monaco = monaco;

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