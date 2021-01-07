<template>
    <div class="data-grid px-4 mt-4">
        <grid ref="grid" :cols="cols" :search="search" :server="server" :pagination="pagination" :sort="sort" :autoWidth="false"></grid>
    </div>
</template>

<script lang="js">
import Grid from 'gridjs-vue';
import {html, h} from 'gridjs';
import {url_append} from "../laravel-datagrid";
import Swal from "sweetalert2";

export default {
    name: 'DataGrid',
    components: {
        Grid
    },
    props: [
        'columns',
        'baseUrl'
    ],
    data() {
        return {
            cols: this.columns.map((col) => {col.formatter = (cell) => html(cell); return col;}).concat(
                [{
                    name: 'Actions',
                    sort: false,
                    width: 50,
                    formatter: (cell, row) => {
                        return h('a', {
                            href:'#',
                            className: 'btn btn-sm btn-danger mx-1 datatable-delete-action',
                            onClick: () => this.delete(row.cells[0].data,row.cells[1].data)
                        }, h('i',{className: "fas fa-trash"}));
                    }
                }]
            ),
            search: {
                server: {
                    url: (prev, keyword) => url_append(prev, `search=${keyword}`),
                }
            },
            pagination: {
                limit: 15,
                server: {
                    url: (prev, page, limit) => url_append(prev, `limit=${limit}&page=${page + 1}`)
                }
            },
            sort: {
                multiColumn: true,
                server: {
                    url: (prev, columns) => {
                        if (!columns.length) return prev;

                        return url_append(prev, columns.map( col => `order[]=${this.cols[col.index].id}&dir[]=${col.direction === 1 ? 'asc' : 'desc'}`)
                            .join('&'));
                    }
                }
            },
            server: {
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
                },
            }
        }
    },
    mounted() {
        console.log(this.cols);
    },
    methods:{
        delete(id, name) {
            Swal.fire({
                title: 'Are you sure?',
                html: '<div>This action will delete ' + name.trim() + '. You won\'t be able to revert this!</div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                animation: false
            }).then((result) => {
                if (result.value) {
                    axios.delete(this.baseUrl + '/' + id)
                        .then((data) => {
                            this.$refs.grid.update();
                            if (!data.data) {
                                throw Error;
                            }
                            Swal.fire({
                                    title: 'Deleted!',
                                    html: '<div>' + name + ' has been deleted.</div>',
                                    icon: 'success',
                                    animation: false
                                }
                            )
                        })
                        .catch(() => {
                            Swal.fire({
                                    title: 'Error!',
                                    html:'<div>' + name + ' delete failed.</div>',
                                    icon: 'error',
                                    animation: false
                                }
                            )
                        });
                }
            });
        }
    }
}
</script>
