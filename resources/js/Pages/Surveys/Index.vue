<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

import { ref } from 'vue';
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
import { PlusIcon, MinusIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    surveys: {
        type: Object,
        default: () => ({}),
    },
});

const isOpen = ref(false);
const isOpenNoti = ref(false);
const isOpenQuestions = ref(false); // Estado para el modal de preguntas
const currentSurvey = ref({}); // Encuesta actual para editar preguntas
const questions = ref([]); // Preguntas y opciones

function closeModal() {
    isOpen.value = false;
}
function openModal() {
    form.name = '';
    form.state = false;
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

function editModal(survey, id) {
    form.name = survey.name;
    form.state = !!survey.state;
    form.id = id;
    isOpen.value = true;
}

function editQuestionsModal(survey) {
    currentSurvey.value = survey;

    // Verificar si la encuesta tiene preguntas asociadas
    if (survey.questions && survey.questions.length > 0) {
        // Cargar las preguntas y opciones existentes
        questions.value = survey.questions.map((question) => ({
            text: question.title,
            options: question.options.map((option) => ({
                text: option.option,
            })),
        }));
    } else {
        // Inicializar con campos vacíos si no hay preguntas
        questions.value = [
            {
                text: '',
                options: [{ text: '' }],
            },
        ];
    }

    isOpenQuestions.value = true;
}

function closeQuestionsModal() {
    isOpenQuestions.value = false;
}

function addOption(questionIndex) {
    questions.value[questionIndex].options.push({ text: '' });
}

function removeOption(questionIndex, optionIndex) {
    questions.value[questionIndex].options.splice(optionIndex, 1);
}

function addQuestion() {
    questions.value.push({
        text: '',
        options: [{ text: '' }],
    });
}

function removeQuestion(questionIndex) {
    questions.value.splice(questionIndex, 1);
}

async function submitQuestions() {
    try {
        // Estructurar las preguntas con el survey_id
        const formattedQuestions = questions.value.map((question) => ({
            title: question.text,
            survey_id: currentSurvey.value.id,
            options: question.options.map((option) => ({
                option: option.text,
            })),
        }));

        // Enviar las preguntas al backend
        const response = await axios.post(route('questions.update'), { questions: formattedQuestions });

        // Verificar la respuesta del backend
        if (response.data.success) {
            console.log('Preguntas y opciones actualizadas exitosamente:', response.data);

            // Recargar los datos del servidor
            Inertia.reload({ only: ['surveys'] });

            closeQuestionsModal();
        } else {
            console.error('Error al actualizar las preguntas:', response.data.message);
        }
    } catch (error) {
        console.error('Error al enviar las preguntas:', error);
    }
}

const form = useForm({
    id: 0,
    name: '',
    state: false,
});

const submitCreate = () => {
    if (form.id == 0) {
        form.post(route("surveys.store"), {
            onSuccess: () => {
                isOpen.value = false;
                Inertia.reload({ only: ['surveys'] });
            },
        });
    } else {
        form.patch(route("surveys.update"), {
            onSuccess: () => {
                isOpen.value = false;
                Inertia.reload({ only: ['surveys'] });
            },
        });
    }
};

const submitDelete = () => {
    form.delete(route("surveys.destroy"), {
        onSuccess: () => isOpenNoti.value = false,
    });
};
</script>

<template>

    <Head title="surveys" />

    <Layout>
        <div class="mt-4 flex justify-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registro de encuestas</h2>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="survey in surveys" :key="survey.id"
                    class="bg-white mt-3 overflow-hidden shadow-sm sm:rounded-lg">
                    <Disclosure v-slot="{ open }">
                        <DisclosureButton
                            class="flex w-full justify-between bg-blue-200 px-4 py-2 text-left text-md font-semibold text-gray-900 hover:bg-violet-400 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                            <span>{{ survey.name }}</span>
                            <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                class="h-5 w-5 text-purple-500" />
                        </DisclosureButton>
                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                            <div style="white-space: pre;" class="break-all overflow-y-auto max-h-48">
                                <ul>
                                    <li>
                                        <span class="font-semibold text-dark">Nombre: </span>{{ survey.name }}
                                    </li>
                                    <li>
                                        <span class="font-semibold text-dark">Estado: </span>
                                        <span v-if="survey.state" class="text-green-500">Activo</span>
                                        <span v-else class="text-red-500">Inactivo</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-primary px-8 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="editModal(survey, survey.id)">
                                    Editar
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border bg-info px-8 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="editQuestionsModal(survey)">
                                    Editar preguntas y respuestas
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center shadow rounded-full border border-transparent bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="openNoti(survey.id)">
                                    Eliminar
                                </button>
                            </div>
                        </DisclosurePanel>
                    </Disclosure>
                </div>
            </div>

            <!-- Botón para agregar encuesta -->
            <div class="fixed top-[80vh] right-1 inline-block p-1">
                <button @click="openModal"
                    class="bg-primary text-white active:bg-teal-900 font-bold uppercase text-xs px-2 py-2 rounded-full shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button">
                    <PlusIcon class="h-5 w-5 text-white transition duration-150 ease-in-out group-hover:text-opacity-80"
                        aria-hidden="true" />
                </button>
            </div>

            <!-- Modal Add & Edit -->
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
                                        <TextInput id="name" type="text" class="mt-1 block w-full p-2"
                                            v-model="form.name" required autofocus autocomplete="name" />
                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                    <div class="mt-2">
                                        <InputLabel for="state" value="Estado" class="text-white" />
                                        <div class="flex items-center">
                                            <input id="state" type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                :checked="form.state" @change="form.state = $event.target.checked" />
                                            <span class="ml-2 text-white">
                                                {{ form.state ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.state" />
                                    </div>

                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
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
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-muted p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-xl font-medium leading-6 text-gray-100">
                                        <div>
                                            Confirmación
                                        </div>
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-white text-lg">
                                            ¿Estás seguro de que deseas borrar la encuesta?
                                        </p>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-full bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
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

            <!-- Modal Questions -->
            <TransitionRoot appear :show="isOpenQuestions" as="template">
                <Dialog as="div" @close="closeQuestionsModal" class="relative z-10">
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
                                    class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-muted p-8 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-xl font-medium leading-6 text-gray-100">
                                        <div>
                                            Preguntas para la encuesta: {{ currentSurvey.name }}
                                        </div>
                                    </DialogTitle>
                                    <div class="mt-4">
                                        <div v-for="(question, qIndex) in questions" :key="qIndex" class="mb-6">
                                            <!-- Botón para eliminar la pregunta -->
                                            <div class="flex items-center mb-2">
                                                <button
                                                    type="button"
                                                    class="mr-2 bg-rose-500 text-white rounded-full p-2 hover:bg-rose-900"
                                                    @click="removeQuestion(qIndex)"
                                                >
                                                    <MinusIcon class="h-5 w-5" />
                                                </button>
                                                <InputLabel :for="'question-' + qIndex" value="Pregunta" class="text-white" />
                                            </div>

                                            <!-- Input para la pregunta -->
                                            <TextInput
                                                :id="'question-' + qIndex"
                                                type="text"
                                                class="mt-1 block w-full p-2"
                                                v-model="question.text"
                                                required
                                                placeholder="Escribe la pregunta"
                                            />

                                            <!-- Opciones asociadas a la pregunta -->
                                            <div v-for="(option, oIndex) in question.options" :key="oIndex" class="mt-2 flex items-center">
                                                <!-- Botón para eliminar la opción -->
                                                <button
                                                    type="button"
                                                    class="mr-2 bg-rose-500 text-white rounded-full p-2 hover:bg-rose-900"
                                                    @click="removeOption(qIndex, oIndex)"
                                                >
                                                    <MinusIcon class="h-5 w-5" />
                                                </button>

                                                <!-- Input para la opción -->
                                                <TextInput
                                                    :id="'option-' + qIndex + '-' + oIndex"
                                                    type="text"
                                                    class="mt-1 block w-full p-2"
                                                    v-model="option.text"
                                                    required
                                                    placeholder="Escribe una opción"
                                                />

                                                <!-- Botón para agregar una nueva opción -->
                                                <button
                                                    type="button"
                                                    class="ml-2 bg-primary text-white rounded-full p-2 hover:bg-teal-900"
                                                    @click="addOption(qIndex)"
                                                >
                                                    <PlusIcon class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Botón para agregar una nueva pregunta -->
                                        <button
                                            type="button"
                                            class="mt-4 bg-primary text-white rounded-full px-4 py-2 hover:bg-teal-900"
                                            @click="addQuestion"
                                        >
                                            <PlusIcon class="h-5 w-5 inline-block mr-2" /> Agregar pregunta
                                        </button>
                                    </div>
                                    <div class="mt-6 flex justify-between">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-full bg-rose-500 px-6 py-2 text-sm font-medium text-white hover:bg-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeQuestionsModal"
                                        >
                                            Cerrar
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-full bg-primary px-6 py-2 text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submitQuestions"
                                        >
                                            Guardar
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
