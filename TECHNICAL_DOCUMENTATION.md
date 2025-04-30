# Documentación Técnica de System

## Resumen

Este documento proporciona una visión técnica completa del proyecto "System" desarrollado con Laravel, detallando su estructura, dependencias y configuraciones clave.

## 1. Análisis de la Estructura del Proyecto

El proyecto se adhiere a la estructura de directorios estándar de Laravel, con algunas posibles personalizaciones que se destacan a continuación:

-   **app**:
    -   Contiene la lógica central de la aplicación.
    -   Organizado en subdirectorios como `Http` (Controladores, Middleware), `Models` (modelos Eloquent), `Providers` (proveedores de servicios), `Actions` (Acciones).
    -   Personalización: Esta es el área donde reside la mayor parte de la lógica central de la aplicación, incluyendo cualquier clase personalizada, servicios y reglas de negocio.
-   **bootstrap**:
    -   Contiene los archivos que inicializan el framework, incluyendo `app.php`, que crea y devuelve la instancia de la aplicación.
    -   Personalización: Es poco probable que se haya modificado.
-   **config**:
    -   Contiene los archivos de configuración de la aplicación (ej. `app.php`, `database.php`, `mail.php`).
    -   Personalización: Los archivos de configuración pueden ser personalizados para alterar el comportamiento de la aplicación.
-   **database**:
    -   Contiene `migrations` (definiciones del esquema de la base de datos), `factories` (fábricas de modelos para pruebas) y `seeders` (sembrado de datos iniciales).
    -   Personalización: Esta es un área clave para personalizaciones que se alineen con el diseño específico de la base de datos del proyecto.
-   **public**:
    -   La raíz del documento, contiene `index.php`, que es el punto de entrada para todas las solicitudes.
    -   También contiene activos como `favicon.ico` y `robots.txt`.
    -   Personalización: Principalmente archivos estáticos, las modificaciones podrían ser raras.
-   **resources**:
    -   Contiene activos (CSS, JS), archivos de idioma y vistas (plantillas Blade).
    -   Personalización: La mayoría de los cambios relacionados con el front-end ocurren aquí.
-   **routes**:
    -   Define las rutas de la aplicación (`web.php`, `api.php`, `console.php`).
    -   Personalización: Las rutas personalizadas se definen aquí.
-   **storage**:
    -   Contiene plantillas Blade compiladas, sesiones basadas en archivos, logs, etc.
    -   Personalización: Aquí se podrían configurar destinos de log o ubicaciones de almacenamiento personalizados.
-   **tests**:
    -   Contiene pruebas automatizadas (`Unit` y `Feature`).
    -   Personalización: Esta es un área crítica para probar la calidad del código y asegurar la estabilidad.
-   **vendor**:
    -   Contiene las dependencias de Composer (librerías de terceros).
    -   Personalización: Evitar modificaciones directas.
-   **Others:**
    *   .idx/: Este directorio probablemente se utiliza para el entorno de desarrollo interno o depuraciones.

## 2. Análisis de `composer.json`

El archivo `composer.json` define las dependencias de PHP para el proyecto.

### Versión de PHP

-   `php`: `^8.2`
    -   La versión mínima requerida de PHP es 8.2.

### Paquetes Requeridos

-   `inertiajs/inertia-laravel`: `^2.0`
    -   Adaptador del lado del servidor para Inertia.js, que permite una arquitectura de aplicación de una sola página (SPA) dentro de una aplicación Laravel.
-   `laravel/framework`: `^11.31`
    -   El framework Laravel, componentes principales.
-   `laravel/jetstream`: `^5.3`
    -   Andamiaje de la aplicación que proporciona autenticación, gestión de perfiles y características de equipos.
-   `laravel/sanctum`: `^4.0`
    -   Autenticación de API mediante tokens.
-   `laravel/tinker`: `^2.9`
    -   REPL (Read-Eval-Print Loop) para interactuar con la aplicación a través de la línea de comandos.
-   `tightenco/ziggy`: `^2.5`
    -   Proporciona la capacidad de usar rutas nombradas en JavaScript, mejorando la consistencia entre el enrutamiento del lado del servidor y del lado del cliente.

### Dependencias de Desarrollo (`require-dev`)

-   `fakerphp/faker`: `^1.23`
    -   Generación de datos de prueba para crear datos simulados realistas.
-   `laravel/pail`: `^1.1`
    -   Proporciona información de depuración.
-   `laravel/pint`: `^1.13`
    -   Corrector de estilo de código PHP con opiniones, que garantiza la consistencia del estilo del código.
-   `laravel/sail`: `^1.26`
    -   Entorno de desarrollo de Docker, que simplifica la configuración del desarrollo local.
-   `mockery/mockery`: `^1.6`
    -   Framework de mocking para escribir pruebas unitarias más efectivas.
-   `nunomaduro/collision`: `^8.1`
    -   Reporte de errores, que facilita la lectura de los detalles de los errores en la terminal.
-   `phpunit/phpunit`: `^11.0.1`
    -   Framework de pruebas de PHP para escribir pruebas unitarias.

### Scripts de Ejecución

-   `dev`:
    -   Usa `concurrently` para ejecutar en paralelo:
        -   `php artisan serve`: Inicia el servidor de desarrollo local.
        -   `php artisan queue:listen --tries=1`: Inicia el listener de colas para gestionar trabajos en segundo plano.
        -   `php artisan pail --timeout=0`: Inicia Pail para depuración.
        -   `npm run dev`: Inicia el servidor de desarrollo de Vite.

### Configuración

-   `allow-plugins`: Incluye `pestphp` y `php-http`
    -   `pestphp/pest-plugin`: Plugin para usar el framework de pruebas Pest.
    -   `php-http/discovery`: Usado para la detección de HTTP.

## 3. Análisis de `package.json`

El archivo `package.json` define las dependencias del frontend del proyecto.

### Dependencias de Desarrollo (`devDependencies`)

-   `@inertiajs/vue3`: `^2.0`
    -   Adaptador del lado del cliente para Inertia.js con Vue 3.
-   `@tailwindcss/forms`: `^0.5.7`
    -   Plugin de Tailwind CSS para el estilo de formularios.
-   `@tailwindcss/typography`: `^0.5.10`
    -   Plugin de Tailwind CSS para el estilo de tipografía.
-   `@vitejs/plugin-vue`: `^5.0.0`
    -   Plugin de Vue para la herramienta de compilación Vite.
-   `autoprefixer`: `^10.4.16`
    -   Prefijado de CSS.
-   `axios`: `^1.7.4`
    -   Cliente HTTP.
-   `concurrently`: `^9.0.1`
    -   Ejecuta múltiples comandos concurrentemente.
-   `laravel-vite-plugin`: `^1.2.0`
    -   Plugin de Vite para Laravel.
-   `postcss`: `^8.4.32`
    -   Transformación de CSS.
-   `tailwindcss`: `^3.4.0`
    -   Framework CSS de utilidad primero.
-   `vite`: `^6.0.11`
    -   Herramienta de compilación de frontend.
-   `vue`: `^3.3.13`
    -   Framework de frontend.

### Dependencias (`dependencies`)

-   `@headlessui/vue`: `^1.7.23`
    -   Componentes de UI sin estilo.
-   `@heroicons/vue`: `^2.2.0`
    -   Librería de iconos.
-   `@inertiajs/inertia-vue3`: `^0.6.0`
    -   Adaptador del lado del cliente para Inertia.js con Vue 3.