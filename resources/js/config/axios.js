import axios from "axios";

export const $server = axios.create({
  timeout: 5000,
  withCredentials: true,
  headers: {
    "Accept-Language": navigator.language,
    "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
    Accept: "application/json",
    //     "X-Socket-ID": window.$echo.socket_id,
  },
});
