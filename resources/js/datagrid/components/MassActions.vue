<script setup>

import SelectInput from "@/Components/SelectInput.vue";
import PlayIcon from "@/Components/Icons/PlayIcon.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref, defineEmits, computed} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    massActions: Array,
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['run']);

const selectedAction = ref('');

const run = () => {
    emit('run', {action: selectedAction.value});
    confirmingAction.value = false;
};

const confirmingAction = ref(false);
const form = useForm({});

const confirmAction = () => {
    confirmingAction.value = true;
};

const selectedActionLabel = computed(() => {
    for (let i in props.massActions) {
        if (props.massActions[i].action == selectedAction.value) {
            return props.massActions[i].label;
        }
    }
});


</script>

<template>
    <div class="m-3">
        <InputLabel>Mass action</InputLabel>
        <div class="grid grid-flow-col auto-cols-max mb-3">
            <SelectInput
                :disabled="disabled"
                :model-options="massActions"
                v-model="selectedAction"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 block w-80 mt-1"
            >
                <template #option="{option}">
                    <option :value="option.action">{{ option.label }}</option>
                </template>
                >
            </SelectInput>
            <SecondaryButton :disabled="!selectedAction || disabled" @click="confirmAction" class="h-50 mt-1 ml-2 !px-1"
                             :title="__('Run')">
                <PlayIcon class="w-8 h-4"></PlayIcon>
            </SecondaryButton>
        </div>

        <ConfirmationModal :show="confirmingAction" @close="confirmingAction = false">
            <template #title>
                {{ selectedActionLabel }}
            </template>

            <template #content>
                {{ __('Are you sure you want to run action?') }}
            </template>

            <template #footer>
                <SecondaryButton @click="confirmingAction = false">
                    {{ __('Cancel') }}
                </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="run"
                >
                    {{ __('Run') }}
                </PrimaryButton>
            </template>
        </ConfirmationModal>
    </div>
</template>

<style scoped>

</style>
