<script>
import Pagination from "./Pagination.vue";
import Actions from "./Actions.vue";
import SelectInput from "./SelectInput.vue";
import InputLabel from "./InputLabel.vue";
import PrimaryButton from "./PrimaryButton.vue";
import PlayIcon from "./Icons/PlayIcon.vue";
import SecondaryButton from "./SecondaryButton.vue";
import MassActions from "./MassActions.vue";
import {ServerConfig} from "../ServerConfig";
import "../css/datagrid.min.css";

const searchParams = new URLSearchParams(window.location.search);

export default {
    components: {
        MassActions,
        SecondaryButton, PlayIcon, PrimaryButton, InputLabel, SelectInput, Actions, Pagination},
    props: {
        columns: Array,
        rows: Object,
        massActions: {
            type: Array,
            default: []
        }
    },

    data() {
        return {
            data_rows: this.rows,
            data_columns: this.columns,
            searchKeyword: searchParams.get('search') || '',
            timer: null,
            order: searchParams.get('order') || [],
            dir: searchParams.get('dir') || [],
            selectedIds: [],
            allSelected: false,
            server: new ServerConfig()
        }
    },
    watch: {
        // whenever question changes, this function will run
        selectedIds(selectedIds, oldSelectedIds) {
            this.$emit('rowsSelected', selectedIds);
        },
        rows(newRows, oldRows){
            this.data_rows = newRows;
        }
    },
    methods: {
        search() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }

            this.timer = setTimeout(() => {
                // this.$inertia.get(route(route().current()), {search: this.searchKeyword}, {preserveState: true});
                this.server.search(this.searchKeyword).then(
                    (result) => this.data_rows = result
                );
            }, 500);
        },
        sort(column, multisort) {

            this.determineSortOrder(column, multisort);

            this.server.sort(this.order, this.dir).then(
                (result) => this.data_rows = result
            );
        },
        sortIcon(column) {
            let orderIndex = this.order.indexOf(column.id);

            if (orderIndex !== -1) {
                switch (this.dir[orderIndex]) {
                    case 'asc':
                        return '&darr;'
                    case 'desc':
                        return '&uarr;'
                }
            }

            return '';
        },
        determineSortOrder(column, multisort) {
            let orderIndex = this.order.indexOf(column.id);

            let dir = 'asc';

            // It was not sorted yet by this column add asc sort order
            if (orderIndex === -1) {
                this.order.push(column.id);
                this.dir.push(dir);
                return;
            }

            dir = this.dir[orderIndex] === 'asc' ? 'desc' : null;

            // It is not multisort, clear the sort orders and set the new sort order by this column
            if (!multisort) {
                this.order = []
                this.dir = [];
                if (dir !== null) {
                    this.order.push(column.id);
                    this.dir.push(dir);
                }

                return;
            }

            // It is multisort, change the direction or clean the sort order
            if (dir === null) {
                delete this.dir[orderIndex];
                delete this.order[orderIndex];
            } else {
                this.dir[orderIndex] = dir
            }
        },
        dot(path, object) {
            return path.split('.').reduce(function (prev, curr) {
                return prev ? prev = prev[curr] : undefined;
            }, object);
        },
        toggleSelection(id)
        {
            let index = this.selectedIds.indexOf(id)

            if(index > -1) {
                this.selectedIds.splice(index, 1);
                return;
            }

            this.selectedIds.push(id);
        },
        toggleSelectAll(selected)
        {
            let key = this.data_rows.key;

            if (selected) {
                this.selectedIds = this.data_rows.data.map((row) => row[key]);
                return;
            }

            this.selectedIds = [];

        }
    },


    mounted() {

    }
}
</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="relative overflow-x-auto">
                <slot name="filters">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-3 pb-1 bg-white dark:bg-gray-900">
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input v-model="searchKeyword" @keyup="search" name="search" type="text" id="table-search"
                                       class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       :placeholder="'Search'"
                                >
                            </div>
                        </div>
                    </div>
                </slot>
                <div class="mb-0 border-b border-gray-200 grid grid-cols-1 sm:grid-cols-2 bg-gray-50 rounded-t">
                    <div class="justify-self-start w-100">
                        <slot name="massactions" v-bind:selectedIds="selectedIds">
                            <MassActions v-if="massActions.length" :mass-actions="massActions" :disabled="selectedIds.length == 0" @run="(e) => $emit(e.action, selectedIds)"></MassActions>
                        </slot>
                    </div>
                    <div class=" justify-self-end mt-auto">
                        <slot name="createnew">
                        </slot>
                    </div>
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th v-if="massActions.length" scope="col" class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox" @change="this.toggleSelectAll(this.allSelected)" v-model="allSelected"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                        </div>
                    </th>

                    <th v-for="(column, index) in columns" scope="col" class="px-4 py-3">
                        <a v-if="column.sortable " href="#" @click.prevent="(event) => sort(column, event.shiftKey)">
                            {{ column.name }} <span v-html="sortIcon(column)"></span></a>
                        <span v-else>{{ column.name }}</span>
                    </th>
                    <th class="px-6 py-3">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, index) in data_rows.data" :key=index @click="toggleSelection(row[data_rows.key])"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td v-if="massActions.length" class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" :value="row[data_rows.key]" v-model="selectedIds"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                        </div>
                    </td>

                    <td v-for="(column, index) in data_columns" class="px-4 py-4" v-html="row[column.id] || dot(column.id, row)">
                    </td>
                    <td class="px-1 py-4">
                        <slot name="actions" v-bind:row="row" v-bind:key="data_rows.key">
                            <Actions :row="row" :primarykey="data_rows.key"></Actions>
                        </slot>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination :rows="this.data_rows"></pagination>
        </div>
    </div>
</template>
