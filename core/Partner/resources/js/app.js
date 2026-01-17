/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
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