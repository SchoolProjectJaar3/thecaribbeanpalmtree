import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            /* 
            Kleuren die gebruikt worden in de website, deze zijn ook te vinden in het PVA-document.
            hoofd -> basis -> accent -> detail -> spaarzaam
            */
            colors: {
                'sand-white': '#FDFBF7',
                'deep-blue': '#0B3B5C',
                'turquoise': '#1CA9C9',
                'sand': '#E8C77A',
                'coral': '#F2664B',
            },

            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};