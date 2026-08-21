import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Besmart/Daraz Marketplace Palette
                brand: {
                    50: '#fff5f0',
                    100: '#ffe8d9',
                    200: '#ffd0b3',
                    300: '#ffad80',
                    400: '#ff8340',
                    500: '#f85606', // Primary Besmart Orange
                    600: '#e04d05',
                    700: '#b83d04',
                    800: '#932f03',
                    900: '#792702',
                    950: '#411201',
                },
                deal: {
                    red: '#d0021b',
                    bg: '#ffeaea',
                },
                gold: {
                    400: '#ffd700',
                    500: '#ffc107',
                    600: '#e0a800',
                },
            },
            boxShadow: {
                'card': '0 1px 6px 0 rgba(0,0,0,0.06)',
                'card-hover': '0 4px 20px rgba(0,0,0,0.1)',
            }
        },
    },
    plugins: [forms, typography],
};
