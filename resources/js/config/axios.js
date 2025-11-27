import axios from "axios";

// Header to set local time for the user
axios.defaults.headers.common["X-LOCALTIME"] =
  Intl.DateTimeFormat().resolvedOptions().timeZone;
// Detect current language to the browser for the user
axios.defaults.headers.common["Accept-Language"] = navigator.language;
// Socket id Coming soon
//axios.defaults.headers.common["X-Socket-ID"] = window.$echo.socket_id;

// Global environment for inertia
window.axios = axios;

export const $server = axios.create({
  timeout: 5000,
  withCredentials: true,
  headers: {
    Accept: "application/json",
  },
});
