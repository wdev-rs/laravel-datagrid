<script setup>
import DangerButton from "./DangerButton.vue";
import ConfirmationModal from "./ConfirmationModal.vue";
import SecondaryButton from "./SecondaryButton.vue";
import {ref} from "vue";
import EditIcon from "./Icons/EditIcon.vue";
import DeleteIcon from "./Icons/DeleteIcon.vue";


const props = defineProps({
    row: Object,
    primarykey: String
});

const confirmingDeletion = ref(false);
const form = ref({});

const confirmDeletion = () => {
    confirmingDeletion.value = true;
};

const submitDelete = () => {
    alert('Simulating deleting of row with id='+props.row[props.primarykey]);
};
</script>

<template>
    <div class="grid grid-flow-col auto-cols-max">
        <a :href="'/'+row[primarykey]+'/edit'"
              class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">
            <EditIcon class="fill-blue-600" :title="'Edit'"></EditIcon>
        </a>
        <button @click="confirmDeletion"
              class="font-medium text-red-500 dark:text-blue-500">
              <DeleteIcon class="fill-red-500" :title="'Delete'"></DeleteIcon>
        </button>
    </div>

    <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
        <template #title>
            Are you sure?
        </template>

        <template #content>
            {{'This action will delete ' + 'name' + '. You won\'t be able to revert this!'}}
        </template>

        <template #footer>
            <SecondaryButton @click="confirmingDeletion = false">
                {{'Cancel'}}
            </SecondaryButton>

            <DangerButton
                class="ml-3"
                @click="submitDelete"
            >
                Delete
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
