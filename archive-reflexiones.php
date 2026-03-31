<?php
/**
 * Archive Template for Reflexiones
 *
 * @package CFC_Familiar
 */

get_header();
?>

    <!-- Hero Section -->
    <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=1920&h=800&fit=crop"
                 alt="Reflexiones"
                 class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/60 to-gray-900/40"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-4" data-aos="fade-up">
                Reflexiones
            </h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Contenido para alimentar tu vida espiritual
            </p>
        </div>
    </section>

    <!-- Contenido -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">

                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $reflexiones = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 12,
                    'paged' => $paged,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($reflexiones->have_posts()) :
                ?>

                <!-- Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($reflexiones->have_posts()) : $reflexiones->the_post();
                        $duracion = cfc_get_field('duracion', get_the_ID(), '');
                        $video_url = cfc_get_field('video_url', get_the_ID(), '');
                        $cats = get_the_terms(get_the_ID(), 'category');
                        $cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';

                        // Imagen
                        if (has_post_thumbnail()) {
                            $imagen = get_the_post_thumbnail_url(get_the_ID(), 'cfc-card');
                        } else {
                            $imagen = get_post_meta(get_the_ID(), 'imagen_url', true);
                        }
                        if (empty($imagen) && $video_url) {
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $match)) {
                                $imagen = 'https://img.youtube.com/vi/' . $match[1] . '/maxresdefault.jpg';
                            }
                        }
                        if (empty($imagen)) {
                            $imagen = 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=600&h=400&fit=crop';
                        }
                    ?>
                    <article class="group" data-aos="fade-up">
                        <a href="<?php the_permalink(); ?>" class="block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <!-- Imagen -->
                            <div class="relative h-48 overflow-hidden">
                                <img src="<?php echo esc_url($imagen); ?>"
                                     alt="<?php the_title_attribute(); ?>"
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                <?php if ($video_url) : ?>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-5 h-5 text-gray-900 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Info -->
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                                    <?php if ($cat_name) : ?>
                                    <span class="font-semibold text-primary uppercase"><?php echo esc_html($cat_name); ?></span>
                                    <span>&middot;</span>
                                    <?php endif; ?>
                                    <span><?php echo cfc_format_date(); ?></span>
                                    <?php if ($duracion) : ?>
                                    <span>&middot;</span>
                                    <span><?php echo esc_html($duracion); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                    <?php the_title(); ?>
                                </h3>
                                <p class="text-gray-500 text-sm line-clamp-2">
                                    <?php echo cfc_excerpt(18); ?>
                                </p>
                            </div>
                        </a>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Paginación -->
                <?php if ($reflexiones->max_num_pages > 1) : ?>
                <div class="mt-12 flex justify-center">
                    <div class="flex gap-2">
                        <?php
                        echo paginate_links(array(
                            'total' => $reflexiones->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '&larr;',
                            'next_text' => '&rarr;',
                        ));
                        ?>
                    </div>
                </div>
                <?php endif; wp_reset_postdata(); ?>

                <?php else : ?>
                <div class="text-center py-20">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay reflexiones todavía</h3>
                    <p class="text-gray-500">Pronto agregaremos contenido inspirador.</p>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <?php
    // Zona de contenido libre desde la página (si viene de page template)
    if (is_page()) :
        $content = get_the_content();
        if (!empty(trim($content))) :
    ?>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto cfc-content">
                <?php the_content(); ?>
            </div>
        </div>
    </section>
    <?php
        endif;
    endif;
    ?>

<?php get_footer(); ?>
