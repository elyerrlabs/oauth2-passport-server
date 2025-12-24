/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { setupI18n, __ } from "@/config/locale.js";
import { $notify } from "@/config/notify.js";

//import { $echo } from "./config/echo.js";
import { $server } from "@/config/axios.js";
import VueSweetalert2 from "vue-sweetalert2";

setupI18n();
window.__ = __;
window.$notify = $notify;
window.$server = $server;

createInertiaApp({
  resolve: (name) => require(`./pages/${name}.vue`).default,
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    // app.config.globalProperties.$echo = $echo;
    app.config.globalProperties.$server = $server;
    app.config.globalProperties.__ = __;

    app.use(plugin);
    app.use(VueSweetalert2);
    app.mount(el);
  },
}); 