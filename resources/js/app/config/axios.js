export function createServer(config = {}) {
  const defaultConfig = {
    timeout: 5000,
    withCredentials: true,
    headers: {
      "Accept-Language": navigator.language,
      "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
      Accept: "application/json",
    },
  };

  const instance = {
    interceptors: {
      request: [],
      response: [],
    },

    useRequestInterceptor(fn) {
      this.interceptors.request.push(fn);
    },

    useResponseInterceptor(fn) {
      this.interceptors.response.push(fn);
    },

    async request(url, options = {}) {
      let mergedOptions = {
        ...defaultConfig,
        ...config,
        ...options,
        headers: {
          ...defaultConfig.headers,
          ...(config.headers || {}),
          ...(options.headers || {}),
        },
      };

      if (mergedOptions.params) {
        const query = new URLSearchParams(mergedOptions.params).toString();
        url += (url.includes("?") ? "&" : "?") + query;
        delete mergedOptions.params;
      }

      for (const interceptor of this.interceptors.request) {
        mergedOptions = (await interceptor(mergedOptions)) || mergedOptions;
      }

      const controller = new AbortController();
      const timeout = setTimeout(
        () => controller.abort(),
        mergedOptions.timeout
      );

      try {
        const res = await fetch(url, {
          ...mergedOptions,
          credentials: mergedOptions.withCredentials
            ? "include"
            : "same-origin",
          signal: controller.signal,
        });

        clearTimeout(timeout);

        let data;
        try {
          data = await res.json();
        } catch {
          data = null;
        }

        const response = { status: res.status, data, headers: res.headers };

        if (!res.ok) {
          throw { response };
        }

        let interceptedResponse = response;
        for (const interceptor of this.interceptors.response) {
          interceptedResponse =
            (await interceptor(interceptedResponse)) || interceptedResponse;
        }

        return interceptedResponse;
      } catch (error) {
        clearTimeout(timeout);
        if (error.name === "AbortError") {
          throw {
            response: { status: 408, data: { message: "Request Timeout" } },
          };
        }
        throw error;
      }
    },

    get(url, options = {}) {
      return this.request(url, { ...options, method: "GET" });
    },

    post(url, data, options = {}) {
      return this.request(url, {
        ...options,
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
          ...(options.headers || {}),
        },
      });
    },

    put(url, data, options = {}) {
      return this.request(url, {
        ...options,
        method: "PUT",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
          ...(options.headers || {}),
        },
      });
    },

    delete(url, options = {}) {
      return this.request(url, { ...options, method: "DELETE" });
    },
  };

  return instance;
}

export const $server = createServer();
