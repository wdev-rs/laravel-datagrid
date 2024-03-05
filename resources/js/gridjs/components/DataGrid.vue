<template>
    <div ref="grid" class="data-grid">
    </div>
</template>

<script lang="js">
import {html, h, Grid} from 'gridjs';
import {deleteAction} from "../actions/delete";
import {ServerConfig} from "../ServerConfig";
import "gridjs/dist/theme/mermaid.css";

export default {
    name: 'DataGrid',
    components: {
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
                    width: 120,
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
        const grid = new Grid({
            columns: this.cols,
            server: this.server,
            search: this.search,
            sort: this.sort,
            pagination: this.pagination,
            autoWidth: false,
        });

        grid.render(this.$refs.grid);
    },
    methods: {}
}
</script>
