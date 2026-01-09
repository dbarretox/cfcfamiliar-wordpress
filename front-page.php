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
$default_video = get_template_directory_uri() . '/assets/videos/cfcintrohomepage.mp4';

// Ubicación fields from page meta
$ubi_badge = get_post_meta($inicio_page_id, 'ubi_badge', true) ?: 'Encuéntranos';
$ubi_mostrar_badge = get_post_meta($inicio_page_id, 'ubi_mostrar_badge', true);
if ($ubi_mostrar_badge === '') $ubi_mostrar_badge = '1';
$ubi_titulo_1 = get_post_meta($inicio_page_id, 'ubi_titulo_1', true) ?: 'Localizaciones y';
$ubi_titulo_2 = get_post_meta($inicio_page_id, 'ubi_titulo_2', true) ?: 'Horarios';
$ubi_maps_url = get_post_meta($inicio_page_id, 'ubi_maps_url', true);
if (empty($ubi_maps_url)) {
    $ubi_maps_url = cfc_get_option('google_maps_url', cfc_default('google_maps_url'));
}
$ubi_maps_texto = get_post_meta($inicio_page_id, 'ubi_maps_texto', true) ?: 'Abrir en Google Maps';

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

                <a href="<?php echo esc_url($hero_btn2_url); ?>" target="_blank" class="group inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md border-2 border-white/30 text-white rounded-full font-bold text-lg hover:bg-white/20 transition-all duration-300">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                    </svg>
                    <?php echo esc_html($hero_btn2_texto); ?>
                </a>
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

    <!-- Sección Conéctate -->
    <section id="conexion" class="py-20 bg-gradient-to-br from-gray-50 via-blue-50/30 to-cyan-50/30">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-16" data-aos="fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/10 rounded-full mb-4">
                        <svg class="w-4 h-4 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                        <span class="text-secondary text-sm font-bold uppercase tracking-wider">Únete a la familia</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                        Encuentra Tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary to-accent">Lugar</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Conéctate con personas de tu edad y crece en comunidad
                    </p>
                </div>

                <!-- Grid de grupos -->
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Card Adolescentes -->
                    <div class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-gray-100" data-aos="fade-right">
                        <!-- Imagen con overlay -->
                        <div class="relative h-56 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=600&h=300&fit=crop"
                                 alt="Grupo de Adolescentes"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-purple-900/80 via-purple-600/40 to-transparent"></div>

                            <!-- Badge flotante -->
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-purple-700 px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                13-17 años
                            </div>

                            <!-- Emoji grande -->
                            <div class="absolute bottom-4 left-4 text-6xl opacity-80">
                                &#127918;
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                    <span class="text-2xl">&#127919;</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Adolescentes</h3>
                                </div>
                            </div>

                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Un espacio donde los adolescentes descubren su identidad en Cristo, forman amistades genuinas y se divierten juntos.
                            </p>

                            <!-- Detalles con iconos -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">Sábados 4:00 PM</span>
                                </div>
                            </div>

                            <!-- Botón -->
                            <a href="#" class="group/btn relative w-full flex items-center justify-center gap-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white py-4 rounded-xl font-bold hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <span class="relative z-10">Únete al Grupo</span>
                                <svg class="w-5 h-5 relative z-10 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 transform scale-x-0 group-hover/btn:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                        </div>
                    </div>

                    <!-- Card Jóvenes -->
                    <div class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-gray-100" data-aos="fade-left" data-aos-delay="100">
                        <!-- Imagen con overlay -->
                        <div class="relative h-56 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1523301343968-6a6ebf63c672?w=600&h=300&fit=crop"
                                 alt="Grupo de Jóvenes"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-600/40 to-transparent"></div>

                            <!-- Badge flotante -->
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-blue-700 px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                18-30 años
                            </div>

                            <!-- Emoji grande -->
                            <div class="absolute bottom-4 left-4 text-6xl opacity-80">
                                &#9749;
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                    <span class="text-2xl">&#128640;</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Jóvenes</h3>
                                </div>
                            </div>

                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Conecta con jóvenes adultos mientras navegas los desafíos de la vida, el trabajo y las relaciones con fe y propósito.
                            </p>

                            <!-- Detalles con iconos -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">Viernes 7:00 PM</span>
                                </div>
                            </div>

                            <!-- Botón -->
                            <a href="#" class="group/btn relative w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-4 rounded-xl font-bold hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <span class="relative z-10">Únete al Grupo</span>
                                <svg class="w-5 h-5 relative z-10 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 transform scale-x-0 group-hover/btn:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <span class="text-rose text-sm font-bold uppercase tracking-wider">Contenido</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                        Reflexiones <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose to-pink-500">Recientes</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Inspiración y enseñanzas para transformar tu vida diaria
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

<?php get_footer(); ?>
