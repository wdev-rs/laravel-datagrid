
export function url_append(url, query) {
    if (!url) {
        return '';
    }
    return url + (url.indexOf('?') > -1 ? '&' : '?') + query;
}
