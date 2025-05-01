import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#4C6EF5',
                secondary: '#D64545',
                success: '#066839',
                danger: '#FF6B6B',
                warning: '#E47311',
                info: '#114477',
                light: '#07C8F9',
                dark: '#2E2E3A',
                muted: '#2C2C3E',
                focus: '#7929E8',
                white: '#e5e7eb'
            }
        },
    },

    plugins: [forms, typography],
};
