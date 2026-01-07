<?php
/**
 * Archive Template for Reflexiones
 *
 * @package CFC_Familiar
 */

// Si es acceso directo al archive (no desde page template), verificar que exista página configurada
if (is_post_type_archive('cfc_reflexion') && !is_page()) {
    $reflexiones_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-reflexiones.php',
        'number' => 1
    ));

    if (empty($reflexiones_page)) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include(get_template_directory() . '/404.php');
        exit;
    }
}

get_header();

// Obtener el mensaje destacado (el más reciente con video)
$mensaje_destacado = new WP_Query(array(
    'post_type' => 'cfc_reflexion',
    'posts_per_page' => 1,
    'meta_query' => array(
        array(
            'key' => 'video_url',
            'value' => '',
            'compare' => '!='
        )
    ),
    'orderby' => 'date',
    'order' => 'DESC'
));

// Obtener todas las categorías
$categorias = get_terms(array(
    'taxonomy' => 'categoria_reflexion',
    'hide_empty' => false,
));

// Fallback config para categorías existentes (si no tienen meta guardado)
$categoria_fallback = array(
    'predicas' => array('icon' => '⛪', 'color' => 'purple', 'gradient' => 'from-purple-600 to-indigo-600'),
    'reflexiones' => array('icon' => '🙏', 'color' => 'blue', 'gradient' => 'from-blue-500 to-cyan-500'),
    'youtube' => array('icon' => '▶️', 'color' => 'red', 'gradient' => 'from-red-500 to-rose-500'),
    'podcast' => array('icon' => '🎧', 'color' => 'purple', 'gradient' => 'from-purple-600 to-pink-600'),
    'estudios' => array('icon' => '📖', 'color' => 'green', 'gradient' => 'from-green-500 to-teal-600'),
    'devocionales' => array('icon' => '☀️', 'color' => 'amber', 'gradient' => 'from-amber-500 to-orange-500'),
);

// Defaults para categorías nuevas sin configuración
$default_config = array('icon' => '📖', 'color' => 'blue', 'gradient' => 'from-blue-500 to-cyan-500');

/**
 * Helper: Obtener configuración de una categoría
 * Primero busca en term_meta (BD), luego en fallback, luego defaults
 */
function cfc_get_categoria_config($term_id, $slug, $fallback, $default) {
    // Intentar obtener de la BD
    $icon = get_term_meta($term_id, 'categoria_icono', true);
    $color = get_term_meta($term_id, 'categoria_color', true);
    $gradient = get_term_meta($term_id, 'categoria_gradiente', true);

    // Si tiene datos en BD, usarlos
    if ($icon || $color || $gradient) {
        return array(
            'icon' => $icon ?: $default['icon'],
            'color' => $color ?: $default['color'],
            'gradient' => $gradient ?: $default['gradient'],
        );
    }

    // Si no, usar fallback por slug
    if (isset($fallback[$slug])) {
        return $fallback[$slug];
    }

    // Si no existe en fallback, usar defaults
    return $default;
}


// Obtener valores del metabox si es página con template
$page_id = get_the_ID();
$hero_titulo = get_post_meta($page_id, 'reflexiones_hero_titulo', true) ?: 'Reflexiones';
$hero_subtitulo = get_post_meta($page_id, 'reflexiones_hero_subtitulo', true) ?: 'Contenido para alimentar tu vida espiritual';
$hero_imagen = get_post_meta($page_id, 'reflexiones_hero_imagen', true) ?: 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=1920&h=1080&fit=crop';
?>

    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo esc_url($hero_imagen); ?>"
                 alt="Reflexiones"
                 class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-aos="fade-up">
                <?php echo esc_html($hero_titulo); ?>
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                <?php echo esc_html($hero_subtitulo); ?>
            </p>
        </div>
    </section>

    <!-- Contenido Principal -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <!-- Tabs de Filtrado -->
            <div class="max-w-5xl mx-auto mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-2 flex flex-wrap gap-2" data-aos="fade-up">
                    <button class="filter-tab active" data-filter="todos">
                        <span>&#10024;</span> Todos
                    </button>
                    <?php if ($categorias && !is_wp_error($categorias)) : ?>
                        <?php foreach ($categorias as $cat) :
                            $cat_config = cfc_get_categoria_config($cat->term_id, $cat->slug, $categoria_fallback, $default_config);
                        ?>
                        <button class="filter-tab" data-filter="<?php echo esc_attr($cat->slug); ?>">
                            <span><?php echo esc_html($cat_config['icon']); ?></span> <?php echo esc_html($cat->name); ?>
                        </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sección de Último Mensaje Dominical -->
            <?php if ($mensaje_destacado->have_posts()) : $mensaje_destacado->the_post();
                $video_url = cfc_get_field('video_url', get_the_ID(), '');
                $duracion = cfc_get_field('duracion', get_the_ID(), '');
                $serie = cfc_get_field('serie', get_the_ID(), '');

                // Extraer ID de YouTube
                $youtube_id = '';
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $match)) {
                    $youtube_id = $match[1];
                }
            ?>
            <div class="max-w-6xl mx-auto mb-16" data-filter-section="mensaje-destacado">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">&#128293; Mensaje del Domingo</h2>
                    <?php
                    $predicas_term = get_term_by('slug', 'predicas', 'categoria_reflexion');
                    if ($predicas_term) : ?>
                    <a href="<?php echo esc_url(get_term_link($predicas_term)); ?>" class="text-primary hover:text-primary-dark font-semibold text-sm">
                        Ver todos los mensajes &rarr;
                    </a>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden" data-aos="zoom-in">
                    <div class="grid lg:grid-cols-2 gap-0">
                        <!-- Video del mensaje dominical -->
                        <div class="aspect-video bg-black">
                            <?php if ($youtube_id) : ?>
                            <iframe
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_id); ?>"
                                frameborder="0"
                                allowfullscreen>
                            </iframe>
                            <?php elseif (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover')); ?>
                            <?php else : ?>
                            <div class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                <span class="text-8xl opacity-50">&#128214;</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!-- Info -->
                        <div class="p-8 lg:p-10 flex flex-col justify-center">
                            <?php $cats = get_the_terms(get_the_ID(), 'categoria_reflexion'); ?>
                            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs font-bold mb-4 w-fit">
                                <?php echo $cats && !is_wp_error($cats) ? esc_html(strtoupper($cats[0]->name)) : 'MENSAJE'; ?> &bull; <?php echo cfc_format_date(); ?>
                            </span>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                <?php the_title(); ?>
                            </h3>
                            <p class="text-gray-600 mb-6">
                                <?php echo cfc_excerpt(30); ?>
                            </p>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span><?php the_author(); ?></span>
                                <?php if ($serie) : ?>
                                <span>&bull;</span>
                                <span>Serie: <?php echo esc_html($serie); ?></span>
                                <?php endif; ?>
                                <?php if ($duracion) : ?>
                                <span>&bull;</span>
                                <span><?php echo esc_html($duracion); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; wp_reset_postdata(); ?>

            <!-- Grid de Contenidos Mixtos -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto" id="reflexiones-grid">
                <?php
                // Query principal - todas las reflexiones
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $reflexiones = new WP_Query(array(
                    'post_type' => 'cfc_reflexion',
                    'posts_per_page' => 12,
                    'paged' => $paged,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($reflexiones->have_posts()) :
                    $delay = 0;
                    while ($reflexiones->have_posts()) : $reflexiones->the_post();
                        $tipo = cfc_get_field('tipo_reflexion', get_the_ID(), 'articulo');
                        $duracion = cfc_get_field('duracion', get_the_ID(), '');
                        $video_url = cfc_get_field('video_url', get_the_ID(), '');
                        $podcast_url = cfc_get_field('podcast_url', get_the_ID(), '');
                        $archivo_url = cfc_get_field('archivo_url', get_the_ID(), '');
                        $episodio = cfc_get_field('episodio', get_the_ID(), '');

                        // Obtener categoría
                        $cats = get_the_terms(get_the_ID(), 'categoria_reflexion');
                        $cat_slug = ($cats && !is_wp_error($cats)) ? $cats[0]->slug : 'reflexiones';
                        $cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : 'Reflexión';
                        $cat_term_id = ($cats && !is_wp_error($cats)) ? $cats[0]->term_id : 0;
                        $config = cfc_get_categoria_config($cat_term_id, $cat_slug, $categoria_fallback, $default_config);

                        // Determinar el tipo de card según categoría
                        $is_video = ($tipo === 'video' || $cat_slug === 'predicas' || $cat_slug === 'youtube') && $video_url;
                        $is_podcast = $cat_slug === 'podcast' || $tipo === 'podcast';
                        $is_estudio = $cat_slug === 'estudios';
                        $is_gradient_card = ($cat_slug === 'reflexiones' || $cat_slug === 'devocionales' || $is_estudio) && !$is_video;
                ?>

                <!-- Card Item -->
                <article class="content-item group" data-category="<?php echo esc_attr($cat_slug); ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500">

                        <?php if ($is_video) : ?>
                        <!-- Card de Video/Prédica -->
                        <?php
                        // Obtener imagen con fallback: featured → imagen_url → YouTube thumbnail → random
                        $reflexion_image = cfc_get_reflexion_image(get_the_ID());
                        $youtube_id = '';
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $match)) {
                            $youtube_id = $match[1];
                        }
                        // Si no hay featured ni imagen_url pero hay YouTube, usar thumbnail de YT
                        if (!has_post_thumbnail() && !get_post_meta(get_the_ID(), 'imagen_url', true) && $youtube_id) {
                            $reflexion_image = 'https://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg';
                        }
                        ?>
                        <div class="relative aspect-video">
                            <img src="<?php echo esc_url($reflexion_image); ?>"
                                 alt="<?php the_title_attribute(); ?>"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span class="inline-block px-2 py-1 bg-purple-600 text-white text-xs font-bold rounded mb-2">
                                    <?php echo esc_html(strtoupper($cat_name)); ?>
                                </span>
                                <h4 class="text-white font-bold line-clamp-2"><?php the_title(); ?></h4>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-gray-600 text-sm mb-3">
                                <?php echo cfc_format_date(); ?> <?php if ($duracion) : ?>&bull; <?php echo esc_html($duracion); ?><?php endif; ?>
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><?php the_author(); ?></span>
                                <a href="<?php the_permalink(); ?>" class="text-primary font-semibold hover:underline">Ver video</a>
                            </div>
                        </div>

                        <?php elseif ($is_podcast) : ?>
                        <!-- Card de Podcast -->
                        <div class="relative h-48 bg-gradient-to-br <?php echo esc_attr($config['gradient']); ?> flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                    </svg>
                                </div>
                                <span class="text-white font-bold text-sm">PODCAST CFC</span>
                            </div>
                            <?php if ($episodio) : ?>
                            <span class="absolute top-3 left-3 px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded">
                                EP. <?php echo esc_html($episodio); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-2">
                                <?php the_title(); ?>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                <?php echo cfc_excerpt(15); ?>
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><?php echo $duracion ? esc_html($duracion) : cfc_format_date(); ?></span>
                                <a href="<?php the_permalink(); ?>" class="text-purple-600 font-semibold hover:underline">Escuchar</a>
                            </div>
                        </div>

                        <?php elseif ($is_gradient_card) : ?>
                        <!-- Card con Gradiente (Reflexiones, Devocionales, Estudios) -->
                        <div class="relative h-48 bg-gradient-to-br <?php echo esc_attr($config['gradient']); ?> p-6 flex flex-col justify-between">
                            <span class="inline-block px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded w-fit">
                                <?php echo esc_html(strtoupper($cat_name)); ?>
                            </span>
                            <div>
                                <h3 class="text-white font-bold text-xl mb-2 line-clamp-2">
                                    <?php the_title(); ?>
                                </h3>
                                <p class="text-white/80 text-sm line-clamp-1">
                                    <?php echo cfc_excerpt(10); ?>
                                </p>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                <?php echo cfc_excerpt(20); ?>
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <?php if ($is_estudio && $archivo_url) : ?>
                                <span>PDF</span>
                                <a href="<?php echo esc_url($archivo_url); ?>" target="_blank" class="text-green-600 font-semibold hover:underline">Descargar</a>
                                <?php else : ?>
                                <span><?php echo $duracion ? esc_html($duracion) : cfc_reading_time(); ?></span>
                                <a href="<?php the_permalink(); ?>" class="text-<?php echo esc_attr($config['color']); ?>-600 font-semibold hover:underline">Leer más</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php else : ?>
                        <!-- Card Estándar con Imagen -->
                        <?php $reflexion_image = cfc_get_reflexion_image(get_the_ID()); ?>
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo esc_url($reflexion_image); ?>"
                                 alt="<?php the_title_attribute(); ?>"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span class="inline-block px-2 py-1 bg-<?php echo esc_attr($config['color']); ?>-500 text-white text-xs font-bold rounded mb-2">
                                    <?php echo esc_html(strtoupper($cat_name)); ?>
                                </span>
                                <h4 class="text-white font-bold line-clamp-2"><?php the_title(); ?></h4>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                <?php echo cfc_excerpt(15); ?>
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><?php echo cfc_reading_time(); ?></span>
                                <span><?php the_author(); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </article>
                <?php
                        $delay += 50;
                        if ($delay > 200) $delay = 0;
                    endwhile;
                else :
                ?>
                <div class="col-span-full text-center py-12">
                    <div class="text-6xl mb-4">&#128214;</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay reflexiones todavía</h3>
                    <p class="text-gray-600">Pronto agregaremos contenido inspirador.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Mensaje cuando no hay resultados del filtro -->
            <div id="no-results" class="hidden text-center py-12 max-w-6xl mx-auto">
                <div class="text-6xl mb-4">&#128269;</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay contenido en esta categoría</h3>
                <p class="text-gray-600">Intenta con otra categoría o vuelve a "Todos"</p>
            </div>

            <!-- Paginación -->
            <?php if ($reflexiones->max_num_pages > 1) : ?>
            <div class="max-w-6xl mx-auto mt-12" id="pagination-wrapper">
                <div class="flex justify-center gap-2">
                    <?php
                    echo paginate_links(array(
                        'total' => $reflexiones->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                        'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                        'type' => 'list',
                        'class' => 'pagination',
                    ));
                    ?>
                </div>
            </div>
            <?php endif; wp_reset_postdata(); ?>

            <!-- CTA YouTube -->
            <div class="max-w-4xl mx-auto mt-16 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-3xl p-8 md:p-10 text-center" data-aos="zoom-in">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    <h3 class="text-2xl font-bold">Suscríbete a nuestro canal</h3>
                </div>
                <p class="text-white/90 mb-6">
                    No te pierdas ningún mensaje. Recibe notificaciones de nuevos videos cada semana.
                </p>
                <a href="<?php echo esc_url(cfc_get_option('youtube_channel', cfc_default('youtube_channel'))); ?>" target="_blank" class="inline-flex items-center gap-2 bg-white text-red-600 px-8 py-3 rounded-full font-bold hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    Suscribirse
                </a>
            </div>
        </div>
    </section>

    <style>
        .filter-tab {
            flex: 1;
            min-width: 100px;
            padding: 0.75rem 1rem;
            background: transparent;
            border-radius: 12px;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .filter-tab:hover {
            background: #f3f4f6;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
        }

        .content-item {
            transition: all 0.3s ease;
        }

        .content-item.hidden {
            display: none;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            padding: 0;
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            background: white;
            border-radius: 10px;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .pagination li a:hover {
            background: #f3f4f6;
            color: #1e40af;
        }

        .pagination li span.current {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const contentItems = document.querySelectorAll('.content-item');
        const mensajeDestacado = document.querySelector('[data-filter-section="mensaje-destacado"]');
        const noResults = document.getElementById('no-results');
        const paginationWrapper = document.getElementById('pagination-wrapper');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Get filter value
                const filter = this.dataset.filter;

                // Show/hide mensaje destacado
                if (mensajeDestacado) {
                    if (filter === 'todos' || filter === 'predicas' || filter === 'youtube') {
                        mensajeDestacado.style.display = 'block';
                    } else {
                        mensajeDestacado.style.display = 'none';
                    }
                }

                // Filter content items
                let visibleCount = 0;
                contentItems.forEach(item => {
                    const category = item.dataset.category;
                    if (filter === 'todos' || category === filter) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }

                // Hide pagination when filtering (except "todos")
                if (paginationWrapper) {
                    if (filter === 'todos') {
                        paginationWrapper.style.display = 'block';
                    } else {
                        paginationWrapper.style.display = 'none';
                    }
                }
            });
        });
    });
    </script>

<?php get_footer(); ?>
