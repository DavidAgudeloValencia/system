<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';

import { ref, watch } from 'vue';

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/vue';
import { PlusIcon, EyeIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});

const isOpen = ref(false);
const isOpenNoti = ref(false);
const isOpenSurveyModal = ref(false); // Estado para el modal de encuestas
const availableSurveys = ref([]); // Encuestas disponibles
const selectedSurvey = ref(null); // Encuesta seleccionada
const selectedAnswers = ref({}); // Respuestas seleccionadas
const userId = ref(null); // ID del usuario seleccionado

const users = ref([...props.users]); // Crear un ref para manejar los usuarios localmente

// Observar cambios en los props y sincronizar con el estado local
watch(() => props.users, (newUsers) => {
    users.value = [...newUsers];
});

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
    isOpen.value = false;
}
function closeSurveyModal() {
    isOpenSurveyModal.value = false;
}
function openModal() {
    form.name = '';
    form.email = '';
    form.user_document = '';
    form.apartment = '';
    form.id = 0;
    isOpen.value = true;
}

function closeNoti() {
    form.id = 0;
    isOpenNoti.value = false;
}
function openNoti(id = 0) {
    form.id = id;
    isOpenNoti.value = true;
}

function editModal(user, id) {
    form.name = user.name;
    form.email = user.email;
    form.user_document = user.user_document;
    form.apartment = user.apartment;
    form.id = id;
    isOpen.value = true;
}

async function openSurveyModal(user) {
    userId.value = user.id;

    try {
        const response = await axios.post(route('answers.getAvailableSurveys'), { user_id: user.id });

        if (response.data.success) {
            availableSurveys.value = response.data.surveys;
            isOpenSurveyModal.value = true;
        } else {
            showNotification(response.data.message, 'error');
        }
    } catch (error) {
        showNotification('Error al obtener encuestas disponibles.', 'error');
    }
}

function selectSurvey(survey) {
    selectedSurvey.value = survey;
    selectedAnswers.value = {};
}

function selectAnswer(questionId, optionId) {
    selectedAnswers.value[questionId] = optionId;
}

async function saveAnswers() {
    try {
        const answers = Object.keys(selectedAnswers.value).map((questionId) => ({
            question_id: questionId,
            option_id: selectedAnswers.value[questionId],
        }));

        const response = await axios.post(route('answers.saveAnswers'), {
            user_id: userId.value,
            survey_id: selectedSurvey.value.id,
            answers,
        });

        if (response.data.success) {
            availableSurveys.value = response.data.surveys;
            users.value = response.data.users;
            selectedSurvey.value = null;
            isOpenSurveyModal.value = false;
            showNotification('Respuestas guardadas exitosamente.', 'success');
        } else {
            showNotification(response.data.message, 'error');
        }
    } catch (error) {
        showNotification('Error al guardar respuestas.', 'error');
    }
}

// Estado para el modal de respuestas
const isOpenAnswersModal = ref(false);
const userAnswers = ref([]);

// Función para abrir el modal y cargar las respuestas del usuario para una encuesta específica
async function openAnswersModal(userId, surveyId) {
    try {
        const response = await axios.get(route('user.survey.answers', { userId, surveyId }));

        if (response.data.success) {
            userAnswers.value = response.data.answers;
            isOpenAnswersModal.value = true;
        } else {
            console.error(response.data.message);
        }
    } catch (error) {
        console.error('Error al cargar las respuestas del usuario:', error);
    }
}

// Función para cerrar el modal
function closeAnswersModal() {
    isOpenAnswersModal.value = false;
    userAnswers.value = [];
}

const form = useForm({
    id: 0,
    name: '',
    email: '',
    user_document: '',
    apartment: '',
});

const submitCreate = async () => {
    if (!form.name || !form.email || !form.user_document || !form.apartment) {
        showNotification('Por favor, completa todos los campos.', 'error');
        return;
    }

    try {
        if (form.id == 0) {
            const response = await axios.post(route('users.store'), form);
            if (response.status === 200) {
                users.value = response.data.users; // Actualizar la lista de usuarios
                isOpen.value = false;
                showNotification('Usuario creado exitosamente.', 'success');
            } else {
                showNotification('Ocurrió un error al crear el usuario.', 'error');
            }
        } else {
            const response = await axios.patch(route('users.update'), form);
            if (response.status === 200) {
                users.value = response.data.users; // Actualizar la lista de usuarios
                isOpen.value = false;
                showNotification('Usuario actualizado exitosamente.', 'success');
            } else {
                showNotification('Ocurrió un error al actualizar el usuario.', 'error');
            }
        }
    } catch (error) {
        showNotification('Ocurrió un error al procesar la solicitud.', 'error');
    }
};

const submitDelete = async () => {
    try {
        const response = await axios.delete(route('users.destroy'), { data: { id: form.id } });
        if (response.status === 200) {
            users.value = response.data.users; // Actualizar la lista de usuarios
            isOpenNoti.value = false;
            showNotification('Usuario eliminado exitosamente.', 'success');
        } else {
            showNotification('Ocurrió un error al eliminar el usuario.', 'error');
        }
    } catch (error) {
        showNotification('Ocurrió un error al eliminar el usuario.', 'error');
    }
};
</script>

<template>

    <Head title="users" />

    <Layout>
        <!-- Notificación -->
        <div v-if="notification.visible"
            :class="['fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg', notification.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white']">
            {{ notification.message }}
        </div>
        <div class="mt-4 flex justify-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registro de propietarios</h2>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="user in users" :key="user.id" class="bg-white mt-3 overflow-hidden shadow-sm sm:rounded-lg">
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
                                        <span class="font-semibold text-dark">Correo electronico: </span>{{ user.email
                                        }}
                                    </li>
                                    <li>
                                        <span class="font-semibold text-dark">Número de documento: </span>{{
                                            user.user_document }}
                                    </li>
                                    <li>
                                        <span class="font-semibold text-dark">Número de apartamento: </span>{{
                                            user.apartment }}
                                    </li>
                                </ul>
                                <br>
                                <span class="font-semibold text-dark text-base">Encuestas aplicadas: </span>
                                <ul class="mt-2 space-y-2">
                                    <li v-for="answer in user.answers" :key="answer.survey.id"
                                        class="flex items-center space-x-2">
                                        <span class="text-gray-700">{{ answer.survey.name }}</span>
                                        <button type="button"
                                            class="inline-flex items-center justify-center text-gray-500 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            @click="openAnswersModal(user.id, answer.survey.id)">
                                            <EyeIcon class="h-5 w-5" />
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-primary px-8 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="editModal(user, user.id)">
                                    Editar
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-info px-8 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="openSurveyModal(user)">
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
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-8 text-left align-middle shadow-xl transition-all max-h-[80vh] overflow-y-auto">
                                    <div class="mt-2">
                                        <InputLabel for="name" value="Nombre" class="text-white" />

                                        <TextInput id="name" type="Nombre" class="mt-1 block w-full p-2"
                                            v-model="form.name" required autofocus autocomplete="name" />
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
                                            v-model="form.user_document" required autofocus
                                            autocomplete="user_document" />
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
                                            class="inline-flex justify-center rounded-full border border-transparent bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
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
                    class="bg-primary text-white active:bg-blue-900 font-bold uppercase text-xs px-2 py-2 rounded-full  shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button">
                    <PlusIcon class="h-5 w-5 text-white transition duration-150 ease-in-out group-hover:text-opacity-80"
                        aria-hidden="true" />
                </button>
            </div>

            <!-- Modal Notificacion -->
            <TransitionRoot appear :show="isOpenNoti" as="template">
                <Dialog as="div" @close="closeNoti" class="relative z-10">
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
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-6 text-left align-middle shadow-xl transition-all max-h-[80vh] overflow-y-auto">
                                    <DialogTitle as="h3" class="text-xl font-medium leading-6 text-gray-100">
                                        <div>
                                            Confirmación
                                        </div>
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-white text-lg">
                                            ¿Estás seguro de que deseas borrar el usuario?
                                        </p>
                                        <span class="font-semibold text-sm text-white">*Si continúas, perderás toda la
                                            información asociada a las respuestas enviadas.</span>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full
                                              bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeNoti">
                                            Cancelar
                                        </button>
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full border border-transparent bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
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

            <!-- Modal de Encuestas -->
            <TransitionRoot appear :show="isOpenSurveyModal" as="template">
                <Dialog as="div" @close="closeSurveyModal" class="relative z-10">
                    <!-- Fondo Oscurecido -->
                    <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" />

                    <!-- Contenedor del Diálogo -->
                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-6">
                            <!-- Panel del Diálogo -->
                            <DialogPanel
                                class="w-full max-w-4xl transform rounded-lg bg-muted text-white shadow-2xl p-6 max-h-[80vh] overflow-y-auto">
                                <div class="flex">
                                    <!-- Menú Lateral -->
                                    <div class="w-2/5 border-r pr-6">
                                        <h3 class="text-lg font-semibold text-gray-50 border-b pb-3 mb-4">Encuestas
                                            Disponibles</h3>
                                        <ul class="space-y-3">
                                            <li v-for="survey in availableSurveys" :key="survey.id">
                                                <button @click="selectSurvey(survey)"
                                                    class="w-full text-left bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-md transition">
                                                    {{ survey.name }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Preguntas y Respuestas -->
                                    <div class="w-3/5 pl-6">
                                        <h3 class="text-lg font-semibold text-gray-100 mb-4" v-if="selectedSurvey">
                                            {{ selectedSurvey.name }}
                                        </h3>
                                        <div v-if="selectedSurvey">
                                            <div v-for="question in selectedSurvey.questions" :key="question.id"
                                                class="mt-6">
                                                <p class="font-medium text-gray-50">{{ question.title }}</p>
                                                <div class="mt-4 space-y-2">
                                                    <label v-for="option in question.options" :key="option.id"
                                                        class="flex items-center space-x-3">
                                                        <input type="radio" :name="'question-' + question.id"
                                                            :value="option.id"
                                                            @change="selectAnswer(question.id, option.id)"
                                                            class="text-blue-600 focus:ring-blue-500" />
                                                        <span class="text-gray-50">{{ option.option }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <button @click="saveAnswers"
                                                class="mt-6 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900 shadow-md transition">
                                                Guardar Respuestas
                                            </button>
                                        </div>
                                        <p v-else class="text-gray-500 mt-4">Selecciona una encuesta para comenzar.</p>
                                    </div>
                                </div>
                            </DialogPanel>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Modal de Respuestas -->
            <TransitionRoot appear :show="isOpenAnswersModal" as="template">
                <Dialog as="div" @close="closeAnswersModal" class="relative z-10">
                    <div class="fixed inset-0 bg-muted text-white bg-opacity-50" />
                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-6">
                            <DialogPanel
                                class="w-full max-w-3xl transform rounded-lg bg-muted shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                                <h3 class="text-lg font-semibold text-gray-50 mb-4">Respuestas de la Encuesta</h3>
                                <div v-if="userAnswers.length > 0">
                                    <ul class="space-y-4">
                                        <li v-for="answer in userAnswers" :key="answer.id">
                                            <p class="font-medium text-gray-100">{{ answer.title }}</p>
                                            <ul class="mt-2 space-y-1">
                                                <li v-for="option in answer.options" :key="option.id"
                                                    class="flex items-center space-x-2">
                                                    <span :class="{
                                                        'text-green-400 font-semibold': answer.answers.some(a => a.option_id === option.id),
                                                        'text-gray-400': !answer.answers.some(a => a.option_id === option.id),
                                                    }">
                                                        {{ option.option }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <p v-else class="text-gray-500">No hay respuestas disponibles para esta encuesta.</p>
                                <div class="mt-6 flex justify-end">
                                    <button @click="closeAnswersModal"
                                    class="inline-flex justify-center rounded-full
                                    bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                        Cerrar
                                    </button>
                                </div>
                            </DialogPanel>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </Layout>
</template>
