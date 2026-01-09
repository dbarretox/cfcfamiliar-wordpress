<?php
/**
 * Archive Template for Ministerios
 * NOTA: Los cards son SOLO visuales (imagen + título + descripción)
 * SIN botones, SIN links, SIN fechas
 *
 * @package CFC_Familiar
 */

// Si es acceso directo al archive (no desde page template), verificar que exista página configurada
if (is_post_type_archive('cfc_ministerio') && !is_page()) {
    // Buscar página con template Ministerios
    $ministerios_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-ministerios.php',
        'number' => 1
    ));

    // Si no hay página, mostrar guía de configuración
    if (empty($ministerios_page)) {
        $setup_page_name = 'Ministerios';
        $setup_template = 'page-ministerios.php';
        $setup_template_label = 'Ministerios';
        include(get_template_directory() . '/template-parts/setup-required.php');
        exit;
    }
}

get_header();

// Obtener valores del metabox si es página con template
$page_id = get_the_ID();
$hero_titulo = get_post_meta($page_id, 'ministerios_hero_titulo', true) ?: 'Nuestros Ministerios';
$hero_subtitulo = get_post_meta($page_id, 'ministerios_hero_subtitulo', true) ?: 'Descubre las diferentes formas en las que puedes servir y crecer en nuestra comunidad';
$hero_imagen = get_post_meta($page_id, 'ministerios_hero_imagen', true) ?: 'https://images.unsplash.com/photo-1609234656388-0ff363383899?w=1920&h=1080&fit=crop';
?>

    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo esc_url($hero_imagen); ?>"
                 alt="Ministerios"
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

    <!-- Grid de Ministerios -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    if (have_posts()) :
                        $delay = 0;
                        while (have_posts()) : the_post();
                    ?>
                    <!-- Ministerio Card -->
                    <?php
                    $whatsapp = get_post_meta(get_the_ID(), 'whatsapp_ministerio', true);
                    $lider = get_post_meta(get_the_ID(), 'lider_ministerio', true);
                    $horario = get_post_meta(get_the_ID(), 'horario_reunion', true);
                    ?>
                    <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <!-- Imagen con overlay -->
                        <div class="relative h-56 overflow-hidden">
                            <?php
                            if (has_post_thumbnail()) :
                                the_post_thumbnail('cfc-card', array('class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500'));
                            else :
                                $imagen_url = get_post_meta(get_the_ID(), 'imagen_url', true);
                                if (!$imagen_url) {
                                    $imagen_url = 'https://images.unsplash.com/photo-1609234656388-0ff363383899?w=600&h=400&fit=crop';
                                }
                            ?>
                                <img src="<?php echo esc_url($imagen_url); ?>"
                                     alt="<?php the_title_attribute(); ?>"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
                                <h3 class="text-xl font-bold text-white mb-1"><?php the_title(); ?></h3>
                                <?php if ($lider) : ?>
                                <p class="text-white/80 text-sm"><?php echo esc_html($lider); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Contenido -->
                        <div class="p-5">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                <?php echo cfc_excerpt(20); ?>
                            </p>
                            <?php if ($horario) : ?>
                            <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><?php echo esc_html($horario); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($whatsapp) : ?>
                            <!-- Botón WhatsApp -->
                            <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo rawurlencode('Hola, me gustaría saber más sobre el ministerio de ' . get_the_title()); ?>"
                               target="_blank"
                               class="flex items-center justify-center gap-2 w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-semibold transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347z"/>
                                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.75.75 0 00.917.918l4.462-1.494A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.487 0-4.807-.798-6.694-2.151l-.472-.33-3.089 1.034 1.034-3.089-.33-.472A9.96 9.96 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                                </svg>
                                Más Información
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                            $delay += 50;
                            if ($delay > 300) $delay = 0;
                        endwhile;
                    else :
                        // Default ministerios if none exist
                        $default_ministerios = array(
                            array(
                                'title' => 'Adolescentes y Jóvenes',
                                'desc' => 'Líderes juveniles comparten principios bíblicos para enfrentar los desafíos de la edad con una identidad saludable.',
                                'image' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Adulto Mayor',
                                'desc' => 'Para personas de 57 años en adelante, representan la experiencia y sabiduría, fortaleciendo a las nuevas generaciones.',
                                'image' => 'https://images.unsplash.com/photo-1454391304352-2bf4678b1a7a?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Alabanza y Adoración',
                                'desc' => 'Grupo de siervos comprometidos guiando la alabanza congregacional usando habilidades musicales y dones espirituales.',
                                'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Ministerio a las Parejas',
                                'desc' => 'Formar parejas que sean discípulos de Cristo con fundamentos sólidos en sus matrimonios basados en la Palabra.',
                                'image' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Ministerio a la Familia',
                                'desc' => 'Incorporar y construir familias saludables fundamentadas en la Palabra, garantizando el crecimiento de cada miembro.',
                                'image' => 'https://images.unsplash.com/photo-1606788075819-9574a6edfab3?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Ministerio a la Mujer',
                                'desc' => 'Ayudar de manera integral a encontrar el verdadero propósito, desarrollando una vida que agrade a Dios.',
                                'image' => 'https://images.unsplash.com/photo-1571442463800-1337d7af9d2f?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Ministerio a los Varones',
                                'desc' => 'Ayudar al varón en el crecimiento para que asuma su rol tanto en el hogar como en la sociedad.',
                                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Ministerio de Niños',
                                'desc' => 'Cada domingo enseñamos principios bíblicos a los niños de manera dinámica en la escuela dominical.',
                                'image' => 'https://images.unsplash.com/photo-1519491050282-cf00c82424b4?w=600&h=400&fit=crop'
                            ),
                            array(
                                'title' => 'Privados de Libertad',
                                'desc' => 'Visitamos centros de cumplimiento llevando la Palabra de Dios y esperanza a quienes más lo necesitan.',
                                'image' => 'https://images.unsplash.com/photo-1519834785169-98be25ec3f84?w=600&h=400&fit=crop'
                            ),
                        );

                        foreach ($default_ministerios as $index => $min) :
                    ?>
                    <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="<?php echo ($index % 6) * 50; ?>">
                        <div class="relative h-56 overflow-hidden">
                            <img src="<?php echo esc_url($min['image']); ?>"
                                 alt="<?php echo esc_attr($min['title']); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
                                <h3 class="text-xl font-bold text-white"><?php echo esc_html($min['title']); ?></h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo esc_html($min['desc']); ?></p>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <!-- CTA Section -->
                <div class="mt-20 bg-gradient-to-r from-primary via-secondary to-accent text-white rounded-3xl p-10 text-center" data-aos="zoom-in">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        ¿Listo para Servir?
                    </h2>
                    <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                        Cada persona tiene un lugar para servir. Descubre cómo puedes usar tus dones y talentos para impactar vidas.
                    </p>
                    <?php
                    $whatsapp = cfc_get_option('church_whatsapp', '');
                    if ($whatsapp) :
                    ?>
                    <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo rawurlencode('Hola, me gustaría información para servir en algún ministerio'); ?>"
                       target="_blank"
                       class="inline-flex items-center gap-2 bg-white text-primary px-8 py-3 rounded-full font-bold hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Quiero Servir
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
