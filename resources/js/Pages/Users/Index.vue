<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

import { ref } from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/vue'
import { PlusIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    users: {
        type: Object,
        default: () => ({}),
    },
});

const isOpen = ref(false)
const isOpenNoti = ref(false)

function closeModal() {
    isOpen.value = false
}
function openModal() {
    form.name = ''
    form.email = ''
    form.user_document = ''
    form.apartment = ''
    form.id = 0
    isOpen.value = true
}

function closeNoti() {
    form.id = 0
    isOpenNoti.value = false
}
function openNoti(id = 0) {
    form.id = id
    isOpenNoti.value = true
}

function editModal(user, id) {
    form.name = user.name
    form.email = user.email
    form.user_document = user.user_document
    form.apartment = user.apartment
    form.id = id
    isOpen.value = true
}

const form = useForm({
    id: 0,
    name: '',
    email: '',
    user_document: '',
    apartment: '',
});

const submitCreate = () => {
    if (form.id == 0) {
        form.post(route("user"), {
            onSuccess: () => isOpen.value = false
        });
    } else {
        form.patch(route("user"), {
            onSuccess: () => isOpen.value = false
        });
    }
};

const submitDelete = () => {
    form.delete(route("user"), {
        onSuccess: () => isOpenNoti.value = false
    });
};
</script>

<template>
    <Head title="users" />

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registro de propietarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="user in  users" :key="user.id"
                    class="bg-white mt-3 overflow-hidden shadow-sm sm:rounded-lg">
                    <Disclosure v-slot="{ open }">
                        <DisclosureButton
                            class="flex w-full justify-between bg-blue-200 px-4 py-2 text-left text-md font-semibold text-gray-900 hover:bg-violet-400 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                            <span>{{ user.name }} {{user.apartment}} </span>
                            <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 text-purple-500" />
                        </DisclosureButton>
                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                            <div style="white-space: pre;" class="break-all overflow-y-auto max-h-48">
                                <span>Correo electronico: </span>{{ user.email }}
                                <span>Número de documeno: </span>{{ user.user_document }}
                            </div>
                            <div class="mt-4 flex justify-between">
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-primary px-8 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="editModal(user, user.id)">
                                    Edit
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border border-transparent bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="openNoti(user.id)">
                                    Delete
                                </button>
                            </div>
                        </DisclosurePanel>
                    </Disclosure>
                </div>
            </div>
            <!-- Model Add & Edit -->
            <TransitionRoot appear :show="isOpen" as="template">
                <Dialog as="div" @close="closeModal" class="relative z-10">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                        enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                                <DialogPanel
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                        <div>
                                            <InputLabel for="tittle" value="Tittle" class="text-white" />

                                            <TextInput id="title" type="text" class="mt-1 block w-full"
                                                v-model="form.name" required autofocus autocomplete="Item" />

                                            <InputError class="mt-2" :message="form.errors.name" />
                                        </div>
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <InputLabel for="email" value="Corrreo electronico" class="text-white" />
                                        <TextInput id="title" type="text" class="mt-1 block w-full"
                                                v-model="form.email" required autofocus autocomplete="Item" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                    <div class="mt-2">
                                        <InputLabel for="user_document" value="Número de documento" class="text-white" />
                                        <TextInput id="title" type="text" class="mt-1 block w-full"
                                                v-model="form.user_document" required autofocus autocomplete="Item" />
                                        <InputError class="mt-2" :message="form.errors.user_document" />
                                    </div>
                                    <div class="mt-2">
                                        <InputLabel for="apartment" value="Número de Apartamento" class="text-white" />
                                        <TextInput id="title" type="text" class="mt-1 block w-full"
                                                v-model="form.apartment" required autofocus autocomplete="Item" />
                                        <InputError class="mt-2" :message="form.errors.apartment" />
                                    </div>

                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full
                                             border border-dark bg-gray-300 px-6 py-2 text-sm font-medium text-black hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeModal">
                                            Close
                                        </button>
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full border border-transparent bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submitCreate">
                                            Save
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <div class="fixed top-[80vh] right-1 inline-block p-1">
                <!-- Button Add -->
                <button @click="openModal"
                    class="bg-primary text-white active:bg-secondary font-bold uppercase text-xs px-2 py-2 rounded-full  shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button">
                    <PlusIcon class="h-5 w-5 text-white transition duration-150 ease-in-out group-hover:text-opacity-80"
                        aria-hidden="true" />
                </button>
            </div>

            <!-- Modal Notificacion -->
            <TransitionRoot appear :show="isOpenNoti" as="template">
                <Dialog as="div" @close="closeModalNoti" class="relative z-10">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                        enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                                <DialogPanel
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-100">
                                        <div>
                                            Confirmation
                                        </div>
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-white">
                                            Are you sure to delete?
                                        </p>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full
                                              bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeNoti">
                                            Cancel
                                        </button>
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full border border-transparent bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submitDelete">
                                            Accept
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </Layout>
</template>