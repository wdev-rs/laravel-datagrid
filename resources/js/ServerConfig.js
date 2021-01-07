import {url_append} from "./laravel-datagrid";
export class ServerConfig {
    static search() {
        return {
            server: {
                url: (prev, keyword) => url_append(prev, `search=${keyword}`),
            }
        }
    }

    static pagination() {
        return {
            limit: 15,
            server: {
                url: (prev, page, limit) => url_append(prev, `limit=${limit}&page=${page + 1}`)
            }
        }
    }

    static sort() {
        return {
            multiColumn: true,
            server: {
                url: (prev, columns) => {
                    if (!columns.length) return prev;

                    return url_append(prev, columns.map(col => `order[]=${this.cols[col.index].id}&dir[]=${col.direction === 1 ? 'asc' : 'desc'}`)
                        .join('&'));
                }
            }
        }
    }

    static server() {
        return {
            url: this.baseUrl,
            data: (opts) => {
                return new Promise((resolve, reject) => {
                    axios.get(opts.url).then(result => {
                        resolve({
                            data: result.data.data,
                            total: result.data.total
                        });
                    });
                });
            }
        }
    }
}
