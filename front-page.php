<?php
/**
 * Front Page Template (fallback)
 * Note: Use "Inicio" template for editable homepage
 *
 * @package CFC_Familiar
 */

// Verificar si existe página con template "Inicio" configurada
$inicio_page = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-inicio.php',
    'number' => 1
));

if (empty($inicio_page)) {
    $setup_page_name = 'Inicio';
    $setup_template_label = 'Inicio';
    include(get_template_directory() . '/template-parts/setup-required.php');
    exit;
}

// Get page-specific overrides
$inicio_page_id = $inicio_page[0]->ID;
$custom_direccion = get_post_meta($inicio_page_id, 'ubicacion_direccion', true);
$church_address = !empty($custom_direccion) ? $custom_direccion : cfc_get_option('church_address', cfc_default('church_address'));

// Hero fields from page meta
$hero_video = get_post_meta($inicio_page_id, 'hero_video_url', true);
$hero_image = get_post_meta($inicio_page_id, 'hero_image_url', true);
$hero_badge = get_post_meta($inicio_page_id, 'hero_badge', true) ?: 'En vivo cada domingo';
$hero_mostrar_badge = get_post_meta($inicio_page_id, 'hero_mostrar_badge', true);
if ($hero_mostrar_badge === '') $hero_mostrar_badge = '1';
$hero_titulo_1 = get_post_meta($inicio_page_id, 'hero_titulo_1', true) ?: 'Centro Familiar';
$hero_titulo_2 = get_post_meta($inicio_page_id, 'hero_titulo_2', true) ?: 'Cristiano';
$hero_btn1_texto = get_post_meta($inicio_page_id, 'hero_btn1_texto', true) ?: 'Visítanos Este Domingo';
$hero_btn1_url = get_post_meta($inicio_page_id, 'hero_btn1_url', true) ?: '#horarios';
$hero_btn2_texto = get_post_meta($inicio_page_id, 'hero_btn2_texto', true) ?: 'Ver en Vivo';
$hero_btn2_url = get_post_meta($inicio_page_id, 'hero_btn2_url', true);
if (empty($hero_btn2_url)) {
    $hero_btn2_url = cfc_get_option('youtube_live_url', cfc_default('youtube_live_url'));
}
$estamos_en_vivo = get_post_meta($inicio_page_id, 'estamos_en_vivo', true);
$live_mensaje_offline = get_post_meta($inicio_page_id, 'live_mensaje_offline', true) ?: 'No estamos en vivo en este momento. Próximo servicio: Domingo 10:00 AM';
$default_video = get_template_directory_uri() . '/assets/videos/cfcintrohomepage.mp4';

// Ubicación fields from global options (CFC Familiar → Configuraciones)
$ubi_badge = 'Encuéntranos';
$ubi_mostrar_badge = '1';
$ubi_titulo_1 = 'Localizaciones y';
$ubi_titulo_2 = 'Horarios';
$ubi_maps_url = cfc_get_option('google_maps_url', cfc_default('google_maps_url'));
$ubi_maps_texto = 'Abrir en Google Maps';

// Sección Reflexiones Recientes
$ref_badge = get_post_meta($inicio_page_id, 'ref_badge', true) ?: 'Contenido';
$ref_titulo_1 = get_post_meta($inicio_page_id, 'ref_titulo_1', true) ?: 'Reflexiones';
$ref_titulo_2 = get_post_meta($inicio_page_id, 'ref_titulo_2', true) ?: 'Recientes';
$ref_subtitulo = get_post_meta($inicio_page_id, 'ref_subtitulo', true) ?: 'Inspiración y enseñanzas para transformar tu vida diaria';

get_header();
?>

    <!-- Hero Section -->
    <section id="inicio" class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Video Background -->
        <?php if ($hero_video || file_exists(get_template_directory() . '/assets/videos/cfcintrohomepage.mp4')) : ?>
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="<?php echo esc_url($hero_video ? $hero_video : $default_video); ?>" type="video/mp4">
        </video>
        <?php elseif ($hero_image) : ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="Hero" class="absolute inset-0 w-full h-full object-cover">
        <?php else : ?>
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent"></div>
        <?php endif; ?>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/95 via-blue-900/90 to-indigo-900/85"></div>

        <!-- Partículas decorativas -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-6 max-w-6xl mx-auto hero-content">
            <?php if ($hero_mostrar_badge === '1') : ?>
            <!-- Badge superior -->
            <div class="hero-badge mb-6">
                <span class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white text-sm font-semibold">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-300"></span>
                    </span>
                    <?php echo esc_html($hero_badge); ?>
                </span>
            </div>
            <?php endif; ?>

            <!-- Título principal -->
            <h1 class="hero-title text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white mb-8 leading-tight">
                <span class="block mb-2"><?php echo esc_html($hero_titulo_1); ?></span>
                <span class="block bg-gradient-to-r from-blue-400 via-cyan-300 to-sky-200 bg-clip-text text-transparent">
                    <?php echo esc_html($hero_titulo_2); ?>
                </span>
            </h1>

            <!-- Botones de acción -->
            <div class="hero-buttons flex flex-col sm:flex-row gap-5 justify-center items-center">
                <a href="<?php echo esc_url($hero_btn1_url); ?>" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-primary rounded-full font-bold text-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:scale-105 hover:text-white">
                    <span class="relative z-10 transition-colors duration-300 group-hover:text-white"><?php echo esc_html($hero_btn1_texto); ?></span>
                    <svg class="w-5 h-5 relative z-10 transition-all duration-300 group-hover:translate-x-1 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary transform -skew-x-12 scale-x-0 group-hover:scale-x-110 transition-transform origin-left duration-300"></div>
                </a>

                <?php if ($estamos_en_vivo === '1') : ?>
                <a href="<?php echo esc_url($hero_btn2_url); ?>" target="_blank" class="group inline-flex items-center gap-3 px-8 py-4 bg-red-500/90 backdrop-blur-md border-2 border-red-400 text-white rounded-full font-bold text-lg hover:bg-red-600 transition-all duration-300 animate-pulse">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                    </span>
                    <?php echo esc_html($hero_btn2_texto); ?>
                </a>
                <?php else : ?>
                <button type="button" onclick="document.getElementById('modal-no-live').classList.remove('hidden')" class="group inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md border-2 border-white/30 text-white rounded-full font-bold text-lg hover:bg-white/20 transition-all duration-300">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                    </svg>
                    <?php echo esc_html($hero_btn2_texto); ?>
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="hero-scroll absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 flex flex-col items-center gap-2">
            <span class="text-white/60 text-xs uppercase tracking-wider">Descubre más</span>
            <div class="animate-bounce">
                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- Sección Horarios Compacta -->
    <section id="horarios" class="py-16 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12" data-aos="fade-up">
                    <?php if ($ubi_mostrar_badge === '1') : ?>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/5 rounded-full mb-3">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-primary text-sm font-bold uppercase tracking-wider"><?php echo esc_html($ubi_badge); ?></span>
                    </div>
                    <?php endif; ?>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900">
                        <?php echo esc_html($ubi_titulo_1); ?> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary"><?php echo esc_html($ubi_titulo_2); ?></span>
                    </h2>
                </div>

                <!-- Card única horizontal -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100" data-aos="zoom-in">
                    <div class="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">

                        <!-- Horario Domingo -->
                        <div class="p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Horarios</h3>
                                    <p class="text-sm text-gray-600">Nuestras reuniones</p>
                                </div>
                            </div>

                            <!-- Card del Domingo -->
                            <div class="bg-gradient-to-br from-blue-50 via-cyan-50 to-blue-50 rounded-2xl p-6 border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                            <span class="text-3xl">&#9728;&#65039;</span>
                                        </div>
                                        <div>
                                            <p class="text-lg font-bold text-gray-900"><?php echo esc_html(cfc_get_option('service_day', cfc_default('service_day'))); ?></p>
                                            <p class="text-sm text-gray-600">Servicio Principal</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-black text-primary leading-none"><?php echo esc_html(str_replace(' AM', '', str_replace(' PM', '', cfc_get_option('service_time', cfc_default('service_time'))))); ?></p>
                                        <p class="text-xs text-gray-600 font-semibold mt-1"><?php echo strpos(cfc_get_option('service_time', cfc_default('service_time')), 'PM') !== false ? 'PM' : 'AM'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 bg-gradient-to-br from-rose to-pink-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Ubicación</h3>
                                    <p class="text-sm text-gray-600">Cómo llegar</p>
                                </div>
                            </div>

                            <!-- Info de contacto -->
                            <div class="space-y-4">
                                <!-- Dirección -->
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Dirección</p>
                                        <p class="text-sm font-medium text-gray-900"><?php echo esc_html($church_address); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Google Maps -->
                    <div class="border-t border-gray-100">
                        <a href="<?php echo esc_url($ubi_maps_url); ?>" target="_blank" class="group flex items-center justify-center gap-3 w-full py-5 bg-gradient-to-r from-primary via-secondary to-primary bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-bold transition-all duration-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span><?php echo esc_html($ubi_maps_texto); ?></span>
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Grupos - Carrusel (desactivada temporalmente) -->
    <?php if (false) :
    $grupos = new WP_Query(array(
        'post_type' => 'cfc_grupo',
        'posts_per_page' => -1,
        'meta_key' => 'orden',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ));
    $color_map = array(
        'purple' => array('from' => 'from-purple-500', 'to' => 'to-pink-500', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50'),
        'blue'   => array('from' => 'from-blue-500', 'to' => 'to-cyan-500', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50'),
        'green'  => array('from' => 'from-green-500', 'to' => 'to-teal-500', 'text' => 'text-green-700', 'bg' => 'bg-green-50'),
        'orange' => array('from' => 'from-orange-500', 'to' => 'to-amber-500', 'text' => 'text-orange-700', 'bg' => 'bg-orange-50'),
        'pink'   => array('from' => 'from-pink-500', 'to' => 'to-rose-500', 'text' => 'text-pink-700', 'bg' => 'bg-pink-50'),
    );
    if ($grupos->have_posts()) :
    ?>
    <section id="conexion" class="py-20 bg-gray-100">
        <div class="max-w-[1400px] mx-auto">
            <!-- Header -->
            <div class="px-6 md:px-12 mb-8" data-aos="fade-up">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Encuentra tu lugar. <span class="text-gray-400">Conéctate.</span>
                </h2>
            </div>

            <!-- Carrusel con flecha -->
            <div class="relative">
                <div id="grupos-carousel" class="flex gap-5 overflow-x-auto pl-6 md:pl-12 pr-6 pb-8 snap-x snap-mandatory cursor-grab active:cursor-grabbing" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
                    <?php while ($grupos->have_posts()) : $grupos->the_post();
                        $color = get_post_meta(get_the_ID(), 'color', true) ?: 'blue';
                        $c = isset($color_map[$color]) ? $color_map[$color] : $color_map['blue'];
                        $edad = get_post_meta(get_the_ID(), 'rango_edad', true);
                        $horario = get_post_meta(get_the_ID(), 'horario_reunion', true) ?: get_post_meta(get_the_ID(), 'horario', true);
                        $imagen = get_post_meta(get_the_ID(), 'imagen_url', true) ?: 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=500&h=350&fit=crop';
                        $btn_url = get_post_meta(get_the_ID(), 'btn_url', true) ?: '#';
                        $btn_texto = get_post_meta(get_the_ID(), 'btn_texto', true) ?: 'Únete';
                    ?>
                    <div class="group flex-shrink-0 w-80 snap-start bg-white rounded-3xl p-6 flex flex-col transition-shadow duration-300 hover:shadow-xl" style="min-height: 420px;">
                        <!-- Top -->
                        <div class="mb-4">
                            <?php if ($edad) : ?>
                            <span class="text-xs font-semibold <?php echo esc_attr($c['text']); ?> uppercase tracking-wide"><?php echo esc_html($edad); ?></span>
                            <?php endif; ?>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1"><?php the_title(); ?></h3>
                        </div>

                        <!-- Imagen -->
                        <div class="flex-1 flex items-center justify-center overflow-hidden rounded-2xl mb-4">
                            <img src="<?php echo esc_url($imagen); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover rounded-2xl transform group-hover:scale-105 transition-transform duration-700">
                        </div>

                        <!-- Bottom -->
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-sm text-gray-500"><?php echo $horario ? esc_html($horario) : ''; ?></span>
                            <a href="<?php echo esc_url($btn_url); ?>" class="inline-flex items-center gap-1 bg-gradient-to-r <?php echo esc_attr($c['from'] . ' ' . $c['to']); ?> text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:shadow-lg transition-all">
                                <?php echo esc_html($btn_texto); ?>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <!-- Flecha flotante derecha -->
                <button onclick="document.getElementById('grupos-carousel').scrollBy({left:340,behavior:'smooth'})" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-white/80 backdrop-blur shadow-lg hover:shadow-xl items-center justify-center text-gray-700 hover:text-gray-900 transition-all z-10 border border-gray-200/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </section>
    <script>
    (function(){
        var el = document.getElementById('grupos-carousel');
        if (!el) return;
        var isDown = false, startX, scrollLeft;
        el.addEventListener('mousedown', function(e) { isDown = true; startX = e.pageX - el.offsetLeft; scrollLeft = el.scrollLeft; });
        el.addEventListener('mouseleave', function() { isDown = false; });
        el.addEventListener('mouseup', function() { isDown = false; });
        el.addEventListener('mousemove', function(e) { if (!isDown) return; e.preventDefault(); el.scrollLeft = scrollLeft - (e.pageX - el.offsetLeft - startX); });
    })();
    </script>
    <?php endif; // fin if have_posts ?>
    <?php endif; // fin sección grupos desactivada ?>

    <!-- Sección Reflexiones Recientes -->
    <section id="reflexiones" class="py-20 bg-gradient-to-br from-white via-gray-50 to-blue-50/30">
        <div class="container mx-auto px-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-16" data-aos="fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-rose/10 rounded-full mb-4">
                        <svg class="w-4 h-4 text-rose" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-rose text-sm font-bold uppercase tracking-wider"><?php echo esc_html($ref_badge); ?></span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                        <?php echo esc_html($ref_titulo_1); ?> <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose to-pink-500"><?php echo esc_html($ref_titulo_2); ?></span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        <?php echo esc_html($ref_subtitulo); ?>
                    </p>
                </div>

                <!-- Grid de reflexiones -->
                <div class="grid md:grid-cols-3 gap-8">
                    <?php
                    $reflexiones = new WP_Query(array(
                        'post_type' => 'cfc_reflexion',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));

                    if ($reflexiones->have_posts()) :
                        $delay = 0;
                        while ($reflexiones->have_posts()) : $reflexiones->the_post();
                            $tipo = cfc_get_field('tipo_reflexion', get_the_ID(), 'articulo');
                            $duracion = cfc_get_field('duracion', get_the_ID(), '');
                    ?>
                    <!-- Reflexión Card -->
                    <article class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-gray-100" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <!-- Imagen -->
                        <div class="relative h-48 overflow-hidden">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('cfc-card', array('class' => 'w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700')); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-gradient-to-br from-primary to-secondary"></div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>

                            <!-- Badge -->
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-gray-700 px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                <?php echo $duracion ? esc_html($duracion) : cfc_reading_time(); ?>
                            </div>

                            <!-- Categoría -->
                            <?php $cats = get_the_terms(get_the_ID(), 'categoria_reflexion'); ?>
                            <?php if ($cats && !is_wp_error($cats)) : ?>
                            <div class="absolute bottom-4 left-4 bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold">
                                <?php echo esc_html($cats[0]->name); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <!-- Meta -->
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                <span class="flex items-center gap-1">
                                    &#9999;&#65039; <?php the_author(); ?>
                                </span>
                                <span>•</span>
                                <span><?php echo cfc_format_date(); ?></span>
                            </div>

                            <!-- Título -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">
                                <?php the_title(); ?>
                            </h3>

                            <!-- Descripción -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?php echo cfc_excerpt(15); ?>
                            </p>

                            <!-- Botón -->
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary font-semibold text-sm hover:gap-3 transition-all gap-2 group/link">
                                Leer más
                                <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php
                            $delay += 100;
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Default cards if no reflexiones exist
                        $default_reflexiones = array(
                            array('title' => 'La Fe en Acción', 'type' => 'Video', 'badge' => 'EN VIVO'),
                            array('title' => 'El Poder del Perdón', 'type' => 'Podcast', 'badge' => '30 min'),
                            array('title' => 'Familias Fuertes', 'type' => 'Artículo', 'badge' => '5 min lectura'),
                        );
                        foreach ($default_reflexiones as $index => $ref) :
                    ?>
                    <article class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-gray-100" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="relative h-48 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                            <span class="text-6xl opacity-50">&#128214;</span>
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-gray-700 px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                <?php echo esc_html($ref['badge']); ?>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                <span><?php echo esc_html($ref['type']); ?></span>
                                <span>•</span>
                                <span>Nuevo</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo esc_html($ref['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4">Contenido inspirador para tu crecimiento espiritual.</p>
                        </div>
                    </article>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <!-- Botón ver todas -->
                <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?php echo esc_url(get_post_type_archive_link('cfc_reflexion')); ?>" class="inline-flex items-center gap-3 bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full font-bold hover:shadow-xl transition-all duration-300 group">
                        Ver todas las reflexiones
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección ¿Primera vez aquí? -->
    <section id="nuevo" class="py-20 bg-gradient-to-r from-primary via-secondary to-accent text-white relative overflow-hidden">
        <!-- Pattern decorativo -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>

        <div class="container mx-auto px-6 text-center relative z-10">
            <div class="max-w-3xl mx-auto">
                <span class="inline-block mb-6 px-6 py-2 bg-white/20 backdrop-blur rounded-full text-sm font-bold" data-aos="fade-up">
                    &#128075; Te Esperamos
                </span>
                <h2 class="text-4xl md:text-5xl font-black mb-6" data-aos="fade-up" data-aos-delay="100">
                    ¿Primera vez aquí?
                </h2>
                <p class="text-xl text-white/90 mb-10 leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                    Nos encantaría conocerte. Ven tal como eres y descubre una familia que te recibirá con los brazos abiertos.
                </p>
                <a href="<?php echo esc_url(home_url('/visitanos')); ?>" class="inline-block bg-white text-primary px-10 py-4 rounded-full font-bold text-lg hover:scale-105 transition-transform shadow-2xl" data-aos="fade-up" data-aos-delay="300">
                    Visítanos &#128591;
                </a>
            </div>
        </div>
    </section>

    <!-- Modal No Estamos en Vivo -->
    <div id="modal-no-live" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modal-no-live').classList.add('hidden')"></div>

            <!-- Modal Content -->
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gradient-to-br from-primary to-secondary p-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-white/20 mb-4">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">No estamos en vivo</h3>
                </div>
                <div class="bg-white px-8 py-6">
                    <p class="text-gray-600 text-center text-lg leading-relaxed">
                        <?php echo esc_html($live_mensaje_offline); ?>
                    </p>
                </div>
                <div class="bg-gray-50 px-8 py-4">
                    <button type="button" onclick="document.getElementById('modal-no-live').classList.add('hidden')" class="w-full inline-flex justify-center items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-full hover:shadow-lg transition-all">
                        <span>Entendido</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
