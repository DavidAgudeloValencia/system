<template>
    <div>
      <h1>Usuarios</h1>
      <ul>
        <li v-for="user in users" :key="user.id">
          {{ user.name }} - {{ user.email }}
          <inertia-link :href="`/users/${user.id}`">Ver</inertia-link>
          <inertia-link :href="`/users/${user.id}/edit`">Editar</inertia-link>
        </li>
      </ul>
      <inertia-link href="/users/create">Crear nuevo usuario</inertia-link>
    </div>
  </template>

  <script>
  export default {
    props: {
      users: Array,
    },
  };
  </script>
