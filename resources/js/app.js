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
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { setupI18n, __ } from "./app/config/locale.js";

import { customComponents } from "./app/config/customComponents.js";
//import { $echo } from "./app/config/echo.js";
import { $server } from "./app/config/axios.js";
import { layouts } from "./app/config/layouts.js";

//Quasar
import { Quasar, Ripple, ClosePopup, Notify, Dialog, Loading } from "quasar";
import "quasar/dist/quasar.css";
import "@quasar/extras/material-icons/material-icons.css";
import { QComponents } from "./app/config/quasar.js";

//Vue date picker
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
//import iconSet from "quasar/icon-set/material-icons.js";

//icons https://pictogrammers.com/library/mdi/
import "@mdi/font/css/materialdesignicons.css";

const i18n = setupI18n();
window.__ = __;

createInertiaApp({
  resolve: (name) =>
    resolvePageComponent(
      `./app/pages/${name}.vue`,
      import.meta.glob("./app/pages/**/*.vue")
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    customComponents.forEach((index) => {
      app.component(index[0], index[1]);
    });

    layouts.forEach((index) => {
      app.component(index[0], index[1]);
    });

    app.use(Quasar, {
      plugins: {
        Notify,
        Dialog,
        Loading,
      },
      directives: {
        Ripple,
        ClosePopup,
      },
      //iconSet: iconSet,
    });

    QComponents.forEach((item) => {
      app.component(item.name, item);
    });

    // app.config.globalProperties.$echo = $echo;
    app.config.globalProperties.$server = $server;
    app.config.globalProperties.__ = __;

    app.component("VueDatePicker", VueDatePicker);
    app.use(plugin);
    //  app.use(i18n);
    app.mount(el);
  },
});
