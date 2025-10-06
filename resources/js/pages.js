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
import flatpickr from "flatpickr";
import "../css/app.css";
import "@tailwindplus/elements";
import { $notify } from "./app/config/notify.js";
window.$notify = $notify;

import "./config/editor.js";

import Quill from "quill";
import "quill/dist/quill.core.css";
window.Quill = Quill;

flatpickr(".date", {
  dateFormat: "Y-m-d",
  locale: "en",
  maxDate: "today",
});

flatpickr(".datetime", {
  enableTime: true,
  dateFormat: "Y-m-d H:i",
  locale: "en",
  maxDate: "today",
  minuteIncrement: 1,
});

flatpickr(".range", {
  mode: "range",
  enableTime: true,
  dateFormat: "Y-m-d H:i",
  locale: "en",
  maxDate: "today",
  minuteIncrement: 1,
});
