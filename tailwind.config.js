/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        'node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx}',
        'node_modules/flowbite/**/*.{js,jsx,ts,tsx}'
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif']
            },
            container: {
                center: true,
                padding: '1rem',
                screens: {
                    '2xl': '1140px'
                }
            },
            colors: {
                primary: '#616bc4',
                primaryDark: '#41488A',
                sliver: '#718096',
                darkText: '#c5c5c5',
            }
        },
    },
    plugins: [
        require('flowbite/plugin'),
        require('@tailwindcss/forms'),
    ],
}
