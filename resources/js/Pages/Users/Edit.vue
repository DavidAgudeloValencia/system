<template>
    <div>
      <h1>Editar Usuario</h1>
      <form @submit.prevent="updateUser">
        <input v-model="form.name" type="text" placeholder="Nombre" />
        <input v-model="form.email" type="email" placeholder="Correo" />
        <input v-model="form.user_document" type="text" placeholder="Documento" />
        <input v-model="form.apartment" type="text" placeholder="Apartamento" />
        <button type="submit">Actualizar</button>
      </form>
    </div>
  </template>

  <script>
  import { Inertia } from '@inertiajs/inertia';

  export default {
    props: {
      user: Object,
    },
    data() {
      return {
        form: { ...this.user },
      };
    },
    methods: {
      updateUser() {
        Inertia.put(`/users/${this.user.id}`, this.form);
      },
    },
  };
  </script>
