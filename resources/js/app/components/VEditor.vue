<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->
<template>
    <div>
        <div class="row items-center q-gutter-sm q-mb-sm">
            <q-icon name="mdi-file-document" color="primary" size="sm" />
            <div class="text-h6">{{ label }}</div>
        </div>
        <form
            autocorrect="on"
            autocapitalize="on"
            autocomplete="on"
            spellcheck="true"
        >
            <q-editor
                v-model="editorContent"
                :dense="$q.screen.lt.md"
                :toolbar="toolbar"
                :fonts="fonts"
                min-height="300px"
                ref="editorRef"
            >
                <template v-slot:table>
                    <q-btn
                        square
                        outline
                        flat
                        @click="insertTable"
                        size="sm"
                        icon="mdi-table"
                    >
                        <q-tooltip>Add table</q-tooltip>
                    </q-btn>
                </template>
                <template v-slot:table-row>
                    <q-btn
                        square
                        outline
                        flat
                        size="sm"
                        icon="mdi-table-row"
                        @click="insertRowBelowCursor"
                    >
                        <q-tooltip>Add row</q-tooltip>
                    </q-btn>
                </template>
                <template v-slot:table-col>
                    <q-btn
                        square
                        outline
                        flat
                        size="sm"
                        icon="mdi-table-column"
                        @click="insertColumnAfterCursor"
                    >
                        <q-tooltip>Add column</q-tooltip>
                    </q-btn>
                </template>
                <template v-slot:table-row-del>
                    <q-btn
                        square
                        outline
                        flat
                        size="sm"
                        icon="mdi-table-row-remove"
                        @click="deleteRowAtCursor"
                    >
                        <q-tooltip>Delete row</q-tooltip>
                    </q-btn>
                </template>
                <template v-slot:table-col-del>
                    <q-btn
                        square
                        outline
                        flat
                        size="sm"
                        icon="mdi-table-column-remove"
                        @click="deleteColumnAtCursor"
                    >
                        <q-tooltip>Delete column</q-tooltip>
                    </q-btn>
                </template>
                <template v-slot:table-del>
                    <q-btn
                        square
                        outline
                        flat
                        size="sm"
                        icon="mdi-table-large-remove"
                        @click="deleteTableAtCursor"
                    >
                        <q-tooltip>Delete table</q-tooltip>
                    </q-btn>
                </template>
                <template v-slot:clear-format>
                    <q-btn
                        square
                        outline
                        flat
                        size="sm"
                        icon="mdi-format-clear"
                        @click="clearFormat"
                    >
                        <q-tooltip>Clear format</q-tooltip>
                    </q-btn>
                </template>
            </q-editor>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: String,
            default: "",
        },
        label: {
            type: String,
            default: "Editor",
        },
    },

    emits: ["update:modelValue"],

    data() {
        return {
            editorRef: null,
            rows: 3,
            cols: 3,
            toolbar: [
                [
                    {
                        label: this.$q.lang.editor.align,
                        icon: this.$q.iconSet.editor.align,
                        fixedLabel: true,
                        list: "only-icons",
                        options: [
                            "left",
                            "center",
                            "right",
                            "justify",
                            "outdent",
                            "indent",
                        ],
                    },
                ],

                [
                    "bold",
                    "italic",
                    "strike",
                    "underline",
                    "subscript",
                    "superscript",
                ],
                [
                    {
                        label: this.$q.lang.editor.formatting,
                        icon: this.$q.iconSet.editor.formatting,
                        list: "no-icons",
                        options: [
                            "p",
                            "code",
                            "h6",
                            "h5",
                            "h4",
                            "h3",
                            "h2",
                            "h1",
                        ],
                    },
                    {
                        label: this.$q.lang.editor.fontSize,
                        icon: this.$q.iconSet.editor.fontSize,
                        fixedLabel: true,
                        fixedIcon: true,
                        list: "no-icons",
                        options: [
                            "size-1",
                            "size-2",
                            "size-3",
                            "size-4",
                            "size-5",
                            "size-6",
                            "size-7",
                        ],
                    },
                    {
                        label: this.$q.lang.editor.defaultFont,
                        icon: this.$q.iconSet.editor.font,
                        fixedIcon: true,
                        list: "no-icons",
                        options: [
                            "default_font",
                            "arial",
                            "arial_black",
                            "comic_sans",
                            "courier_new",
                            "impact",
                            "lucida_grande",
                            "times_new_roman",
                            "verdana",
                        ],
                    },
                    "removeFormat",
                ],
                [
                    "quote",
                    "unordered",
                    "ordered",
                    "hr",
                    "link",
                    "table",
                    "table-row",
                    "table-col",
                    "table-del",
                    "table-row-del",
                    "table-col-del",
                ],
                ["fullscreen"],
                ["undo", "redo"],
                ["viewsource"],
            ],
            fonts: {
                arial: "Arial",
                arial_black: "Arial Black",
                comic_sans: "Comic Sans MS",
                courier_new: "Courier New",
                impact: "Impact",
                lucida_grande: "Lucida Grande",
                times_new_roman: "Times New Roman",
                verdana: "Verdana",
            },
        };
    },

    computed: {
        editorContent: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit("update:modelValue", value);
            },
        },
    },

    methods: {
        insertTable() {
            const editor = this.$refs.editorRef;
            if (!editor) return;

            editor.caret.restore();
            let tableHtml = `<table style="border-collapse: collapse; width: 100%; border: 1px solid #ccc;">`;

            tableHtml += "<thead><tr>";
            for (let j = 0; j < this.cols; j++) {
                tableHtml += `<th style="padding: 8px; border: 1px solid #ccc; background-color: #f5f5f5;">Title ${
                    j + 1
                }</th>`;
            }
            tableHtml += "</tr></thead>";

            tableHtml += "<tbody>";
            for (let i = 0; i < this.rows; i++) {
                tableHtml += "<tr>";
                for (let j = 0; j < this.cols; j++) {
                    tableHtml += `<td style="padding: 8px; border: 1px solid #ccc;">&nbsp;</td>`;
                }
                tableHtml += "</tr>";
            }
            tableHtml += "</tbody></table>";

            editor.runCmd("insertHTML", tableHtml);
            editor.focus();
        },

        insertRowBelowCursor() {
            const selection = window.getSelection();
            if (!selection || selection.rangeCount === 0) return;

            const range = selection.getRangeAt(0);
            const node = range.startContainer;

            const td =
                node.nodeType === 3
                    ? node.parentNode.closest("td, th")
                    : node.closest("td, th");
            if (!td) return;

            const tr = td.closest("tr");
            const tbody = tr?.parentElement;
            if (!tbody || !tr) return;

            const colCount = tr.cells.length;

            const newRow = document.createElement("tr");
            for (let i = 0; i < colCount; i++) {
                const newCell = document.createElement("td");
                newCell.style.padding = "8px";
                newCell.style.border = "1px solid #ccc";
                newCell.innerHTML = "&nbsp;";
                newRow.appendChild(newCell);
            }

            if (tr.nextSibling) {
                tbody.insertBefore(newRow, tr.nextSibling);
            } else {
                tbody.appendChild(newRow);
            }
        },

        insertColumnAfterCursor() {
            const selection = window.getSelection();
            if (!selection || selection.rangeCount === 0) return;

            const range = selection.getRangeAt(0);
            const node = range.startContainer;

            const td =
                node.nodeType === 3
                    ? node.parentNode.closest("td, th")
                    : node.closest("td, th");
            if (!td) return;

            const tr = td.closest("tr");
            const table = td.closest("table");
            if (!tr || !table) return;

            const colIndex = Array.from(tr.cells).indexOf(td);

            table.querySelectorAll("tr").forEach((row, rowIndex) => {
                const cells = Array.from(row.children);
                let newCell;

                if (
                    row.parentElement.tagName === "THEAD" ||
                    row.querySelector("th")
                ) {
                    newCell = document.createElement("th");
                    newCell.style.padding = "8px";
                    newCell.style.border = "1px solid #ccc";
                    newCell.style.backgroundColor = "#f5f5f5";
                    newCell.innerText = `Title ${colIndex + 2}`;
                } else {
                    newCell = document.createElement("td");
                    newCell.style.padding = "8px";
                    newCell.style.border = "1px solid #ccc";
                    newCell.innerHTML = "&nbsp;";
                }

                if (colIndex < cells.length - 1) {
                    row.insertBefore(newCell, cells[colIndex + 1]);
                } else {
                    row.appendChild(newCell);
                }
            });
        },

        deleteTableAtCursor() {
            const selection = window.getSelection();
            if (!selection || selection.rangeCount === 0) return;

            const range = selection.getRangeAt(0);
            const node = range.startContainer;

            const table =
                node.nodeType === 1
                    ? node.closest("table")
                    : node.parentElement?.closest("table");
            if (table) {
                table.remove();
            }
        },

        deleteRowAtCursor() {
            const selection = window.getSelection();
            if (!selection || selection.rangeCount === 0) return;

            const range = selection.getRangeAt(0);
            let node = range.startContainer;

            if (node.nodeType === 3) {
                node = node.parentElement;
            }

            const row = node.closest("tr");
            if (row) {
                row.remove();
            }
        },

        deleteColumnAtCursor() {
            const selection = window.getSelection();
            if (!selection || selection.rangeCount === 0) return;

            const range = selection.getRangeAt(0);
            let node = range.startContainer;

            if (node.nodeType === 3) {
                node = node.parentElement;
            }

            const cell = node.closest("td, th");
            if (!cell) return;

            const table = cell.closest("table");
            if (!table) return;

            const cellIndex = cell.cellIndex;

            for (const row of table.rows) {
                if (row.cells[cellIndex]) {
                    row.deleteCell(cellIndex);
                }
            }
        },

        clearFormat() {
            const selection = window.getSelection();
            if (!selection.rangeCount) return;

            const range = selection.getRangeAt(0);
            const selectedText = selection.toString();

            if (!selectedText.trim()) return;

            range.deleteContents();

            const textNode = document.createTextNode(selectedText);
            range.insertNode(textNode);

            selection.removeAllRanges();
            selection.addRange(range);
        },
    },
};
</script>

<style lang="scss">
.q-editor {
    border: 1px solid #ddd;
    border-radius: 4px;

    &__toolbar {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        background-color: #f5f5f5;
    }

    &__content {
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        background-color: #fff;
        min-height: 200px;
    }
}

.q-editor table {
    width: 100%;
    border-collapse: collapse;
    margin: 1em 0;
}

.q-editor table td {
    padding: 8px;
    border: 1px solid #ddd;
}

.q-editor ul,
.q-editor ol {
    margin: 0 0 1em 1.5em;
    padding: 0;
    list-style-position: inside;
}
.q-editor ul {
    list-style-type: disc;
}
.q-editor ol {
    list-style-type: decimal;
}
.q-editor li {
    margin: 0.25em 0;
    padding: 0;
}
.q-editor li ul,
.q-editor li ol {
    margin-bottom: 0;
}
.q-editor li li {
    margin: 0.125em 0;
}
.q-editor li li ul,
.q-editor li li ol {
    margin-top: 0;
}
.q-editor blockquote {
    border-left: 4px solid rgba(0, 0, 0, 0.12);
    margin: 0 0 1em;
    padding: 0 1em;
    color: rgba(0, 0, 0, 0.54);
}
.q-editor blockquote p {
    margin: 0.5em 0;
    padding: 0;
}
.q-editor pre {
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
    font-family: monospace;
    margin: 0 0 1em;
    padding: 1em;
    overflow-x: auto;
}
.q-editor code {
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
    font-family: monospace;
    padding: 0.2em 0.4em;
}
.q-editor table button {
    background: #f0f0f0;
    border: 1px solid #ccc;
    padding: 2px 6px;
    font-size: 0.75em;
    cursor: pointer;
    border-radius: 2px;
}

.q-editor table button:hover {
    background: #e0e0e0;
}
</style>
