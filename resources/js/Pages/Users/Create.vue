<template>
    <div>
      <h1>Crear Usuario</h1>
      <form @submit.prevent="createUser">
        <input v-model="form.name" type="text" placeholder="Nombre" />
        <input v-model="form.email" type="email" placeholder="Correo" />
        <input v-model="form.user_document" type="text" placeholder="Documento" />
        <input v-model="form.apartment" type="text" placeholder="Apartamento" />
        <button type="submit">Guardar</button>
      </form>
    </div>
  </template>

  <script>
//   import { ref } from 'vue';
  import { Inertia } from '@inertiajs/vue3';

  export default {
    data() {
      return {
        form: {
          name: '',
          email: '',
          user_document: '',
          apartment: '',
        },
      };
    },
    methods: {
      createUser() {
        Inertia.post('/users', this.form);
      },
    },
  };
  </script>
