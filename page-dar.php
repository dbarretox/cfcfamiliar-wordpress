<?php
/**
 * Template Name: Dar
 *
 * @package CFC_Familiar
 */

get_header();
$whatsapp = cfc_get_option('church_whatsapp', cfc_default('church_whatsapp'));
?>

    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1920&h=1080&fit=crop"
                 alt="Dar"
                 class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-aos="fade-up">
                Tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">Generosidad</span>
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                "Cada uno debe dar según lo que haya decidido en su corazón, porque Dios ama al que da con alegría" - 2 Corintios 9:7
            </p>
        </div>
    </section>

    <!-- Why Give Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    ¿Por Qué <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Dar</span>?
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    Dar es una expresión de nuestra gratitud a Dios y nuestra fe en Su provisión. Cuando damos, participamos en la obra que Dios está haciendo en nuestra comunidad y en el mundo.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto mb-16">
                <!-- Adoración -->
                <div class="text-center" data-aos="fade-up">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Adoración</h3>
                    <p class="text-gray-600">
                        Dar es un acto de adoración que honra a Dios y reconoce que todo lo que tenemos viene de Él.
                    </p>
                </div>

                <!-- Comunidad -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 bg-gradient-to-br from-secondary to-accent rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Comunidad</h3>
                    <p class="text-gray-600">
                        Tus ofrendas sostienen los ministerios que transforman vidas y fortalecen nuestra comunidad.
                    </p>
                </div>

                <!-- Impacto -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-rose rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Impacto</h3>
                    <p class="text-gray-600">
                        Juntos podemos alcanzar más personas y marcar una diferencia en nuestro mundo.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Donation Methods Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Formas de <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Dar</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Elige la opción que mejor se adapte a ti
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Transferencia Bancaria -->
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl" data-aos="zoom-in">
                    <div class="relative h-48 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
                        </div>
                        <svg class="w-20 h-20 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Transferencia Bancaria</h3>

                        <?php
                        $banco_nombre = get_post_meta(get_the_ID(), 'banco_nombre', true) ?: 'Banco General';
                        $banco_tipo = get_post_meta(get_the_ID(), 'banco_tipo', true) ?: '';
                        $banco_cuenta = get_post_meta(get_the_ID(), 'banco_cuenta', true) ?: '04-47-99-123456-7';
                        $banco_titular = get_post_meta(get_the_ID(), 'banco_titular', true) ?: 'Centro Familiar Cristiano';
                        ?>
                        <!-- Banco -->
                        <div class="mb-6 p-4 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-xl border border-primary/10">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-gray-800"><?php echo esc_html($banco_nombre); ?></h4>
                                <span class="text-xs bg-primary/20 text-primary px-3 py-1 rounded-full font-semibold">Principal</span>
                            </div>
                            <div class="space-y-2">
                                <?php if ($banco_tipo) : ?>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 text-sm">
                                    <span class="text-gray-600 font-medium">Tipo:</span>
                                    <span class="text-gray-800"><?php echo esc_html($banco_tipo); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 text-sm">
                                    <span class="text-gray-600 font-medium">Cuenta:</span>
                                    <div class="flex items-center gap-2">
                                        <code class="bg-white px-3 py-1 rounded font-mono text-gray-800" id="cuenta-banco"><?php echo esc_html($banco_cuenta); ?></code>
                                        <button onclick="copyToClipboard('<?php echo esc_js($banco_cuenta); ?>')" class="bg-primary text-white px-3 py-1 rounded text-xs font-semibold hover:bg-blue-700 transition">
                                            Copiar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-6">
                            <p class="text-sm text-gray-600">
                                <strong>Titular:</strong> <?php echo esc_html($banco_titular); ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Por favor, indica tu nombre en el concepto
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="max-w-3xl mx-auto mt-16 p-8 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Transparencia y Confianza</h3>
                <p class="text-gray-600 text-center leading-relaxed mb-6">
                    Nos comprometemos a administrar tus ofrendas con integridad y transparencia. Cada donación se utiliza para:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">Ministerios y Programas</p>
                    </div>
                    <div class="text-center p-4">
                        <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">Equipamiento e Instalaciones</p>
                    </div>
                    <div class="text-center p-4">
                        <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">Misiones y Alcance</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-primary via-secondary to-accent relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                ¿Tienes preguntas?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Si tienes alguna pregunta sobre cómo dar o quieres conocer más sobre cómo se utilizan las ofrendas, estamos aquí para ayudarte.
            </p>
            <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo rawurlencode('Hola, tengo una pregunta sobre las ofrendas'); ?>"
               target="_blank"
               class="inline-flex items-center gap-2 bg-white text-primary px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                Contáctanos por WhatsApp
            </a>
        </div>
    </section>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Número de cuenta copiado!');
        });
    }
    </script>

<?php get_footer(); ?>
