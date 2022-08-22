import Vue from 'vue'

import DataGrid from "./components/DataGrid.vue";
Vue.component('data-grid', DataGrid);

export function url_append(url, query) {
    if (!url) {
        return '';
    }
    return url + (url.indexOf('?') > -1 ? '&' : '?') + query;
}
