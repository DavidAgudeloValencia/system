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

// Estado para las notificaciones
const notification = ref({
    message: '',
    type: '', // 'success' o 'error'
    visible: false,
});

// Método para mostrar notificaciones
function showNotification(message, type = 'success') {
    notification.value = { message, type, visible: true };
    setTimeout(() => {
        notification.value.visible = false;
    }, 3000); // Ocultar la notificación después de 3 segundos
}

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
    if (!form.name || !form.email || !form.user_document || !form.apartment) {
        showNotification('Por favor, completa todos los campos.', 'error');
        return;
    }

    if (form.id == 0) {
        form.post(route("users.store"), {
            onSuccess: () => {
                isOpen.value = false;
                showNotification('Usuario creado exitosamente.', 'success');
            },
            onError: () => {
                showNotification('Ocurrió un error al crear el usuario.', 'error');
            },
        });
    } else {
        form.patch(route("users.update"), {
            onSuccess: () => {
                isOpen.value = false;
                showNotification('Usuario actualizado exitosamente.', 'success');
            },
            onError: () => {
                showNotification('Ocurrió un error al actualizar el usuario.', 'error');
            },
        });
    }
};

const submitDelete = () => {
    form.delete(route("users.destroy"), {
        onSuccess: () => {
            isOpenNoti.value = false;
            showNotification('Usuario eliminado exitosamente.', 'success');
        },
        onError: () => {
            showNotification('Ocurrió un error al eliminar el usuario.', 'error');
        },
    });
};
</script>

<template>

    <Head title="users" />

    <Layout>
        <!-- Notificación -->
        <div v-if="notification.visible" :class="['fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg', notification.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white']">
            {{ notification.message }}
        </div>
        <div class="mt-4 flex justify-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registro de propietarios</h2>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="user in users" :key="user.id"
                    class="bg-white mt-3 overflow-hidden shadow-sm sm:rounded-lg">
                    <Disclosure v-slot="{ open }">
                        <DisclosureButton
                            class="flex w-full justify-between bg-blue-200 px-4 py-2 text-left text-md font-semibold text-gray-900 hover:bg-violet-400 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                            <span>{{ user.name }} {{ user.apartment }} </span>
                            <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                class="h-5 w-5 text-purple-500" />
                        </DisclosureButton>
                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                            <div style="white-space: pre;" class="break-all overflow-y-auto max-h-48">
                                <ul>
                                    <li>
                                        <span class="font-semibold text-dark">Nombre: </span>{{ user.name }}
                                    </li>
                                    <li>
                                        <span class="font-semibold text-dark">Correo electronico: </span>{{ user.email }}
                                    </li>
                                    <li>
                                        <span class="font-semibold text-dark">Número de documento: </span>{{ user.user_document }}
                                    </li>
                                    <li>
                                        <span class="font-semibold text-dark">Número de apartamento: </span>{{ user.apartment }}
                                    </li>
                                </ul>
                                <br>
                                <span class="   font-semibold text-dark text-base">Encuestas aplicadas: </span>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-primary px-8 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="editModal(user, user.id)">
                                    Editar
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-info px-8 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="editModal(user, user.id)">
                                    Aplicar encuesta
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border border-transparent bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="openNoti(user.id)">
                                    Eliminar
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
                        enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                                <DialogPanel
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-8 text-left align-middle shadow-xl transition-all">
                                    <div class="mt-2">
                                        <InputLabel for="name" value="Nombre" class="text-white" />

                                        <TextInput id="name" type="Nombre" class="mt-1 block w-full p-2" v-model="form.name"
                                            required autofocus autocomplete="name" />
                                        <InputError class="mt-2" :message="form.errors.name" />

                                    </div>
                                    <div class="mt-2">
                                        <InputLabel for="email" value="Correo electrónico" class="text-white" />
                                        <TextInput id="email" type="text" class="mt-1 block w-full" v-model="form.email"
                                            required autofocus autocomplete="email" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                    <div class="mt-2">
                                        <InputLabel for="user_document" value="Número de documento"
                                            class="text-white" />
                                        <TextInput id="user_document" type="text" class="mt-1 block w-full"
                                            v-model="form.user_document" required autofocus autocomplete="user_document" />
                                        <InputError class="mt-2" :message="form.errors.user_document" />
                                    </div>
                                    <div class="mt-2">
                                        <InputLabel for="apartment" value="Número de apartamento" class="text-white" />
                                        <TextInput id="apartment" type="text" class="mt-1 block w-full"
                                            v-model="form.apartment" required autofocus autocomplete="apartment" />
                                        <InputError class="mt-2" :message="form.errors.apartment" />
                                    </div>

                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full
                                             bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeModal">
                                            Cerrar
                                        </button>
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full border border-transparent bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submitCreate">
                                            Guardar
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
                    class="bg-primary text-white active:bg-teal-900 font-bold uppercase text-xs px-2 py-2 rounded-full  shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button">
                    <PlusIcon class="h-5 w-5 text-white transition duration-150 ease-in-out group-hover:text-opacity-80"
                        aria-hidden="true" />
                </button>
            </div>

            <!-- Modal Notificacion -->
            <TransitionRoot appear :show="isOpenNoti" as="template">
                <Dialog as="div" @close="closeModalNoti" class="relative z-10">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                        enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                                <DialogPanel
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-xl font-medium leading-6 text-gray-100">
                                        <div>
                                            Confirmación
                                        </div>
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-white text-lg">
                                            ¿Estás seguro de que deseas borrar el usuario?
                                        </p>
                                        <span class="font-semibold text-sm text-white">*Las encuestas respondidas se eliminarán permanentemente. Esta acción no se puede deshacer.</span>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full
                                              bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeNoti">
                                            Cancelar
                                        </button>
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full border border-transparent bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submitDelete">
                                            Aceptar
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
