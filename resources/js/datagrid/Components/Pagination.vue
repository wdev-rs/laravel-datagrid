<script>

export default {
    components: {},
    props: {
       rows: Object,
    },

    data() {
        return {
        }
    },

    methods: {
    },

    mounted() {
    },

    computed: {
        showingFrom() {
            return (this.rows.currentPage - 1) * this.rows.limit + 1;
        },
        showingTo() {
            const to = this.showingFrom + this.rows.limit - 1;

            if (to > this.rows.total) {
                return this.rows.total;
            }

            return to;
        }
    }
}
</script>

<template>
    <nav class="flex items-center justify-between pt-4 pr-4" aria-label="Table navigation">
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400"><span class="font-semibold text-gray-900 dark:text-white">{{showingFrom}}-{{showingTo}}</span> <span class="font-semibold text-gray-900 dark:text-white">/ {{rows.total}}</span></span>
        <ul class="inline-flex -space-x-px text-sm h-8">
            <li v-for="(link, key) in rows.paginationLinks">
                <a v-if="link.url !== null" :href="link.url" v-html="link.label" aria-current="page" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight border border-gray-300" :class="link.active ? 'bg-blue-50 hover:bg-blue-100 hover:text-blue-700 text-blue-600' : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700'" >
                </a>
                <span v-else v-html="link.label" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"></span>
            </li>
        </ul>
    </nav>
</template>
