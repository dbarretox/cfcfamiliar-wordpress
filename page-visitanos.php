<?php
/**
 * Template Name: Visítanos
 *
 * @package CFC_Familiar
 */

get_header();

// Verificar que la página esté configurada
cfc_require_page_setup(array('visitanos_hero_titulo', 'horario_viernes_hora', 'horario_sabado_hora'), 'Visítanos', 'Visítanos');

$whatsapp = cfc_get_option('church_whatsapp', cfc_default('church_whatsapp'));
$google_maps = cfc_get_option('google_maps_url', cfc_default('google_maps_url'));
$address = cfc_get_option('church_address', cfc_default('church_address'));
$service_day = cfc_get_option('service_day', cfc_default('service_day'));
$service_time = cfc_get_option('service_time', cfc_default('service_time'));
?>

    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1606788075819-9574a6edfab3?w=1920&h=1080&fit=crop"
                 alt="Familia en la iglesia"
                 class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <div class="mb-6" data-aos="fade-down">
                <span class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white text-sm font-semibold">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-300"></span>
                    </span>
                    Te esperamos este domingo
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-aos="fade-up">
                Ven y <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">Visítanos</span>
            </h1>

            <p class="text-xl text-white/90 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Nos encantaría conocerte y que formes parte de nuestra familia
            </p>
        </div>
    </section>

    <!-- Sección Info General -->
    <section id="info" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">

                <!-- Información General en Card único -->
                <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 mb-12" data-aos="fade-up">
                    <div class="text-center mb-10">
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
                            Nuestros <span class="text-primary">Horarios</span>
                        </h2>
                        <p class="text-gray-600">Te esperamos en cualquiera de nuestras reuniones</p>
                    </div>

                    <?php
                    // Get dynamic values
                    $h_viernes_nombre = get_post_meta(get_the_ID(), 'horario_viernes_nombre', true) ?: 'Viernes';
                    $h_viernes_hora = get_post_meta(get_the_ID(), 'horario_viernes_hora', true) ?: '7:00 PM';
                    $h_viernes_desc = get_post_meta(get_the_ID(), 'horario_viernes_desc', true) ?: 'Estudio Bíblico';
                    $h_sabado_nombre = get_post_meta(get_the_ID(), 'horario_sabado_nombre', true) ?: 'Sábado';
                    $h_sabado_hora = get_post_meta(get_the_ID(), 'horario_sabado_hora', true) ?: '4:00 PM';
                    $h_sabado_desc = get_post_meta(get_the_ID(), 'horario_sabado_desc', true) ?: 'Reunión de Jóvenes';
                    ?>
                    <!-- Horarios en grid -->
                    <div class="grid md:grid-cols-3 gap-8 mb-10">
                        <!-- Domingo -->
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl">&#9728;&#65039;</span>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2"><?php echo esc_html($service_day); ?></h3>
                            <p class="text-3xl font-black text-primary mb-1"><?php echo esc_html($service_time); ?></p>
                            <p class="text-sm text-gray-600">Servicio Principal</p>
                        </div>

                        <!-- Viernes -->
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl">&#128214;</span>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2"><?php echo esc_html($h_viernes_nombre); ?></h3>
                            <p class="text-3xl font-black text-purple-600 mb-1"><?php echo esc_html($h_viernes_hora); ?></p>
                            <p class="text-sm text-gray-600"><?php echo esc_html($h_viernes_desc); ?></p>
                        </div>

                        <!-- Sábado -->
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl">&#128591;</span>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2"><?php echo esc_html($h_sabado_nombre); ?></h3>
                            <p class="text-3xl font-black text-green-600 mb-1"><?php echo esc_html($h_sabado_hora); ?></p>
                            <p class="text-sm text-gray-600"><?php echo esc_html($h_sabado_desc); ?></p>
                        </div>
                    </div>

                    <hr class="border-gray-200 my-10">

                    <!-- Información de ubicación -->
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">&#128205; Nuestra Ubicación</h3>
                        <div class="max-w-md mx-auto space-y-2 text-gray-700">
                            <p class="font-semibold text-lg"><?php echo esc_html(cfc_get_option('church_name', cfc_default('church_name'))); ?></p>
                            <p><?php echo esc_html($address); ?></p>
                        </div>

                        <!-- Botones de navegación -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                            <a href="<?php echo esc_url($google_maps); ?>"
                               target="_blank"
                               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary to-secondary text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg transition-all">
                               <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Abrir en Google Maps
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Galería simple -->
                <div class="mb-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
                            Nuestra <span class="text-primary">Comunidad</span>
                        </h2>
                        <p class="text-gray-600">Momentos especiales en nuestra iglesia</p>
                    </div>

                    <?php
                    $galeria_1 = get_post_meta(get_the_ID(), 'galeria_1', true) ?: 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=400&h=300&fit=crop';
                    $galeria_2 = get_post_meta(get_the_ID(), 'galeria_2', true) ?: 'https://images.unsplash.com/photo-1523301343968-6a6ebf63c672?w=400&h=300&fit=crop';
                    $galeria_3 = get_post_meta(get_the_ID(), 'galeria_3', true) ?: 'https://images.unsplash.com/photo-1509021436665-8f07dbf5bf1d?w=400&h=300&fit=crop';
                    ?>
                    <div class="grid md:grid-cols-3 gap-4">
                        <img src="<?php echo esc_url($galeria_1); ?>" alt="Comunidad" class="w-full h-48 object-cover rounded-2xl shadow-lg">
                        <img src="<?php echo esc_url($galeria_2); ?>" alt="Jóvenes" class="w-full h-48 object-cover rounded-2xl shadow-lg">
                        <img src="<?php echo esc_url($galeria_3); ?>" alt="Alabanza" class="w-full h-48 object-cover rounded-2xl shadow-lg">
                    </div>
                </div>

                <!-- CTA Final con WhatsApp -->
                <div class="bg-gradient-to-r from-primary via-secondary to-accent text-white rounded-3xl p-10 text-center" data-aos="zoom-in">
                    <h3 class="text-2xl md:text-3xl font-bold mb-4">¿Tienes preguntas?</h3>
                    <p class="mb-6 text-white/90">Estamos aquí para ayudarte. Contáctanos por WhatsApp</p>
                    <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo rawurlencode('Hola, me gustaría visitar la iglesia este domingo'); ?>"
                       target="_blank"
                       class="inline-flex items-center gap-2 bg-white text-primary px-8 py-3 rounded-full font-bold hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Escribir por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
