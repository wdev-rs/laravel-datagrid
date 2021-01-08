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
                        return h('div', {className: "text-center"},
                            deleteAction.call(this, row.cells[0].data,row.cells[1].data)
                        )
                    }
                }]
            ),
            search: ServerConfig.search.call(this),
            pagination: ServerConfig.pagination.call(this),
            sort: ServerConfig.sort.call(this),
            server: ServerConfig.server.call(this)
        }
    },
    mounted() {
    },
    methods:{
    }
}
</script>
