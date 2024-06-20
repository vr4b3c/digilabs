/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors');


module.exports = {
    content: ['./app/Presenters/templates/*.latte', './www/js/*.js', './www/css/*.css'],
    safelist: [],
    theme: { 
        screens: {
            sm: '480px',
            md: '768px',
            lg: '976px',
            xl: '1440px',
        },
        fontFamily: {
            sans: [ 'Montserrat', 'Arial', 'sans-serif'],                 
        },
        colors: { 
            transparent: 'transparent',
            black: colors.black,
            white: colors.white,       
            primary: {
                100: '#d4d4ff',
                300: '#8080ff',               
                500: '#2a2aff',
                700: '#0000d4',
                900: '#000080',
            }
            
        }, 
        container: {
            center: true,
        },
        extend: {},
    }, 
    plugins: []
}
