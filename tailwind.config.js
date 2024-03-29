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
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            textColor: {
                skin: {
                    base: 'var(--color-text-base)',
                    secondary: 'var(--color-text-secondary)',
                }
            },
            backgroundColor: {
                skin: {
                    primary: 'var(--color-tertiary)',
                    secondary: 'var(--color-primary)',
                    tertiary: 'var(--color-secondary)',
                    hoverPrimary: 'var(--color-quaternary)',
                    hoverSecondary: 'var(--color-secondary)',
                }
            },
            keyframes: {
                fadeOut: {
                    '0%': { opacity: '1' },
                    '100%': { opacity: '0' },
                },
            },
            animation: {
                fadeOut: 'fadeOut 9s forwards',
            },
        },
    },

    plugins: [forms, typography],
};
