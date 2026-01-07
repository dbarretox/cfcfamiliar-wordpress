<?php
/**
 * Main Template File (Fallback)
 *
 * @package CFC_Familiar
 */

get_header();
?>

    <!-- Hero Section -->
    <section class="relative h-[40vh] min-h-[300px] flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary via-secondary to-accent">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl font-black text-white mb-4" data-aos="fade-up">
                <?php
                if (is_home()) {
                    echo 'Blog';
                } elseif (is_search()) {
                    echo 'Resultados de búsqueda';
                } else {
                    echo 'Contenido';
                }
                ?>
            </h1>
            <?php if (is_search()) : ?>
            <p class="text-xl text-white/90" data-aos="fade-up" data-aos-delay="100">
                Resultados para: "<?php echo get_search_query(); ?>"
            </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Content -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <?php if (have_posts()) : ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while (have_posts()) : the_post(); ?>
                    <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all" data-aos="fade-up">
                        <?php if (has_post_thumbnail()) : ?>
                        <div class="relative h-48 overflow-hidden">
                            <?php the_post_thumbnail('cfc-card', array('class' => 'w-full h-full object-cover')); ?>
                        </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <div class="text-xs text-gray-500 mb-2"><?php echo cfc_format_date(); ?></div>
                            <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-primary transition-colors">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="text-gray-600 text-sm mb-4"><?php echo cfc_excerpt(20); ?></p>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary font-semibold text-sm">
                                Leer más
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '&larr; Anterior',
                        'next_text' => 'Siguiente &rarr;',
                    ));
                    ?>
                </div>
                <?php else : ?>
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">&#128269;</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No se encontró contenido</h2>
                    <p class="text-gray-600 mb-6">Lo sentimos, no hay resultados para mostrar.</p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-full font-semibold hover:bg-primary-dark transition-colors">
                        Volver al inicio
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
