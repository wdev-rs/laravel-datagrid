Vue.component('data-grid', require('./components/DataGrid').default);

import { GridGlobal } from 'gridjs-vue'

Vue.use(GridGlobal)

export function url_append(url, query) {
    if (!url) {
        return '';
    }
    return url + (url.indexOf('?') > -1 ? '&' : '?') + query;
}
