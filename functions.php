<?php
/**
 * CFC Familiar Theme Functions
 *
 * @package CFC_Familiar
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme version
define('CFC_VERSION', '1.0.0');
define('CFC_THEME_DIR', get_template_directory());
define('CFC_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function cfc_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => __('Menú Principal', 'cfc-familiar'),
        'footer'  => __('Menú Footer', 'cfc-familiar'),
        'mobile'  => __('Menú Móvil', 'cfc-familiar'),
    ));

    // Register Image Sizes
    add_image_size('cfc-hero', 1920, 1080, true);
    add_image_size('cfc-card', 600, 400, true);
    add_image_size('cfc-thumbnail', 400, 300, true);
    add_image_size('cfc-square', 400, 400, true);
}
add_action('after_setup_theme', 'cfc_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function cfc_enqueue_assets() {
    // Google Fonts - Inter
    wp_enqueue_style(
        'cfc-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap',
        array(),
        null
    );

    // Tailwind CSS via CDN
    wp_enqueue_script(
        'tailwindcss',
        'https://cdn.tailwindcss.com',
        array(),
        null
    );

    // AOS Animation Library
    wp_enqueue_style(
        'aos-css',
        'https://unpkg.com/aos@2.3.1/dist/aos.css',
        array(),
        '2.3.1'
    );
    wp_enqueue_script(
        'aos-js',
        'https://unpkg.com/aos@2.3.1/dist/aos.js',
        array(),
        '2.3.1',
        true
    );

    // Tailwind Config
    wp_enqueue_script(
        'cfc-tailwind-config',
        CFC_THEME_URI . '/assets/js/tailwind-config.js',
        array('tailwindcss'),
        CFC_VERSION,
        false
    );

    // Main CSS
    wp_enqueue_style(
        'cfc-main-style',
        CFC_THEME_URI . '/assets/css/main.css',
        array('aos-css'),
        CFC_VERSION
    );

    // Main JS
    wp_enqueue_script(
        'cfc-main-js',
        CFC_THEME_URI . '/assets/js/main.js',
        array('aos-js'),
        CFC_VERSION,
        true
    );

    // Initialize AOS inline
    wp_add_inline_script('aos-js', "
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 50
            });
        });
    ");
}
add_action('wp_enqueue_scripts', 'cfc_enqueue_assets');

/**
 * Register Custom Post Types
 */
function cfc_register_post_types() {
    // Eventos
    register_post_type('cfc_evento', array(
        'labels' => array(
            'name'               => __('Eventos', 'cfc-familiar'),
            'singular_name'      => __('Evento', 'cfc-familiar'),
            'add_new'            => __('Agregar Evento', 'cfc-familiar'),
            'add_new_item'       => __('Agregar Nuevo Evento', 'cfc-familiar'),
            'edit_item'          => __('Editar Evento', 'cfc-familiar'),
            'new_item'           => __('Nuevo Evento', 'cfc-familiar'),
            'view_item'          => __('Ver Evento', 'cfc-familiar'),
            'search_items'       => __('Buscar Eventos', 'cfc-familiar'),
            'not_found'          => __('No se encontraron eventos', 'cfc-familiar'),
            'not_found_in_trash' => __('No hay eventos en la papelera', 'cfc-familiar'),
            'menu_name'          => __('Eventos', 'cfc-familiar'),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'eventos'),
        'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    ));

    // Ministerios
    register_post_type('cfc_ministerio', array(
        'labels' => array(
            'name'               => __('Ministerios', 'cfc-familiar'),
            'singular_name'      => __('Ministerio', 'cfc-familiar'),
            'add_new'            => __('Agregar Ministerio', 'cfc-familiar'),
            'add_new_item'       => __('Agregar Nuevo Ministerio', 'cfc-familiar'),
            'edit_item'          => __('Editar Ministerio', 'cfc-familiar'),
            'new_item'           => __('Nuevo Ministerio', 'cfc-familiar'),
            'view_item'          => __('Ver Ministerio', 'cfc-familiar'),
            'search_items'       => __('Buscar Ministerios', 'cfc-familiar'),
            'not_found'          => __('No se encontraron ministerios', 'cfc-familiar'),
            'not_found_in_trash' => __('No hay ministerios en la papelera', 'cfc-familiar'),
            'menu_name'          => __('Ministerios', 'cfc-familiar'),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'ministerios'),
        'menu_icon'           => 'dashicons-groups',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    ));

    // Equipo / Líderes
    register_post_type('cfc_equipo', array(
        'labels' => array(
            'name'               => __('Equipo', 'cfc-familiar'),
            'singular_name'      => __('Miembro', 'cfc-familiar'),
            'add_new'            => __('Agregar Miembro', 'cfc-familiar'),
            'add_new_item'       => __('Agregar Nuevo Miembro', 'cfc-familiar'),
            'edit_item'          => __('Editar Miembro', 'cfc-familiar'),
            'new_item'           => __('Nuevo Miembro', 'cfc-familiar'),
            'view_item'          => __('Ver Miembro', 'cfc-familiar'),
            'search_items'       => __('Buscar Miembros', 'cfc-familiar'),
            'not_found'          => __('No se encontraron miembros', 'cfc-familiar'),
            'not_found_in_trash' => __('No hay miembros en la papelera', 'cfc-familiar'),
            'menu_name'          => __('Equipo', 'cfc-familiar'),
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-groups',
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true,
    ));

    // Reflexiones
    register_post_type('cfc_reflexion', array(
        'labels' => array(
            'name'               => __('Reflexiones', 'cfc-familiar'),
            'singular_name'      => __('Reflexión', 'cfc-familiar'),
            'add_new'            => __('Agregar Reflexión', 'cfc-familiar'),
            'add_new_item'       => __('Agregar Nueva Reflexión', 'cfc-familiar'),
            'edit_item'          => __('Editar Reflexión', 'cfc-familiar'),
            'new_item'           => __('Nueva Reflexión', 'cfc-familiar'),
            'view_item'          => __('Ver Reflexión', 'cfc-familiar'),
            'search_items'       => __('Buscar Reflexiones', 'cfc-familiar'),
            'not_found'          => __('No se encontraron reflexiones', 'cfc-familiar'),
            'not_found_in_trash' => __('No hay reflexiones en la papelera', 'cfc-familiar'),
            'menu_name'          => __('Reflexiones', 'cfc-familiar'),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'reflexiones'),
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
        'show_in_rest'        => true,
    ));
}
add_action('init', 'cfc_register_post_types');

/**
 * Register Taxonomies
 */
function cfc_register_taxonomies() {
    // Tipo de Evento
    register_taxonomy('tipo_evento', 'cfc_evento', array(
        'labels' => array(
            'name'              => __('Tipos de Evento', 'cfc-familiar'),
            'singular_name'     => __('Tipo de Evento', 'cfc-familiar'),
            'search_items'      => __('Buscar Tipos', 'cfc-familiar'),
            'all_items'         => __('Todos los Tipos', 'cfc-familiar'),
            'edit_item'         => __('Editar Tipo', 'cfc-familiar'),
            'update_item'       => __('Actualizar Tipo', 'cfc-familiar'),
            'add_new_item'      => __('Agregar Nuevo Tipo', 'cfc-familiar'),
            'new_item_name'     => __('Nombre del Nuevo Tipo', 'cfc-familiar'),
            'menu_name'         => __('Tipos de Evento', 'cfc-familiar'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'tipo-evento'),
    ));

    // Tipo de Ministerio
    register_taxonomy('tipo_ministerio', 'cfc_ministerio', array(
        'labels' => array(
            'name'              => __('Tipos de Ministerio', 'cfc-familiar'),
            'singular_name'     => __('Tipo de Ministerio', 'cfc-familiar'),
            'search_items'      => __('Buscar Tipos', 'cfc-familiar'),
            'all_items'         => __('Todos los Tipos', 'cfc-familiar'),
            'edit_item'         => __('Editar Tipo', 'cfc-familiar'),
            'update_item'       => __('Actualizar Tipo', 'cfc-familiar'),
            'add_new_item'      => __('Agregar Nuevo Tipo', 'cfc-familiar'),
            'new_item_name'     => __('Nombre del Nuevo Tipo', 'cfc-familiar'),
            'menu_name'         => __('Tipos de Ministerio', 'cfc-familiar'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'tipo-ministerio'),
    ));

    // Categoría de Reflexión
    register_taxonomy('categoria_reflexion', 'cfc_reflexion', array(
        'labels' => array(
            'name'              => __('Categorías', 'cfc-familiar'),
            'singular_name'     => __('Categoría', 'cfc-familiar'),
            'search_items'      => __('Buscar Categorías', 'cfc-familiar'),
            'all_items'         => __('Todas las Categorías', 'cfc-familiar'),
            'edit_item'         => __('Editar Categoría', 'cfc-familiar'),
            'update_item'       => __('Actualizar Categoría', 'cfc-familiar'),
            'add_new_item'      => __('Agregar Nueva Categoría', 'cfc-familiar'),
            'new_item_name'     => __('Nombre de la Nueva Categoría', 'cfc-familiar'),
            'menu_name'         => __('Categorías', 'cfc-familiar'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'categoria-reflexion'),
    ));
}
add_action('init', 'cfc_register_taxonomies');

/**
 * Meta fields for Categoria Reflexion taxonomy
 */
function cfc_categoria_reflexion_icons() {
    return array(
        '⛪' => 'Iglesia/Prédicas',
        '🎧' => 'Podcast',
        '📖' => 'Estudios/Libro',
        '☀️' => 'Devocionales',
        '🙏' => 'Oración/Reflexión',
        '▶️' => 'Video/YouTube',
        '🎵' => 'Música/Alabanza',
        '💡' => 'Ideas',
        '❤️' => 'Amor',
        '✝️' => 'Cruz',
        '🔥' => 'Fuego/Avivamiento',
        '🌟' => 'Estrella',
        '📝' => 'Notas',
        '🎯' => 'Objetivo',
    );
}

function cfc_categoria_reflexion_colors() {
    return array(
        'purple' => 'Morado',
        'blue' => 'Azul',
        'red' => 'Rojo',
        'green' => 'Verde',
        'amber' => 'Ámbar/Naranja',
        'pink' => 'Rosa',
        'indigo' => 'Índigo',
        'teal' => 'Verde Azulado',
        'cyan' => 'Cian',
    );
}

function cfc_categoria_reflexion_gradients() {
    return array(
        'from-purple-600 to-indigo-600' => 'Morado → Índigo',
        'from-blue-500 to-cyan-500' => 'Azul → Cian',
        'from-red-500 to-rose-500' => 'Rojo → Rosa',
        'from-green-500 to-teal-600' => 'Verde → Teal',
        'from-amber-500 to-orange-500' => 'Ámbar → Naranja',
        'from-purple-600 to-pink-600' => 'Morado → Rosa',
        'from-indigo-500 to-blue-500' => 'Índigo → Azul',
        'from-pink-500 to-rose-500' => 'Rosa → Rose',
    );
}

// Add fields when creating new category
function cfc_categoria_reflexion_add_fields() {
    $icons = cfc_categoria_reflexion_icons();
    $colors = cfc_categoria_reflexion_colors();
    $gradients = cfc_categoria_reflexion_gradients();
    ?>
    <div class="form-field">
        <label for="categoria_icono">Icono</label>
        <select name="categoria_icono" id="categoria_icono" style="font-size: 20px;">
            <?php foreach ($icons as $icon => $label) : ?>
                <option value="<?php echo esc_attr($icon); ?>"><?php echo $icon . ' ' . esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description">Selecciona el icono que representa esta categoría</p>
    </div>
    <div class="form-field">
        <label for="categoria_color">Color</label>
        <select name="categoria_color" id="categoria_color">
            <?php foreach ($colors as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description">Color principal para badges y textos</p>
    </div>
    <div class="form-field">
        <label for="categoria_gradiente">Gradiente</label>
        <select name="categoria_gradiente" id="categoria_gradiente">
            <?php foreach ($gradients as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description">Gradiente para fondos de cards</p>
    </div>
    <?php
}
add_action('categoria_reflexion_add_form_fields', 'cfc_categoria_reflexion_add_fields');

// Add fields when editing category
function cfc_categoria_reflexion_edit_fields($term) {
    $icons = cfc_categoria_reflexion_icons();
    $colors = cfc_categoria_reflexion_colors();
    $gradients = cfc_categoria_reflexion_gradients();

    $current_icon = get_term_meta($term->term_id, 'categoria_icono', true);
    $current_color = get_term_meta($term->term_id, 'categoria_color', true);
    $current_gradient = get_term_meta($term->term_id, 'categoria_gradiente', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="categoria_icono">Icono</label></th>
        <td>
            <select name="categoria_icono" id="categoria_icono" style="font-size: 20px;">
                <?php foreach ($icons as $icon => $label) : ?>
                    <option value="<?php echo esc_attr($icon); ?>" <?php selected($current_icon, $icon); ?>><?php echo $icon . ' ' . esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description">Selecciona el icono que representa esta categoría</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="categoria_color">Color</label></th>
        <td>
            <select name="categoria_color" id="categoria_color">
                <?php foreach ($colors as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($current_color, $value); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description">Color principal para badges y textos</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="categoria_gradiente">Gradiente</label></th>
        <td>
            <select name="categoria_gradiente" id="categoria_gradiente">
                <?php foreach ($gradients as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($current_gradient, $value); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description">Gradiente para fondos de cards</p>
        </td>
    </tr>
    <?php
}
add_action('categoria_reflexion_edit_form_fields', 'cfc_categoria_reflexion_edit_fields');

// Save category meta
function cfc_save_categoria_reflexion_meta($term_id) {
    if (isset($_POST['categoria_icono'])) {
        update_term_meta($term_id, 'categoria_icono', sanitize_text_field($_POST['categoria_icono']));
    }
    if (isset($_POST['categoria_color'])) {
        update_term_meta($term_id, 'categoria_color', sanitize_text_field($_POST['categoria_color']));
    }
    if (isset($_POST['categoria_gradiente'])) {
        update_term_meta($term_id, 'categoria_gradiente', sanitize_text_field($_POST['categoria_gradiente']));
    }
}
add_action('created_categoria_reflexion', 'cfc_save_categoria_reflexion_meta');
add_action('edited_categoria_reflexion', 'cfc_save_categoria_reflexion_meta');

/**
 * Show notice on Posts page (not used in this theme)
 */
function cfc_posts_not_used_notice() {
    $screen = get_current_screen();
    if ($screen && ($screen->id === 'edit-post' || $screen->post_type === 'post')) {
        ?>
        <div class="notice notice-info" style="border-left-color: #0083ca; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
            <p style="font-size: 14px;">
                <strong>Este tema no usa Entradas (Posts).</strong>
                Usa los Custom Post Types: <a href="<?php echo admin_url('edit.php?post_type=cfc_reflexion'); ?>">Reflexiones</a>,
                <a href="<?php echo admin_url('edit.php?post_type=cfc_evento'); ?>">Eventos</a>,
                <a href="<?php echo admin_url('edit.php?post_type=cfc_ministerio'); ?>">Ministerios</a>.
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'cfc_posts_not_used_notice');

/**
 * ACF Options Pages (if ACF is installed)
 */
function cfc_acf_options_pages() {
    if (function_exists('acf_add_options_page')) {
        // Main Options Page
        acf_add_options_page(array(
            'page_title'    => __('Opciones del Tema', 'cfc-familiar'),
            'menu_title'    => __('CFC Opciones', 'cfc-familiar'),
            'menu_slug'     => 'cfc-options',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-church',
            'position'      => 2,
        ));

        // Sub Pages
        acf_add_options_sub_page(array(
            'page_title'    => __('Información de Contacto', 'cfc-familiar'),
            'menu_title'    => __('Contacto', 'cfc-familiar'),
            'parent_slug'   => 'cfc-options',
        ));

        acf_add_options_sub_page(array(
            'page_title'    => __('Redes Sociales', 'cfc-familiar'),
            'menu_title'    => __('Redes Sociales', 'cfc-familiar'),
            'parent_slug'   => 'cfc-options',
        ));

        acf_add_options_sub_page(array(
            'page_title'    => __('Horarios de Servicio', 'cfc-familiar'),
            'menu_title'    => __('Horarios', 'cfc-familiar'),
            'parent_slug'   => 'cfc-options',
        ));
    }
}
add_action('acf/init', 'cfc_acf_options_pages');

/**
 * Helper Functions
 */

// Get ACF field with fallback to post_meta
function cfc_get_field($field_name, $post_id = false, $default = '') {
    // If no post_id, get current post
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }

    // Try ACF first
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        if ($value) return $value;
    }

    // Fallback to post_meta
    if ($post_id) {
        $value = get_post_meta($post_id, $field_name, true);
        if ($value) return $value;
    }

    return $default;
}

// cfc_get_option is defined later with native options support

// Custom excerpt function
function cfc_excerpt($length = 20, $post_id = null) {
    if ($post_id) {
        $post = get_post($post_id);
        $content = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
    } else {
        global $post;
        $content = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
    }

    $content = strip_tags($content);
    $content = strip_shortcodes($content);
    $words = explode(' ', $content);

    if (count($words) > $length) {
        $words = array_slice($words, 0, $length);
        $content = implode(' ', $words) . '...';
    }

    return $content;
}

// Get reading time
function cfc_reading_time($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200);

    return $reading_time . ' min lectura';
}

/**
 * Check if page template is ready (has required content/meta)
 * If not ready, shows setup guide page and exits
 *
 * @param array $required_fields Array of meta field keys that should have values
 * @param string $page_name Name of the page for setup guide
 * @param string $template_label Human readable template name
 */
function cfc_require_page_setup($required_fields = array(), $page_name = 'Página', $template_label = '') {
    $post_id = get_the_ID();
    $is_ready = true;

    // Check if any required field is filled
    if (!empty($required_fields)) {
        $has_any_field = false;
        foreach ($required_fields as $field) {
            $value = get_post_meta($post_id, $field, true);
            if (!empty($value)) {
                $has_any_field = true;
                break;
            }
        }
        if (!$has_any_field) {
            $is_ready = false;
        }
    }

    // If not ready, show setup guide
    if (!$is_ready) {
        $setup_page_name = $page_name;
        $setup_template_label = $template_label ?: $page_name;
        include(get_template_directory() . '/template-parts/setup-required.php');
        exit;
    }

    return true;
}

/**
 * Get reflexion image URL with fallback logic
 * Priority: 1) Featured image, 2) imagen_url meta, 3) Random from array
 */
function cfc_get_reflexion_image($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }

    // 1. Check for featured image
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'cfc-card');
    }

    // 2. Check for imagen_url meta
    $imagen_url = get_post_meta($post_id, 'imagen_url', true);
    if ($imagen_url) {
        return $imagen_url;
    }

    // 3. Random fallback images
    $random_images = array(
        'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=800&h=600&fit=crop', // Bible
        'https://images.unsplash.com/photo-1519834785169-98be25ec3f84?w=800&h=600&fit=crop', // Worship
        'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop', // Nature/mountains
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop', // Sunrise
        'https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=800&h=600&fit=crop', // Church candles
        'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?w=800&h=600&fit=crop', // Peaceful landscape
        'https://images.unsplash.com/photo-1473172707857-f9e276582ab6?w=800&h=600&fit=crop', // Cross sunset
        'https://images.unsplash.com/photo-1544027993-37dbfe43562a?w=800&h=600&fit=crop', // Coffee and bible
    );

    // Use post_id to get consistent random image for same post
    $index = $post_id % count($random_images);
    return $random_images[$index];
}

// Format date in Spanish
function cfc_format_date($date = null, $format = 'j M Y') {
    if (!$date) {
        $date = get_the_date('U');
    }

    $months = array(
        'Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr',
        'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago',
        'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'
    );

    $formatted = date($format, $date);
    return str_replace(array_keys($months), array_values($months), $formatted);
}

/**
 * Default Theme Options (fallback values)
 */
function cfc_defaults() {
    return array(
        'church_name'       => 'Centro Familiar Cristiano',
        'church_address'    => 'Av. Principal #123, Ciudad de Panamá',
        'church_phone'      => '+507 6999-3772',
        'church_email'      => 'info@cfcfamiliar.com',
        'church_whatsapp'   => '50769993772',
        'google_maps_url'   => 'https://maps.google.com',
        'service_day'       => 'Domingo',
        'service_time'      => '10:00 AM',
        'youtube_channel'   => '#',
        'facebook_url'      => '#',
        'instagram_url'     => '#',
        'youtube_live_url'  => '#',
    );
}

// Get default value
function cfc_default($key) {
    $defaults = cfc_defaults();
    return isset($defaults[$key]) ? $defaults[$key] : '';
}

/**
 * Disable Gutenberg for Custom Post Types (optional)
 */
function cfc_disable_gutenberg($use_block_editor, $post_type) {
    // Uncomment to disable Gutenberg for specific post types
    // if (in_array($post_type, array('cfc_evento', 'cfc_ministerio', 'cfc_reflexion'))) {
    //     return false;
    // }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'cfc_disable_gutenberg', 10, 2);

/**
 * Admin Customizations
 */
function cfc_admin_styles() {
    echo '<style>
        .post-type-cfc_evento .wp-list-table .column-title,
        .post-type-cfc_ministerio .wp-list-table .column-title,
        .post-type-cfc_reflexion .wp-list-table .column-title {
            width: 40%;
        }

        /* Metabox Grid Layout */
        .cfc-metabox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .cfc-metabox-grid .cfc-metabox-field.full-width {
            grid-column: 1 / -1;
        }
        .cfc-metabox-grid .cfc-metabox-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1d2327;
        }
        .cfc-metabox-grid .cfc-metabox-field input,
        .cfc-metabox-grid .cfc-metabox-field select,
        .cfc-metabox-grid .cfc-metabox-field textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .cfc-metabox-grid .cfc-metabox-field input:focus,
        .cfc-metabox-grid .cfc-metabox-field select:focus,
        .cfc-metabox-grid .cfc-metabox-field textarea:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }
        .cfc-metabox-grid .description {
            margin-top: 4px;
            color: #646970;
            font-size: 12px;
        }
        .cfc-section-title {
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 16px 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #c3c4c7;
            grid-column: 1 / -1;
        }
        .cfc-section-title:first-child {
            margin-top: 0;
        }
        @media (max-width: 782px) {
            .cfc-metabox-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Legacy single-column fields */
        .cfc-metabox-field { margin-bottom: 15px; }
        .cfc-metabox-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .cfc-metabox-field input { width: 100%; padding: 8px; }
    </style>';
}
add_action('admin_head', 'cfc_admin_styles');

/**
 * Metaboxes for Custom Post Types
 */
function cfc_add_metaboxes() {
    // Eventos - 2 metaboxes
    add_meta_box('cfc_evento_fecha', 'Fecha y Ubicación', 'cfc_evento_fecha_html', 'cfc_evento', 'normal', 'high');
    add_meta_box('cfc_evento_media', 'Imagen y Registro', 'cfc_evento_media_html', 'cfc_evento', 'normal', 'high');

    // Ministerios - 2 metaboxes
    add_meta_box('cfc_ministerio_info', 'Información del Ministerio', 'cfc_ministerio_info_html', 'cfc_ministerio', 'normal', 'high');
    add_meta_box('cfc_ministerio_imagen', 'Imagen', 'cfc_ministerio_imagen_html', 'cfc_ministerio', 'normal', 'high');

    // Reflexiones - 2 metaboxes
    add_meta_box('cfc_reflexion_info', 'Información Básica', 'cfc_reflexion_info_html', 'cfc_reflexion', 'normal', 'high');
    add_meta_box('cfc_reflexion_media', 'Media y Enlaces', 'cfc_reflexion_media_html', 'cfc_reflexion', 'normal', 'high');

    // Equipo - 2 metaboxes
    add_meta_box('cfc_equipo_info', 'Información del Miembro', 'cfc_equipo_info_html', 'cfc_equipo', 'normal', 'high');
    add_meta_box('cfc_equipo_visual', 'Apariencia', 'cfc_equipo_visual_html', 'cfc_equipo', 'normal', 'high');

    // Template DAR - 2 metaboxes
    add_meta_box('cfc_dar_hero', 'Hero Section', 'cfc_dar_hero_html', 'page', 'normal', 'high');
    add_meta_box('cfc_dar_banco', 'Información Bancaria', 'cfc_dar_banco_html', 'page', 'normal', 'high');

    // Template VISITANOS - 3 metaboxes
    add_meta_box('cfc_visitanos_hero', 'Hero Section', 'cfc_visitanos_hero_html', 'page', 'normal', 'high');
    add_meta_box('cfc_visitanos_horarios', 'Horarios de Servicios', 'cfc_visitanos_horarios_html', 'page', 'normal', 'high');
    add_meta_box('cfc_visitanos_galeria', 'Galería de Imágenes', 'cfc_visitanos_galeria_html', 'page', 'normal', 'high');

    // Template QUIENES SOMOS - 2 metaboxes
    add_meta_box('cfc_quienes_hero', 'Hero Section', 'cfc_quienes_hero_html', 'page', 'normal', 'high');
    add_meta_box('cfc_quienes_mision', 'Misión y Visión', 'cfc_quienes_mision_html', 'page', 'normal', 'high');

    // Template INICIO - 3 metaboxes
    add_meta_box('cfc_inicio_hero', 'Hero Section', 'cfc_inicio_hero_html', 'page', 'normal', 'high');
    add_meta_box('cfc_inicio_adolescentes', 'Sección Adolescentes', 'cfc_inicio_adolescentes_html', 'page', 'normal', 'high');
    add_meta_box('cfc_inicio_jovenes', 'Sección Jóvenes', 'cfc_inicio_jovenes_html', 'page', 'normal', 'high');

    // Template EVENTOS - 2 metaboxes
    add_meta_box('cfc_eventos_hero', 'Hero Section', 'cfc_eventos_hero_html', 'page', 'normal', 'high');
    add_meta_box('cfc_eventos_calendar', 'Google Calendar', 'cfc_eventos_calendar_html', 'page', 'normal', 'high');

    // Template MINISTERIOS - 1 metabox
    add_meta_box('cfc_ministerios_hero', 'Hero Section', 'cfc_ministerios_hero_html', 'page', 'normal', 'high');

    // Template REFLEXIONES - 1 metabox
    add_meta_box('cfc_reflexiones_hero', 'Hero Section', 'cfc_reflexiones_hero_html', 'page', 'normal', 'high');
}
add_action('add_meta_boxes', 'cfc_add_metaboxes');

/**
 * =====================================================
 * METABOXES PARA TEMPLATES DE PÁGINAS
 * =====================================================
 */

/**
 * Template DAR: Hero Section
 */
function cfc_dar_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="dar_hero_titulo">Título</label>
            <input type="text" id="dar_hero_titulo" name="dar_hero_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'dar_hero_titulo', true) ?: 'Dar'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="dar_hero_subtitulo">Subtítulo</label>
            <input type="text" id="dar_hero_subtitulo" name="dar_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'dar_hero_subtitulo', true) ?: 'Tu generosidad transforma vidas'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="dar_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="dar_hero_imagen" name="dar_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'dar_hero_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <?php
}

/**
 * Template DAR: Información Bancaria
 */
function cfc_dar_banco_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="banco_nombre">Nombre del Banco</label>
            <input type="text" id="banco_nombre" name="banco_nombre" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_nombre', true)); ?>" placeholder="Ej: Banco General">
        </div>
        <div class="cfc-metabox-field">
            <label for="banco_tipo">Tipo de Cuenta</label>
            <input type="text" id="banco_tipo" name="banco_tipo" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_tipo', true)); ?>" placeholder="Ej: Cuenta de Ahorros">
        </div>
        <div class="cfc-metabox-field">
            <label for="banco_cuenta">Número de Cuenta</label>
            <input type="text" id="banco_cuenta" name="banco_cuenta" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_cuenta', true)); ?>" placeholder="Ej: 04-47-99-123456-7">
        </div>
        <div class="cfc-metabox-field">
            <label for="banco_titular">Titular de la Cuenta</label>
            <input type="text" id="banco_titular" name="banco_titular" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_titular', true)); ?>" placeholder="Ej: Centro Familiar Cristiano">
        </div>
    </div>
    <?php
}

/**
 * Template VISITANOS: Hero Section
 */
function cfc_visitanos_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="visitanos_hero_titulo">Título</label>
            <input type="text" id="visitanos_hero_titulo" name="visitanos_hero_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'visitanos_hero_titulo', true) ?: 'Visítanos'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="visitanos_hero_subtitulo">Subtítulo</label>
            <input type="text" id="visitanos_hero_subtitulo" name="visitanos_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'visitanos_hero_subtitulo', true) ?: 'Te esperamos con los brazos abiertos'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="visitanos_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="visitanos_hero_imagen" name="visitanos_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'visitanos_hero_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <?php
}

/**
 * Template VISITANOS: Horarios de Servicios
 */
function cfc_visitanos_horarios_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width" style="background: #f0f0f1; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <strong>Horario Viernes (Estudio Bíblico)</strong>
        </div>
        <div class="cfc-metabox-field">
            <label for="horario_viernes_nombre">Nombre</label>
            <input type="text" id="horario_viernes_nombre" name="horario_viernes_nombre" value="<?php echo esc_attr(get_post_meta($post->ID, 'horario_viernes_nombre', true) ?: 'Viernes'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="horario_viernes_hora">Hora</label>
            <input type="text" id="horario_viernes_hora" name="horario_viernes_hora" value="<?php echo esc_attr(get_post_meta($post->ID, 'horario_viernes_hora', true) ?: '7:00 PM'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="horario_viernes_desc">Descripción</label>
            <input type="text" id="horario_viernes_desc" name="horario_viernes_desc" value="<?php echo esc_attr(get_post_meta($post->ID, 'horario_viernes_desc', true) ?: 'Estudio Bíblico'); ?>">
        </div>

        <div class="cfc-metabox-field full-width" style="background: #f0f0f1; padding: 10px; border-radius: 4px; margin: 15px 0;">
            <strong>Horario Sábado (Jóvenes)</strong>
        </div>
        <div class="cfc-metabox-field">
            <label for="horario_sabado_nombre">Nombre</label>
            <input type="text" id="horario_sabado_nombre" name="horario_sabado_nombre" value="<?php echo esc_attr(get_post_meta($post->ID, 'horario_sabado_nombre', true) ?: 'Sábado'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="horario_sabado_hora">Hora</label>
            <input type="text" id="horario_sabado_hora" name="horario_sabado_hora" value="<?php echo esc_attr(get_post_meta($post->ID, 'horario_sabado_hora', true) ?: '4:00 PM'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="horario_sabado_desc">Descripción</label>
            <input type="text" id="horario_sabado_desc" name="horario_sabado_desc" value="<?php echo esc_attr(get_post_meta($post->ID, 'horario_sabado_desc', true) ?: 'Reunión de Jóvenes'); ?>">
        </div>
    </div>
    <?php
}

/**
 * Template VISITANOS: Galería de Imágenes
 */
function cfc_visitanos_galeria_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="galeria_1">Imagen 1 (URL)</label>
            <input type="url" id="galeria_1" name="galeria_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'galeria_1', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
        <div class="cfc-metabox-field">
            <label for="galeria_2">Imagen 2 (URL)</label>
            <input type="url" id="galeria_2" name="galeria_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'galeria_2', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
        <div class="cfc-metabox-field">
            <label for="galeria_3">Imagen 3 (URL)</label>
            <input type="url" id="galeria_3" name="galeria_3" value="<?php echo esc_attr(get_post_meta($post->ID, 'galeria_3', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <?php
}

/**
 * Template QUIENES SOMOS: Hero Section
 */
function cfc_quienes_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="quienes_hero_titulo">Título</label>
            <input type="text" id="quienes_hero_titulo" name="quienes_hero_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'quienes_hero_titulo', true) ?: 'Quiénes Somos'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="quienes_hero_subtitulo">Subtítulo</label>
            <input type="text" id="quienes_hero_subtitulo" name="quienes_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'quienes_hero_subtitulo', true) ?: 'Conoce nuestra historia y misión'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="quienes_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="quienes_hero_imagen" name="quienes_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'quienes_hero_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <?php
}

/**
 * Template QUIENES SOMOS: Misión y Visión
 */
function cfc_quienes_mision_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="mision">Nuestra Misión</label>
            <textarea id="mision" name="mision" rows="4" placeholder="Escribe la misión de la iglesia..."><?php echo esc_textarea(get_post_meta($post->ID, 'mision', true)); ?></textarea>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="vision">Nuestra Visión</label>
            <textarea id="vision" name="vision" rows="4" placeholder="Escribe la visión de la iglesia..."><?php echo esc_textarea(get_post_meta($post->ID, 'vision', true)); ?></textarea>
        </div>
    </div>
    <p class="description">El equipo de líderes se edita desde el CPT "Equipo" en el menú lateral.</p>
    <?php
}

/**
 * Template INICIO: Hero Section
 */
function cfc_inicio_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="hero_video_url">URL del Video (MP4)</label>
            <input type="url" id="hero_video_url" name="hero_video_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_video_url', true)); ?>" placeholder="https://ejemplo.com/video.mp4">
            <p class="description">Video de fondo del hero. Si está vacío, usará la imagen.</p>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="hero_image_url">URL de Imagen de Fondo</label>
            <input type="url" id="hero_image_url" name="hero_image_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_image_url', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
        <div class="cfc-metabox-field">
            <label for="hero_badge">Texto del Badge</label>
            <input type="text" id="hero_badge" name="hero_badge" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_badge', true) ?: 'En vivo cada domingo'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="hero_titulo_1">Título Línea 1</label>
            <input type="text" id="hero_titulo_1" name="hero_titulo_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_titulo_1', true) ?: 'Centro Familiar'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="hero_titulo_2">Título Línea 2 (destacado)</label>
            <input type="text" id="hero_titulo_2" name="hero_titulo_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_titulo_2', true) ?: 'Cristiano'); ?>">
        </div>
    </div>
    <?php
}

/**
 * Template INICIO: Sección Adolescentes
 */
function cfc_inicio_adolescentes_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="adol_titulo">Título</label>
            <input type="text" id="adol_titulo" name="adol_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'adol_titulo', true) ?: 'Adolescentes'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="adol_edad">Rango de Edad</label>
            <input type="text" id="adol_edad" name="adol_edad" value="<?php echo esc_attr(get_post_meta($post->ID, 'adol_edad', true) ?: '13-17 años'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="adol_horario">Horario</label>
            <input type="text" id="adol_horario" name="adol_horario" value="<?php echo esc_attr(get_post_meta($post->ID, 'adol_horario', true) ?: 'Sábados 4:00 PM'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="adol_imagen">URL de Imagen</label>
            <input type="url" id="adol_imagen" name="adol_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'adol_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="adol_desc">Descripción</label>
            <textarea id="adol_desc" name="adol_desc" rows="3"><?php echo esc_textarea(get_post_meta($post->ID, 'adol_desc', true)); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Template INICIO: Sección Jóvenes
 */
function cfc_inicio_jovenes_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="jov_titulo">Título</label>
            <input type="text" id="jov_titulo" name="jov_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'jov_titulo', true) ?: 'Jóvenes'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="jov_edad">Rango de Edad</label>
            <input type="text" id="jov_edad" name="jov_edad" value="<?php echo esc_attr(get_post_meta($post->ID, 'jov_edad', true) ?: '18-30 años'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="jov_horario">Horario</label>
            <input type="text" id="jov_horario" name="jov_horario" value="<?php echo esc_attr(get_post_meta($post->ID, 'jov_horario', true) ?: 'Viernes 7:00 PM'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="jov_imagen">URL de Imagen</label>
            <input type="url" id="jov_imagen" name="jov_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'jov_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="jov_desc">Descripción</label>
            <textarea id="jov_desc" name="jov_desc" rows="3"><?php echo esc_textarea(get_post_meta($post->ID, 'jov_desc', true)); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Template EVENTOS: Google Calendar
 */
function cfc_eventos_calendar_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    $default_embed = 'https://calendar.google.com/calendar/embed?height=600&wkst=2&bgcolor=%23ffffff&ctz=America%2FPanama&showTitle=0&showNav=1&showDate=1&showPrint=0&showTabs=0&showCalendars=0&showTz=0&mode=MONTH&src=Y2VudHJvZmFtaWxpYXJjcmlzdGlhbm9AZ21haWwuY29t&color=%234285F4';
    $default_subscribe = 'https://calendar.google.com/calendar/u/0?cid=Y2VudHJvZmFtaWxpYXJjcmlzdGlhbm9AZ21haWwuY29t';
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="eventos_calendar_embed">URL del Calendar Embed</label>
            <input type="url" id="eventos_calendar_embed" name="eventos_calendar_embed" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_calendar_embed', true) ?: $default_embed); ?>">
            <p class="description">Ve a Google Calendar > Configuración > Integrar calendario para obtener este URL</p>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="eventos_calendar_subscribe">URL para Suscribirse</label>
            <input type="url" id="eventos_calendar_subscribe" name="eventos_calendar_subscribe" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_calendar_subscribe', true) ?: $default_subscribe); ?>">
            <p class="description">Link para que los visitantes agreguen el calendario a su Google Calendar</p>
        </div>
    </div>
    <?php
}

/**
 * Template EVENTOS: Hero Section
 */
function cfc_eventos_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="eventos_hero_titulo">Título</label>
            <input type="text" id="eventos_hero_titulo" name="eventos_hero_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_hero_titulo', true) ?: 'Nuestros Eventos'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="eventos_hero_subtitulo">Subtítulo</label>
            <input type="text" id="eventos_hero_subtitulo" name="eventos_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_hero_subtitulo', true) ?: 'Únete a nuestras actividades y crece en comunidad'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="eventos_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="eventos_hero_imagen" name="eventos_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_hero_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <p class="description">Los eventos individuales se crean desde el menú "Eventos" en el panel lateral.</p>
    <?php
}

/**
 * Template MINISTERIOS: Hero Section
 */
function cfc_ministerios_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="ministerios_hero_titulo">Título</label>
            <input type="text" id="ministerios_hero_titulo" name="ministerios_hero_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'ministerios_hero_titulo', true) ?: 'Nuestros Ministerios'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="ministerios_hero_subtitulo">Subtítulo</label>
            <input type="text" id="ministerios_hero_subtitulo" name="ministerios_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'ministerios_hero_subtitulo', true) ?: 'Descubre las diferentes formas en las que puedes servir y crecer'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="ministerios_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="ministerios_hero_imagen" name="ministerios_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'ministerios_hero_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <p class="description">Los ministerios individuales se crean desde el menú "Ministerios" en el panel lateral.</p>
    <?php
}

/**
 * Template REFLEXIONES: Hero Section
 */
function cfc_reflexiones_hero_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="reflexiones_hero_titulo">Título</label>
            <input type="text" id="reflexiones_hero_titulo" name="reflexiones_hero_titulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'reflexiones_hero_titulo', true) ?: 'Reflexiones'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="reflexiones_hero_subtitulo">Subtítulo</label>
            <input type="text" id="reflexiones_hero_subtitulo" name="reflexiones_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'reflexiones_hero_subtitulo', true) ?: 'Contenido para alimentar tu vida espiritual'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="reflexiones_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="reflexiones_hero_imagen" name="reflexiones_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'reflexiones_hero_imagen', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>
    </div>
    <p class="description">Las reflexiones individuales se crean desde el menú "Reflexiones" en el panel lateral.</p>
    <?php
}

/**
 * Save Page Template Fields
 */
function cfc_page_fields_save($post_id) {
    if (!isset($_POST['cfc_page_fields_nonce']) || !wp_verify_nonce($_POST['cfc_page_fields_nonce'], 'cfc_page_fields_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (get_post_type($post_id) !== 'page') return;

    $fields = array(
        // Dar - Hero + Banco
        'dar_hero_titulo', 'dar_hero_subtitulo', 'dar_hero_imagen',
        'banco_nombre', 'banco_cuenta', 'banco_titular', 'banco_tipo',
        // Visitanos - Hero + Horarios + Galeria
        'visitanos_hero_titulo', 'visitanos_hero_subtitulo', 'visitanos_hero_imagen',
        'horario_viernes_nombre', 'horario_viernes_hora', 'horario_viernes_desc',
        'horario_sabado_nombre', 'horario_sabado_hora', 'horario_sabado_desc',
        'galeria_1', 'galeria_2', 'galeria_3',
        // Quienes Somos - Hero + Mision
        'quienes_hero_titulo', 'quienes_hero_subtitulo', 'quienes_hero_imagen',
        'mision', 'vision',
        // Inicio - Hero + Adolescentes + Jovenes
        'hero_video_url', 'hero_image_url', 'hero_badge', 'hero_titulo_1', 'hero_titulo_2',
        'adol_titulo', 'adol_desc', 'adol_edad', 'adol_horario', 'adol_imagen',
        'jov_titulo', 'jov_desc', 'jov_edad', 'jov_horario', 'jov_imagen',
        // Eventos - Hero + Calendar
        'eventos_hero_titulo', 'eventos_hero_subtitulo', 'eventos_hero_imagen',
        'eventos_calendar_embed', 'eventos_calendar_subscribe',
        // Ministerios - Hero
        'ministerios_hero_titulo', 'ministerios_hero_subtitulo', 'ministerios_hero_imagen',
        // Reflexiones - Hero
        'reflexiones_hero_titulo', 'reflexiones_hero_subtitulo', 'reflexiones_hero_imagen'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'cfc_page_fields_save');

/**
 * JavaScript para mostrar/ocultar metaboxes según template seleccionado
 */
function cfc_page_metaboxes_visibility_script() {
    global $pagenow, $post_type;
    if (($pagenow !== 'post.php' && $pagenow !== 'post-new.php') || $post_type !== 'page') return;
    ?>
    <script>
    jQuery(document).ready(function($) {
        var metaboxMap = {
            'dar': ['cfc_dar_hero', 'cfc_dar_banco'],
            'visitanos': ['cfc_visitanos_hero', 'cfc_visitanos_horarios', 'cfc_visitanos_galeria'],
            'quienes-somos': ['cfc_quienes_hero', 'cfc_quienes_mision'],
            'inicio': ['cfc_inicio_hero', 'cfc_inicio_adolescentes', 'cfc_inicio_jovenes'],
            'eventos': ['cfc_eventos_hero', 'cfc_eventos_calendar'],
            'ministerios': ['cfc_ministerios_hero'],
            'reflexiones': ['cfc_reflexiones_hero']
        };

        var allMetaboxes = [];
        for (var key in metaboxMap) {
            allMetaboxes = allMetaboxes.concat(metaboxMap[key]);
        }

        function updateMetaboxVisibility() {
            var template = '';

            // Gutenberg
            if (wp.data && wp.data.select('core/editor')) {
                template = wp.data.select('core/editor').getEditedPostAttribute('template') || '';
            }
            // Classic editor
            if (!template) {
                var $select = $('#page_template');
                if ($select.length) {
                    template = $select.val();
                }
            }

            // Ocultar todos los metaboxes de templates
            allMetaboxes.forEach(function(id) {
                $('#' + id).hide();
            });

            // Mostrar los metaboxes del template actual
            for (var key in metaboxMap) {
                if (template.indexOf(key) !== -1) {
                    metaboxMap[key].forEach(function(id) {
                        $('#' + id).show();
                    });
                    break;
                }
            }
        }

        // Ejecutar al cargar
        updateMetaboxVisibility();

        // Classic editor
        $('#page_template').on('change', updateMetaboxVisibility);

        // Gutenberg
        if (wp.data && wp.data.subscribe) {
            var lastTemplate = '';
            wp.data.subscribe(function() {
                if (wp.data.select('core/editor')) {
                    var template = wp.data.select('core/editor').getEditedPostAttribute('template') || '';
                    if (template !== lastTemplate) {
                        lastTemplate = template;
                        updateMetaboxVisibility();
                    }
                }
            });
        }
    });
    </script>
    <?php
}
add_action('admin_footer', 'cfc_page_metaboxes_visibility_script');

/**
 * Evento Metabox 1: Fecha y Ubicación
 */
function cfc_evento_fecha_html($post) {
    wp_nonce_field('cfc_evento_save', 'cfc_evento_nonce');
    $fecha = get_post_meta($post->ID, 'fecha_evento', true);
    $fecha_fin = get_post_meta($post->ID, 'fecha_fin_evento', true);
    $hora = get_post_meta($post->ID, 'hora_evento', true);
    $hora_fin = get_post_meta($post->ID, 'hora_fin_evento', true);
    $ubicacion = get_post_meta($post->ID, 'ubicacion_evento', true);
    $maps_url = get_post_meta($post->ID, 'maps_url', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="fecha_evento">Fecha Inicio</label>
            <input type="date" id="fecha_evento" name="fecha_evento" value="<?php echo esc_attr($fecha); ?>">
        </div>

        <div class="cfc-metabox-field">
            <label for="fecha_fin_evento">Fecha Fin <small>(opcional)</small></label>
            <input type="date" id="fecha_fin_evento" name="fecha_fin_evento" value="<?php echo esc_attr($fecha_fin); ?>">
            <p class="description">Para eventos de varios días</p>
        </div>

        <div class="cfc-metabox-field">
            <label for="hora_evento">Hora Inicio</label>
            <input type="text" id="hora_evento" name="hora_evento" value="<?php echo esc_attr($hora); ?>" placeholder="Ej: 7:00 PM">
        </div>

        <div class="cfc-metabox-field">
            <label for="hora_fin_evento">Hora Fin <small>(opcional)</small></label>
            <input type="text" id="hora_fin_evento" name="hora_fin_evento" value="<?php echo esc_attr($hora_fin); ?>" placeholder="Ej: 9:00 PM">
        </div>

        <div class="cfc-metabox-field">
            <label for="ubicacion_evento">Ubicación</label>
            <input type="text" id="ubicacion_evento" name="ubicacion_evento" value="<?php echo esc_attr($ubicacion); ?>" placeholder="Ej: Centro Familiar Cristiano">
        </div>

        <div class="cfc-metabox-field">
            <label for="maps_url">URL Google Maps <small>(opcional)</small></label>
            <input type="url" id="maps_url" name="maps_url" value="<?php echo esc_attr($maps_url); ?>" placeholder="https://maps.google.com/...">
        </div>
    </div>
    <?php
}

/**
 * Evento Metabox 2: Imagen y Registro
 */
function cfc_evento_media_html($post) {
    $imagen_url = get_post_meta($post->ID, 'imagen_url', true);
    $registro_url = get_post_meta($post->ID, 'registro_url', true);
    $texto_boton = get_post_meta($post->ID, 'texto_boton', true);
    $costo = get_post_meta($post->ID, 'costo_evento', true);
    $capacidad = get_post_meta($post->ID, 'capacidad_evento', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="imagen_url">URL de Imagen</label>
            <input type="url" id="imagen_url" name="imagen_url" value="<?php echo esc_attr($imagen_url); ?>" placeholder="https://images.unsplash.com/...">
            <p class="description">Si no usas Imagen Destacada, pon la URL aquí. Si está vacío, se usará imagen aleatoria.</p>
        </div>

        <div class="cfc-metabox-field">
            <label for="costo_evento">Costo</label>
            <input type="text" id="costo_evento" name="costo_evento" value="<?php echo esc_attr($costo); ?>" placeholder="Gratis / $25.00">
        </div>

        <div class="cfc-metabox-field">
            <label for="capacidad_evento">Capacidad <small>(opcional)</small></label>
            <input type="text" id="capacidad_evento" name="capacidad_evento" value="<?php echo esc_attr($capacidad); ?>" placeholder="100 personas">
        </div>

        <div class="cfc-metabox-field">
            <label for="registro_url">URL de Registro</label>
            <input type="url" id="registro_url" name="registro_url" value="<?php echo esc_attr($registro_url); ?>" placeholder="https://forms.google.com/...">
        </div>

        <div class="cfc-metabox-field">
            <label for="texto_boton">Texto del Botón</label>
            <input type="text" id="texto_boton" name="texto_boton" value="<?php echo esc_attr($texto_boton); ?>" placeholder="Registrar Ahora">
        </div>
    </div>
    <?php
}

function cfc_evento_save($post_id) {
    if (!isset($_POST['cfc_evento_nonce']) || !wp_verify_nonce($_POST['cfc_evento_nonce'], 'cfc_evento_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('fecha_evento', 'fecha_fin_evento', 'hora_evento', 'hora_fin_evento', 'ubicacion_evento', 'maps_url', 'imagen_url', 'registro_url', 'texto_boton', 'costo_evento', 'capacidad_evento');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_cfc_evento', 'cfc_evento_save');

/**
 * Ministerio Metabox 1: Información del Ministerio
 */
function cfc_ministerio_info_html($post) {
    wp_nonce_field('cfc_ministerio_save', 'cfc_ministerio_nonce');
    $lider = get_post_meta($post->ID, 'lider_ministerio', true);
    $horario = get_post_meta($post->ID, 'horario_reunion', true);
    $whatsapp = get_post_meta($post->ID, 'whatsapp_ministerio', true);
    $ubicacion = get_post_meta($post->ID, 'ubicacion_ministerio', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="lider_ministerio">Líder</label>
            <input type="text" id="lider_ministerio" name="lider_ministerio" value="<?php echo esc_attr($lider); ?>" placeholder="Ej: Pastor Juan Pérez">
        </div>

        <div class="cfc-metabox-field">
            <label for="horario_reunion">Horario de Reunión</label>
            <input type="text" id="horario_reunion" name="horario_reunion" value="<?php echo esc_attr($horario); ?>" placeholder="Ej: Sábados 4:00 PM">
        </div>

        <div class="cfc-metabox-field">
            <label for="whatsapp_ministerio">WhatsApp de Contacto</label>
            <input type="text" id="whatsapp_ministerio" name="whatsapp_ministerio" value="<?php echo esc_attr($whatsapp); ?>" placeholder="Ej: 50769993772 (sin + ni espacios)">
            <p class="description">Número para botón de WhatsApp en la tarjeta del ministerio</p>
        </div>

        <div class="cfc-metabox-field">
            <label for="ubicacion_ministerio">Ubicación</label>
            <input type="text" id="ubicacion_ministerio" name="ubicacion_ministerio" value="<?php echo esc_attr($ubicacion); ?>" placeholder="Ej: Salón Principal">
        </div>
    </div>
    <?php
}

/**
 * Ministerio Metabox 2: Imagen
 */
function cfc_ministerio_imagen_html($post) {
    $imagen_url = get_post_meta($post->ID, 'imagen_url', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="imagen_url">URL de Imagen</label>
            <input type="url" id="imagen_url" name="imagen_url" value="<?php echo esc_attr($imagen_url); ?>" placeholder="https://images.unsplash.com/...">
            <p class="description">Si no usas Imagen Destacada, pon la URL aquí.</p>
        </div>
    </div>
    <?php
}

function cfc_ministerio_save($post_id) {
    if (!isset($_POST['cfc_ministerio_nonce']) || !wp_verify_nonce($_POST['cfc_ministerio_nonce'], 'cfc_ministerio_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('lider_ministerio', 'horario_reunion', 'whatsapp_ministerio', 'ubicacion_ministerio', 'imagen_url');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_cfc_ministerio', 'cfc_ministerio_save');

/**
 * Reflexión Metabox 1: Información Básica
 */
function cfc_reflexion_info_html($post) {
    wp_nonce_field('cfc_reflexion_save', 'cfc_reflexion_nonce');
    $tipo = get_post_meta($post->ID, 'tipo_reflexion', true);
    $duracion = get_post_meta($post->ID, 'duracion', true);
    $serie = get_post_meta($post->ID, 'serie', true);
    $episodio = get_post_meta($post->ID, 'episodio', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="tipo_reflexion">Tipo de Contenido</label>
            <select id="tipo_reflexion" name="tipo_reflexion">
                <option value="articulo" <?php selected($tipo, 'articulo'); ?>>Artículo</option>
                <option value="video" <?php selected($tipo, 'video'); ?>>Video</option>
                <option value="podcast" <?php selected($tipo, 'podcast'); ?>>Podcast</option>
                <option value="devocional" <?php selected($tipo, 'devocional'); ?>>Devocional</option>
            </select>
        </div>

        <div class="cfc-metabox-field">
            <label for="duracion">Duración</label>
            <input type="text" id="duracion" name="duracion" value="<?php echo esc_attr($duracion); ?>" placeholder="Ej: 45 min">
        </div>

        <div class="cfc-metabox-field">
            <label for="serie">Serie / Tema</label>
            <input type="text" id="serie" name="serie" value="<?php echo esc_attr($serie); ?>" placeholder="Ej: Encuentros">
        </div>

        <div class="cfc-metabox-field">
            <label for="episodio">Episodio</label>
            <input type="text" id="episodio" name="episodio" value="<?php echo esc_attr($episodio); ?>" placeholder="Ej: 24">
        </div>
    </div>
    <?php
}

/**
 * Reflexión Metabox 2: Media y Enlaces
 */
function cfc_reflexion_media_html($post) {
    $imagen_url = get_post_meta($post->ID, 'imagen_url', true);
    $video_url = get_post_meta($post->ID, 'video_url', true);
    $podcast_url = get_post_meta($post->ID, 'podcast_url', true);
    $archivo_url = get_post_meta($post->ID, 'archivo_url', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="imagen_url">URL de Imagen</label>
            <input type="url" id="imagen_url" name="imagen_url" value="<?php echo esc_attr($imagen_url); ?>" placeholder="https://images.unsplash.com/...">
            <p class="description">Si no usas Imagen Destacada, pon la URL aquí. Si está vacío, se usará imagen aleatoria.</p>
        </div>

        <div class="cfc-metabox-field">
            <label for="video_url">URL del Video (YouTube)</label>
            <input type="url" id="video_url" name="video_url" value="<?php echo esc_attr($video_url); ?>" placeholder="https://youtube.com/watch?v=...">
        </div>

        <div class="cfc-metabox-field">
            <label for="podcast_url">URL del Podcast</label>
            <input type="url" id="podcast_url" name="podcast_url" value="<?php echo esc_attr($podcast_url); ?>" placeholder="https://open.spotify.com/...">
        </div>

        <div class="cfc-metabox-field full-width">
            <label for="archivo_url">URL del Archivo (PDF)</label>
            <input type="url" id="archivo_url" name="archivo_url" value="<?php echo esc_attr($archivo_url); ?>" placeholder="https://...archivo.pdf">
            <p class="description">Para estudios bíblicos o material descargable</p>
        </div>
    </div>
    <?php
}

function cfc_reflexion_save($post_id) {
    if (!isset($_POST['cfc_reflexion_nonce']) || !wp_verify_nonce($_POST['cfc_reflexion_nonce'], 'cfc_reflexion_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('tipo_reflexion', 'imagen_url', 'video_url', 'podcast_url', 'archivo_url', 'duracion', 'serie', 'episodio');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_cfc_reflexion', 'cfc_reflexion_save');

/**
 * Equipo Metabox 1: Información del Miembro
 */
function cfc_equipo_info_html($post) {
    wp_nonce_field('cfc_equipo_save', 'cfc_equipo_nonce');
    $cargo = get_post_meta($post->ID, 'cargo', true);
    $orden = get_post_meta($post->ID, 'orden', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="cargo">Cargo / Rol</label>
            <input type="text" id="cargo" name="cargo" value="<?php echo esc_attr($cargo); ?>" placeholder="Ej: Pastor Principal">
        </div>

        <div class="cfc-metabox-field">
            <label for="orden">Orden de aparición</label>
            <input type="number" id="orden" name="orden" value="<?php echo esc_attr($orden); ?>" placeholder="1, 2, 3..." min="1">
        </div>
    </div>
    <?php
}

/**
 * Equipo Metabox 2: Apariencia
 */
function cfc_equipo_visual_html($post) {
    $icono = get_post_meta($post->ID, 'icono', true);
    $color = get_post_meta($post->ID, 'color', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="icono">Icono (emoji)</label>
            <input type="text" id="icono" name="icono" value="<?php echo esc_attr($icono); ?>" placeholder="Ej: ✝️ 🎵 🚀">
        </div>

        <div class="cfc-metabox-field">
            <label for="color">Color del badge</label>
            <select id="color" name="color">
                <option value="primary" <?php selected($color, 'primary'); ?>>Azul (Pastores)</option>
                <option value="purple" <?php selected($color, 'purple'); ?>>Morado (Alabanza)</option>
                <option value="blue" <?php selected($color, 'blue'); ?>>Celeste (Jóvenes)</option>
                <option value="orange" <?php selected($color, 'orange'); ?>>Naranja (Niños)</option>
                <option value="green" <?php selected($color, 'green'); ?>>Verde (Administración)</option>
                <option value="indigo" <?php selected($color, 'indigo'); ?>>Índigo (Varones)</option>
                <option value="pink" <?php selected($color, 'pink'); ?>>Rosa (Mujeres)</option>
            </select>
        </div>
    </div>
    <?php
}

function cfc_equipo_save($post_id) {
    if (!isset($_POST['cfc_equipo_nonce']) || !wp_verify_nonce($_POST['cfc_equipo_nonce'], 'cfc_equipo_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('cargo', 'icono', 'color', 'orden');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_cfc_equipo', 'cfc_equipo_save');

/**
 * Theme Options Page (native, no ACF needed)
 */
function cfc_add_options_page() {
    add_menu_page(
        'Opciones CFC',
        'CFC Opciones',
        'manage_options',
        'cfc-options',
        'cfc_options_page_html',
        'dashicons-admin-home',
        2
    );
}
add_action('admin_menu', 'cfc_add_options_page');

function cfc_options_page_html() {
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['cfc_options_nonce']) && wp_verify_nonce($_POST['cfc_options_nonce'], 'cfc_options_save')) {
        $options = array(
            'church_name', 'church_address', 'church_phone', 'church_email', 'church_whatsapp',
            'google_maps_url', 'service_day', 'service_time',
            'facebook_url', 'instagram_url', 'youtube_channel', 'youtube_live_url'
        );
        foreach ($options as $opt) {
            if (isset($_POST[$opt])) {
                update_option('cfc_' . $opt, sanitize_text_field($_POST[$opt]));
            }
        }
        echo '<div class="notice notice-success"><p>Opciones guardadas correctamente.</p></div>';
    }

    // Get current values
    $values = array();
    $defaults = cfc_defaults();
    foreach ($defaults as $key => $default) {
        $values[$key] = get_option('cfc_' . $key, $default);
    }
    ?>
    <div class="wrap">
        <h1>Opciones del Tema CFC</h1>
        <form method="post" action="">
            <?php wp_nonce_field('cfc_options_save', 'cfc_options_nonce'); ?>

            <h2 class="title">Información de la Iglesia</h2>
            <table class="form-table">
                <tr>
                    <th><label for="church_name">Nombre de la Iglesia</label></th>
                    <td><input type="text" id="church_name" name="church_name" value="<?php echo esc_attr($values['church_name']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="church_address">Dirección</label></th>
                    <td><input type="text" id="church_address" name="church_address" value="<?php echo esc_attr($values['church_address']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="church_phone">Teléfono</label></th>
                    <td><input type="text" id="church_phone" name="church_phone" value="<?php echo esc_attr($values['church_phone']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="church_email">Email</label></th>
                    <td><input type="email" id="church_email" name="church_email" value="<?php echo esc_attr($values['church_email']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="church_whatsapp">WhatsApp</label></th>
                    <td><input type="text" id="church_whatsapp" name="church_whatsapp" value="<?php echo esc_attr($values['church_whatsapp']); ?>" class="regular-text" placeholder="50769993772 (sin + ni espacios)"></td>
                </tr>
                <tr>
                    <th><label for="google_maps_url">URL Google Maps</label></th>
                    <td><input type="url" id="google_maps_url" name="google_maps_url" value="<?php echo esc_attr($values['google_maps_url']); ?>" class="regular-text"></td>
                </tr>
            </table>

            <h2 class="title">Horarios de Servicio</h2>
            <table class="form-table">
                <tr>
                    <th><label for="service_day">Día del Servicio</label></th>
                    <td><input type="text" id="service_day" name="service_day" value="<?php echo esc_attr($values['service_day']); ?>" class="regular-text" placeholder="Domingo"></td>
                </tr>
                <tr>
                    <th><label for="service_time">Hora del Servicio</label></th>
                    <td><input type="text" id="service_time" name="service_time" value="<?php echo esc_attr($values['service_time']); ?>" class="regular-text" placeholder="10:00 AM"></td>
                </tr>
            </table>

            <h2 class="title">Redes Sociales</h2>
            <table class="form-table">
                <tr>
                    <th><label for="facebook_url">Facebook</label></th>
                    <td><input type="url" id="facebook_url" name="facebook_url" value="<?php echo esc_attr($values['facebook_url']); ?>" class="regular-text" placeholder="https://facebook.com/tuiglesia"></td>
                </tr>
                <tr>
                    <th><label for="instagram_url">Instagram</label></th>
                    <td><input type="url" id="instagram_url" name="instagram_url" value="<?php echo esc_attr($values['instagram_url']); ?>" class="regular-text" placeholder="https://instagram.com/tuiglesia"></td>
                </tr>
                <tr>
                    <th><label for="youtube_channel">Canal de YouTube</label></th>
                    <td><input type="url" id="youtube_channel" name="youtube_channel" value="<?php echo esc_attr($values['youtube_channel']); ?>" class="regular-text" placeholder="https://youtube.com/@tuiglesia"></td>
                </tr>
                <tr>
                    <th><label for="youtube_live_url">YouTube en Vivo</label></th>
                    <td><input type="url" id="youtube_live_url" name="youtube_live_url" value="<?php echo esc_attr($values['youtube_live_url']); ?>" class="regular-text" placeholder="https://youtube.com/@tuiglesia/live"></td>
                </tr>
            </table>

            <?php submit_button('Guardar Cambios'); ?>
        </form>
    </div>
    <?php
}

/**
 * Override cfc_get_option to use native options
 */
function cfc_get_option($field_name, $default = '') {
    // Try native option first
    $value = get_option('cfc_' . $field_name, '');
    if ($value) return $value;

    // Try ACF if available
    if (function_exists('get_field')) {
        $value = get_field($field_name, 'option');
        if ($value) return $value;
    }

    return $default;
}

/**
 * Custom Menu Walker for Desktop Navigation
 */
class CFC_Desktop_Menu_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $is_dar = in_array('menu-dar', $classes) || strtolower($item->title) === 'dar';

        if ($is_dar) {
            $output .= '<a href="' . esc_url($item->url) . '" class="ml-2 bg-gradient-to-r from-primary to-secondary text-white px-6 py-2.5 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center gap-2">';
            $output .= '<span>&#128157;</span>';
            $output .= '<span>' . esc_html($item->title) . '</span>';
            $output .= '</a>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '" class="nav-link px-4 py-2 rounded-lg font-medium transition-all">' . esc_html($item->title) . '</a>';
        }
    }
}

/**
 * Custom Menu Walker for Mobile Navigation
 */
class CFC_Mobile_Menu_Walker extends Walker_Nav_Menu {
    private $icons = array(
        'visítanos' => '&#128205;',
        'visitanos' => '&#128205;',
        'quiénes somos' => '&#128101;',
        'quienes somos' => '&#128101;',
        'ministerios' => '&#128591;',
        'reflexiones' => '&#128214;',
        'eventos' => '&#127881;',
        'dar' => '&#128157;',
        'inicio' => '&#127968;',
    );

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $is_dar = in_array('menu-dar', $classes) || strtolower($item->title) === 'dar';
        $title_lower = strtolower($item->title);
        $icon = isset($this->icons[$title_lower]) ? $this->icons[$title_lower] : '&#128279;';

        if ($is_dar) {
            $output .= '<a href="' . esc_url($item->url) . '" class="mobile-menu-item group flex items-center gap-4 bg-white/20 backdrop-blur-md text-white px-6 py-4 rounded-xl hover:bg-white/30 transition-all duration-300 mt-4">';
            $output .= '<span class="text-2xl">' . $icon . '</span>';
            $output .= '<span class="text-xl font-bold">' . esc_html($item->title) . '</span>';
            $output .= '</a>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '" class="mobile-menu-item group flex items-center gap-4 text-white px-6 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">';
            $output .= '<span class="text-2xl opacity-80 group-hover:opacity-100 transition-opacity">' . $icon . '</span>';
            $output .= '<span class="text-xl font-semibold">' . esc_html($item->title) . '</span>';
            $output .= '</a>';
        }
    }
}

/**
 * Include ACF Fields documentation
 */
require_once CFC_THEME_DIR . '/inc/acf-fields.php';
require_once CFC_THEME_DIR . '/inc/github-updater.php';

/**
 * Create Sample Events (runs once)
 */
function cfc_create_sample_events() {
    // Check if already created
    if (get_option('cfc_sample_events_created')) {
        return;
    }

    // Create taxonomy terms first
    $conferencia_term = term_exists('Conferencia', 'tipo_evento');
    if (!$conferencia_term) {
        $conferencia_term = wp_insert_term('Conferencia', 'tipo_evento');
    }

    $celebracion_term = term_exists('Celebración', 'tipo_evento');
    if (!$celebracion_term) {
        $celebracion_term = wp_insert_term('Celebración', 'tipo_evento');
    }

    // Event 1: Conferencia Anual de Fe
    $evento1_id = wp_insert_post(array(
        'post_title'    => 'Conferencia Anual de Fe',
        'post_content'  => 'Tres días de adoración poderosa, enseñanza inspiradora y comunión profunda. Únete a nosotros para este tiempo especial donde juntos buscaremos el rostro de Dios y seremos renovados en nuestra fe.',
        'post_excerpt'  => 'Tres días de adoración poderosa, enseñanza inspiradora y comunión profunda.',
        'post_status'   => 'publish',
        'post_type'     => 'cfc_evento',
    ));

    if ($evento1_id && !is_wp_error($evento1_id)) {
        // Set taxonomy
        if (!is_wp_error($conferencia_term)) {
            $term_id = is_array($conferencia_term) ? $conferencia_term['term_id'] : $conferencia_term;
            wp_set_object_terms($evento1_id, (int)$term_id, 'tipo_evento');
        }
        // Set meta fields (works with or without ACF)
        update_post_meta($evento1_id, 'fecha_evento', '2025-12-15');
        update_post_meta($evento1_id, 'hora_evento', '7:00 PM');
        update_post_meta($evento1_id, 'ubicacion_evento', 'Centro Familiar Cristiano');
    }

    // Event 2: Celebración de Navidad
    $evento2_id = wp_insert_post(array(
        'post_title'    => 'Celebración de Navidad',
        'post_content'  => 'Una noche especial para toda la familia con música y celebración. Ven a celebrar el nacimiento de nuestro Salvador con villancicos, presentaciones especiales y mucho amor.',
        'post_excerpt'  => 'Una noche especial para toda la familia con música y celebración.',
        'post_status'   => 'publish',
        'post_type'     => 'cfc_evento',
    ));

    if ($evento2_id && !is_wp_error($evento2_id)) {
        // Set taxonomy
        if (!is_wp_error($celebracion_term)) {
            $term_id = is_array($celebracion_term) ? $celebracion_term['term_id'] : $celebracion_term;
            wp_set_object_terms($evento2_id, (int)$term_id, 'tipo_evento');
        }
        // Set meta fields
        update_post_meta($evento2_id, 'fecha_evento', '2025-12-22');
        update_post_meta($evento2_id, 'hora_evento', '6:00 PM');
        update_post_meta($evento2_id, 'ubicacion_evento', 'Centro Familiar Cristiano');
    }

    // Mark as created
    update_option('cfc_sample_events_created', true);
}
add_action('init', 'cfc_create_sample_events', 20);

/**
 * Create Sample Ministerios (runs once)
 */
function cfc_create_sample_ministerios() {
    if (get_option('cfc_sample_ministerios_created')) {
        return;
    }

    $ministerios = array(
        array(
            'title' => 'Adolescentes y Jóvenes',
            'desc' => 'Líderes juveniles comparten principios bíblicos para enfrentar los desafíos de la edad con una identidad saludable. Un espacio donde los jóvenes pueden crecer en su fe mientras construyen amistades significativas.',
            'lider' => 'Pastor de Jóvenes',
            'horario' => 'Sábados 4:00 PM',
            'image' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Adulto Mayor',
            'desc' => 'Para personas de 57 años en adelante, representan la experiencia y sabiduría, fortaleciendo a las nuevas generaciones. Un ministerio que honra y valora a nuestros adultos mayores.',
            'lider' => 'Coordinador de Adulto Mayor',
            'horario' => 'Jueves 10:00 AM',
            'image' => 'https://images.unsplash.com/photo-1454391304352-2bf4678b1a7a?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Alabanza y Adoración',
            'desc' => 'Grupo de siervos comprometidos guiando la alabanza congregacional usando habilidades musicales y dones espirituales. Preparamos cada servicio para crear una atmósfera de adoración genuina.',
            'lider' => 'Director de Alabanza',
            'horario' => 'Ensayos: Viernes 7:00 PM',
            'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Ministerio a las Parejas',
            'desc' => 'Formar parejas que sean discípulos de Cristo con fundamentos sólidos en sus matrimonios basados en la Palabra. Ofrecemos consejería, talleres y actividades para fortalecer tu relación.',
            'lider' => 'Pastores de Parejas',
            'horario' => 'Último sábado del mes',
            'image' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Ministerio a la Familia',
            'desc' => 'Incorporar y construir familias saludables fundamentadas en la Palabra, garantizando el crecimiento de cada miembro. Actividades y enseñanzas para toda la familia.',
            'lider' => 'Coordinador de Familias',
            'horario' => 'Domingos después del servicio',
            'image' => 'https://images.unsplash.com/photo-1606788075819-9574a6edfab3?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Ministerio a la Mujer',
            'desc' => 'Ayudar de manera integral a encontrar el verdadero propósito, desarrollando una vida que agrade a Dios. Reuniones de oración, estudios bíblicos y eventos especiales.',
            'lider' => 'Líder de Mujeres',
            'horario' => 'Primer sábado del mes',
            'image' => 'https://images.unsplash.com/photo-1571442463800-1337d7af9d2f?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Ministerio a los Varones',
            'desc' => 'Ayudar al varón en el crecimiento para que asuma su rol tanto en el hogar como en la sociedad. Desayunos, retiros y estudios para hombres.',
            'lider' => 'Líder de Varones',
            'horario' => 'Segundo sábado del mes',
            'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Ministerio de Niños',
            'desc' => 'Cada domingo enseñamos principios bíblicos a los niños de manera dinámica en la escuela dominical. Clases por edades con maestros capacitados y comprometidos.',
            'lider' => 'Directora de Niños',
            'horario' => 'Domingos 10:00 AM',
            'image' => 'https://images.unsplash.com/photo-1519491050282-cf00c82424b4?w=600&h=400&fit=crop'
        ),
        array(
            'title' => 'Privados de Libertad',
            'desc' => 'Visitamos centros de cumplimiento llevando la Palabra de Dios y esperanza a quienes más lo necesitan. Un ministerio de restauración y amor.',
            'lider' => 'Coordinador de Misiones',
            'horario' => 'Sábados (según programación)',
            'image' => 'https://images.unsplash.com/photo-1519834785169-98be25ec3f84?w=600&h=400&fit=crop'
        ),
    );

    foreach ($ministerios as $min) {
        $post_id = wp_insert_post(array(
            'post_title'    => $min['title'],
            'post_content'  => $min['desc'],
            'post_excerpt'  => $min['desc'],
            'post_status'   => 'publish',
            'post_type'     => 'cfc_ministerio',
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'lider_ministerio', $min['lider']);
            update_post_meta($post_id, 'horario_reunion', $min['horario']);
            // Store image URL for reference (featured image would need actual upload)
            update_post_meta($post_id, 'imagen_url', $min['image']);
        }
    }

    update_option('cfc_sample_ministerios_created', true);
}
add_action('init', 'cfc_create_sample_ministerios', 21);

/**
 * Create Sample Equipo Members (runs once)
 */
function cfc_create_sample_equipo() {
    if (get_option('cfc_sample_equipo_created')) {
        return;
    }

    $equipo = array(
        array(
            'nombre' => 'Pastor Principal',
            'cargo' => 'Pastor Principal',
            'icono' => '✝️',
            'color' => 'primary',
            'orden' => 1
        ),
        array(
            'nombre' => 'Pastora',
            'cargo' => 'Co-Pastora',
            'icono' => '💜',
            'color' => 'pink',
            'orden' => 2
        ),
        array(
            'nombre' => 'Líder de Alabanza',
            'cargo' => 'Director de Alabanza',
            'icono' => '🎵',
            'color' => 'purple',
            'orden' => 3
        ),
        array(
            'nombre' => 'Pastor de Jóvenes',
            'cargo' => 'Líder de Jóvenes',
            'icono' => '🚀',
            'color' => 'blue',
            'orden' => 4
        ),
        array(
            'nombre' => 'Directora de Niños',
            'cargo' => 'Ministerio Infantil',
            'icono' => '🎈',
            'color' => 'orange',
            'orden' => 5
        ),
        array(
            'nombre' => 'Líder de Varones',
            'cargo' => 'Ministerio de Varones',
            'icono' => '💪',
            'color' => 'indigo',
            'orden' => 6
        ),
    );

    foreach ($equipo as $miembro) {
        $post_id = wp_insert_post(array(
            'post_title'    => $miembro['nombre'],
            'post_status'   => 'publish',
            'post_type'     => 'cfc_equipo',
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'cargo', $miembro['cargo']);
            update_post_meta($post_id, 'icono', $miembro['icono']);
            update_post_meta($post_id, 'color', $miembro['color']);
            update_post_meta($post_id, 'orden', $miembro['orden']);
        }
    }

    update_option('cfc_sample_equipo_created', true);
}
add_action('init', 'cfc_create_sample_equipo', 22);

/**
 * Create sample Reflexiones categories and content
 */
function cfc_create_sample_reflexiones() {
    if (get_option('cfc_sample_reflexiones_created')) {
        return;
    }

    // Create categories
    $categorias = array(
        'predicas' => 'Prédicas',
        'reflexiones' => 'Reflexiones',
        'youtube' => 'YouTube',
        'podcast' => 'Podcast',
        'estudios' => 'Estudios',
        'devocionales' => 'Devocionales',
    );

    foreach ($categorias as $slug => $name) {
        if (!term_exists($slug, 'categoria_reflexion')) {
            wp_insert_term($name, 'categoria_reflexion', array('slug' => $slug));
        }
    }

    // Create sample reflexiones
    $reflexiones = array(
        array(
            'title' => 'Viviendo en Su Presencia',
            'content' => 'Un mensaje transformador sobre cómo cultivar una vida de intimidad con Dios en medio de las ocupaciones diarias. Descubre cómo la presencia de Dios puede ser tu refugio constante.',
            'categoria' => 'predicas',
            'tipo' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duracion' => '48 min',
            'serie' => 'Encuentros',
        ),
        array(
            'title' => 'El Poder de la Gratitud',
            'content' => 'La gratitud transforma nuestra perspectiva y nos acerca más a Dios. Aprende cómo desarrollar un corazón agradecido en todas las circunstancias.',
            'categoria' => 'predicas',
            'tipo' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
            'duracion' => '42 min',
            'serie' => 'Encuentros',
        ),
        array(
            'title' => 'Cuando Dios Parece Callado',
            'content' => 'Encontrando paz en los tiempos de espera. Una reflexión sobre la fidelidad de Dios aún en el silencio. A veces Dios trabaja más cuando menos lo sentimos.',
            'categoria' => 'reflexiones',
            'tipo' => 'articulo',
            'duracion' => '3 min lectura',
        ),
        array(
            'title' => 'El Amor que Transforma',
            'content' => 'Cómo el amor de Dios cambia vidas. Una mirada profunda al amor incondicional de Dios y cómo podemos reflejarlo en nuestras relaciones.',
            'categoria' => 'reflexiones',
            'tipo' => 'articulo',
            'duracion' => '5 min lectura',
        ),
        array(
            'title' => 'Renovados Cada Mañana',
            'content' => '"Por la misericordia de Jehová no hemos sido consumidos, porque nunca decayeron sus misericordias; nuevas son cada mañana; grande es tu fidelidad." - Lamentaciones 3:22-23',
            'categoria' => 'devocionales',
            'tipo' => 'devocional',
            'duracion' => '2 min lectura',
        ),
        array(
            'title' => 'Matrimonios Fuertes',
            'content' => 'Principios bíblicos para el matrimonio. En este episodio exploramos cómo construir una relación matrimonial basada en los fundamentos de la Palabra de Dios.',
            'categoria' => 'podcast',
            'tipo' => 'podcast',
            'podcast_url' => 'https://open.spotify.com/episode/example',
            'duracion' => '35 min',
            'episodio' => '24',
        ),
        array(
            'title' => 'Criando Hijos con Propósito',
            'content' => 'Consejos prácticos para padres cristianos. Cómo guiar a nuestros hijos en el camino del Señor con amor, paciencia y sabiduría.',
            'categoria' => 'podcast',
            'tipo' => 'podcast',
            'podcast_url' => 'https://open.spotify.com/episode/example2',
            'duracion' => '42 min',
            'episodio' => '25',
        ),
        array(
            'title' => 'Las Parábolas de Jesús',
            'content' => 'Guía de estudio completa sobre las parábolas de Jesús. Material descargable para grupos de estudio bíblico.',
            'categoria' => 'estudios',
            'tipo' => 'articulo',
            'archivo_url' => '#',
            'duracion' => 'PDF - 15 páginas',
        ),
    );

    foreach ($reflexiones as $index => $reflexion) {
        $existing = get_page_by_title($reflexion['title'], OBJECT, 'cfc_reflexion');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title' => $reflexion['title'],
                'post_content' => $reflexion['content'],
                'post_type' => 'cfc_reflexion',
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s', strtotime("-{$index} days")),
            ));

            if ($post_id && !is_wp_error($post_id)) {
                // Set category
                $term = get_term_by('slug', $reflexion['categoria'], 'categoria_reflexion');
                if ($term) {
                    wp_set_object_terms($post_id, $term->term_id, 'categoria_reflexion');
                }

                // Set meta fields
                update_post_meta($post_id, 'tipo_reflexion', $reflexion['tipo']);
                if (!empty($reflexion['video_url'])) {
                    update_post_meta($post_id, 'video_url', $reflexion['video_url']);
                }
                if (!empty($reflexion['podcast_url'])) {
                    update_post_meta($post_id, 'podcast_url', $reflexion['podcast_url']);
                }
                if (!empty($reflexion['archivo_url'])) {
                    update_post_meta($post_id, 'archivo_url', $reflexion['archivo_url']);
                }
                if (!empty($reflexion['duracion'])) {
                    update_post_meta($post_id, 'duracion', $reflexion['duracion']);
                }
                if (!empty($reflexion['serie'])) {
                    update_post_meta($post_id, 'serie', $reflexion['serie']);
                }
                if (!empty($reflexion['episodio'])) {
                    update_post_meta($post_id, 'episodio', $reflexion['episodio']);
                }
            }
        }
    }

    update_option('cfc_sample_reflexiones_created', true);
}
add_action('init', 'cfc_create_sample_reflexiones', 23);
