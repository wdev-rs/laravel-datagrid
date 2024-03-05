export class ServerConfig {

    constructor() {
    }

    search(keyword) {
        const params = this.params();
        params.delete('search');
        params.delete('page');
        params.delete('limit');

        if (keyword.length) {
            params.set('search', keyword)
        }

        return this.get(params);
    }

    pagination(page, limit) {
        const params = this.params();

        params.set('page', page)
        params.set('limit', limit)

        return this.get(params);
    }

    sort(order, dir) {
        const params = this.params();

        params.delete('order[]');
        params.delete('dir[]');

        if (order.length) {
            order.forEach(function (ord, index) {
                params.append('order[]', ord)
                params.append('dir[]', dir[index] || 'asc')
            });
        }

        return this.get(params);
    }

    get(params) {
        params.delete('t');

        const historyUrl = this.baseUrl() + (params.size ? '?' + params.toString() : '');

        params.set('t', Date.now());
        const nocacheUrl = this.baseUrl() + '?' + params.toString();

        return new Promise((resolve, reject) => {
            axios.get(nocacheUrl).then(result => {
                history.pushState({}, '', historyUrl);
                resolve(result.data);
            });
        });
    }

    baseUrl() {
        return (window.location.origin + window.location.pathname);
    }

    params() {
        return new URLSearchParams(window.location.search)
    }
}
