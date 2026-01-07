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
                // Podcast embed for reflexiones
                $podcast_url = cfc_get_field('podcast_url', get_the_ID(), '');
                if ($podcast_url && $post_type === 'cfc_reflexion') :
                    // Detect Spotify URL and extract episode ID
                    $is_spotify = strpos($podcast_url, 'spotify.com') !== false;
                    $spotify_id = '';
                    if ($is_spotify) {
                        preg_match('/episode\/([a-zA-Z0-9]+)/', $podcast_url, $spotify_matches);
                        $spotify_id = isset($spotify_matches[1]) ? $spotify_matches[1] : '';
                    }
                ?>
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Escuchar Podcast</h3>
                            <p class="text-sm text-gray-500">Disponible en Spotify</p>
                        </div>
                    </div>
                    <?php if ($spotify_id) : ?>
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <iframe
                            style="border-radius:12px"
                            src="https://open.spotify.com/embed/episode/<?php echo esc_attr($spotify_id); ?>?utm_source=generator&theme=0"
                            width="100%"
                            height="152"
                            frameBorder="0"
                            allowfullscreen=""
                            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                            loading="lazy">
                        </iframe>
                    </div>
                    <?php else : ?>
                    <a href="<?php echo esc_url($podcast_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                        </svg>
                        Escuchar en Spotify
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php
                // Downloadable file (PDF) for reflexiones
                $archivo_url = cfc_get_field('archivo_url', get_the_ID(), '');
                if ($archivo_url && $post_type === 'cfc_reflexion') :
                ?>
                <div class="mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                                    <path d="M8 12h8v2H8zm0 4h8v2H8z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Material de Estudio</h3>
                                <p class="text-sm text-gray-500">Documento PDF disponible para descargar</p>
                            </div>
                        </div>
                        <a href="<?php echo esc_url($archivo_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2.5 rounded-full font-semibold hover:shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Descargar PDF
                        </a>
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

    <?php
    // Related posts for reflexiones
    if ($post_type === 'cfc_reflexion') :
        $related = new WP_Query(array(
            'post_type' => 'cfc_reflexion',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'orderby' => 'rand',
        ));
        if ($related->have_posts()) :
    ?>
    <!-- Related Reflexiones -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-12">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Más Reflexiones</span>
                </h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="group">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all hover:-translate-y-2">
                            <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('cfc-card', array('class' => 'w-full h-48 object-cover')); ?>
                            <?php else : ?>
                            <div class="w-full h-48 bg-gradient-to-br from-primary to-secondary"></div>
                            <?php endif; ?>
                            <div class="p-6">
                                <?php
                                $cat = get_the_terms(get_the_ID(), 'categoria_reflexion');
                                if ($cat && !is_wp_error($cat)) :
                                ?>
                                <span class="text-primary text-sm font-semibold"><?php echo esc_html($cat[0]->name); ?></span>
                                <?php endif; ?>
                                <h3 class="text-lg font-bold text-gray-800 mt-2 mb-3 group-hover:text-primary transition-colors line-clamp-2">
                                    <?php the_title(); ?>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2"><?php echo cfc_excerpt(15); ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <div class="text-center mt-10">
                    <a href="<?php echo esc_url(get_post_type_archive_link('cfc_reflexion')); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all">
                        Ver Todas las Reflexiones
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php
        endif;
        wp_reset_postdata();
    endif;
    ?>

<?php
// Floating Sticky Audio Bar for Reflexiones with Podcast
if ($post_type === 'cfc_reflexion') :
    $sticky_podcast_url = cfc_get_field('podcast_url', get_queried_object_id(), '');
    $sticky_spotify_id = '';
    if ($sticky_podcast_url && strpos($sticky_podcast_url, 'spotify.com') !== false) {
        preg_match('/episode\/([a-zA-Z0-9]+)/', $sticky_podcast_url, $spotify_matches);
        $sticky_spotify_id = isset($spotify_matches[1]) ? $spotify_matches[1] : '';
    }

    if ($sticky_podcast_url) :
?>
<!-- Floating Audio Bar -->
<div id="floating-audio-bar" class="fixed bottom-0 left-0 right-0 z-40 transform translate-y-full transition-transform duration-300">
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 shadow-2xl border-t border-gray-700">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between gap-4">
                <!-- Left: Info -->
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-semibold text-sm truncate"><?php echo get_the_title(get_queried_object_id()); ?></p>
                        <p class="text-gray-400 text-xs">Disponible en Spotify</p>
                    </div>
                </div>
                <!-- Right: Actions -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="<?php echo esc_url($sticky_podcast_url); ?>" target="_blank" rel="noopener noreferrer" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <span class="hidden sm:inline">Escuchar</span>
                    </a>
                    <button id="close-audio-bar" class="text-gray-400 hover:text-white p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var bar = document.getElementById('floating-audio-bar');
    var closeBtn = document.getElementById('close-audio-bar');
    var dismissed = false;
    var showThreshold = 400;

    function updateBar() {
        if (dismissed) return;
        if (window.scrollY > showThreshold) {
            bar.classList.remove('translate-y-full');
        } else {
            bar.classList.add('translate-y-full');
        }
    }

    window.addEventListener('scroll', updateBar);

    closeBtn.addEventListener('click', function() {
        dismissed = true;
        bar.classList.add('translate-y-full');
    });
})();
</script>
<?php
    endif;
endif;
?>

<?php get_footer(); ?>
