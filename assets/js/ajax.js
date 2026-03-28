// ============================================================
// AnkForum - ajax.js
// AJAX helper utilities
// ============================================================

'use strict';

window.API = {
    csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    async request(url, options = {}) {
        const defaults = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': this.csrfToken(),
            }
        };

        // Merge headers
        if (options.headers) {
            options.headers = { ...defaults.headers, ...options.headers };
        } else {
            options.headers = { ...defaults.headers };
        }

        // Don't set Content-Type for FormData (browser sets it with boundary)
        if (options.body instanceof FormData) {
            delete options.headers['Content-Type'];
        }

        try {
            const res = await fetch(url, { ...options, headers: options.headers });

            let data;
            try {
                data = await res.json();
            } catch(e) {
                throw { error: 'Server trả về phản hồi không hợp lệ (HTTP ' + res.status + ')' };
            }

            if (!res.ok) {
                throw data; // Preserve full error object from server
            }

            return data;
        } catch (err) {
            if (err.status === 401 || err.error?.includes?.('đăng nhập')) {
                Toast.info('Vui lòng đăng nhập để tiếp tục');
                setTimeout(() => location.href = '/?page=login', 1500);
            }
            throw err;
        }
    },

    get(url) {
        return this.request(url, { method: 'GET' });
    },

    post(url, data) {
        if (data instanceof FormData) {
            return this.request(url, { method: 'POST', body: data });
        }
        return this.request(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });
    },

    delete(url, data = {}) {
        return this.request(url, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });
    },

    patch(url, data) {
        if (data instanceof FormData) {
            return this.request(url, { method: 'POST', body: data });
        }
        return this.request(url, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });
    },
};
