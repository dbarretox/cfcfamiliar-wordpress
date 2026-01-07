<?php
/**
 * Template Name: Quiénes Somos
 *
 * @package CFC_Familiar
 */

get_header();

// Verificar que la página esté configurada
cfc_require_page_setup(array('quienes_hero_titulo', 'mision', 'vision'), 'Quiénes Somos', 'Quiénes Somos');

// Colores para los badges del equipo
$colores = array(
    'primary' => array('bg' => 'from-primary to-secondary', 'text' => 'text-primary'),
    'purple'  => array('bg' => 'from-purple-500 to-pink-500', 'text' => 'text-purple-600'),
    'blue'    => array('bg' => 'from-blue-500 to-cyan-500', 'text' => 'text-blue-600'),
    'orange'  => array('bg' => 'from-yellow-500 to-orange-500', 'text' => 'text-orange-600'),
    'green'   => array('bg' => 'from-green-500 to-teal-500', 'text' => 'text-green-600'),
    'indigo'  => array('bg' => 'from-indigo-500 to-purple-500', 'text' => 'text-indigo-600'),
    'pink'    => array('bg' => 'from-pink-500 to-rose-500', 'text' => 'text-pink-600'),
);
?>

    <?php
    // Hero section data from metabox
    $hero_titulo = get_post_meta(get_the_ID(), 'quienes_hero_titulo', true) ?: 'Quiénes Somos';
    $hero_subtitulo = get_post_meta(get_the_ID(), 'quienes_hero_subtitulo', true) ?: 'Una iglesia con visión de reino, enfocada en la predicación y enseñanza de la palabra';
    $hero_imagen = get_post_meta(get_the_ID(), 'quienes_hero_imagen', true) ?: 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=1920&h=1080&fit=crop';
    ?>
    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo esc_url($hero_imagen); ?>"
                 alt="Comunidad"
                 class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-aos="fade-up">
                <?php
                $words = explode(' ', $hero_titulo);
                if (count($words) > 1) {
                    $last_word = array_pop($words);
                    echo esc_html(implode(' ', $words)) . ' <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">' . esc_html($last_word) . '</span>';
                } else {
                    echo esc_html($hero_titulo);
                }
                ?>
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                <?php echo esc_html($hero_subtitulo); ?>
            </p>
        </div>
    </section>

    <!-- Misión y Visión -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <?php
                $mision = get_post_meta(get_the_ID(), 'mision', true) ?: 'Somos una Iglesia que ama, glorifica y sirve a Dios como una sola familia.';
                $vision = get_post_meta(get_the_ID(), 'vision', true) ?: 'Queremos ser una iglesia comprometida en formar discípulos de Jesús para ganar a la comunidad y al mundo.';
                ?>
                <!-- Misión -->
                <div class="text-center" data-aos="fade-right">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Misión</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        <?php echo esc_html($mision); ?>
                    </p>
                </div>

                <!-- Visión -->
                <div class="text-center" data-aos="fade-left">
                    <div class="w-24 h-24 bg-gradient-to-br from-secondary to-accent rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Visión</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        <?php echo esc_html($vision); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Valores Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Nuestros <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Valores</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Los pilares que guían nuestra vida y ministerio
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Fe -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">&#10013;&#65039;</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Fe</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sabiendo que es imposible agradar a Dios sin ella, nuestra obra es por Fe y no por esfuerzo humano.
                    </p>
                </div>

                <!-- Amor -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gradient-to-br from-rose to-pink-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">&#10084;&#65039;</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Amor</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Es el vínculo perfecto que nos une, entendiendo que el amor todo lo espera, todo lo soporta, sufre y cree.
                    </p>
                </div>

                <!-- Familia -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-accent to-secondary rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">&#128106;</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Familia</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Somos una familia donde Dios es nuestro padre y Jesús nuestro amigo y señor. Toda situación que afecta a uno de sus miembros, toca a la familia.
                    </p>
                </div>

                <!-- Palabra y Espíritu -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">&#128214;</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Palabra y Espíritu</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Las Sagradas Escrituras y la autoridad del Espíritu Santo son la luz que alumbra el camino y la guía para realizar la obra.
                    </p>
                </div>

                <!-- Compromiso -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">&#129309;</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Compromiso</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Estamos comprometidos nosotros y nuestros recursos a la extensión del reino, honrando nuestros compromisos y alianzas.
                    </p>
                </div>

                <!-- Excelencia -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">&#11088;</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Excelencia</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Buscamos hacer todo como para el Señor, con excelencia y dedicación en cada área de servicio.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Equipo Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Quién es <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Quién</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Un equipo apasionado sirviendo juntos a nuestra comunidad
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <?php
                $equipo = new WP_Query(array(
                    'post_type' => 'cfc_equipo',
                    'posts_per_page' => -1,
                    'meta_key' => 'orden',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                ));

                if ($equipo->have_posts()) :
                    $delay = 0;
                    while ($equipo->have_posts()) : $equipo->the_post();
                        $cargo = get_post_meta(get_the_ID(), 'cargo', true);
                        $icono = get_post_meta(get_the_ID(), 'icono', true) ?: '&#10013;&#65039;';
                        $color = get_post_meta(get_the_ID(), 'color', true) ?: 'primary';
                        $color_classes = isset($colores[$color]) ? $colores[$color] : $colores['primary'];
                ?>
                <div class="group" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500">
                        <!-- Imagen -->
                        <div class="aspect-[4/5] overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('cfc-card', array('class' => 'w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700')); ?>
                            <?php else : ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <span class="text-6xl opacity-30">&#128100;</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <div class="p-6 bg-gradient-to-br from-gray-50 to-white">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br <?php echo esc_attr($color_classes['bg']); ?> rounded-xl flex items-center justify-center shadow-md">
                                    <span class="text-xl"><?php echo $icono; ?></span>
                                </div>
                                <div class="w-16 h-[2px] bg-gradient-to-r from-primary/30 to-transparent"></div>
                            </div>
                            <h4 class="font-bold text-gray-900 text-xl mb-1"><?php the_title(); ?></h4>
                            <p class="text-sm <?php echo esc_attr($color_classes['text']); ?> font-semibold tracking-wider uppercase"><?php echo esc_html($cargo); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                        $delay += 50;
                        if ($delay > 250) $delay = 0;
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                <!-- Mensaje si no hay equipo -->
                <div class="col-span-full text-center py-12">
                    <div class="text-6xl mb-4">&#128101;</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Equipo por agregar</h3>
                    <p class="text-gray-600">Ve al admin → Equipo → Agregar Nuevo para agregar miembros del equipo.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- De Qué Somos Parte Section -->
    <section class="py-20 bg-gradient-to-br from-primary/5 to-secondary/5">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        De Qué Somos <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Parte</span>
                    </h2>
                </div>

                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden" data-aos="zoom-in">
                    <div class="grid md:grid-cols-2 items-center">
                        <div class="p-10">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Iglesia Cuadrangular</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed mb-6">
                                Somos parte de la Iglesia del Evangelio Cuadrangular de Panamá, un movimiento global comprometido con la predicación del evangelio completo de Jesucristo.
                            </p>
                            <p class="text-gray-600 leading-relaxed mb-8">
                                Unidos con iglesias alrededor del mundo, compartimos la visión de alcanzar a las naciones con el mensaje transformador del amor de Dios.
                            </p>
                        </div>
                        <div class="relative h-80 md:h-full min-h-[320px]">
                            <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=600&h=400&fit=crop"
                                 alt="Iglesia Cuadrangular"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/20 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary via-secondary to-accent text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">
                ¿Quieres Conocernos?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Te invitamos a visitarnos y ser parte de nuestra familia. ¡Nos encantaría conocerte!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(home_url('/visitanos')); ?>" class="bg-white text-primary px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all">
                    Visítanos
                </a>
                <?php $whatsapp = cfc_get_option('church_whatsapp', cfc_default('church_whatsapp')); ?>
                <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" class="bg-white/10 backdrop-blur-sm border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white/20 transition-all">
                    Contáctanos
                </a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
