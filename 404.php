<?php
/**
 * 404 Error Page Template
 *
 * @package CFC_Familiar
 */

get_header();
?>

    <!-- 404 Section -->
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50/30 py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Ilustración -->
                <div class="mb-8" data-aos="fade-up">
                    <div class="relative inline-block">
                        <span class="text-[150px] md:text-[200px] font-black text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-accent leading-none">
                            404
                        </span>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-6xl">&#128533;</span>
                        </div>
                    </div>
                </div>

                <!-- Mensaje -->
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4" data-aos="fade-up" data-aos-delay="100">
                    Página no encontrada
                </h1>
                <p class="text-xl text-gray-600 mb-8" data-aos="fade-up" data-aos-delay="200">
                    Lo sentimos, la página que buscas no existe o ha sido movida a otra ubicación.
                </p>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full font-bold hover:shadow-xl hover:scale-105 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Ir al Inicio
                    </a>
                    <a href="javascript:history.back()" class="inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-8 py-4 rounded-full font-bold border-2 border-gray-200 hover:border-primary hover:text-primary transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver Atrás
                    </a>
                </div>

                <!-- Enlaces útiles -->
                <div class="mt-12 pt-8 border-t border-gray-200" data-aos="fade-up" data-aos-delay="400">
                    <p class="text-gray-600 mb-4">¿Qué te gustaría hacer?</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="<?php echo esc_url(home_url('/visitanos')); ?>" class="text-primary hover:text-primary-dark font-semibold transition-colors">Visítanos</a>
                        <span class="text-gray-300">|</span>
                        <a href="<?php echo esc_url(get_post_type_archive_link('cfc_reflexion')); ?>" class="text-primary hover:text-primary-dark font-semibold transition-colors">Reflexiones</a>
                        <span class="text-gray-300">|</span>
                        <a href="<?php echo esc_url(get_post_type_archive_link('cfc_evento')); ?>" class="text-primary hover:text-primary-dark font-semibold transition-colors">Eventos</a>
                        <span class="text-gray-300">|</span>
                        <a href="<?php echo esc_url(get_post_type_archive_link('cfc_ministerio')); ?>" class="text-primary hover:text-primary-dark font-semibold transition-colors">Ministerios</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
