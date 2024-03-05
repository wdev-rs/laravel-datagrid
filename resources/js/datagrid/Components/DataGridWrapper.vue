<script setup>
    import DataGrid from './DataGrid.vue'

    const props = defineProps({
        columns: Array,
        rows: Object,
    });

    const alert = (text) => {
        window.alert(text);
    }
</script>

<template>
    <data-grid
        :columns="props.columns"
        :rows="props.rows"
        :mass-actions="[{'action' : 'massDelete', 'label': 'Delete'}]"
        @massDelete="(selectedIds) => alert('Simulating mass delete on id(s): ' + selectedIds.join(','))"
    ></data-grid>
</template>

<style scoped>

</style>
