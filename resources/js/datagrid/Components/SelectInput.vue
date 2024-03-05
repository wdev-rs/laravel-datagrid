<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String|Number,
    modelOptions: Object
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
    >
        <slot name="placeholder">
            <option value="">Please select</option>
        </slot>
        <slot name="option" v-for="(option, index) in modelOptions" v-bind:option="option">
            <option :value="option.id">{{option.name}}</option>
        </slot>

    </select>
</template>
