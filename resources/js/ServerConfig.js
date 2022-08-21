import {url_append} from "./laravel-datagrid";
export class ServerConfig {
    static search(keyword) {
        return {
            server: {
                url: (prev, keyword) => url_append(prev, `search=${keyword}`),
            },
            debounceTimeout: 500,
            keyword: keyword
        }
    }

    static pagination(page, limit) {
        return {
            limit: limit,
            server: {
                url: (prev, page, limit) => url_append(prev, `limit=${limit}&page=${page + 1}`)
            },
            page: page,
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

    static server(rows) {
        return {
            url: this.baseUrl,
            data: (opts) => {
                // When first loading the grid don't do ajax call
                if (!ServerConfig.initComplete) {
                    ServerConfig.initComplete = true;

                    return rows;
                }

                return new Promise((resolve, reject) => {
                    axios.get(opts.url).then(result => {
                        history.pushState({}, '', opts.url);
                        resolve(result.data);
                    });
                });
            }
        }
    }
}

ServerConfig.initComplete = false;
