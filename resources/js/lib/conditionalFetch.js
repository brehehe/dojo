const etags = new Map();

export async function conditionalJsonFetch(input, options = {}) {
    const url = typeof input === 'string' ? input : input.url;
    const headers = new Headers(options.headers || {});
    const etag = etags.get(url);

    if (etag) {
        headers.set('If-None-Match', etag);
    }

    const response = await fetch(input, {
        ...options,
        cache: options.cache ?? 'no-cache',
        headers,
    });

    if (response.status === 304) {
        return { response, data: null, notModified: true };
    }

    if (!response.ok) {
        return { response, data: null, notModified: false };
    }

    const contentType = response.headers.get('Content-Type') || '';
    if (!contentType.includes('application/json')) {
        return { response, data: null, notModified: false };
    }

    let data = null;
    try {
        data = await response.json();
    } catch {
        return { response, data: null, notModified: false };
    }

    const nextEtag = response.headers.get('ETag');
    if (nextEtag) {
        etags.set(url, nextEtag);
    }

    return { response, data, notModified: false };
}

export function clearConditionalFetchCache(input = null) {
    if (!input) {
        etags.clear();
        return;
    }

    etags.delete(typeof input === 'string' ? input : input.url);
}
