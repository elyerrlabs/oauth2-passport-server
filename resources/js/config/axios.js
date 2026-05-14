import axios from "axios";

axios.defaults.timeout = 20000;
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
  withCredentials: true,
  headers: {
    Accept: "application/json",
  },
});

axios.interceptors.response.use(
  (response) => response,
  (error) => {
    const { response } = error;

    if (response) {
      switch (response.status) {
        case 419:
        case 423:
        case 405:
        case 401:
          window.location.reload();
          break;
      }
    }

    return Promise.reject(error);
  }
);