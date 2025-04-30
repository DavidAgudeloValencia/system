<template>
    <Layout>
        <div class="py-12">
            <!-- Modal para preguntas y opciones -->
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
                                            <InputLabel :for="'question-' + qIndex" value="Pregunta" class="text-white" />
                                            <TextInput
                                                :id="'question-' + qIndex"
                                                type="text"
                                                class="mt-1 block w-full p-2"
                                                v-model="question.text"
                                                required
                                                placeholder="Escribe la pregunta"
                                            />
                                            <div v-for="(option, oIndex) in question.options" :key="oIndex" class="mt-2 flex items-center">
                                                <TextInput
                                                    :id="'option-' + qIndex + '-' + oIndex"
                                                    type="text"
                                                    class="mt-1 block w-full p-2"
                                                    v-model="option.text"
                                                    required
                                                    placeholder="Escribe una opción"
                                                />
                                                <button
                                                    type="button"
                                                    class="ml-2 bg-primary text-white rounded-full p-2 hover:bg-teal-900"
                                                    @click="addOption(qIndex)"
                                                >
                                                    <PlusIcon class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
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

<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import { PlusIcon } from '@heroicons/vue/24/solid';

const isOpenQuestions = ref(false);
const currentSurvey = ref({ name: '' });
const questions = ref([]);

function openQuestionsModal(survey) {
    currentSurvey.value = survey;
    questions.value = [
        {
            text: '',
            options: [{ text: '' }],
        },
    ];
    isOpenQuestions.value = true;
}

function closeQuestionsModal() {
    isOpenQuestions.value = false;
}

function addOption(questionIndex) {
    questions.value[questionIndex].options.push({ text: '' });
}

function addQuestion() {
    questions.value.push({
        text: '',
        options: [{ text: '' }],
    });
}

function submitQuestions() {
    console.log('Preguntas enviadas:', questions.value);
    closeQuestionsModal();
}
</script>
