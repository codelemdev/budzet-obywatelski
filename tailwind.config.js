const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Models/*.php',
    ],

    safelist: [
        'bg-yellow-200',
        'bg-red-200',
        'bg-yellow-50',
        'bg-red-50',
    ],

    theme: {
        extend: {
            spacing: {
                22: '5.5rem',
                44: '11rem',
                70: '17.5rem',
                76: '19rem',
                104: '26rem',
                175: '43.75rem',
            },
            maxWidth: {
                custom: '68.5rem',
            },
            boxShadow: {
                card: '4px 4px 15px 0 rgba(36, 37, 38, 0.08)',
                dialog: '3px 4px 15px 0 rgba(36, 37, 38, 0.22)',
            },
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xxs: ['0.625rem', { lineHeight: '1rem' }],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/line-clamp'),
    ],
};
