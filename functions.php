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
define('CFC_VERSION', '1.0.4');
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

    // Tailwind CSS (compilado)
    wp_enqueue_style(
        'cfc-tailwind',
        CFC_THEME_URI . '/assets/css/tailwind.css',
        array(),
        CFC_VERSION
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

    // Main CSS
    wp_enqueue_style(
        'cfc-main-style',
        CFC_THEME_URI . '/assets/css/main.css',
        array('cfc-tailwind', 'aos-css'),
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
 * Favicon
 */
function cfc_favicon() {
    echo '<link rel="icon" type="image/svg+xml" href="' . CFC_THEME_URI . '/assets/images/favicon.svg">';
    echo '<link rel="icon" type="image/png" href="' . CFC_THEME_URI . '/assets/images/favicon.png">';
}
add_action('wp_head', 'cfc_favicon');

/**
 * Admin bar: contextual edit links on frontend
 */
function cfc_admin_bar_edit_links($wp_admin_bar) {
    if (is_admin() || !is_admin_bar_showing()) return;

    $url = $_SERVER['REQUEST_URI'];

    $links = array();
    if (strpos($url, 'quienes-somos') !== false) {
        $links[] = array('id' => 'cfc-edit-equipo', 'title' => 'Editar Equipo', 'href' => admin_url('edit.php?post_type=cfc_equipo'));
    }
    if (strpos($url, 'eventos') !== false) {
        $links[] = array('id' => 'cfc-edit-eventos', 'title' => 'Editar Eventos', 'href' => admin_url('edit.php?post_type=cfc_evento'));
    }
    if (strpos($url, 'ministerios') !== false) {
        $links[] = array('id' => 'cfc-edit-ministerios', 'title' => 'Editar Ministerios', 'href' => admin_url('edit.php?post_type=cfc_ministerio'));
    }

    foreach ($links as $link) {
        $wp_admin_bar->add_node(array(
            'id' => $link['id'],
            'title' => $link['title'],
            'href' => $link['href'],
            'parent' => 'edit',
        ));
    }
}
add_action('admin_bar_menu', 'cfc_admin_bar_edit_links', 100);

/**
 * Back to list button on CPT edit screens
 */
function cfc_back_to_list_button() {
    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'post') return;

    $links = array(
        'cfc_equipo' => array('url' => 'edit.php?post_type=cfc_equipo', 'label' => 'Ver Todos los Miembros'),
        'cfc_evento' => array('url' => 'edit.php?post_type=cfc_evento', 'label' => 'Ver Todos los Eventos'),
        'cfc_ministerio' => array('url' => 'edit.php?post_type=cfc_ministerio', 'label' => 'Ver Todos los Ministerios'),
        'cfc_grupo' => array('url' => 'edit.php?post_type=cfc_grupo', 'label' => 'Ver Todos los Grupos'),
    );

    if (!isset($links[$screen->post_type])) return;
    $link = $links[$screen->post_type];

    echo '<script>jQuery(function($){
        $(".wrap .page-title-action").after(\'<a href="' . admin_url($link['url']) . '" class="page-title-action">&larr; ' . $link['label'] . '</a>\');
    });</script>';
}
add_action('admin_footer', 'cfc_back_to_list_button');

/**
 * Custom update messages with "View page" link for CPTs
 */
function cfc_post_updated_messages($messages) {
    $links = array(
        'cfc_equipo'      => array('name' => 'Miembro', 'url' => home_url('/quienes-somos/')),
        'cfc_evento'      => array('name' => 'Evento', 'url' => home_url('/eventos/')),
        'cfc_ministerio'  => array('name' => 'Ministerio', 'url' => home_url('/ministerios/')),
        'cfc_grupo'       => array('name' => 'Grupo', 'url' => home_url('/')),
    );

    foreach ($links as $pt => $data) {
        $view = ' <a href="' . esc_url($data['url']) . '">Ver página</a>';
        $messages[$pt] = array(
            0  => '',
            1  => $data['name'] . ' actualizado.' . $view,
            2  => 'Campo actualizado.',
            3  => 'Campo eliminado.',
            4  => $data['name'] . ' actualizado.' . $view,
            5  => '',
            6  => $data['name'] . ' publicado.' . $view,
            7  => $data['name'] . ' guardado.',
            8  => $data['name'] . ' enviado.' . $view,
            9  => $data['name'] . ' programado.' . $view,
            10 => 'Borrador actualizado.' . $view,
        );
    }
    return $messages;
}
add_filter('post_updated_messages', 'cfc_post_updated_messages');

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
        'show_in_menu'        => 'cfc-familiar',
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
        'show_in_menu'        => 'cfc-familiar',
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
        'show_in_menu'        => 'cfc-familiar',
        'has_archive'         => false,
        'rewrite'             => false,
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true,
    ));

    // Grupos
    register_post_type('cfc_grupo', array(
        'labels' => array(
            'name'               => __('Grupos', 'cfc-familiar'),
            'singular_name'      => __('Grupo', 'cfc-familiar'),
            'add_new'            => __('Agregar Grupo', 'cfc-familiar'),
            'add_new_item'       => __('Agregar Nuevo Grupo', 'cfc-familiar'),
            'edit_item'          => __('Editar Grupo', 'cfc-familiar'),
            'new_item'           => __('Nuevo Grupo', 'cfc-familiar'),
            'view_item'          => __('Ver Grupo', 'cfc-familiar'),
            'search_items'       => __('Buscar Grupos', 'cfc-familiar'),
            'not_found'          => __('No se encontraron grupos', 'cfc-familiar'),
            'not_found_in_trash' => __('No hay grupos en la papelera', 'cfc-familiar'),
            'menu_name'          => __('Grupos', 'cfc-familiar'),
        ),
        'public'              => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'show_in_menu'        => 'cfc-familiar',
        'supports'            => array('title', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    ));

}
add_action('init', 'cfc_register_post_types');

/**
 * Register virtual pages (no WordPress Pages needed)
 */
function cfc_virtual_pages_rewrite() {
    add_rewrite_rule('^quienes-somos/?$', 'index.php?cfc_virtual_page=quienes-somos', 'top');
    add_rewrite_rule('^visitanos/?$', 'index.php?cfc_virtual_page=visitanos', 'top');
    add_rewrite_rule('^dar/?$', 'index.php?cfc_virtual_page=dar', 'top');
}
add_action('init', 'cfc_virtual_pages_rewrite');

function cfc_virtual_pages_query_var($vars) {
    $vars[] = 'cfc_virtual_page';
    return $vars;
}
add_filter('query_vars', 'cfc_virtual_pages_query_var');

function cfc_virtual_pages_template($template) {
    $virtual = get_query_var('cfc_virtual_page');
    if (!$virtual) return $template;

    $templates = array(
        'quienes-somos' => 'page-quienes-somos.php',
        'visitanos'     => 'page-visitanos.php',
        'dar'           => 'page-dar.php',
    );

    if (isset($templates[$virtual])) {
        $file = get_template_directory() . '/' . $templates[$virtual];
        if (file_exists($file)) {
            // Set up proper response
            global $wp_query;
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            status_header(200);
            return $file;
        }
    }

    return $template;
}
add_filter('template_include', 'cfc_virtual_pages_template');

/**
 * Flush rewrite rules on theme activation
 */
function cfc_flush_rewrites() {
    cfc_register_post_types();
    cfc_virtual_pages_rewrite();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'cfc_flush_rewrites');

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
add_action('category_add_form_fields', 'cfc_categoria_reflexion_add_fields');

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
add_action('category_edit_form_fields', 'cfc_categoria_reflexion_edit_fields');

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
add_action('created_category', 'cfc_save_categoria_reflexion_meta');
add_action('edited_category', 'cfc_save_categoria_reflexion_meta');


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
 * Render Lucide icon SVG by name
 */
function cfc_icon_svg($name, $class = 'w-6 h-6') {
    $icons = array(
        'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
        'heart' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'mic' => '<path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/>',
        'book-open' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'flame' => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
        'baby' => '<path d="M9 12h.01"/><path d="M15 12h.01"/><path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"/>',
        'shield' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
        'biceps' => '<path d="M14.5 17.5 3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="m16 16 3.5 3.5"/><path d="m19 19 2 2"/><path d="M14.5 6.5 18 3l3 3-3.5 3.5"/>',
        'heart-handshake' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08c.82.82 2.13.85 3 .07l2.07-1.9a2.82 2.82 0 0 1 3.79 0l2.96 2.66"/><path d="m18 15-2-2"/><path d="m15 18-2-2"/>',
        'flower2' => '<path d="M12 5a3 3 0 1 1 3 3m-3-3a3 3 0 1 0-3 3m3-3v1M9 8a3 3 0 1 0 3 3M9 8h1m5 0a3 3 0 1 1-3 3m3-3h-1m-2 3v-1"/><circle cx="12" cy="8" r="2"/><path d="M12 10v12"/><path d="M12 22c4.2 0 7-1.667 7-5-4.2 0-7 1.667-7 5Z"/><path d="M12 22c-4.2 0-7-1.667-7-5 4.2 0 7 1.667 7 5Z"/>',
        'monitor' => '<rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>',
        'rocket' => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>',
        'flower' => '<circle cx="12" cy="12" r="3"/><path d="M12 2a4 4 0 0 1 0 8 4 4 0 0 1 0-8z"/><path d="M12 16a4 4 0 0 1 0 8 4 4 0 0 1 0-8z"/><path d="M2 12a4 4 0 0 1 8 0 4 4 0 0 1-8 0z"/><path d="M16 12a4 4 0 0 1 8 0 4 4 0 0 1-8 0z"/>',
        'dumbbell' => '<path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/>',
        'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'hand-helping' => '<path d="M11 12h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 14"/><path d="m7 18 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 13 6 6"/>',
        'cross' => '<path d="M11 2a1 1 0 0 1 2 0v7h7a1 1 0 0 1 0 2h-7v11a1 1 0 0 1-2 0V11H4a1 1 0 0 1 0-2h7z"/>',
        'church' => '<path d="m18 7 4 2v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9l4-2"/><path d="M14 22v-4a2 2 0 0 0-4 0v4"/><path d="M18 22V5l-6-3-6 3v17"/><path d="M12 7v5"/><path d="M10 9h4"/>',
        'hands-praying' => '<path d="M7 20.981a4.828 4.828 0 0 1-3.535-1.465l-.707-.707a1 1 0 0 1 0-1.414l7.778-7.778a3 3 0 0 1 1.414-.793"/><path d="M17 20.981a4.828 4.828 0 0 0 3.535-1.465l.707-.707a1 1 0 0 0 0-1.414l-7.778-7.778a3 3 0 0 0-1.414-.793"/><path d="M12 2v7"/>',
        'guitar' => '<path d="m11.9 12.1 4.514-4.514"/><path d="M20.1 2.3a1 1 0 0 1 1.4 1.4l-1.1 1.1 2.3 2.3-3.5 3.5-2.3-2.3-1.1 1.1a1 1 0 0 1-1.4-1.4z"/><path d="m6 16 2 2"/><path d="M12.4 17.6a8 8 0 0 1-9-9l1-2.3a1 1 0 0 1 .9-.5h.5a1 1 0 0 1 .9.6l.9 1.7a1 1 0 0 1-.2 1.2L5 11.5a6 6 0 0 0 7.5 7.5l1.8-2.4a1 1 0 0 1 1.2-.2l1.7.9a1 1 0 0 1 .6.9v.5a1 1 0 0 1-.5.9z"/>',
        'globe' => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>',
        'graduation-cap' => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
        'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
        'megaphone' => '<path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
        'coffee' => '<path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v3a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a2 2 0 1 1 0 4h-1"/><path d="M6 2v2"/><path d="M6 18h8"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
        'crown' => '<path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L20.266 6.5a.5.5 0 0 1 .734.545l-2.494 13.178A2 2 0 0 1 16.542 22H7.458a2 2 0 0 1-1.964-1.777L3 7.045a.5.5 0 0 1 .734-.545l3.36 2.664a1 1 0 0 0 1.516-.294z"/>',
        'gift' => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
        'camera' => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',
        'wifi' => '<path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.859a10 10 0 0 1 14 0"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/>',
        'wrench' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    );
    if (!isset($icons[$name])) return '';
    return '<svg class="' . esc_attr($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $icons[$name] . '</svg>';
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
        'footer_description' => 'Una iglesia donde cada persona encuentra un hogar en la presencia de Dios. Te esperamos con los brazos abiertos.',
    );
}

// Get default value
function cfc_default($key) {
    $defaults = cfc_defaults();
    return isset($defaults[$key]) ? $defaults[$key] : '';
}

/**
 * Theme Changelog / Release Notes
 */
function cfc_get_changelog() {
    return array(
        '1.0.4' => array(
            'date' => '2026-03-21',
            'changes' => array(
                'Tailwind CSS compilado (reemplaza CDN, mejor rendimiento)',
                'Compatibilidad con Gravity Forms (estilos protegidos)',
                'Fix: Menú mobile se quedaba trabado',
                'Nuevo: Archivo XML de demo content para importación',
            )
        ),
        '1.0.3' => array(
            'date' => '2026-01-07',
            'changes' => array(
                'Nuevo: Menú reorganizado con submenús (Info, Configuraciones, Footer)',
                'Nuevo: Página separada para configuración del Footer',
                'Mejora: Panel de información del tema más limpio',
            )
        ),
        '1.0.2' => array(
            'date' => '2026-01-07',
            'changes' => array(
                'Fix: Screenshot visible en pantalla de actualizaciones',
                'Fix: Información del tema con valores fallback',
            )
        ),
        '1.0.1' => array(
            'date' => '2026-01-07',
            'changes' => array(
                'Menús dinámicos con soporte para clase CSS "menu-dar"',
                'Aviso para admins cuando el menú no está configurado',
                'Notas de versión en CFC Opciones',
                'Mejoras en la página de opciones',
            )
        ),
        '1.0.0' => array(
            'date' => '2026-01-07',
            'changes' => array(
                'Versión inicial del tema',
                'Custom Post Types: Eventos, Ministerios + Reflexiones (Posts nativos)',
                'Integración con Spotify y YouTube',
                'Sistema de actualizaciones desde GitHub',
                'Panel de opciones personalizado',
            )
        ),
    );
}

/**
 * Disable Gutenberg for pages with custom templates and CPTs
 */
function cfc_disable_gutenberg($use_block_editor, $post_type) {
    if (in_array($post_type, array('post', 'page', 'cfc_evento', 'cfc_ministerio', 'cfc_equipo'))) {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'cfc_disable_gutenberg', 10, 2);

/**
 * Admin Customizations
 */
function cfc_admin_styles() {
    echo '<style>
        /* ========================================
           CFC Metabox Styles - Minimal & Clean
           ======================================== */

        /* Metabox container */
        .postbox[id^="cfc_"] {
            border: 1px solid #e5e7eb !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin-bottom: 20px !important;
        }

        .postbox[id^="cfc_"]:hover { box-shadow: none !important; }

        /* Metabox header - simple gray */
        .postbox[id^="cfc_"] .postbox-header {
            background: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .postbox[id^="cfc_"] .postbox-header h2 {
            font-size: 13px;
            font-weight: 600;
            color: #374151 !important;
        }

        /* Content area */
        [id^="cfc_"] .inside {
            padding: 0 !important;
            margin: 0 !important;
            background: #fff !important;
            border: none;
        }

        /* Grid */
        .cfc-metabox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 20px;
        }

        .cfc-metabox-grid .cfc-metabox-field.full-width { grid-column: 1 / -1; }

        /* Fields */
        .cfc-metabox-field > label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            color: #374151;
            font-size: 13px;
        }

        .cfc-metabox-field > label small { font-weight: 400; color: #9ca3af; }

        /* Inputs */
        .cfc-metabox-field input[type="text"],
        .cfc-metabox-field input[type="url"],
        .cfc-metabox-field input[type="email"],
        .cfc-metabox-field input[type="number"],
        .cfc-metabox-field input[type="date"],
        .cfc-metabox-field input[type="time"],
        .cfc-metabox-field select,
        .cfc-metabox-field textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            background: #fff;
            color: #1f2937;
        }

        .cfc-metabox-field input:focus,
        .cfc-metabox-field select:focus,
        .cfc-metabox-field textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
            outline: none;
        }

        .cfc-metabox-field textarea { min-height: 80px; resize: vertical; }

        .cfc-metabox-field .description {
            margin-top: 4px;
            color: #9ca3af;
            font-size: 12px;
        }

        /* Section dividers - minimal */
        .cfc-section-title {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            padding: 10px 0 6px 0;
            border-bottom: 1px solid #e5e7eb;
            grid-column: 1 / -1;
        }

        /* Toggle */
        .cfc-toggle-field {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            margin-top: 16px;
        }

        .cfc-toggle-content { flex: 1; }
        .cfc-toggle-label { font-weight: 500; color: #374151; font-size: 13px; }
        .cfc-toggle-desc { font-size: 12px; color: #9ca3af; margin: 2px 0 0 0; }

        .cfc-toggle-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
        .cfc-toggle-switch input { opacity: 0; width: 0; height: 0; }

        .cfc-toggle-slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #d1d5db; transition: 0.2s; border-radius: 24px;
        }

        .cfc-toggle-slider:before {
            position: absolute; content: "";
            height: 18px; width: 18px; left: 3px; bottom: 3px;
            background: white; transition: 0.2s; border-radius: 50%;
        }

        .cfc-toggle-switch input:checked + .cfc-toggle-slider { background: #3b82f6; }
        .cfc-toggle-switch input:checked + .cfc-toggle-slider:before { transform: translateX(20px); }

        /* Checkbox */
        .cfc-checkbox-field {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 12px; background: #f9fafb;
            border: 1px solid #e5e7eb; border-radius: 4px; cursor: pointer; margin-top: 16px;
        }
        .cfc-checkbox-field input[type="checkbox"] { display: none; }
        .cfc-checkbox-field .cfc-checkbox-content { flex: 1; }
        .cfc-checkbox-field .cfc-checkbox-label { font-weight: 500; color: #374151; font-size: 13px; }
        .cfc-checkbox-field .cfc-checkbox-desc { font-size: 12px; color: #9ca3af; margin: 0; }

        @media (max-width: 782px) {
            .cfc-metabox-grid { grid-template-columns: 1fr; padding: 16px; gap: 12px; }
        }

        .cfc-metabox-field { margin-bottom: 0; }
    </style>';

    // Hide title + featured image sidebar for Equipo CPT (handled in metabox)
    global $post;
    if ($post && $post->post_type === 'cfc_equipo') {
        echo '<style>#titlediv, #postimagediv { display: none !important; }</style>';
    }
    if ($post && $post->post_type === 'cfc_grupo') {
        echo '<style>#postimagediv { display: none !important; }</style>';
    }

    // Extra styles for locked CFC pages: hide unnecessary UI
    if ($post && $post->post_type === 'page') {
        $cfc_slugs = array('inicio', 'quienes-somos', 'eventos', 'ministerios', 'reflexiones', 'visitanos', 'dar');
        if (in_array($post->post_name, $cfc_slugs)) {
            echo '<style>
                /* Hide unnecessary elements on locked CFC pages */
                #pageparentdiv, #slugdiv, #postcustom,
                #postdivrich, #wp-content-editor-container,
                #edit-slug-box, #minor-publishing,
                .page-title-action,
                #screen-meta, #screen-meta-links,
                #postbox-container-1 #revisionsdiv { display: none !important; }

                /* Banner metabox clean look */
                #cfc_page_banner { border: none !important; box-shadow: none !important; background: #f8fafc; }
                #cfc_page_banner .postbox-header { display: none !important; }
                #cfc_page_banner .inside { padding: 0 !important; }
            </style>';
        }
    }
}
add_action('admin_head', 'cfc_admin_styles');

/**
 * Enqueue media uploader on Equipo edit screens
 */
function cfc_admin_enqueue_scripts($hook) {
    global $post;
    if (($hook === 'post.php' || $hook === 'post-new.php') && $post && in_array($post->post_type, array('cfc_equipo', 'cfc_grupo'))) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'cfc_admin_enqueue_scripts');

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

    // Posts (Reflexiones) - 2 metaboxes
    add_meta_box('cfc_reflexion_info', 'Información Básica', 'cfc_reflexion_info_html', 'post', 'normal', 'high');
    add_meta_box('cfc_reflexion_media', 'Media y Enlaces', 'cfc_reflexion_media_html', 'post', 'normal', 'high');

    // Grupos - 1 metabox
    add_meta_box('cfc_grupo_info', 'Información del Grupo', 'cfc_grupo_info_html', 'cfc_grupo', 'normal', 'high');

    // Equipo - 2 metaboxes
    add_meta_box('cfc_equipo_info', 'Información del Miembro', 'cfc_equipo_info_html', 'cfc_equipo', 'normal', 'high');
    add_meta_box('cfc_equipo_visual', 'Apariencia', 'cfc_equipo_visual_html', 'cfc_equipo', 'normal', 'high');

}
add_action('add_meta_boxes', 'cfc_add_metaboxes');

/**
 * Lock CFC template pages: hide editor, show info banner with action buttons
 */
function cfc_lock_template_pages() {
    global $post;
    if (!$post || $post->post_type !== 'page') return;

    $pages = array(
        'inicio' => array(
            'icon'  => 'dashicons-admin-home',
            'title' => 'Página de Inicio',
            'desc'  => 'La página principal del sitio. Se genera automáticamente con el contenido de las secciones.',
            'links' => array(
                array('Configuraciones', 'admin.php?page=cfc-configuraciones', 'dashicons-admin-settings'),
                array('Reflexiones', 'edit.php', 'dashicons-edit'),
                array('Grupos', 'edit.php?post_type=cfc_grupo', 'dashicons-groups'),
            ),
        ),
        'quienes-somos' => array(
            'icon'  => 'dashicons-groups',
            'title' => 'Quiénes Somos',
            'desc'  => 'Muestra la misión, visión y el equipo pastoral de la iglesia.',
            'links' => array(
                array('Editar Equipo Pastoral', 'edit.php?post_type=cfc_equipo', 'dashicons-groups'),
            ),
        ),
        'eventos' => array(
            'icon'  => 'dashicons-calendar-alt',
            'title' => 'Eventos',
            'desc'  => 'Muestra los próximos eventos y el calendario de la iglesia.',
            'links' => array(
                array('Administrar Eventos', 'edit.php?post_type=cfc_evento', 'dashicons-calendar-alt'),
            ),
        ),
        'ministerios' => array(
            'icon'  => 'dashicons-heart',
            'title' => 'Ministerios',
            'desc'  => 'Muestra los ministerios de la iglesia.',
            'links' => array(
                array('Administrar Ministerios', 'edit.php?post_type=cfc_ministerio', 'dashicons-heart'),
            ),
        ),
        'reflexiones' => array(
            'icon'  => 'dashicons-book-alt',
            'title' => 'Reflexiones',
            'desc'  => 'Muestra las reflexiones y devocionales publicados.',
            'links' => array(
                array('Administrar Entradas', 'edit.php', 'dashicons-edit'),
            ),
        ),
        'visitanos' => array(
            'icon'  => 'dashicons-location',
            'title' => 'Visítanos',
            'desc'  => 'Muestra la ubicación, horarios y galería de la iglesia.',
            'links' => array(
                array('Configuraciones', 'admin.php?page=cfc-configuraciones', 'dashicons-admin-settings'),
            ),
        ),
        'dar' => array(
            'icon'  => 'dashicons-money-alt',
            'title' => 'Dar / Ofrendas',
            'desc'  => 'Muestra la información para donaciones y ofrendas.',
            'links' => array(
                array('Configuraciones', 'admin.php?page=cfc-configuraciones', 'dashicons-admin-settings'),
            ),
        ),
    );

    $slug = $post->post_name;
    if (!isset($pages[$slug])) return;

    // Hide editor
    remove_post_type_support('page', 'editor');

    // Add banner metabox
    $config = $pages[$slug];
    add_meta_box('cfc_page_banner', 'Información de la Página', function() use ($config) {
        ?>
        <div style="text-align:center; padding:40px 20px;">
            <div style="display:inline-flex; align-items:center; justify-content:center; width:64px; height:64px; border-radius:16px; background:linear-gradient(135deg, #1e40af, #3b82f6); margin-bottom:16px;">
                <span class="dashicons <?php echo esc_attr($config['icon']); ?>" style="font-size:32px; width:32px; height:32px; color:#fff;"></span>
            </div>
            <h2 style="font-size:24px; font-weight:700; margin:0 0 8px; color:#1e293b;"><?php echo esc_html($config['title']); ?></h2>
            <p style="font-size:15px; color:#64748b; margin:0 0 24px; max-width:400px; margin-left:auto; margin-right:auto;"><?php echo esc_html($config['desc']); ?></p>
            <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                <?php foreach ($config['links'] as $link) : ?>
                <a href="<?php echo esc_url(admin_url($link[1])); ?>" style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#1e40af; color:#fff; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px; transition:background 0.2s;" onmouseover="this.style.background='#1e3a8a'" onmouseout="this.style.background='#1e40af'">
                    <span class="dashicons <?php echo esc_attr($link[2]); ?>" style="font-size:18px; width:18px; height:18px;"></span>
                    <?php echo esc_html($link[0]); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <p style="font-size:13px; color:#94a3b8; margin-top:24px;">Esta página se genera automáticamente. No necesita ser editada.</p>
        </div>
        <?php
    }, 'page', 'normal', 'high');
}
add_action('add_meta_boxes', 'cfc_lock_template_pages', 20);

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
            <input type="url" id="dar_hero_imagen" name="dar_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'dar_hero_imagen', true)); ?>">
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
            <input type="text" id="banco_nombre" name="banco_nombre" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_nombre', true) ?: 'Banco General'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="banco_tipo">Tipo de Cuenta</label>
            <input type="text" id="banco_tipo" name="banco_tipo" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_tipo', true) ?: 'Cuenta Corriente'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="banco_cuenta">Número de Cuenta</label>
            <input type="text" id="banco_cuenta" name="banco_cuenta" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_cuenta', true) ?: '04-47-99-123456-7'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="banco_titular">Titular de la Cuenta</label>
            <input type="text" id="banco_titular" name="banco_titular" value="<?php echo esc_attr(get_post_meta($post->ID, 'banco_titular', true) ?: 'Centro Familiar Cristiano'); ?>">
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
            <input type="url" id="visitanos_hero_imagen" name="visitanos_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'visitanos_hero_imagen', true)); ?>">
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
            <input type="url" id="galeria_1" name="galeria_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'galeria_1', true) ?: 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=400&h=300&fit=crop'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="galeria_2">Imagen 2 (URL)</label>
            <input type="url" id="galeria_2" name="galeria_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'galeria_2', true) ?: 'https://images.unsplash.com/photo-1523301343968-6a6ebf63c672?w=400&h=300&fit=crop'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="galeria_3">Imagen 3 (URL)</label>
            <input type="url" id="galeria_3" name="galeria_3" value="<?php echo esc_attr(get_post_meta($post->ID, 'galeria_3', true) ?: 'https://images.unsplash.com/photo-1509021436665-8f07dbf5bf1d?w=400&h=300&fit=crop'); ?>">
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
            <input type="text" id="quienes_hero_subtitulo" name="quienes_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'quienes_hero_subtitulo', true) ?: 'Una iglesia con visión de reino, enfocada en la predicación y enseñanza de la palabra'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="quienes_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="quienes_hero_imagen" name="quienes_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'quienes_hero_imagen', true) ?: 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=1920&h=1080&fit=crop'); ?>">
        </div>
    </div>
    <?php
}

/**
 * Template QUIENES SOMOS: Nuestro Liderazgo (Pastores)
 */
function cfc_quienes_pastores_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="pastores_imagen">Foto de Familia Pastoral (URL)</label>
            <input type="url" id="pastores_imagen" name="pastores_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'pastores_imagen', true)); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="pastores_texto">Texto / Mensaje</label>
            <textarea id="pastores_texto" name="pastores_texto" rows="4"><?php echo esc_textarea(get_post_meta($post->ID, 'pastores_texto', true) ?: 'Confiando en Dios quien nos da la sabiduría y fortaleza, para proclamar las buenas nuevas de Salvación. Continuamos escuchando la voz de Dios y que sea guía en nuestro caminar, para que cuando venga nos encuentre haciendo su voluntad.'); ?></textarea>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="pastores_nombres">Nombres de los Pastores</label>
            <input type="text" id="pastores_nombres" name="pastores_nombres" value="<?php echo esc_attr(get_post_meta($post->ID, 'pastores_nombres', true) ?: 'Pastores – Julio y Gladys Bolivar'); ?>">
        </div>
    </div>
    <p class="description">Esta sección aparece antes de "Quién es Quién" con la foto a un lado y el texto al otro.</p>
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
            <textarea id="mision" name="mision" rows="4"><?php echo esc_textarea(get_post_meta($post->ID, 'mision', true) ?: 'Somos una Iglesia que ama, glorifica y sirve a Dios como una sola familia.'); ?></textarea>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="vision">Nuestra Visión</label>
            <textarea id="vision" name="vision" rows="4"><?php echo esc_textarea(get_post_meta($post->ID, 'vision', true) ?: 'Queremos ser una iglesia comprometida en formar discípulos de Jesús para ganar a la comunidad y al mundo.'); ?></textarea>
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
    $mostrar_badge = get_post_meta($post->ID, 'hero_mostrar_badge', true);
    if ($mostrar_badge === '') $mostrar_badge = '1'; // Default: mostrar
    $estamos_en_vivo = get_post_meta($post->ID, 'estamos_en_vivo', true);
    if ($estamos_en_vivo === '') $estamos_en_vivo = '0'; // Default: no estamos en vivo
    ?>
    <div class="cfc-metabox-grid">
        <!-- Fondo -->
        <div class="cfc-section-title">Fondo</div>
        <div class="cfc-metabox-field">
            <label for="hero_video_url">URL del Video (MP4)</label>
            <input type="url" id="hero_video_url" name="hero_video_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_video_url', true)); ?>" placeholder="https://ejemplo.com/video.mp4">
            <p class="description">Si está vacío, usará la imagen o el video local.</p>
        </div>
        <div class="cfc-metabox-field">
            <label for="hero_image_url">URL de Imagen de Fondo</label>
            <input type="url" id="hero_image_url" name="hero_image_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_image_url', true)); ?>" placeholder="https://images.unsplash.com/...">
        </div>

        <!-- Badge -->
        <div class="cfc-section-title">Badge Superior</div>
        <div class="cfc-metabox-field">
            <label for="hero_badge">Texto del Badge</label>
            <input type="text" id="hero_badge" name="hero_badge" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_badge', true) ?: 'En vivo cada domingo'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <div class="cfc-toggle-field">
                <div class="cfc-toggle-content">
                    <div class="cfc-toggle-label">Mostrar Badge</div>
                    <p class="cfc-toggle-desc">Activa para mostrar el badge animado en el hero</p>
                </div>
                <label class="cfc-toggle-switch">
                    <input type="checkbox" id="hero_mostrar_badge" name="hero_mostrar_badge" value="1" <?php checked($mostrar_badge, '1'); ?>>
                    <span class="cfc-toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Títulos -->
        <div class="cfc-section-title">Títulos</div>
        <div class="cfc-metabox-field">
            <label for="hero_titulo_1">Título Línea 1</label>
            <input type="text" id="hero_titulo_1" name="hero_titulo_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_titulo_1', true) ?: 'Centro Familiar'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="hero_titulo_2">Título Línea 2 (destacado)</label>
            <input type="text" id="hero_titulo_2" name="hero_titulo_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_titulo_2', true) ?: 'Cristiano'); ?>">
        </div>

        <!-- Botón Principal -->
        <div class="cfc-section-title">Botón Principal</div>
        <div class="cfc-metabox-field">
            <label for="hero_btn1_texto">Texto del Botón</label>
            <input type="text" id="hero_btn1_texto" name="hero_btn1_texto" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_btn1_texto', true) ?: 'Visítanos Este Domingo'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="hero_btn1_url">URL del Botón</label>
            <input type="text" id="hero_btn1_url" name="hero_btn1_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_btn1_url', true) ?: '#horarios'); ?>">
        </div>

        <!-- Botón Secundario -->
        <div class="cfc-section-title">Botón Secundario (Ver en Vivo)</div>
        <div class="cfc-metabox-field">
            <label for="hero_btn2_texto">Texto del Botón</label>
            <input type="text" id="hero_btn2_texto" name="hero_btn2_texto" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_btn2_texto', true) ?: 'Ver en Vivo'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="hero_btn2_url">URL del Botón</label>
            <input type="url" id="hero_btn2_url" name="hero_btn2_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'hero_btn2_url', true)); ?>" placeholder="https://youtube.com/live/...">
            <p class="description">URL del live actual (cambia cada domingo).</p>
        </div>
        <div class="cfc-metabox-field">
            <div class="cfc-toggle-field">
                <div class="cfc-toggle-content">
                    <div class="cfc-toggle-label">¿Estamos en Vivo?</div>
                    <p class="cfc-toggle-desc">Activa cuando el servicio esté transmitiendo en vivo</p>
                </div>
                <label class="cfc-toggle-switch">
                    <input type="checkbox" id="estamos_en_vivo" name="estamos_en_vivo" value="1" <?php checked($estamos_en_vivo, '1'); ?>>
                    <span class="cfc-toggle-slider"></span>
                </label>
            </div>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="live_mensaje_offline">Mensaje cuando NO estamos en vivo</label>
            <textarea id="live_mensaje_offline" name="live_mensaje_offline" rows="2"><?php echo esc_textarea(get_post_meta($post->ID, 'live_mensaje_offline', true) ?: 'No estamos en vivo en este momento. Próximo servicio: Domingo 10:00 AM'); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Template INICIO: Sección Encuentra Tu Lugar
 */
function cfc_inicio_encuentralugar_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <!-- Header de la sección -->
        <div class="cfc-section-title">Encabezado de Sección</div>
        <div class="cfc-metabox-field">
            <label for="etl_badge">Texto del Badge</label>
            <input type="text" id="etl_badge" name="etl_badge" value="<?php echo esc_attr(get_post_meta($post->ID, 'etl_badge', true) ?: 'Únete a la familia'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="etl_subtitulo">Subtítulo</label>
            <input type="text" id="etl_subtitulo" name="etl_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'etl_subtitulo', true) ?: 'Conéctate con personas de tu edad y crece en comunidad'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="etl_titulo_1">Título Línea 1</label>
            <input type="text" id="etl_titulo_1" name="etl_titulo_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'etl_titulo_1', true) ?: 'Encuentra Tu'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="etl_titulo_2">Título Línea 2 (destacado)</label>
            <input type="text" id="etl_titulo_2" name="etl_titulo_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'etl_titulo_2', true) ?: 'Lugar'); ?>">
        </div>

        <!-- Subsección Adolescentes -->
        <div class="cfc-section-title">Card Adolescentes</div>
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
            <textarea id="adol_desc" name="adol_desc" rows="2"><?php echo esc_textarea(get_post_meta($post->ID, 'adol_desc', true)); ?></textarea>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="adol_btn_url">URL del Botón</label>
            <input type="text" id="adol_btn_url" name="adol_btn_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'adol_btn_url', true)); ?>" placeholder="https://wa.me/507... o mailto:correo@ejemplo.com">
        </div>

        <!-- Subsección Jóvenes -->
        <div class="cfc-section-title">Card Jóvenes</div>
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
            <textarea id="jov_desc" name="jov_desc" rows="2"><?php echo esc_textarea(get_post_meta($post->ID, 'jov_desc', true)); ?></textarea>
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="jov_btn_url">URL del Botón</label>
            <input type="text" id="jov_btn_url" name="jov_btn_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'jov_btn_url', true)); ?>" placeholder="https://wa.me/507... o mailto:correo@ejemplo.com">
        </div>
    </div>
    <?php
}

/**
 * Template INICIO: Sección Reflexiones Recientes
 */
function cfc_inicio_reflexiones_html($post) {
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field">
            <label for="ref_badge">Texto del Badge</label>
            <input type="text" id="ref_badge" name="ref_badge" value="<?php echo esc_attr(get_post_meta($post->ID, 'ref_badge', true) ?: 'Contenido'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="ref_subtitulo">Subtítulo</label>
            <input type="text" id="ref_subtitulo" name="ref_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'ref_subtitulo', true) ?: 'Inspiración y enseñanzas para transformar tu vida diaria'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="ref_titulo_1">Título Línea 1</label>
            <input type="text" id="ref_titulo_1" name="ref_titulo_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'ref_titulo_1', true) ?: 'Reflexiones'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="ref_titulo_2">Título Línea 2 (destacado)</label>
            <input type="text" id="ref_titulo_2" name="ref_titulo_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'ref_titulo_2', true) ?: 'Recientes'); ?>">
        </div>
    </div>
    <?php
}

/**
 * Template INICIO: Ubicación y Horarios
 */
function cfc_inicio_ubicacion_html($post) {
    wp_nonce_field('cfc_page_fields_save', 'cfc_page_fields_nonce');

    // Get saved values
    $mostrar_badge = get_post_meta($post->ID, 'ubi_mostrar_badge', true);
    if ($mostrar_badge === '') $mostrar_badge = '1';
    ?>
    <div class="cfc-metabox-grid">
        <!-- Badge -->
        <div class="cfc-section-title">Badge Superior</div>
        <div class="cfc-metabox-field">
            <label for="ubi_badge">Texto del Badge</label>
            <input type="text" id="ubi_badge" name="ubi_badge" value="<?php echo esc_attr(get_post_meta($post->ID, 'ubi_badge', true) ?: 'Encuéntranos'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <div class="cfc-toggle-field">
                <div class="cfc-toggle-content">
                    <div class="cfc-toggle-label">Mostrar Badge</div>
                    <p class="cfc-toggle-desc">Activa para mostrar el badge de la sección</p>
                </div>
                <label class="cfc-toggle-switch">
                    <input type="checkbox" id="ubi_mostrar_badge" name="ubi_mostrar_badge" value="1" <?php checked($mostrar_badge, '1'); ?>>
                    <span class="cfc-toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Títulos -->
        <div class="cfc-section-title">Títulos</div>
        <div class="cfc-metabox-field">
            <label for="ubi_titulo_1">Título Línea 1</label>
            <input type="text" id="ubi_titulo_1" name="ubi_titulo_1" value="<?php echo esc_attr(get_post_meta($post->ID, 'ubi_titulo_1', true) ?: 'Localizaciones y'); ?>">
        </div>
        <div class="cfc-metabox-field">
            <label for="ubi_titulo_2">Título Línea 2 (destacado)</label>
            <input type="text" id="ubi_titulo_2" name="ubi_titulo_2" value="<?php echo esc_attr(get_post_meta($post->ID, 'ubi_titulo_2', true) ?: 'Horarios'); ?>">
        </div>

        <!-- Dirección -->
        <div class="cfc-section-title">Dirección</div>
        <div class="cfc-metabox-field full-width">
            <label for="ubicacion_direccion">Texto de Dirección</label>
            <input type="text" id="ubicacion_direccion" name="ubicacion_direccion" value="<?php echo esc_attr(get_post_meta($post->ID, 'ubicacion_direccion', true) ?: 'Calle Julio Fábrega, Edificio #5857'); ?>">
        </div>

        <!-- Botón Google Maps -->
        <div class="cfc-section-title">Botón Google Maps</div>
        <div class="cfc-metabox-field">
            <label for="ubi_maps_url">URL de Google Maps</label>
            <input type="url" id="ubi_maps_url" name="ubi_maps_url" value="<?php echo esc_attr(get_post_meta($post->ID, 'ubi_maps_url', true)); ?>" placeholder="https://maps.google.com/...">
            <p class="description">Dejar vacío para usar el de Configuraciones</p>
        </div>
        <div class="cfc-metabox-field">
            <label for="ubi_maps_texto">Texto del Botón</label>
            <input type="text" id="ubi_maps_texto" name="ubi_maps_texto" value="<?php echo esc_attr(get_post_meta($post->ID, 'ubi_maps_texto', true) ?: 'Abrir en Google Maps'); ?>">
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
            <input type="text" id="eventos_hero_subtitulo" name="eventos_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_hero_subtitulo', true) ?: 'Únete a nosotros en actividades que fortalecerán tu fe y comunidad'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="eventos_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="eventos_hero_imagen" name="eventos_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'eventos_hero_imagen', true)); ?>">
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
            <input type="text" id="ministerios_hero_subtitulo" name="ministerios_hero_subtitulo" value="<?php echo esc_attr(get_post_meta($post->ID, 'ministerios_hero_subtitulo', true) ?: 'Descubre las diferentes formas en las que puedes servir y crecer en nuestra comunidad'); ?>">
        </div>
        <div class="cfc-metabox-field full-width">
            <label for="ministerios_hero_imagen">Imagen de Fondo (URL)</label>
            <input type="url" id="ministerios_hero_imagen" name="ministerios_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'ministerios_hero_imagen', true) ?: 'https://images.unsplash.com/photo-1609234656388-0ff363383899?w=1920&h=1080&fit=crop'); ?>">
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
            <input type="url" id="reflexiones_hero_imagen" name="reflexiones_hero_imagen" value="<?php echo esc_attr(get_post_meta($post->ID, 'reflexiones_hero_imagen', true) ?: 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=1920&h=1080&fit=crop'); ?>">
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
        // Quienes Somos - Hero + Pastores + Mision
        'quienes_hero_titulo', 'quienes_hero_subtitulo', 'quienes_hero_imagen',
        'pastores_imagen', 'pastores_texto', 'pastores_nombres',
        'mision', 'vision',
        // Inicio - Hero + Adolescentes + Jovenes + Ubicacion
        'hero_video_url', 'hero_image_url', 'hero_badge', 'hero_mostrar_badge', 'hero_titulo_1', 'hero_titulo_2',
        'hero_btn1_texto', 'hero_btn1_url', 'hero_btn2_texto', 'hero_btn2_url', 'live_mensaje_offline',
        'adol_titulo', 'adol_desc', 'adol_edad', 'adol_horario', 'adol_imagen', 'adol_btn_url',
        'jov_titulo', 'jov_desc', 'jov_edad', 'jov_horario', 'jov_imagen', 'jov_btn_url',
        'etl_badge', 'etl_titulo_1', 'etl_titulo_2', 'etl_subtitulo',
        'ref_badge', 'ref_titulo_1', 'ref_titulo_2', 'ref_subtitulo',
        'ubicacion_direccion', 'ubi_badge', 'ubi_titulo_1', 'ubi_titulo_2', 'ubi_maps_url', 'ubi_maps_texto',
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

    // Handle checkbox fields specially (they don't send value when unchecked)
    $checkbox_fields = array('hero_mostrar_badge', 'ubi_mostrar_badge', 'estamos_en_vivo');
    foreach ($checkbox_fields as $checkbox) {
        $value = isset($_POST[$checkbox]) ? '1' : '0';
        update_post_meta($post_id, $checkbox, $value);
    }
}
add_action('save_post', 'cfc_page_fields_save');

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
    $mantener_visible = get_post_meta($post->ID, 'mantener_visible', true);
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

        <div class="cfc-metabox-field full-width">
            <div class="cfc-toggle-field">
                <div class="cfc-toggle-content">
                    <div class="cfc-toggle-label">Mantener visible siempre</div>
                    <p class="cfc-toggle-desc">Si está desactivado, el evento se ocultará automáticamente después de la fecha</p>
                </div>
                <label class="cfc-toggle-switch">
                    <input type="checkbox" id="mantener_visible" name="mantener_visible" value="1" <?php checked($mantener_visible, '1'); ?>>
                    <span class="cfc-toggle-slider"></span>
                </label>
            </div>
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

    // Checkbox: mantener visible siempre
    $mantener_visible = isset($_POST['mantener_visible']) ? '1' : '0';
    update_post_meta($post_id, 'mantener_visible', $mantener_visible);
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
add_action('save_post_post', 'cfc_reflexion_save');

/**
 * Grupo Metabox: Información del Grupo
 */
function cfc_grupo_info_html($post) {
    wp_nonce_field('cfc_grupo_save', 'cfc_grupo_nonce');
    $rango_edad = get_post_meta($post->ID, 'rango_edad', true);
    $horario = get_post_meta($post->ID, 'horario', true);
    $imagen_url = get_post_meta($post->ID, 'imagen_url', true);
    $btn_url = get_post_meta($post->ID, 'btn_url', true);
    $btn_texto = get_post_meta($post->ID, 'btn_texto', true);
    $color = get_post_meta($post->ID, 'color', true);
    $orden = get_post_meta($post->ID, 'orden', true);
    $foto_id = get_post_thumbnail_id($post->ID);
    $foto_url = $foto_id ? wp_get_attachment_image_url($foto_id, 'medium') : '';
    ?>
    <div class="cfc-metabox-grid">
        <!-- Foto -->
        <div class="cfc-metabox-field full-width">
            <label>Imagen de Fondo</label>
            <div id="cfc-grupo-foto-preview" style="margin-bottom:10px; <?php echo $foto_url ? '' : 'display:none;'; ?>">
                <img src="<?php echo esc_url($foto_url); ?>" style="max-width:300px; height:auto; border-radius:12px; border:2px solid #e5e7eb;">
            </div>
            <input type="hidden" id="cfc_grupo_foto_id" name="_thumbnail_id" value="<?php echo esc_attr($foto_id); ?>">
            <button type="button" id="cfc-grupo-foto-upload" class="button"><?php echo $foto_url ? 'Cambiar Imagen' : 'Seleccionar Imagen'; ?></button>
            <?php if ($foto_url) : ?>
            <button type="button" id="cfc-grupo-foto-remove" class="button" style="color:#dc2626;">Quitar</button>
            <?php endif; ?>
            <p class="description">Sube una imagen para el grupo. Si no subes ninguna, se usará la URL de abajo (opcional).</p>
        </div>

        <!-- URL fallback -->
        <div class="cfc-metabox-field full-width" id="cfc-grupo-url-fallback" style="<?php echo $foto_url ? 'display:none;' : ''; ?>">
            <label for="imagen_url">Imagen por URL (opcional)</label>
            <input type="url" id="imagen_url" name="imagen_url" value="<?php echo esc_attr($imagen_url); ?>" placeholder="https://images.unsplash.com/...">
            <p class="description">Solo se usa si no hay imagen subida arriba.</p>
        </div>

        <div class="cfc-metabox-field">
            <label for="rango_edad">Rango de Edad</label>
            <input type="text" id="rango_edad" name="rango_edad" value="<?php echo esc_attr($rango_edad); ?>" placeholder="Ej: 13-17 años">
        </div>

        <div class="cfc-metabox-field">
            <label for="horario">Horario</label>
            <input type="text" id="horario" name="horario" value="<?php echo esc_attr($horario); ?>" placeholder="Ej: Sábados 4:00 PM">
        </div>

        <div class="cfc-metabox-field">
            <label for="btn_url">URL del Botón</label>
            <input type="url" id="btn_url" name="btn_url" value="<?php echo esc_attr($btn_url); ?>" placeholder="https://...">
        </div>

        <div class="cfc-metabox-field">
            <label for="btn_texto">Texto del Botón</label>
            <input type="text" id="btn_texto" name="btn_texto" value="<?php echo esc_attr($btn_texto); ?>" placeholder="Únete al Grupo">
        </div>

        <div class="cfc-metabox-field">
            <label for="color">Color del Grupo</label>
            <select id="color" name="color">
                <option value="purple" <?php selected($color, 'purple'); ?>>Morado</option>
                <option value="blue" <?php selected($color, 'blue'); ?>>Azul</option>
                <option value="green" <?php selected($color, 'green'); ?>>Verde</option>
                <option value="orange" <?php selected($color, 'orange'); ?>>Naranja</option>
                <option value="pink" <?php selected($color, 'pink'); ?>>Rosa</option>
            </select>
        </div>

        <div class="cfc-metabox-field">
            <label for="orden">Orden de aparición</label>
            <input type="number" id="orden" name="orden" value="<?php echo esc_attr($orden); ?>" placeholder="1, 2, 3..." min="1">
        </div>
    </div>

    <script>
    jQuery(function($) {
        $('#cfc-grupo-foto-upload').on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({ title: 'Seleccionar Imagen', button: { text: 'Usar esta imagen' }, multiple: false, library: { type: 'image' } });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                var url = att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url;
                $('#cfc_grupo_foto_id').val(att.id);
                $('#cfc-grupo-foto-preview').show().find('img').attr('src', url);
                $('#cfc-grupo-foto-upload').text('Cambiar Imagen');
                $('#cfc-grupo-url-fallback').hide();
                if (!$('#cfc-grupo-foto-remove').length) {
                    $('#cfc-grupo-foto-upload').after(' <button type="button" id="cfc-grupo-foto-remove" class="button" style="color:#dc2626;">Quitar</button>');
                    bindGrupoRemove();
                }
            });
            frame.open();
        });
        function bindGrupoRemove() {
            $('#cfc-grupo-foto-remove').on('click', function(e) {
                e.preventDefault();
                $('#cfc_grupo_foto_id').val('');
                $('#cfc-grupo-foto-preview').hide();
                $('#cfc-grupo-foto-upload').text('Seleccionar Imagen');
                $('#cfc-grupo-url-fallback').show();
                $(this).remove();
            });
        }
        bindGrupoRemove();
    });
    </script>
    <?php
}

function cfc_grupo_save($post_id) {
    if (!isset($_POST['cfc_grupo_nonce']) || !wp_verify_nonce($_POST['cfc_grupo_nonce'], 'cfc_grupo_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $text_fields = array('rango_edad', 'horario', 'btn_texto', 'color', 'orden');
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    $url_fields = array('imagen_url', 'btn_url');
    foreach ($url_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, esc_url_raw($_POST[$field]));
        }
    }
}
add_action('save_post_cfc_grupo', 'cfc_grupo_save');

/**
 * Equipo Metabox 1: Información del Miembro
 */
function cfc_equipo_info_html($post) {
    wp_nonce_field('cfc_equipo_save', 'cfc_equipo_nonce');
    $nombre = get_post_meta($post->ID, 'nombre', true);
    $cargo = get_post_meta($post->ID, 'cargo', true);
    $orden = get_post_meta($post->ID, 'orden', true);
    $es_pastor = get_post_meta($post->ID, 'es_pastor', true);
    $biografia = get_post_meta($post->ID, 'biografia', true);
    ?>
    <div class="cfc-metabox-grid">
        <div class="cfc-metabox-field full-width">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo esc_attr($nombre); ?>" placeholder="Ej: Guillermo y Edilma Campbell">
            <p class="description">Nombre de la persona o pareja</p>
        </div>

        <div class="cfc-metabox-field">
            <label for="cargo">Cargo / Rol</label>
            <input type="text" id="cargo" name="cargo" value="<?php echo esc_attr($cargo); ?>" placeholder="Ej: Pastores Principales">
        </div>

        <div class="cfc-metabox-field">
            <label for="orden">Orden de aparición</label>
            <input type="number" id="orden" name="orden" value="<?php echo esc_attr($orden); ?>" placeholder="1, 2, 3..." min="1">
        </div>

        <!-- Pastor Principal toggle -->
        <div class="cfc-metabox-field full-width" style="background:#f0f7ff; padding:16px; border-radius:8px; border:1px solid #bfdbfe;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-weight:600;">
                <input type="checkbox" id="es_pastor" name="es_pastor" value="1" <?php checked($es_pastor, '1'); ?> style="width:18px; height:18px;">
                Familia Pastoral (se muestra en sección "Nuestro Liderazgo")
            </label>
            <p class="description" style="margin-top:6px;">Solo un miembro puede tener esto activado. Al marcar aquí, se desmarca de cualquier otro.</p>
        </div>

        <!-- Biografía (visible si es pastor) -->
        <div class="cfc-metabox-field full-width" id="cfc-biografia-field" style="<?php echo $es_pastor ? '' : 'display:none;'; ?>">
            <label for="biografia">Biografía / Descripción</label>
            <textarea id="biografia" name="biografia" rows="4" placeholder="Texto que aparece junto a la foto en la sección de liderazgo..."><?php echo esc_textarea($biografia); ?></textarea>
        </div>
    </div>

    <script>
    document.getElementById('es_pastor').addEventListener('change', function() {
        document.getElementById('cfc-biografia-field').style.display = this.checked ? '' : 'none';
    });
    </script>
    <?php
}

/**
 * Equipo Metabox 2: Apariencia
 */
function cfc_equipo_visual_html($post) {
    $icono = get_post_meta($post->ID, 'icono', true);
    $color = get_post_meta($post->ID, 'color', true);
    $foto_id = get_post_thumbnail_id($post->ID);
    $foto_url = $foto_id ? wp_get_attachment_image_url($foto_id, 'medium') : '';
    ?>
    <div class="cfc-metabox-grid">
        <!-- Foto -->
        <div class="cfc-metabox-field full-width">
            <label>Foto</label>
            <div id="cfc-foto-preview" style="margin-bottom:10px; <?php echo $foto_url ? '' : 'display:none;'; ?>">
                <img src="<?php echo esc_url($foto_url); ?>" style="max-width:200px; height:auto; border-radius:12px; border:2px solid #e5e7eb;">
            </div>
            <input type="hidden" id="cfc_foto_id" name="_thumbnail_id" value="<?php echo esc_attr($foto_id); ?>">
            <button type="button" id="cfc-foto-upload" class="button"><?php echo $foto_url ? 'Cambiar Foto' : 'Seleccionar Foto'; ?></button>
            <?php if ($foto_url) : ?>
            <button type="button" id="cfc-foto-remove" class="button" style="color:#dc2626;">Quitar</button>
            <?php endif; ?>
            <p class="description">Foto de la persona o pareja (se recomienda formato vertical)</p>
        </div>

        <!-- Icono -->
        <div class="cfc-metabox-field full-width">
            <label>Icono <small style="color:#6b7280; font-weight:normal;">(dejar vacío = logo CFC)</small></label>
            <input type="hidden" id="icono" name="icono" value="<?php echo esc_attr($icono); ?>">
            <div id="cfc-icon-grid" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;">
                <?php
                $icons = array(
                    '' => array('Logo CFC', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>'),
                    'music' => array('Alabanza', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>'),
                    'heart' => array('Amor', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>'),
                    'cross' => array('Fe', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 2a1 1 0 0 1 2 0v7h7a1 1 0 0 1 0 2h-7v11a1 1 0 0 1-2 0V11H4a1 1 0 0 1 0-2h7z"/></svg>'),
                    'users' => array('Comunidad', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'),
                    'book-open' => array('Biblia', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>'),
                    'mic' => array('Predica', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/></svg>'),
                    'flame' => array('Fuego', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>'),
                    'baby' => array('Ninos', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h.01"/><path d="M15 12h.01"/><path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"/></svg>'),
                    'rocket' => array('Jovenes', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>'),
                    'shield' => array('Ujieres', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>'),
                    'biceps' => array('Varones', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 17.5 3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="m16 16 3.5 3.5"/><path d="m19 19 2 2"/><path d="M14.5 6.5 18 3l3 3-3.5 3.5"/></svg>'),
                    'heart-handshake' => array('Parejas', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08c.82.82 2.13.85 3 .07l2.07-1.9a2.82 2.82 0 0 1 3.79 0l2.96 2.66"/><path d="m18 15-2-2"/><path d="m15 18-2-2"/></svg>'),
                    'flower2' => array('Mujeres', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5a3 3 0 1 1 3 3m-3-3a3 3 0 1 0-3 3m3-3v1M9 8a3 3 0 1 0 3 3M9 8h1m5 0a3 3 0 1 1-3 3m3-3h-1m-2 3v-1"/><circle cx="12" cy="8" r="2"/><path d="M12 10v12"/><path d="M12 22c4.2 0 7-1.667 7-5-4.2 0-7 1.667-7 5Z"/><path d="M12 22c-4.2 0-7-1.667-7-5 4.2 0 7 1.667 7 5Z"/></svg>'),
                    'monitor' => array('Multimedia', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>'),
                );
                foreach ($icons as $key => $data) :
                    $is_selected = ($icono === $key) || ($key === '' && empty($icono));
                    $sel_style = $is_selected ? 'outline:2px solid #1e40af; background:#eff6ff;' : '';
                ?>
                <button type="button" class="cfc-icon-btn" data-icon="<?php echo esc_attr($key); ?>" title="<?php echo esc_attr($data[0]); ?>" style="width:44px; height:44px; border:1px solid #e5e7eb; border-radius:10px; background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s; color:#374151; <?php echo $sel_style; ?>">
                    <span style="width:22px; height:22px; display:block;"><?php echo $data[1]; ?></span>
                </button>
                <?php endforeach; ?>
            </div>
            <p class="description" style="margin-top:8px;">Selecciona un icono. "Logo CFC" usa el logo de la iglesia.</p>
        </div>

        <!-- Color -->
        <div class="cfc-metabox-field full-width">
            <label for="color">Color del badge</label>
            <select id="color" name="color">
                <option value="primary" <?php selected($color, 'primary'); ?>>Azul Primario (Evangelismo)</option>
                <option value="purple" <?php selected($color, 'purple'); ?>>Morado (Alabanza)</option>
                <option value="blue" <?php selected($color, 'blue'); ?>>Celeste (Jóvenes)</option>
                <option value="orange" <?php selected($color, 'orange'); ?>>Naranja (Niños/Ujieres)</option>
                <option value="green" <?php selected($color, 'green'); ?>>Verde (Células)</option>
                <option value="indigo" <?php selected($color, 'indigo'); ?>>Índigo (Varones)</option>
                <option value="pink" <?php selected($color, 'pink'); ?>>Rosa (Mujeres)</option>
                <option value="teal" <?php selected($color, 'teal'); ?>>Turquesa (Multimedia)</option>
                <option value="amber" <?php selected($color, 'amber'); ?>>Ámbar (Administración)</option>
            </select>
        </div>
    </div>

    <script>
    jQuery(function($) {
        // Icon picker
        $('.cfc-icon-btn').on('click', function() {
            $('.cfc-icon-btn').css({'outline':'none', 'background':'#fff'});
            $(this).css({'outline':'2px solid #1e40af', 'background':'#eff6ff'});
            $('#icono').val($(this).data('icon'));
        });

        // Photo upload
        $('#cfc-foto-upload').on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Seleccionar Foto',
                button: { text: 'Usar esta foto' },
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#cfc_foto_id').val(attachment.id);
                var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                $('#cfc-foto-preview').show().find('img').attr('src', url);
                $('#cfc-foto-upload').text('Cambiar Foto');
                if (!$('#cfc-foto-remove').length) {
                    $('#cfc-foto-upload').after(' <button type="button" id="cfc-foto-remove" class="button" style="color:#dc2626;">Quitar</button>');
                    bindRemove();
                }
            });
            frame.open();
        });

        function bindRemove() {
            $('#cfc-foto-remove').on('click', function(e) {
                e.preventDefault();
                $('#cfc_foto_id').val('');
                $('#cfc-foto-preview').hide();
                $('#cfc-foto-upload').text('Seleccionar Foto');
                $(this).remove();
            });
        }
        bindRemove();
    });
    </script>
    <?php
}

function cfc_equipo_save($post_id) {
    if (!isset($_POST['cfc_equipo_nonce']) || !wp_verify_nonce($_POST['cfc_equipo_nonce'], 'cfc_equipo_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('nombre', 'cargo', 'icono', 'color', 'orden');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Pastor Principal: solo uno puede tenerlo
    if (!empty($_POST['es_pastor'])) {
        // Desmarcar todos los demás
        $otros = get_posts(array('post_type' => 'cfc_equipo', 'posts_per_page' => -1, 'exclude' => array($post_id), 'meta_key' => 'es_pastor', 'meta_value' => '1'));
        foreach ($otros as $otro) {
            delete_post_meta($otro->ID, 'es_pastor');
        }
        update_post_meta($post_id, 'es_pastor', '1');
    } else {
        delete_post_meta($post_id, 'es_pastor');
    }

    // Biografía
    if (isset($_POST['biografia'])) {
        update_post_meta($post_id, 'biografia', sanitize_textarea_field($_POST['biografia']));
    }

    // Auto-set post title from nombre field
    if (!empty($_POST['nombre'])) {
        remove_action('save_post_cfc_equipo', 'cfc_equipo_save');
        wp_update_post(array('ID' => $post_id, 'post_title' => sanitize_text_field($_POST['nombre'])));
        add_action('save_post_cfc_equipo', 'cfc_equipo_save');
    }
}
add_action('save_post_cfc_equipo', 'cfc_equipo_save');

/**
 * Equipo admin columns: show Cargo
 */
function cfc_equipo_admin_columns($columns) {
    $new = array();
    foreach ($columns as $key => $val) {
        $new[$key] = $val;
        if ($key === 'title') {
            $new['cargo'] = 'Cargo / Rol';
        }
    }
    return $new;
}
add_filter('manage_cfc_equipo_posts_columns', 'cfc_equipo_admin_columns');

function cfc_equipo_admin_column_data($column, $post_id) {
    if ($column === 'cargo') {
        $cargo = get_post_meta($post_id, 'cargo', true);
        echo $cargo ? esc_html($cargo) : '<span style="color:#999;">—</span>';
    }
}
add_action('manage_cfc_equipo_posts_custom_column', 'cfc_equipo_admin_column_data', 10, 2);

/**
 * Theme Options Page (native, no ACF needed)
 */
function cfc_add_options_page() {
    // Main menu - Info/Updates only
    add_menu_page(
        'CFC Familiar',
        'CFC Familiar',
        'manage_options',
        'cfc-familiar',
        'cfc_info_page_html',
        'dashicons-admin-home',
        2
    );

    // Submenu: Info (same as main, but shows in submenu)
    add_submenu_page(
        'cfc-familiar',
        'Información',
        'Información',
        'manage_options',
        'cfc-familiar',
        'cfc_info_page_html'
    );

    // Submenu: Configuraciones
    add_submenu_page(
        'cfc-familiar',
        'Configuraciones',
        'Configuraciones',
        'manage_options',
        'cfc-configuraciones',
        'cfc_settings_page_html'
    );

    // Submenu: Footer
    add_submenu_page(
        'cfc-familiar',
        'Footer',
        'Footer',
        'manage_options',
        'cfc-footer',
        'cfc_footer_page_html'
    );
}
add_action('admin_menu', 'cfc_add_options_page');

/**
 * Disable comments entirely
 */
function cfc_disable_comments() {
    // Remove comments from admin menu
    remove_menu_page('edit-comments.php');
    // Remove comments from admin bar
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('admin_menu', 'cfc_disable_comments');

function cfc_disable_comments_support() {
    // Remove comment support from all post types
    foreach (get_post_types() as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }
}
add_action('init', 'cfc_disable_comments_support', 100);

// Close comments on frontend
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

/**
 * Shared admin styles for CFC options pages
 */
function cfc_options_page_styles() {
    ?>
    <style>
        .cfc-options-wrap { max-width: 900px; margin: 0; padding: 20px 20px 20px 0; }
        .cfc-header { background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3b82f6 100%); border-radius: 16px; padding: 30px 40px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 10px 40px rgba(30, 58, 95, 0.3); }
        .cfc-header-icon { width: 60px; height: 60px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .cfc-header-icon svg { width: 32px; height: 32px; fill: white; }
        .cfc-header-icon .dashicons { font-size: 32px; width: 32px; height: 32px; color: white; }
        .cfc-header-content h1 { color: white; font-size: 28px; font-weight: 600; margin: 0 0 5px 0; }
        .cfc-header-content p { color: rgba(255,255,255,0.8); font-size: 14px; margin: 0; }

        .cfc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        @media (max-width: 1200px) { .cfc-grid { grid-template-columns: 1fr; } }

        .cfc-card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 24px; }
        .cfc-card-header { padding: 20px 24px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 12px; }
        .cfc-card-header.update { background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-bottom: none; }
        .cfc-card-header.update h2, .cfc-card-header.update p { color: white; }
        .cfc-card-header.theme { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-bottom: none; }
        .cfc-card-header.theme h2, .cfc-card-header.theme p { color: white; }
        .cfc-card-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cfc-card-icon.green { background: rgba(255,255,255,0.2); }
        .cfc-card-icon.purple { background: rgba(255,255,255,0.2); }
        .cfc-card-icon.blue { background: #dbeafe; color: #2563eb; }
        .cfc-card-icon.orange { background: #ffedd5; color: #ea580c; }
        .cfc-card-icon.pink { background: #fce7f3; color: #db2777; }
        .cfc-card-icon .dashicons { font-size: 20px; width: 20px; height: 20px; }
        .cfc-card-icon.green .dashicons, .cfc-card-icon.purple .dashicons { color: white; }
        .cfc-card-header h2 { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
        .cfc-card-header p { font-size: 12px; color: #6b7280; margin: 2px 0 0 0; }
        .cfc-card-body { padding: 24px; }

        .cfc-update-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
        .cfc-update-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; border: none; }
        .cfc-update-btn.github { background: #24292e; color: white; }
        .cfc-update-btn.github:hover { background: #1a1e22; transform: translateY(-1px); }
        .cfc-update-btn.wp { background: #0073aa; color: white; }
        .cfc-update-btn.wp:hover { background: #005a87; transform: translateY(-1px); }
        .cfc-update-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .cfc-update-btn .dashicons { font-size: 18px; width: 18px; height: 18px; }

        #cfc-update-result { padding: 14px 16px; border-radius: 10px; font-size: 14px; display: none; margin-bottom: 16px; }
        #cfc-update-result.success { background: #d1fae5; border: 1px solid #10b981; color: #065f46; }
        #cfc-update-result.warning { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; }
        #cfc-update-result.error { background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; }
        #cfc-update-result a { color: inherit; font-weight: 600; }

        .cfc-note { background: #f3f4f6; border-radius: 8px; padding: 12px 14px; font-size: 13px; color: #4b5563; }
        .cfc-note strong { color: #374151; }

        .cfc-theme-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .cfc-theme-item { background: #f9fafb; border-radius: 10px; padding: 16px; }
        .cfc-theme-item.full { grid-column: span 2; }
        .cfc-theme-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; margin-bottom: 4px; font-weight: 500; }
        .cfc-theme-value { font-size: 15px; color: #111827; font-weight: 500; }
        .cfc-theme-value code { background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 14px; }
        .cfc-theme-value a { color: #2563eb; text-decoration: none; }
        .cfc-theme-value a:hover { text-decoration: underline; }

        .cfc-form-section { margin-bottom: 24px; }
        .cfc-form-section:last-child { margin-bottom: 0; }
        .cfc-form-title { font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .cfc-form-title .dashicons { font-size: 16px; width: 16px; height: 16px; color: #6b7280; }

        .cfc-form-grid { display: grid; gap: 16px; }
        .cfc-form-grid.cols-2 { grid-template-columns: 1fr 1fr; }
        @media (max-width: 600px) { .cfc-form-grid.cols-2 { grid-template-columns: 1fr; } }

        .cfc-field { display: flex; flex-direction: column; }
        .cfc-field label { font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .cfc-field input, .cfc-field textarea { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; width: 100%; }
        .cfc-field input:focus, .cfc-field textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .cfc-field input::placeholder, .cfc-field textarea::placeholder { color: #9ca3af; }
        .cfc-field .hint { font-size: 11px; color: #9ca3af; margin-top: 4px; }

        .cfc-submit-area { padding: 20px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; }
        .cfc-submit-btn { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color: white; border: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .cfc-submit-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }

        .cfc-toast { position: fixed; top: 50px; right: 20px; background: #10b981; color: white; padding: 14px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 9999; animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s forwards; }
        @keyframes slideIn { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }

        .dashicons.spin { animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
    <?php
}

/**
 * Info Page - Updates, Theme Info, Changelog
 */
function cfc_info_page_html() {
    if (!current_user_can('manage_options')) return;

    $theme = wp_get_theme();
    $theme_headers = get_file_data(get_template_directory() . '/style.css', array('GitHub Theme URI' => 'GitHub Theme URI'));
    $github_uri = $theme_headers['GitHub Theme URI'];

    cfc_options_page_styles();
    ?>
    <div class="cfc-options-wrap">
        <!-- Header -->
        <div class="cfc-header">
            <div class="cfc-header-icon">
                <span class="dashicons dashicons-admin-home"></span>
            </div>
            <div class="cfc-header-content">
                <h1>CFC Familiar</h1>
                <p>Panel de información del tema Centro Familiar Cristiano</p>
            </div>
        </div>

        <!-- Top Row: Updates & Theme Info -->
        <div class="cfc-grid">
            <!-- Updates Card -->
            <div class="cfc-card">
                <div class="cfc-card-header update">
                    <div class="cfc-card-icon green">
                        <span class="dashicons dashicons-update"></span>
                    </div>
                    <div>
                        <h2>Actualizaciones</h2>
                        <p>Verifica si hay nuevas versiones disponibles</p>
                    </div>
                </div>
                <div class="cfc-card-body">
                    <div id="cfc-update-result"></div>
                    <div class="cfc-update-grid">
                        <button type="button" id="cfc-check-github-update" class="cfc-update-btn github">
                            <span class="dashicons dashicons-github"></span>
                            Verificar en GitHub
                        </button>
                        <button type="button" id="cfc-force-wp-update" class="cfc-update-btn wp">
                            <span class="dashicons dashicons-wordpress"></span>
                            Verificar en WordPress
                        </button>
                    </div>
                    <div class="cfc-note">
                        <strong>Tip:</strong> Crea un "Release" en GitHub con un tag de versión (ej: v1.0.1) para habilitar actualizaciones automáticas.
                    </div>
                </div>
            </div>

            <!-- Theme Info Card -->
            <div class="cfc-card">
                <div class="cfc-card-header theme">
                    <div class="cfc-card-icon purple">
                        <span class="dashicons dashicons-admin-appearance"></span>
                    </div>
                    <div>
                        <h2>Información del Tema</h2>
                        <p>Detalles técnicos del tema activo</p>
                    </div>
                </div>
                <div class="cfc-card-body">
                    <div class="cfc-theme-grid">
                        <div class="cfc-theme-item">
                            <div class="cfc-theme-label">Tema</div>
                            <div class="cfc-theme-value"><?php echo esc_html($theme->get('Name')); ?></div>
                        </div>
                        <div class="cfc-theme-item">
                            <div class="cfc-theme-label">Versión</div>
                            <div class="cfc-theme-value"><code><?php echo esc_html($theme->get('Version')); ?></code></div>
                        </div>
                        <div class="cfc-theme-item">
                            <div class="cfc-theme-label">Autor</div>
                            <div class="cfc-theme-value"><?php echo esc_html($theme->get('Author')); ?></div>
                        </div>
                        <div class="cfc-theme-item">
                            <div class="cfc-theme-label">PHP Requerido</div>
                            <div class="cfc-theme-value"><?php echo esc_html($theme->get('RequiresPHP') ?: '7.4+'); ?></div>
                        </div>
                        <?php if ($github_uri) : ?>
                        <div class="cfc-theme-item full">
                            <div class="cfc-theme-label">Repositorio</div>
                            <div class="cfc-theme-value"><a href="<?php echo esc_url($github_uri); ?>" target="_blank"><?php echo esc_html($github_uri); ?></a></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Changelog Card -->
        <div class="cfc-card">
            <div class="cfc-card-header" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); border-bottom: none;">
                <div class="cfc-card-icon" style="background: rgba(255,255,255,0.2);">
                    <span class="dashicons dashicons-clipboard" style="color: white;"></span>
                </div>
                <div>
                    <h2 style="color: white;">Notas de Versión</h2>
                    <p style="color: rgba(255,255,255,0.8);">Historial de cambios del tema</p>
                </div>
            </div>
            <div class="cfc-card-body" style="max-height: 300px; overflow-y: auto;">
                <?php
                $changelog = cfc_get_changelog();
                $current_version = $theme->get('Version');
                foreach ($changelog as $version => $data) :
                    $is_current = ($version === $current_version);
                ?>
                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; <?php echo $is_current ? '' : 'opacity: 0.7;'; ?>">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="background: <?php echo $is_current ? '#10b981' : '#6b7280'; ?>; color: white; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                            v<?php echo esc_html($version); ?>
                        </span>
                        <?php if ($is_current) : ?>
                        <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500;">ACTUAL</span>
                        <?php endif; ?>
                        <span style="color: #9ca3af; font-size: 13px;"><?php echo esc_html($data['date']); ?></span>
                    </div>
                    <ul style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.8;">
                        <?php foreach ($data['changes'] as $change) : ?>
                        <li><?php echo esc_html($change); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var nonce = '<?php echo wp_create_nonce('cfc_update_check'); ?>';

        function showResult(message, type) {
            $('#cfc-update-result')
                .html(message)
                .removeClass('success warning error')
                .addClass(type)
                .slideDown(200);
        }

        $('#cfc-check-github-update').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).find('.dashicons').addClass('spin');
            $('#cfc-update-result').slideUp(100);

            $.post(ajaxurl, {
                action: 'cfc_check_github_release',
                nonce: nonce
            }, function(response) {
                $btn.prop('disabled', false).find('.dashicons').removeClass('spin');
                if (response.success) {
                    var type = response.data.has_update ? 'warning' : 'success';
                    var msg = response.data.message;
                    if (response.data.github_url) {
                        msg += ' <a href="' + response.data.github_url + '" target="_blank">Ver en GitHub &rarr;</a>';
                    }
                    showResult(msg, type);
                } else {
                    showResult(response.data || 'Error desconocido', 'error');
                }
            }).fail(function() {
                $btn.prop('disabled', false).find('.dashicons').removeClass('spin');
                showResult('Error de conexión', 'error');
            });
        });

        $('#cfc-force-wp-update').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).find('.dashicons').addClass('spin');
            $('#cfc-update-result').slideUp(100);

            $.post(ajaxurl, {
                action: 'cfc_force_update_check',
                nonce: nonce
            }, function(response) {
                $btn.prop('disabled', false).find('.dashicons').removeClass('spin');
                if (response.success) {
                    var type = response.data.has_update ? 'warning' : 'success';
                    var msg = response.data.message;
                    if (response.data.update_url && response.data.has_update) {
                        msg += ' <a href="' + response.data.update_url + '">Ir a Temas &rarr;</a>';
                    }
                    showResult(msg, type);
                } else {
                    showResult(response.data || 'Error desconocido', 'error');
                }
            }).fail(function() {
                $btn.prop('disabled', false).find('.dashicons').removeClass('spin');
                showResult('Error de conexión', 'error');
            });
        });
    });
    </script>
    <?php
}

/**
 * Settings Page - Site Configuration
 */
function cfc_settings_page_html() {
    if (!current_user_can('manage_options')) return;

    $saved = false;
    if (isset($_POST['cfc_settings_nonce']) && wp_verify_nonce($_POST['cfc_settings_nonce'], 'cfc_settings_save')) {
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
        $saved = true;
    }

    $values = array();
    $defaults = cfc_defaults();
    foreach ($defaults as $key => $default) {
        $values[$key] = get_option('cfc_' . $key, $default);
    }

    cfc_options_page_styles();
    ?>
    <div class="cfc-options-wrap">
        <?php if ($saved): ?>
        <div class="cfc-toast">Cambios guardados correctamente</div>
        <?php endif; ?>

        <!-- Header -->
        <div class="cfc-header">
            <div class="cfc-header-icon">
                <span class="dashicons dashicons-admin-settings"></span>
            </div>
            <div class="cfc-header-content">
                <h1>Configuraciones</h1>
                <p>Información de la iglesia, horarios y redes sociales</p>
            </div>
        </div>

        <!-- Settings Card -->
        <div class="cfc-card">
            <form method="post" action="">
                <?php wp_nonce_field('cfc_settings_save', 'cfc_settings_nonce'); ?>
                <div class="cfc-card-body">
                    <!-- Church Info -->
                    <div class="cfc-form-section">
                        <div class="cfc-form-title">
                            <span class="dashicons dashicons-building"></span>
                            Información de la Iglesia
                        </div>
                        <div class="cfc-form-grid cols-2">
                            <div class="cfc-field">
                                <label for="church_name">Nombre de la Iglesia</label>
                                <input type="text" id="church_name" name="church_name" value="<?php echo esc_attr($values['church_name']); ?>" placeholder="Centro Familiar Cristiano">
                            </div>
                            <div class="cfc-field">
                                <label for="church_email">Correo Electrónico</label>
                                <input type="email" id="church_email" name="church_email" value="<?php echo esc_attr($values['church_email']); ?>" placeholder="info@tuiglesia.com">
                            </div>
                            <div class="cfc-field">
                                <label for="church_phone">Teléfono</label>
                                <input type="text" id="church_phone" name="church_phone" value="<?php echo esc_attr($values['church_phone']); ?>" placeholder="+507 123-4567">
                            </div>
                            <div class="cfc-field">
                                <label for="church_whatsapp">WhatsApp</label>
                                <input type="text" id="church_whatsapp" name="church_whatsapp" value="<?php echo esc_attr($values['church_whatsapp']); ?>" placeholder="50712345678">
                                <span class="hint">Sin espacios ni símbolos (ej: 50712345678)</span>
                            </div>
                            <div class="cfc-field" style="grid-column: span 2;">
                                <label for="church_address">Dirección</label>
                                <input type="text" id="church_address" name="church_address" value="<?php echo esc_attr($values['church_address']); ?>" placeholder="Calle Principal, Ciudad, País">
                            </div>
                            <div class="cfc-field" style="grid-column: span 2;">
                                <label for="google_maps_url">URL de Google Maps</label>
                                <input type="url" id="google_maps_url" name="google_maps_url" value="<?php echo esc_attr($values['google_maps_url']); ?>" placeholder="https://maps.google.com/...">
                            </div>
                        </div>
                    </div>

                    <!-- Service Times -->
                    <div class="cfc-form-section">
                        <div class="cfc-form-title">
                            <span class="dashicons dashicons-clock"></span>
                            Horarios de Servicio
                        </div>
                        <div class="cfc-form-grid cols-2">
                            <div class="cfc-field">
                                <label for="service_day">Día del Servicio</label>
                                <input type="text" id="service_day" name="service_day" value="<?php echo esc_attr($values['service_day']); ?>" placeholder="Domingo">
                            </div>
                            <div class="cfc-field">
                                <label for="service_time">Hora del Servicio</label>
                                <input type="text" id="service_time" name="service_time" value="<?php echo esc_attr($values['service_time']); ?>" placeholder="10:00 AM">
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="cfc-form-section">
                        <div class="cfc-form-title">
                            <span class="dashicons dashicons-share"></span>
                            Redes Sociales
                        </div>
                        <div class="cfc-form-grid cols-2">
                            <div class="cfc-field">
                                <label for="facebook_url">Facebook</label>
                                <input type="url" id="facebook_url" name="facebook_url" value="<?php echo esc_attr($values['facebook_url']); ?>" placeholder="https://facebook.com/tuiglesia">
                            </div>
                            <div class="cfc-field">
                                <label for="instagram_url">Instagram</label>
                                <input type="url" id="instagram_url" name="instagram_url" value="<?php echo esc_attr($values['instagram_url']); ?>" placeholder="https://instagram.com/tuiglesia">
                            </div>
                            <div class="cfc-field">
                                <label for="youtube_channel">Canal de YouTube</label>
                                <input type="url" id="youtube_channel" name="youtube_channel" value="<?php echo esc_attr($values['youtube_channel']); ?>" placeholder="https://youtube.com/@tuiglesia">
                            </div>
                            <div class="cfc-field">
                                <label for="youtube_live_url">YouTube en Vivo</label>
                                <input type="url" id="youtube_live_url" name="youtube_live_url" value="<?php echo esc_attr($values['youtube_live_url']); ?>" placeholder="https://youtube.com/@tuiglesia/live">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="cfc-submit-area">
                    <button type="submit" class="cfc-submit-btn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        setTimeout(function() { $('.cfc-toast').remove(); }, 3000);
    });
    </script>
    <?php
}

/**
 * Footer Page - Footer Settings
 */
function cfc_footer_page_html() {
    if (!current_user_can('manage_options')) return;

    $saved = false;
    if (isset($_POST['cfc_footer_nonce']) && wp_verify_nonce($_POST['cfc_footer_nonce'], 'cfc_footer_save')) {
        if (isset($_POST['footer_description'])) {
            update_option('cfc_footer_description', sanitize_textarea_field($_POST['footer_description']));
        }
        $saved = true;
    }

    $footer_description = get_option('cfc_footer_description', cfc_defaults()['footer_description']);

    cfc_options_page_styles();
    ?>
    <div class="cfc-options-wrap">
        <?php if ($saved): ?>
        <div class="cfc-toast">Cambios guardados correctamente</div>
        <?php endif; ?>

        <!-- Header -->
        <div class="cfc-header" style="background: linear-gradient(135deg, #374151 0%, #4b5563 50%, #6b7280 100%);">
            <div class="cfc-header-icon">
                <span class="dashicons dashicons-editor-kitchensink"></span>
            </div>
            <div class="cfc-header-content">
                <h1>Footer</h1>
                <p>Configuración del pie de página del sitio</p>
            </div>
        </div>

        <!-- Footer Settings Card -->
        <div class="cfc-card">
            <form method="post" action="">
                <?php wp_nonce_field('cfc_footer_save', 'cfc_footer_nonce'); ?>
                <div class="cfc-card-body">
                    <div class="cfc-form-section">
                        <div class="cfc-form-title">
                            <span class="dashicons dashicons-text"></span>
                            Descripción del Footer
                        </div>
                        <div class="cfc-form-grid">
                            <div class="cfc-field" style="grid-column: span 2;">
                                <label for="footer_description">Texto que aparece bajo el logo</label>
                                <textarea id="footer_description" name="footer_description" rows="4" placeholder="Breve descripción de la iglesia que aparece en el footer"><?php echo esc_textarea($footer_description); ?></textarea>
                                <span class="hint">Este texto aparece debajo del logo en el pie de página junto a las redes sociales</span>
                            </div>
                        </div>
                    </div>

                    <div class="cfc-note" style="margin-top: 16px;">
                        <strong>Nota:</strong> Las redes sociales, horarios y datos de contacto del footer se configuran en <a href="<?php echo admin_url('admin.php?page=cfc-configuraciones'); ?>">Configuraciones</a>.
                    </div>
                </div>
                <div class="cfc-submit-area">
                    <button type="submit" class="cfc-submit-btn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        setTimeout(function() { $('.cfc-toast').remove(); }, 3000);
    });
    </script>
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
 * Custom Menu Walker for Footer Navigation
 */
class CFC_Footer_Menu_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<a href="' . esc_url($item->url) . '" class="block text-gray-400 hover:text-white transition-colors">' . esc_html($item->title) . '</a>';
    }
}

/**
 * Include ACF Fields documentation
 */
require_once CFC_THEME_DIR . '/inc/acf-fields.php';
require_once CFC_THEME_DIR . '/inc/github-updater.php';

// Sample events function removed - events should be created manually by admin

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
 * Create Sample Grupos (runs once)
 */
function cfc_create_sample_grupos() {
    if (get_option('cfc_sample_grupos_created')) {
        return;
    }

    $grupos = array(
        array(
            'title'      => 'Adolescentes',
            'desc'       => 'Un espacio donde los adolescentes descubren su identidad en Cristo, forman amistades genuinas y se divierten juntos.',
            'rango_edad' => '13-17 años',
            'horario'    => 'Sábados 4:00 PM',
            'color'      => 'purple',
            'imagen_url' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=600&h=300&fit=crop',
            'orden'      => 1,
        ),
        array(
            'title'      => 'Jóvenes',
            'desc'       => 'Conecta con jóvenes adultos mientras navegas los desafíos de la vida, el trabajo y las relaciones con fe y propósito.',
            'rango_edad' => '18-30 años',
            'horario'    => 'Viernes 7:00 PM',
            'color'      => 'blue',
            'imagen_url' => 'https://images.unsplash.com/photo-1523301343968-6a6ebf63c672?w=600&h=300&fit=crop',
            'orden'      => 2,
        ),
        array(
            'title'      => 'Parejas',
            'desc'       => 'Fortalece tu matrimonio con fundamentos bíblicos. Talleres, consejería y actividades para crecer juntos como pareja.',
            'rango_edad' => 'Para matrimonios',
            'horario'    => 'Último sábado del mes',
            'color'      => 'green',
            'imagen_url' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600&h=300&fit=crop',
            'orden'      => 3,
        ),
        array(
            'title'      => 'Mujeres',
            'desc'       => 'Encuentra tu verdadero propósito y desarrolla una vida que agrade a Dios. Reuniones de oración y estudios bíblicos.',
            'rango_edad' => 'Para todas las edades',
            'horario'    => 'Primer sábado del mes',
            'color'      => 'pink',
            'imagen_url' => 'https://images.unsplash.com/photo-1571442463800-1337d7af9d2f?w=600&h=300&fit=crop',
            'orden'      => 4,
        ),
        array(
            'title'      => 'Varones',
            'desc'       => 'Crece como hombre de Dios y asume tu rol en el hogar y la sociedad. Desayunos, retiros y estudios para hombres.',
            'rango_edad' => 'Para todos los hombres',
            'horario'    => 'Segundo sábado del mes',
            'color'      => 'orange',
            'imagen_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=300&fit=crop',
            'orden'      => 5,
        ),
    );

    foreach ($grupos as $grupo) {
        $post_id = wp_insert_post(array(
            'post_title'    => $grupo['title'],
            'post_excerpt'  => $grupo['desc'],
            'post_status'   => 'publish',
            'post_type'     => 'cfc_grupo',
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'rango_edad', $grupo['rango_edad']);
            update_post_meta($post_id, 'horario', $grupo['horario']);
            update_post_meta($post_id, 'color', $grupo['color']);
            update_post_meta($post_id, 'imagen_url', $grupo['imagen_url']);
            update_post_meta($post_id, 'btn_texto', 'Únete al Grupo');
            update_post_meta($post_id, 'orden', $grupo['orden']);
        }
    }

    update_option('cfc_sample_grupos_created', true);
}
add_action('init', 'cfc_create_sample_grupos', 22);

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
        if (!term_exists($slug, 'category')) {
            wp_insert_term($name, 'category', array('slug' => $slug));
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
        $existing = get_page_by_title($reflexion['title'], OBJECT, 'post');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title' => $reflexion['title'],
                'post_content' => $reflexion['content'],
                'post_type' => 'post',
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s', strtotime("-{$index} days")),
            ));

            if ($post_id && !is_wp_error($post_id)) {
                // Set category
                $term = get_term_by('slug', $reflexion['categoria'], 'category');
                if ($term) {
                    wp_set_object_terms($post_id, $term->term_id, 'category');
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

/**
 * ====================================
 * GitHub Theme Update Checker
 * ====================================
 */

/**
 * Force WordPress to check for theme updates via AJAX
 */
function cfc_force_theme_update_check() {
    check_ajax_referer('cfc_update_check', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('No tienes permisos para realizar esta acción.');
    }

    // Delete the transient that stores update info to force fresh check
    delete_site_transient('update_themes');

    // Force WordPress to check for theme updates
    wp_update_themes();

    // Get the update info
    $updates = get_site_transient('update_themes');
    $theme_slug = get_template();
    $current_theme = wp_get_theme();
    $current_version = $current_theme->get('Version');

    // Check if there's an update for our theme
    if (isset($updates->response[$theme_slug])) {
        $update_info = $updates->response[$theme_slug];
        $new_version = $update_info['new_version'] ?? 'desconocida';
        wp_send_json_success(array(
            'has_update' => true,
            'current_version' => $current_version,
            'new_version' => $new_version,
            'message' => sprintf('¡Actualización disponible! Versión actual: %s → Nueva versión: %s', $current_version, $new_version),
            'update_url' => admin_url('themes.php')
        ));
    } else {
        wp_send_json_success(array(
            'has_update' => false,
            'current_version' => $current_version,
            'message' => sprintf('El tema está actualizado (versión %s).', $current_version)
        ));
    }
}
add_action('wp_ajax_cfc_force_update_check', 'cfc_force_theme_update_check');

/**
 * Check GitHub for latest release
 */
function cfc_check_github_release() {
    check_ajax_referer('cfc_update_check', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('No tienes permisos para realizar esta acción.');
    }

    $current_theme = wp_get_theme();

    // Read custom headers from style.css (WordPress doesn't read custom headers by default)
    $style_file = get_template_directory() . '/style.css';
    $theme_data = get_file_data($style_file, array(
        'GitHub Theme URI' => 'GitHub Theme URI',
        'Update URI' => 'Update URI'
    ));

    $github_uri = !empty($theme_data['GitHub Theme URI']) ? $theme_data['GitHub Theme URI'] : $theme_data['Update URI'];

    if (empty($github_uri)) {
        wp_send_json_error('No se encontró GitHub Theme URI en el tema.');
    }

    // Extract owner/repo from GitHub URI
    preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $github_uri, $matches);
    if (count($matches) < 3) {
        wp_send_json_error('URL de GitHub inválida.');
    }

    $owner = $matches[1];
    $repo = str_replace('.git', '', $matches[2]);

    // Get latest release from GitHub API
    $api_url = "https://api.github.com/repos/{$owner}/{$repo}/releases/latest";
    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress/' . get_bloginfo('version')
        ),
        'timeout' => 15
    ));

    if (is_wp_error($response)) {
        // Try tags if no releases
        $tags_url = "https://api.github.com/repos/{$owner}/{$repo}/tags";
        $response = wp_remote_get($tags_url, array(
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version')
            ),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('Error conectando con GitHub: ' . $response->get_error_message());
        }

        $tags = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($tags)) {
            wp_send_json_success(array(
                'has_update' => false,
                'current_version' => $current_theme->get('Version'),
                'message' => 'No se encontraron releases ni tags en GitHub.',
                'github_url' => $github_uri
            ));
        }

        $latest_tag = $tags[0]['name'];
        $latest_version = ltrim($latest_tag, 'v');
    } else {
        $release = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($release['message']) && $release['message'] === 'Not Found') {
            // No releases, check commits
            wp_send_json_success(array(
                'has_update' => false,
                'current_version' => $current_theme->get('Version'),
                'message' => 'No hay releases publicados en GitHub. Usa "Releases" en GitHub para publicar versiones.',
                'github_url' => $github_uri . '/releases'
            ));
        }

        $latest_version = isset($release['tag_name']) ? ltrim($release['tag_name'], 'v') : null;
    }

    $current_version = $current_theme->get('Version');

    if ($latest_version && version_compare($latest_version, $current_version, '>')) {
        wp_send_json_success(array(
            'has_update' => true,
            'current_version' => $current_version,
            'new_version' => $latest_version,
            'message' => sprintf('¡Nueva versión disponible en GitHub! %s → %s', $current_version, $latest_version),
            'github_url' => $github_uri . '/releases'
        ));
    } else {
        wp_send_json_success(array(
            'has_update' => false,
            'current_version' => $current_version,
            'github_version' => $latest_version ?: 'No disponible',
            'message' => sprintf('El tema está actualizado (versión %s).', $current_version),
            'github_url' => $github_uri
        ));
    }
}
add_action('wp_ajax_cfc_check_github_release', 'cfc_check_github_release');
