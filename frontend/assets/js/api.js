// assets/js/api.js
(() => {
  const API_BASE = "http://94.228.124.202:8080/api";
  const DEFAULT_TIMEOUT_MS = 15000;

  const withTimeout = (promise, ms = DEFAULT_TIMEOUT_MS) =>
    Promise.race([
      promise,
      new Promise((_, reject) =>
        setTimeout(() => reject(new Error("Превышено время ожидания")), ms)
      ),
    ]);

  async function http(method, path, body, headers = {}) {
    const url = path.startsWith("http") ? path : `${API_BASE}${path}`;
    const cfg = {
      method,
      headers: {
        "Accept": "application/json",
        ...(body && typeof body === "object" && !(body instanceof FormData)
          ? { "Content-Type": "application/json" }
          : {}),
        ...headers,
      },
      credentials: "omit",
    };

    if (body) {
      cfg.body =
        body instanceof FormData ? body : JSON.stringify(body);
    }

    let resp;
    try {
      resp = await withTimeout(fetch(url, cfg));
    } catch (e) {
      return { data: null, error: e };
    }

    let data = null;
    try {
      // На случай пустого ответа
      const text = await resp.text();
      data = text ? JSON.parse(text) : null;
    } catch (e) {
      // сервер вернул не-JSON
      return {
        data: null,
        error: new Error(`Некорректный ответ сервера (${resp.status})`),
      };
    }

    if (!resp.ok) {
      const msg =
        (data && (data.message || data.error)) ||
        `Ошибка ${resp.status}`;
      const err = new Error(msg);
      err.status = resp.status;
      err.details = data;
      return { data: null, error: err };
    }

    return { data, error: null };
  }

  // ===== Публичные методы API =====

  // Сервисы (для каталога/карточек)
  async function getServices(params = {}) {
    const query = new URLSearchParams(params).toString();
    return http("GET", `/services${query ? `?${query}` : ""}`);
  }

  // Создание заявки/бронирования
  // payload: { name, phone, email?, date_from, date_to, guests, comment?, service_id? }
  async function createApplication(payload) {
    return http("POST", `/applications`, payload);
  }

  // Пинг (удобно проверить CORS/связь)
  async function health() {
    // если нет отдельного эндпоинта — можно пинговать /services
    return http("GET", `/services?limit=1`);
  }

  // Экспорт в глобал
  window.Api = {
    getServices,
    createApplication,
    health,
    _http: http, // на всякий случай
  };
})();