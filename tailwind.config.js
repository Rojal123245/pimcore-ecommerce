/** @type {import('tailwindcss').Config} */
const theme = require("tailwindcss/defaultTheme");
module.exports = {
    content: [
        './templates/**/*.html.twig',
        './assets/**/*.js|jsx|ts|tsx',
    ],
    safelist: [
        { pattern: /prose-(a|ul|ol|strong|p|li):text-(h1|h2|h3|h4|h5|body)/ },
        { pattern: /(pt|pb|px|py)-(sm|md|lg|xl|xxl|xxxl)/ }
    ],
    theme: {
        extend: {
            colors: {
                white: '#fff',
                black: '#000',
                'primary': {
                    900: '#AD0000',
                    800: '#C90000',
                    700: '#DA0000',
                    500: '#FF0000',
                    400: '#FF340D',
                },
                'feedback': {
                    error: '#FF0000',
                    success: '#009900',
                    warning: '#FF8000',
                    info: '#00B1B4',
                },
                'neutral': {
                    'black': '#000',
                    900: '#3B424A',
                    700: '#666D76',
                    500: '#939598',
                    400: '#B7B9BB',
                    300: '#DBDCDA',
                    200: '#f5f5f5',
                    white: '#fff',
                }
            },
            screens: {
                'sm': '640px',
                'md': '768px',
                'lg': '1024px',
                'xl': '1280px',
                '2xl': '1536px',

            },
            spacing: {
                'xxs': '8px',
                'xs': '16px',
                'sm': '24px',
                'md': '32px',
                'lg': '48px',
                'xl': '64px',
                'xxl': '80px',
                'xxxl': '112px',
                'xxxxl': '136px',
            },
            // container: {
            //     screens: {
            //         sm: '100%',
            //         md: '100%',
            //         lg: '100%',
            //         xl: '1348px',
            //     },
            //     padding: {
            //         sm: '24px',
            //         md: '60px',
            //         lg: '24px',
            //         xl: '24px',
            //     },
            //
            // },
            fontFamily: {
                'noto': ['"Noto Sans"', 'sans-serif'],
                'noto-regular': ['"Noto Sans Regular"', 'sans-serif'],
                'noto-medium': ['"Noto Sans Medium"', 'sans-serif'],
                'noto-light': ['"Noto Sans Light"', 'sans-serif'],
                'noto-bold': ['"Noto Sans Bold"', 'sans-serif']
            },
            transitionProperty: {
                height: 'height',
            },
            typography: ({theme}) =>  ({
                DEFAULT: {
                    css: {
                        '--tw-prose-counters': theme('colors.black'),
                        color: theme('colors.black'),
                        a: {
                            color: theme('colors.primary[500]')
                        },
                        ul: {
                           marginTop: 0
                        },
                        ol: {
                            marginTop: 0
                        },
                        'li:marker': {
                            color: theme('colors.black')
                        },
                        p: {
                            marginTop: 0,
                            marginBottom: 0
                        },
                        li: {
                            marginTop: 0,
                            marginBottom: 0
                        }
                    }
                }
            })
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}

