<template>
    <div class="data-grid">
        <grid ref="grid"
              :cols="cols"
              :search="search"
              :server="server"
              :pagination="pagination"
              :sort="sort"
              :autoWidth="false">
        </grid>
    </div>
</template>

<script lang="js">
import Grid from 'gridjs-vue';
import {html, h} from 'gridjs';
import {deleteAction} from "../actions/delete";
import {ServerConfig} from "../ServerConfig";

export default {
    name: 'DataGrid',
    components: {
        Grid
    },
    props: [
        'rows',
        'columns',
        'baseUrl'
    ],
    data() {
        return {
            cols: this.columns.map((col) => {
                col.formatter = (cell) => html(cell);
                return col;
            }).concat(
                [{
                    name: 'Actions',
                    sort: false,
                    width: 50,
                    formatter: (cell, row) => {
                        return h('div', {className: "text-center"},
                            deleteAction.call(this, row.cells[0].data, row.cells[1].data)
                        )
                    }
                }]
            ),
            serverConfig: new ServerConfig(this.baseUrl, this.columns),
        }
    },
    computed: {
        search() {
            let rows = this.rows || {};
            return this.serverConfig.search(rows.search || '');
        },
        pagination() {
            let rows = this.rows || {};
            return this.serverConfig.pagination((rows.currentPage || 1) - 1, rows.limit || 15);
        },
        sort() {
            return this.serverConfig.sort()
        },
        server() {
            let rows = this.rows || {};
            return this.serverConfig.server(rows);
        }
    },
    mounted() {
    },
    methods: {}
}
</script>
