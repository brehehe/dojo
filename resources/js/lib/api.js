export function csrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');
}

export function jsonHeaders() {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    const token = csrfToken();
    if (token) {
        headers['X-CSRF-TOKEN'] = token;
    }

    return headers;
}

export function postJson(url, body = {}) {
    return fetch(url, {
        method: 'POST',
        headers: jsonHeaders(),
        credentials: 'same-origin',
        body: JSON.stringify(body),
    });
}
