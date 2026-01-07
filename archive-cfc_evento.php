<?php
/**
 * Archive Template for Eventos
 *
 * @package CFC_Familiar
 */

// Si es acceso directo al archive (no desde page template), verificar que exista página configurada
if (is_post_type_archive('cfc_evento') && !is_page()) {
    $eventos_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-eventos.php',
        'number' => 1
    ));

    if (!current_user_can('edit_pages') && empty($eventos_page)) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include(get_template_directory() . '/404.php');
        exit;
    }
}

get_header();

// Get the Eventos page ID to read metabox values
$eventos_page = get_page_by_path('eventos');
$eventos_page_id = $eventos_page ? $eventos_page->ID : 0;

// Get metabox values with defaults
$calendar_embed = get_post_meta($eventos_page_id, 'eventos_calendar_embed', true) ?: 'https://calendar.google.com/calendar/embed?height=600&wkst=2&bgcolor=%23ffffff&ctz=America%2FPanama&showTitle=0&showNav=1&showDate=1&showPrint=0&showTabs=0&showCalendars=0&showTz=0&mode=MONTH&src=Y2VudHJvZmFtaWxpYXJjcmlzdGlhbm9AZ21haWwuY29t&color=%234285F4';
$calendar_subscribe = get_post_meta($eventos_page_id, 'eventos_calendar_subscribe', true) ?: 'https://calendar.google.com/calendar/u/0?cid=Y2VudHJvZmFtaWxpYXJjcmlzdGlhbm9AZ21haWwuY29t';
$hero_titulo = get_post_meta($eventos_page_id, 'eventos_hero_titulo', true) ?: 'Nuestros Eventos';
$hero_subtitulo = get_post_meta($eventos_page_id, 'eventos_hero_subtitulo', true) ?: 'Únete a nosotros en actividades que fortalecerán tu fe y comunidad';
?>

    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1505935428862-770b6f24f629?w=1920&h=1080&fit=crop"
                alt="Eventos"
                class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-aos="fade-up">
                <?php echo esc_html($hero_titulo); ?>
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
                <?php echo esc_html($hero_subtitulo); ?>
            </p>

            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                <a href="#eventos-destacados" class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-primary rounded-full font-bold hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <span>Ver eventos destacados</span>
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>

                <a href="#calendario" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-md border-2 border-white/30 text-white rounded-full font-bold hover:bg-white/20 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Ver calendario
                </a>
            </div>
        </div>
    </section>

    <!-- Eventos Destacados -->
    <section id="eventos-destacados" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">&#127919; Eventos Destacados</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    No te pierdas estas actividades especiales
                </p>
            </div>

            <?php
            // Query para los 2 eventos más recientes
            $eventos_destacados = new WP_Query(array(
                'post_type' => 'cfc_evento',
                'posts_per_page' => 2,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            $total_eventos = wp_count_posts('cfc_evento')->publish;
            ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
                <?php
                if ($eventos_destacados->have_posts()) :
                    $delay = 0;
                    while ($eventos_destacados->have_posts()) : $eventos_destacados->the_post();
                        $fecha = cfc_get_field('fecha_evento', get_the_ID(), '');
                        $hora = cfc_get_field('hora_evento', get_the_ID(), '');
                        $ubicacion = cfc_get_field('ubicacion_evento', get_the_ID(), '');
                        $tipo = get_the_terms(get_the_ID(), 'tipo_evento');
                ?>
                <!-- Evento Destacado Card -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="relative h-64">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('cfc-hero', array('class' => 'w-full h-full object-cover')); ?>
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=800&h=400&fit=crop"
                                 alt="<?php the_title_attribute(); ?>"
                                 class="w-full h-full object-cover">
                        <?php endif; ?>

                        <?php if ($fecha) :
                            $fecha_obj = strtotime($fecha);
                            $meses_es = array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
                            $mes_num = (int)date('n', $fecha_obj) - 1;
                        ?>
                        <div class="absolute top-4 left-4 bg-white rounded-lg p-3 text-center shadow-lg">
                            <div class="text-2xl font-bold text-primary"><?php echo date('d', $fecha_obj); ?></div>
                            <div class="text-xs text-gray-600 uppercase"><?php echo $meses_es[$mes_num]; ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-8">
                        <?php if ($tipo && !is_wp_error($tipo)) : ?>
                        <div class="inline-block px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs font-bold mb-4">
                            <?php echo esc_html(strtoupper($tipo[0]->name)); ?>
                        </div>
                        <?php endif; ?>

                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            <?php the_title(); ?>
                        </h3>
                        <p class="text-gray-600 mb-6">
                            <?php echo cfc_excerpt(25); ?>
                        </p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <?php if ($fecha) : ?>
                            <div class="flex items-center gap-3">
                                <span>&#128197;</span>
                                <span><?php echo cfc_format_date($fecha_obj, 'j F, Y'); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($hora) : ?>
                            <div class="flex items-center gap-3">
                                <span>&#128336;</span>
                                <span><?php echo esc_html($hora); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($ubicacion) : ?>
                            <div class="flex items-center gap-3">
                                <span>&#128205;</span>
                                <span><?php echo esc_html($ubicacion); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php
                        $registro_url = cfc_get_field('registro_url', get_the_ID(), '');
                        $texto_boton = cfc_get_field('texto_boton', get_the_ID(), '');
                        $btn_url = $registro_url ? $registro_url : get_the_permalink();
                        $btn_text = $texto_boton ? $texto_boton : ($registro_url ? 'Registrar Ahora' : 'Ver Detalles');
                        ?>
                        <a href="<?php echo esc_url($btn_url); ?>" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-primary to-secondary text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            <?php echo esc_html($btn_text); ?>
                        </a>
                    </div>
                </div>
                <?php
                        $delay += 100;
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                <!-- Default eventos si no hay -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg" data-aos="fade-up">
                    <div class="relative h-64">
                        <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=800&h=400&fit=crop"
                             alt="Conferencia"
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4 bg-white rounded-lg p-3 text-center shadow-lg">
                            <div class="text-2xl font-bold text-primary">15</div>
                            <div class="text-xs text-gray-600 uppercase">DIC</div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="inline-block px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs font-bold mb-4">
                            CONFERENCIA
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            Conferencia Anual de Fe
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Tres días de adoración poderosa, enseñanza inspiradora y comunión profunda.
                        </p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <div class="flex items-center gap-3">
                                <span>&#128197;</span>
                                <span>15-17 de Diciembre, 2025</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span>&#128336;</span>
                                <span>7:00 PM</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span>&#128205;</span>
                                <span>Centro Familiar Cristiano</span>
                            </div>
                        </div>
                        <a href="#" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-primary to-secondary text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            Registrarse Ahora
                        </a>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-64">
                        <img src="https://images.unsplash.com/photo-1543269664-56d93c1b41a6?w=800&h=400&fit=crop"
                             alt="Navidad"
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4 bg-white rounded-lg p-3 text-center shadow-lg">
                            <div class="text-2xl font-bold text-primary">22</div>
                            <div class="text-xs text-gray-600 uppercase">DIC</div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="inline-block px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold mb-4">
                            CELEBRACIÓN
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            Celebración de Navidad
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Una noche especial para toda la familia con música y celebración.
                        </p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <div class="flex items-center gap-3">
                                <span>&#128197;</span>
                                <span>22 de Diciembre, 2025</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span>&#128336;</span>
                                <span>6:00 PM</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span>&#128205;</span>
                                <span>Centro Familiar Cristiano</span>
                            </div>
                        </div>
                        <a href="#" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-primary to-secondary text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            Confirmar Asistencia
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Botón Ver más eventos -->
            <?php if ($total_eventos > 2) : ?>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="#todos-eventos" class="inline-flex items-center gap-3 bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full font-bold hover:shadow-xl transition-all duration-300 group">
                    Ver todos los eventos (<?php echo $total_eventos; ?>)
                    <svg class="w-5 h-5 transition-transform group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Todos los Eventos -->
    <section id="todos-eventos" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">&#128197; Todos los Eventos</h2>
                    <p class="text-gray-600 text-lg">
                        Calendario completo de actividades
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    if (have_posts()) :
                        $delay = 0;
                        while (have_posts()) : the_post();
                            $fecha = cfc_get_field('fecha_evento', get_the_ID(), '');
                            $hora = cfc_get_field('hora_evento', get_the_ID(), '');
                            $fecha_obj = $fecha ? strtotime($fecha) : time();
                    ?>
                    <!-- Evento Card -->
                    <a href="<?php the_permalink(); ?>" class="group block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <div class="bg-gradient-to-r from-primary to-secondary p-4">
                            <div class="flex items-center justify-between text-white">
                                <div>
                                    <p class="text-xs font-semibold opacity-90 uppercase"><?php echo date_i18n('l', $fecha_obj); ?></p>
                                    <p class="text-2xl font-bold"><?php echo date('d', $fecha_obj); ?> <?php echo strtoupper(date('M', $fecha_obj)); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold"><?php echo $hora ? esc_html($hora) : '10:00 AM'; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors"><?php the_title(); ?></h4>
                            <p class="text-sm text-gray-600 line-clamp-2"><?php echo cfc_excerpt(15); ?></p>
                        </div>
                    </a>
                    <?php
                            $delay += 50;
                            if ($delay > 200) $delay = 0;
                        endwhile;
                    else :
                    ?>
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">&#128197;</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No hay eventos próximos</h3>
                        <p class="text-gray-600">Pronto anunciaremos nuevas actividades.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Paginación -->
                <?php if (have_posts()) : ?>
                <div class="mt-12 flex justify-center">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '&larr; Anterior',
                        'next_text' => 'Siguiente &rarr;',
                    ));
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Google Calendar Section -->
    <section class="py-16 bg-gray-50" id="calendario">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">&#128197; Calendario de Eventos</h2>
                    <p class="text-gray-600 text-lg">
                        Sincronizado con Google Calendar en tiempo real
                    </p>
                </div>

                <!-- Vista Desktop - Calendario Google Embebido -->
                <div class="hidden lg:block bg-white rounded-3xl p-4 md:p-8 shadow-xl" data-aos="zoom-in">
                    <div class="rounded-2xl overflow-hidden bg-white shadow-inner">
                        <iframe
                            src="<?php echo esc_url($calendar_embed); ?>"
                            style="border: 0"
                            width="100%"
                            height="600"
                            frameborder="0"
                            scrolling="no"
                            class="w-full">
                        </iframe>
                    </div>
                </div>

                <!-- Vista Móvil - Lista de Eventos -->
                <div class="lg:hidden space-y-4">
                    <?php
                    $eventos_mobile = new WP_Query(array(
                        'post_type' => 'cfc_evento',
                        'posts_per_page' => 10,
                        'orderby' => 'meta_value',
                        'meta_key' => 'fecha_evento',
                        'order' => 'ASC',
                        'meta_query' => array(
                            array(
                                'key' => 'fecha_evento',
                                'value' => date('Y-m-d'),
                                'compare' => '>=',
                                'type' => 'DATE'
                            )
                        )
                    ));

                    if ($eventos_mobile->have_posts()) :
                        while ($eventos_mobile->have_posts()) : $eventos_mobile->the_post();
                            $fecha = cfc_get_field('fecha_evento', get_the_ID(), '');
                            $hora = cfc_get_field('hora_evento', get_the_ID(), '');
                            $ubicacion = cfc_get_field('ubicacion_evento', get_the_ID(), '');
                            $tipo = get_the_terms(get_the_ID(), 'tipo_evento');

                            if ($fecha) {
                                $fecha_obj = strtotime($fecha);
                                $meses_es = array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
                                $dias_es = array('DOMINGO','LUNES','MARTES','MIÉRCOLES','JUEVES','VIERNES','SÁBADO');
                                $mes_num = (int)date('n', $fecha_obj) - 1;
                                $dia_num = (int)date('w', $fecha_obj);
                            }

                            // Color según tipo de evento
                            $gradient = 'from-primary to-secondary';
                            if ($tipo && !is_wp_error($tipo)) {
                                $tipo_nombre = strtolower($tipo[0]->name);
                                if (strpos($tipo_nombre, 'joven') !== false || strpos($tipo_nombre, 'juventud') !== false) {
                                    $gradient = 'from-purple-500 to-pink-500';
                                } elseif (strpos($tipo_nombre, 'niño') !== false || strpos($tipo_nombre, 'infantil') !== false) {
                                    $gradient = 'from-yellow-500 to-orange-500';
                                } elseif (strpos($tipo_nombre, 'oración') !== false) {
                                    $gradient = 'from-blue-500 to-cyan-500';
                                }
                            }
                    ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
                        <div class="bg-gradient-to-r <?php echo $gradient; ?> p-4">
                            <div class="flex items-center justify-between text-white">
                                <div>
                                    <?php if ($fecha) : ?>
                                    <p class="text-xs font-semibold opacity-90"><?php echo $dias_es[$dia_num]; ?></p>
                                    <p class="text-2xl font-bold"><?php echo date('d', $fecha_obj); ?> <?php echo $meses_es[$mes_num]; ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold"><?php echo $hora ? esc_html($hora) : '10:00 AM'; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2"><?php the_title(); ?></h4>
                            <?php if (has_excerpt()) : ?>
                            <p class="text-sm text-gray-600 mb-3"><?php echo cfc_excerpt(15); ?></p>
                            <?php endif; ?>
                            <?php if ($ubicacion) : ?>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <span><?php echo esc_html($ubicacion); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                    <div class="bg-white p-8 rounded-2xl text-center shadow-lg">
                        <div class="text-5xl mb-4">&#128197;</div>
                        <p class="text-gray-600">No hay eventos próximos programados.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Botón Suscribirse -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                    <a href="<?php echo esc_url($calendar_subscribe); ?>"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-full font-semibold hover:shadow-lg transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        Suscribirse al calendario
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-br from-primary via-secondary to-accent relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-yellow-200 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                ¿Tienes preguntas sobre algún evento?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Estamos aquí para ayudarte con toda la información que necesites
            </p>
            <?php $whatsapp = cfc_get_option('church_whatsapp', cfc_default('church_whatsapp')); ?>
            <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo rawurlencode('Hola, tengo una pregunta sobre los eventos'); ?>" target="_blank" class="inline-flex items-center gap-2 bg-white text-primary px-8 py-3 rounded-full font-bold hover:shadow-2xl hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                Contáctanos
            </a>
        </div>
    </section>

<?php get_footer(); ?>
