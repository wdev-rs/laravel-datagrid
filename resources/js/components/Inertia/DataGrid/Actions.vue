<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref} from "vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DeleteIcon from "@/Components/Icons/DeleteIcon.vue";


const props = defineProps({
    row: Object,
    primarykey: String
});

const confirmingDeletion = ref(false);
const form = useForm({});

const confirmDeletion = () => {
    confirmingDeletion.value = true;
};

const submitDelete = () => {
    router.delete(route(route().current())+'/'+props.row[props.primarykey], {
        errorBag: 'delete',
        onSuccess: () => confirmingDeletion.value = false
    });
};
</script>

<template>
    <div class="grid grid-flow-col auto-cols-max">
        <Link :href="route(route().current())+'/'+row[primarykey]+'/edit'"
              class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">
            <EditIcon class="fill-blue-600" :title="__('Edit')"></EditIcon>
        </Link>
        <button @click="confirmDeletion"
              class="font-medium text-red-500 dark:text-blue-500">
              <DeleteIcon class="fill-red-500" :title="__('Delete')"></DeleteIcon>
        </button>
    </div>

    <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
        <template #title>
            {{__('datagrid.delete_modal_title')}}
        </template>

        <template #content>
            {{__('datagrid.delete_modal_content')}}
        </template>

        <template #footer>
            <SecondaryButton @click="confirmingDeletion = false">
                {{__('Cancel')}}
            </SecondaryButton>

            <DangerButton
                class="ml-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="submitDelete"
            >
                {{__('Delete')}}
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
