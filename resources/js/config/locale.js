// i18n.js
import { createI18n } from "vue-i18n";

let i18n = null;

export function setupI18n() {
  try {
    const locale = window.locale || "en";
    const messages = { [locale]: window.translation || {} };

    i18n = createI18n({
      legacy: false,
      locale,
      fallbackLocale: "en",
      messages,
    });

    return i18n;
  } catch (error) {
    i18n = createI18n({
      legacy: false,
      locale: "en",
      fallbackLocale: "en",
      messages: { en: {} },
    });
    return i18n;
  }
}

export function __(key, params = {}) {
  if (!i18n) return key;

  let translation = i18n.global.t(key);

  Object.keys(params).forEach((param) => {
    const regex = new RegExp(param, "g");
    translation = translation.replace(regex, params[param]);
  });

  return translation;
}

export async function setLocale(locale) {
  if (!i18n) return;
  const response = await $server.get(`/api/public/language/${locale}`);
  i18n.global.setLocaleMessage(locale, response.data);
  i18n.global.locale.value = locale;
}

export default i18n;
