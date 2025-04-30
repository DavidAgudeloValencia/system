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
                primary: '#4c37c0',
                secondary: '#FF8500',
                success: '#066839',
                danger: '#7C162E',
                warning: '#E47311',
                info: '#114477',
                light: '#07C8F9',
                dark: '#110E1B',
                muted: '#4e4b6a',
                focus: '#7929E8',
                white: '#e6e6e6'
            }
        },
    },

    plugins: [forms, typography],
};
