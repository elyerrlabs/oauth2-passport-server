<!--
OAuth2 Passport Server - Standardized Button Component
Copyright (c) 2026 Elvis Yerel Roman Concha
SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <div>
        <component
            :is="tag"
            :type="tag === 'button' ? type : undefined"
            :href="tag === 'a' ? to : undefined"
            :disabled="disabled"
            :class="buttonClasses"
            :aria-label="ariaLabel"
            @click="handleClick"
        >
            <!-- Loading Spinner -->
            <svg
                v-if="loading"
                :class="spinnerClasses"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>

            <!-- Left Icon -->
            <i
                v-if="resolvedLeftIcon && !loading"
                :class="[resolvedLeftIcon, 'text-base', iconSpacing]"
                aria-hidden="true"
            ></i>

            <!-- Slot for content -->
            <span class="flex items-center gap-2">
                <slot>{{ label }}</slot>
            </span>

            <!-- Right Icon -->
            <i
                v-if="rightIcon && !loading"
                :class="[rightIcon, 'text-base', iconSpacing]"
                aria-hidden="true"
            ></i>
        </component>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    // Variants
    variant: {
        type: String,
        default: "primary",
        validator: (value) =>
            [
                "primary",
                "secondary",
                "outline",
                "ghost",
                "danger",
                "success",
                "warning",
                "dark",
                "light",
            ].includes(value),
    },
    size: {
        type: String,
        default: "md",
        validator: (value) => ["xs", "sm", "md", "lg", "xl"].includes(value),
    },

    // States
    disabled: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    fullWidth: {
        type: Boolean,
        default: false,
    },
    rounded: {
        type: Boolean,
        default: false,
    },
    round: {
        type: Boolean,
        default: false,
    },

    // Content
    label: {
        type: String,
        default: "",
    },
    icon: {
        type: String,
        default: "",
    },
    leftIcon: {
        type: String,
        default: "",
    },
    rightIcon: {
        type: String,
        default: "",
    },
    ariaLabel: {
        type: String,
        default: "",
    },

    // Router / Link props
    to: {
        type: String,
        default: "",
    },
    as: {
        type: String,
        default: "button", // 'button', 'a', 'router-link'
    },
    type: {
        type: String,
        default: "button",
    },
});

const emit = defineEmits(["click"]);

const tag = computed(() => {
    if (props.as === "a") return "a";
    if (props.as === "router-link") return "router-link";
    return "button";
});

const isRound = computed(() => props.round || props.rounded);
const resolvedLeftIcon = computed(() => props.leftIcon || props.icon);

const buttonClasses = computed(() => {
    const baseClasses = [
        "inline-flex",
        "items-center",
        "justify-center",
        "font-medium",
        "transition-all",
        "duration-200",
        "focus:outline-none",
        "focus:ring-2",
        "focus:ring-offset-2",
        "disabled:opacity-50",
        "disabled:cursor-not-allowed",
        "disabled:pointer-events-none",
        "cursor-pointer",
    ];

    // Size classes
    const sizeClasses = {
        xs: "px-2 py-1 text-xs gap-1",
        sm: "px-3 py-2 text-sm gap-2",
        md: "px-4 py-2 text-sm gap-2",
        lg: "px-5 py-3 text-base gap-2",
        xl: "px-6 py-3 text-base gap-3",
    };

    const roundSizeClasses = {
        xs: "h-7 w-7 p-0 text-xs",
        sm: "h-9 w-9 p-0 text-sm",
        md: "h-10 w-10 p-0 text-sm",
        lg: "h-12 w-12 p-0 text-base",
        xl: "h-14 w-14 p-0 text-base",
    };

    // Variant classes
    const variantClasses = {
        primary:
            "bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 focus:ring-slate-500 dark:focus:ring-slate-400 shadow-sm",
        secondary:
            "bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 focus:ring-slate-400",
        outline:
            "border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 focus:ring-slate-400",
        ghost: "text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:ring-slate-400",
        danger: "bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm",
        success:
            "bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 shadow-sm",
        warning:
            "bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-400 shadow-sm",
        dark: "bg-slate-800 text-white hover:bg-slate-900 focus:ring-slate-600 shadow-sm",
        light: "bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 focus:ring-slate-400",
    };

    const classes = [
        ...baseClasses,
        isRound.value ? roundSizeClasses[props.size] : sizeClasses[props.size],
        variantClasses[props.variant],
    ];

    if (props.fullWidth) classes.push("w-full");
    if (isRound.value) classes.push("rounded-full");
    else classes.push("rounded-lg");

    return classes;
});

const iconSpacing = computed(() => {
    if (isRound.value) return "";
    if (props.size === "xs") return "mr-1";
    if (props.size === "sm") return "mr-1.5";
    if (props.size === "md") return "mr-2";
    if (props.size === "lg") return "mr-2.5";
    return "mr-3";
});

const spinnerClasses = computed(() => {
    return ["animate-spin", "h-4", "w-4", isRound.value ? "" : "-ml-1 mr-2"];
});

function handleClick(event) {
    if (props.disabled || props.loading) return;
    emit("click", event);
}
</script>
