/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './**/*.php',
        './assets/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#1e40af',
                'primary-dark': '#1e3a8a',
                'secondary': '#3b82f6',
                'accent': '#0ea5e9',
                'accent-light': '#38bdf8',
                'success': '#10b981',
                'rose': '#ec4899',
                'indigo': '#4f46e5',
                'bg-light': '#f8fafc',
            },
            fontFamily: {
                'sans': ['Inter', 'system-ui', 'sans-serif']
            },
            animation: {
                'bounce-slow': 'bounce 3s infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            }
        }
    },
    plugins: [],
};
