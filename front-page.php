<?php
/**
 * Front Page Template
 * Renders the homepage directly without needing a WordPress Page
 *
 * @package CFC_Familiar
 */

// Church address from global options
$church_address = cfc_get_option('church_address', cfc_default('church_address'));

// Hero fields (defaults)
$hero_video = '';
$hero_image = '';
$hero_badge = 'En vivo cada domingo';
$hero_mostrar_badge = '1';
$hero_titulo_1 = 'Centro Familiar';
$hero_titulo_2 = 'Cristiano';
$hero_btn1_texto = 'Visítanos Este Domingo';
$hero_btn1_url = '#horarios';
$hero_btn2_texto = 'Ver en Vivo';
$hero_btn2_url = cfc_get_option('youtube_live_url', cfc_default('youtube_live_url'));
$estamos_en_vivo = '';
$live_mensaje_offline = 'No estamos en vivo en este momento. Próximo servicio: Domingo 10:00 AM';
$default_video = get_template_directory_uri() . '/assets/videos/cfcintrohomepage.mp4';

// Ubicación fields from global options (CFC Familiar → Configuraciones)
$ubi_badge = 'Encuéntranos';
$ubi_mostrar_badge = '1';
$ubi_titulo_1 = 'Localizaciones y';
$ubi_titulo_2 = 'Horarios';
$ubi_maps_url = cfc_get_option('google_maps_url', cfc_default('google_maps_url'));
$ubi_maps_texto = 'Abrir en Google Maps';

// Sección Reflexiones Recientes
$ref_badge = 'Contenido';
$ref_titulo_1 = 'Reflexiones';
$ref_titulo_2 = 'Recientes';
$ref_subtitulo = 'Inspiración y enseñanzas para transformar tu vida diaria';

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
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-blue-900/65 to-indigo-900/55"></div>

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

    <!-- Sección Grupos - Hero Slider -->
    <?php
    $grupos = new WP_Query(array(
        'post_type' => 'cfc_grupo',
        'posts_per_page' => -1,
        'meta_key' => 'orden',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ));
    if ($grupos->have_posts()) :
        $slides = array();
        while ($grupos->have_posts()) : $grupos->the_post();
            $slides[] = array(
                'title'   => get_the_title(),
                'desc'    => get_the_excerpt(),
                'edad'    => get_post_meta(get_the_ID(), 'rango_edad', true),
                'horario' => get_post_meta(get_the_ID(), 'horario', true),
                'imagen'  => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'cfc-hero') : (get_post_meta(get_the_ID(), 'imagen_url', true) ?: 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=1920&h=800&fit=crop'),
                'btn_url' => get_post_meta(get_the_ID(), 'btn_url', true) ?: '#',
                'btn_texto' => get_post_meta(get_the_ID(), 'btn_texto', true) ?: 'Únete',
            );
        endwhile;
        wp_reset_postdata();
    ?>
    <section id="conexion" class="relative overflow-hidden" data-aos="fade-up">
        <!-- Slides -->
        <div id="grupos-slider" class="relative h-[500px] md:h-[550px]">
            <?php foreach ($slides as $i => $slide) : ?>
            <div class="grupos-slide absolute inset-0 transition-opacity duration-700 <?php echo $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?>">
                <img src="<?php echo esc_url($slide['imagen']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-gray-900/70 to-gray-900/95"></div>
                <div class="relative z-10 h-full flex items-center justify-end">
                    <div class="w-full md:w-1/2 px-8 md:px-16 lg:px-20">
                        <span class="inline-block px-3 py-1 bg-white/15 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider rounded-full mb-4">
                            Encuentra tu lugar
                        </span>
                        <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4">
                            <?php echo esc_html($slide['title']); ?>
                        </h2>
                        <div class="flex items-center gap-3 text-white/80 text-sm mb-4">
                            <?php if ($slide['edad']) : ?>
                            <span><?php echo esc_html($slide['edad']); ?></span>
                            <span>&middot;</span>
                            <?php endif; ?>
                            <?php if ($slide['horario']) : ?>
                            <span><?php echo esc_html($slide['horario']); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-white/80 text-lg mb-8 line-clamp-3"><?php echo esc_html($slide['desc']); ?></p>
                        <?php if ($slide['btn_url'] && $slide['btn_url'] !== '#') : ?>
                        <a href="<?php echo esc_url($slide['btn_url']); ?>" class="inline-flex items-center gap-2 bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 hover:shadow-lg transition-all">
                            <?php echo esc_html($slide['btn_texto']); ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Flechas: ocultas en mobile, visibles en desktop -->
        <button id="grupos-prev" class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/20 items-center justify-center text-white/70 hover:bg-black/40 hover:text-white transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button id="grupos-next" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/20 items-center justify-center text-white/70 hover:bg-black/40 hover:text-white transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Dots centrados -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            <?php foreach ($slides as $i => $slide) : ?>
            <button class="grupos-dot w-2.5 h-2.5 rounded-full transition-all <?php echo $i === 0 ? 'bg-white w-8' : 'bg-white/50'; ?>" data-index="<?php echo $i; ?>"></button>
            <?php endforeach; ?>
        </div>
    </section>

    <script>
    (function() {
        var slides = document.querySelectorAll('.grupos-slide');
        var dots = document.querySelectorAll('.grupos-dot');
        if (slides.length < 2) return;

        var current = 0;
        var total = slides.length;
        var timer;

        function goTo(index) {
            slides[current].classList.remove('opacity-100', 'z-10');
            slides[current].classList.add('opacity-0', 'z-0');
            dots[current].classList.remove('bg-white', 'w-8');
            dots[current].classList.add('bg-white/50');

            current = (index + total) % total;

            slides[current].classList.remove('opacity-0', 'z-0');
            slides[current].classList.add('opacity-100', 'z-10');
            dots[current].classList.remove('bg-white/50');
            dots[current].classList.add('bg-white', 'w-8');
        }

        function autoPlay() { timer = setInterval(function() { goTo(current + 1); }, 5000); }
        function resetTimer() { clearInterval(timer); autoPlay(); }

        document.getElementById('grupos-next').addEventListener('click', function() { goTo(current + 1); resetTimer(); });
        document.getElementById('grupos-prev').addEventListener('click', function() { goTo(current - 1); resetTimer(); });
        dots.forEach(function(dot) {
            dot.addEventListener('click', function() { goTo(parseInt(this.dataset.index)); resetTimer(); });
        });

        autoPlay();
    })();
    </script>
    <?php else : ?>
    <!-- Grupos: Próximamente -->
    <section id="conexion" class="py-20 bg-gray-50" data-aos="fade-up">
        <div class="container mx-auto px-6 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl mb-6">
                <span class="text-4xl">&#129309;</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Grupos de Discipulado</h3>
            <p class="text-gray-500 max-w-md mx-auto">Proximamente tendremos información sobre nuestros grupos. Vuelve pronto.</p>
        </div>
    </section>
    <?php endif; ?>

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
                        'post_type' => 'post',
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
                            <?php $cats = get_the_terms(get_the_ID(), 'category'); ?>
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
                    ?>
                    <div class="md:col-span-3 text-center py-16" data-aos="fade-up">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl mb-6">
                            <span class="text-4xl">&#128214;</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Proximamente</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Estamos preparando contenido inspirador para alimentar tu vida espiritual. Vuelve pronto.</p>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>

                <!-- Botón ver todas -->
                <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="inline-flex items-center gap-3 bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full font-bold hover:shadow-xl transition-all duration-300 group">
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
