/**
 * Configuración de Tailwind CSS
 * Centro Familiar Cristiano
 */

tailwind.config = {
    theme: {
        extend: {
            colors: {
                // Paleta principal
                'primary': '#1e40af',        // Azul royal
                'primary-dark': '#1e3a8a',   // Azul marino profundo
                'secondary': '#3b82f6',      // Azul brillante
                'accent': '#0ea5e9',         // Azul cielo
                'accent-light': '#38bdf8',   // Azul cielo claro

                // Complementarios
                'success': '#10b981',        // Verde
                'rose': '#ec4899',           // Rosa
                'indigo': '#4f46e5',         // Índigo

                // Fondos
                'bg-light': '#f8fafc',       // Blanco azulado
            },
            fontFamily: {
                'sans': ['Inter', 'system-ui', 'sans-serif']
            },
            animation: {
                'bounce-slow': 'bounce 3s infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            }
        }
    }
};
