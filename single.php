<?php
/**
 * Single Post Template
 *
 * @package CFC_Familiar
 */

get_header();

$post_type = get_post_type();
?>

    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <?php if (has_post_thumbnail()) : ?>
        <div class="absolute inset-0">
            <?php the_post_thumbnail('cfc-hero', array('class' => 'w-full h-full object-cover')); ?>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>
        <?php else : ?>
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>
        <?php endif; ?>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <?php
            // Get category/taxonomy badge
            $terms = false;
            if ($post_type === 'cfc_reflexion') {
                $terms = get_the_terms(get_the_ID(), 'categoria_reflexion');
            } elseif ($post_type === 'cfc_evento') {
                $terms = get_the_terms(get_the_ID(), 'tipo_evento');
            } elseif ($post_type === 'cfc_ministerio') {
                $terms = get_the_terms(get_the_ID(), 'tipo_ministerio');
            }
            ?>
            <?php if ($terms && !is_wp_error($terms)) : ?>
            <div class="mb-4" data-aos="fade-up">
                <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                    <?php echo esc_html($terms[0]->name); ?>
                </span>
            </div>
            <?php endif; ?>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-6" data-aos="fade-up" data-aos-delay="100">
                <?php the_title(); ?>
            </h1>

            <div class="flex items-center justify-center gap-4 text-white/80 text-sm" data-aos="fade-up" data-aos-delay="200">
                <span><?php echo cfc_format_date(); ?></span>
                <?php if ($post_type !== 'cfc_ministerio') : ?>
                <span>•</span>
                <span><?php the_author(); ?></span>
                <span>•</span>
                <span><?php echo cfc_reading_time(); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <?php while (have_posts()) : the_post(); ?>

                <?php
                // Video embed for reflexiones
                $video_url = cfc_get_field('video_url', get_the_ID(), '');
                if ($video_url && $post_type === 'cfc_reflexion') :
                    // Extract YouTube video ID
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches);
                    $video_id = isset($matches[1]) ? $matches[1] : '';
                ?>
                <div class="mb-12 rounded-2xl overflow-hidden shadow-xl">
                    <div class="aspect-video bg-black">
                        <iframe
                            class="w-full h-full"
                            src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <?php endif; ?>

                <?php
                // Event details
                if ($post_type === 'cfc_evento') :
                    $fecha = cfc_get_field('fecha_evento', get_the_ID(), '');
                    $hora = cfc_get_field('hora_evento', get_the_ID(), '');
                    $ubicacion = cfc_get_field('ubicacion_evento', get_the_ID(), '');
                    $registro_url = cfc_get_field('registro_url', get_the_ID(), '');
                ?>
                <div class="bg-gradient-to-r from-primary/5 to-secondary/5 rounded-2xl p-6 mb-12 border border-primary/10">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Detalles del Evento</h3>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <?php if ($fecha) : ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Fecha</p>
                                <p class="font-semibold text-gray-900"><?php echo cfc_format_date(strtotime($fecha), 'j M Y'); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($hora) : ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Hora</p>
                                <p class="font-semibold text-gray-900"><?php echo esc_html($hora); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($ubicacion) : ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Ubicación</p>
                                <p class="font-semibold text-gray-900"><?php echo esc_html($ubicacion); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($registro_url) : ?>
                    <div class="mt-6">
                        <a href="<?php echo esc_url($registro_url); ?>" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition-all">
                            Registrarse
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <article class="prose prose-lg max-w-none blog-content">
                    <?php the_content(); ?>
                </article>

                <?php endwhile; ?>

                <!-- Share buttons -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-gray-600 mb-4 font-semibold">Compartir:</p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="w-10 h-10 bg-black text-white rounded-lg flex items-center justify-center hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:bg-green-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Back button -->
                <div class="mt-8">
                    <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-primary transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
