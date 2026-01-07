<?php
/**
 * Template Part: Setup Required
 * Shows when a page template needs to be created/configured
 *
 * @package CFC_Familiar
 *
 * Variables expected:
 * $setup_page_name - Name of the page to create (e.g., "Ministerios")
 * $setup_template - Template file name (e.g., "page-ministerios.php")
 * $setup_template_label - Human readable template name (e.g., "Ministerios")
 */

if (!isset($setup_page_name)) $setup_page_name = 'Página';
if (!isset($setup_template_label)) $setup_template_label = $setup_page_name;

get_header();
?>

<section class="min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto text-center">

            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-8 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                Configuración Pendiente
            </h1>

            <p class="text-xl text-gray-600 mb-12">
                La página <strong class="text-primary"><?php echo esc_html($setup_page_name); ?></strong> necesita ser creada en WordPress.
            </p>

            <!-- Steps -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-left mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    3 pasos para activar esta página
                </h2>

                <div class="space-y-6">
                    <!-- Step 1 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">
                            1
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Crear página</h3>
                            <p class="text-gray-600 text-sm">
                                Ve a <strong>Páginas → Añadir nueva</strong> y crea una página llamada "<strong><?php echo esc_html($setup_page_name); ?></strong>"
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">
                            2
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Asignar plantilla</h3>
                            <p class="text-gray-600 text-sm">
                                En el panel derecho, busca <strong>Plantilla</strong> y selecciona "<strong><?php echo esc_html($setup_template_label); ?></strong>"
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">
                            3
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Publicar</h3>
                            <p class="text-gray-600 text-sm">
                                Haz clic en <strong>Publicar</strong> y listo. La página estará activa.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <?php if (current_user_can('edit_pages')) : ?>
            <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Crear Página Ahora
            </a>
            <?php else : ?>
            <a href="<?php echo home_url(); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Volver al Inicio
            </a>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>
