/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors');


module.exports = {
    content: ['./app/Frontend/Presenters/templates/*.latte', './www/Frontend/js/*.js'],
    safelist: [
        'fixed' 
    ],
    theme: { 
        screens: {
            sm: '480px',
            md: '768px',
            lg: '976px',
            xl: '1440px',
        },
        fontFamily: {
            sans: [ 'Montserrat', 'Arial', 'sans-serif'],
            serif: [ 'Georgia', 'serif'],                  
        },
       
        colors: { 
            transparent: 'transparent',
            black: colors.black,
            white: colors.white,
            gray: colors.gray,         
            orange: colors.orange,  
            red: '#F87171',
            green: '#489646',        
            secondary:  {
                50: '#eaf5fa',
                100: '#c1e2f0',
                200: '#98cfe6',
                300: '#6fbbdc',
                400: '#46a8d2',
                500: '#2d8fb9',
                600: '#236f90',
                700: '#194f67',
                800: '#0f303e',
                900: '#051015',
            },
            primary:  {
                50: '#e5fbff',
                100: '#b3f4ff',
                200: '#80edff',
                300: '#4de6ff',
                400: '#1adfff',
                500: '#00c6e6',
                600: '#009ab3',
                700: '#006e80',
                800: '#00424d',
                900: '#00161a',
            },

        }, 
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                xl: '1rem'
            }
        },
        extend: {},
    }, 
    plugins: [
        'postcss-import': {}, 
    ]
}

/*
import {
    Collapse,
    initTWE,
  } from "tw-elements";
  
  initTWE({ Collapse });
*/