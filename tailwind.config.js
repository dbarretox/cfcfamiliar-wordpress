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
                'indigo-custom': '#4f46e5',
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
    safelist: [
        // Dynamic color classes for CPTs (Grupos, Equipo badges)
        'from-purple-500', 'to-pink-500', 'from-purple-600', 'to-pink-600', 'from-purple-900/80', 'via-purple-600/40', 'bg-purple-50', 'text-purple-600', 'text-purple-700',
        'from-blue-500', 'to-cyan-500', 'from-blue-600', 'to-cyan-600', 'from-blue-900/80', 'via-blue-600/40', 'bg-blue-50', 'text-blue-600', 'text-blue-700',
        'from-green-500', 'to-teal-500', 'from-green-600', 'to-teal-600', 'from-green-900/80', 'via-green-600/40', 'bg-green-50', 'text-green-600', 'text-green-700',
        'from-orange-500', 'to-amber-500', 'from-orange-600', 'to-amber-600', 'from-orange-900/80', 'via-orange-600/40', 'bg-orange-50', 'text-orange-600', 'text-orange-700',
        'from-pink-500', 'to-rose-500', 'from-pink-600', 'to-rose-600', 'from-pink-900/80', 'via-pink-600/40', 'bg-pink-50', 'text-pink-600', 'text-pink-700',
        // Equipo badge colors
        'from-indigo-500', 'to-purple-500', 'text-indigo-600',
        'from-teal-500', 'to-cyan-500', 'text-teal-600',
        'from-amber-500', 'to-yellow-500', 'text-amber-600',
        'from-yellow-500', 'to-orange-500',
    ],
    plugins: [],
};
