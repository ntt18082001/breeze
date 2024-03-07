<script setup>
import {Head, useForm, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import {nextTick, ref, watch} from "vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DangerButton from "@/Components/DangerButton.vue";
import debounce from 'lodash.debounce';

defineProps({
  categories: {
    type: Object,
  }
});

const isShow = ref(false);
const nameInput = ref(null);
const searchTerm = ref('');
const modalInfo = ref({
    action: 'add',
    title: 'Add new category',
});

watch(searchTerm, debounce(() => {
  router.get(route('news-category.index'), {
    search: searchTerm.value
  }, {
    preserveState: true
  });
}, 500));

const form = useForm({
    id: '',
    name: '',
});

const openModal = (act, id = '', name = '') => {
    isShow.value = true;
    changeAction(act);
    if(act === 'edit' || act === 'delete') {
        form.id = id;
        form.name = name;
    }
    if(modalInfo.value.action === 'add' || modalInfo.value.action === 'edit') {
        nextTick(() => nameInput.value.focus());
    }
}
const closeModal = () => {
    isShow.value = false;
    form.reset();
    form.clearErrors();
}
const handleSubmit = () => {
    if(modalInfo.value.action === 'add' || modalInfo.value.action === 'edit') {
        form.post(route('news-category.save'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => nameInput.value.focus(),
            onFinish: () => form.reset(),
        });
    } else {
        form.delete(route('news-category.delete'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => nameInput.value.focus(),
            onFinish: () => form.reset(),
        });
    }
}
const changeAction = (act) => {
    modalInfo.value.action = act;
    if(act === 'add') {
        modalInfo.value.title = 'Add new category';
    }
    if(act === 'edit') {
        modalInfo.value.title = 'Edit category';
    }
    if(act === 'delete') {
        modalInfo.value.title = 'Delete category';
    }
}
</script>

<template>
    <Head title="News Category" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">News Category</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-end">
                            <primary-button @click="openModal('add')">Add new category</primary-button>
                            <div class="w-2/4">
                                <TextInput
                                        id="search"
                                        type="text"
                                        class="block w-full"
                                        placeholder="Search..."
                                        name="search"
                                        v-model="searchTerm"
                                />
                            </div>
                        </div>
                        <div class="border mt-4">
                            <div class="p-4 flex bg-black text-white">
                                <h3 class="basis-1/4 flex items-center">Id</h3>
                                <h3 class="basis-1/4 flex items-center">Name</h3>
                                <h3 class="basis-1/4 flex items-center">Total</h3>
                                <h3 class="basis-1/4 flex items-center">Actions</h3>
                            </div>
                            <div
                                    class="p-4 flex border-t"
                                    v-for="cate in categories.data"
                                    :key="cate.id"
                                    v-if="categories.data.length > 0">
                                <h3 class="basis-1/4 flex items-center">{{ cate.id }}</h3>
                                <h3 class="basis-1/4 flex items-center">{{ cate.name }}</h3>
                                <h3 class="basis-1/4 flex items-center">0</h3>
                                <h3 class="basis-1/4 flex items-center">
                                    <secondary-button class="mr-2"
                                                      @click="openModal('edit', cate.id.toString(), cate.name)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                        </svg>
                                    </secondary-button>
                                    <danger-button @click="openModal('delete', cate.id.toString(), cate.name)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </danger-button>
                                </h3>
                            </div>
                            <p v-else class="p-3 text-center">No data!</p>
                        </div>
                        <pagination v-if="categories.data.length > 0" :links="categories.links" />
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="isShow" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ modalInfo.title }}
                </h2>
                <div class="mt-6" v-if="modalInfo.action === 'delete'">
                    <p>Confirm delete [<span class="font-bold">{{ form.name }}</span>] category?</p>
                </div>
                <div class="mt-6" v-else>
                    <InputLabel for="name" value="Name" class="sr-only" />
                    <TextInput
                            id="name"
                            ref="nameInput"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Name"
                            @keyup.enter="handleSubmit"
                            required
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <PrimaryButton @click="handleSubmit" class="ms-3">Submit</PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
