<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class('font-sans bg-white text-gray-800 antialiased'); ?>>
<?php wp_body_open(); ?>

    <!-- Header -->
    <header id="header" class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center group">
                    <div class="h-14 lg:h-16 relative group-hover:scale-105 transition-all duration-300">
                        <!-- Logo completo blanco para header transparente -->
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-cfc-full-white.svg'); ?>"
                             alt="<?php echo esc_attr(cfc_get_option('church_name', cfc_default('church_name'))); ?>"
                             class="h-full w-auto object-contain logo-white transition-opacity duration-300">
                        <!-- Logo completo azul para header con fondo blanco -->
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-cfc-full-blue.svg'); ?>"
                             alt="<?php echo esc_attr(cfc_get_option('church_name', cfc_default('church_name'))); ?>"
                             class="absolute top-0 left-0 h-full w-auto object-contain logo-blue transition-opacity duration-300">
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center space-x-1">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                            'walker'         => new CFC_Desktop_Menu_Walker(),
                        ));
                    } else {
                        // Fallback if no menu is assigned
                        ?>
                        <a href="<?php echo esc_url(home_url('/visitanos')); ?>" class="nav-link px-4 py-2 rounded-lg font-medium transition-all">Visítanos</a>
                        <a href="<?php echo esc_url(home_url('/quienes-somos')); ?>" class="nav-link px-4 py-2 rounded-lg font-medium transition-all">Quiénes somos</a>
                        <a href="<?php echo esc_url(get_post_type_archive_link('cfc_ministerio')); ?>" class="nav-link px-4 py-2 rounded-lg font-medium transition-all">Ministerios</a>
                        <a href="<?php echo esc_url(get_post_type_archive_link('cfc_reflexion')); ?>" class="nav-link px-4 py-2 rounded-lg font-medium transition-all">Reflexiones</a>
                        <a href="<?php echo esc_url(get_post_type_archive_link('cfc_evento')); ?>" class="nav-link px-4 py-2 rounded-lg font-medium transition-all">Eventos</a>
                        <a href="<?php echo esc_url(home_url('/dar')); ?>" class="ml-2 bg-gradient-to-r from-primary to-secondary text-white px-6 py-2.5 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <span>&#128157;</span>
                            <span>Dar</span>
                        </a>
                        <?php
                    }
                    ?>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors" aria-label="Abrir menú">
                    <svg class="w-6 h-6 header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-gradient-to-br from-primary via-secondary to-accent z-40 transform translate-x-full transition-transform duration-500 ease-in-out md:hidden">
        <!-- Overlay decorativo -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>

        <div class="relative h-full flex flex-col p-6">
            <!-- Header del menú -->
            <div class="flex items-center justify-between mb-12">
                <div class="flex items-center">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-cfc-full-white.svg'); ?>"
                         alt="Centro Familiar Cristiano"
                         class="h-10 w-auto object-contain">
                </div>
                <button id="close-menu" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors" aria-label="Cerrar menú">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu items -->
            <nav class="flex-1 space-y-2">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'walker'         => new CFC_Mobile_Menu_Walker(),
                    ));
                } else {
                    // Fallback if no menu is assigned
                    ?>
                    <a href="<?php echo esc_url(home_url('/visitanos')); ?>" class="mobile-menu-item group flex items-center gap-4 text-white px-6 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <span class="text-2xl opacity-80 group-hover:opacity-100 transition-opacity">&#128205;</span>
                        <span class="text-xl font-semibold">Visítanos</span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/quienes-somos')); ?>" class="mobile-menu-item group flex items-center gap-4 text-white px-6 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <span class="text-2xl opacity-80 group-hover:opacity-100 transition-opacity">&#128101;</span>
                        <span class="text-xl font-semibold">Quiénes Somos</span>
                    </a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('cfc_ministerio')); ?>" class="mobile-menu-item group flex items-center gap-4 text-white px-6 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <span class="text-2xl opacity-80 group-hover:opacity-100 transition-opacity">&#128591;</span>
                        <span class="text-xl font-semibold">Ministerios</span>
                    </a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('cfc_reflexion')); ?>" class="mobile-menu-item group flex items-center gap-4 text-white px-6 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <span class="text-2xl opacity-80 group-hover:opacity-100 transition-opacity">&#128214;</span>
                        <span class="text-xl font-semibold">Reflexiones</span>
                    </a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('cfc_evento')); ?>" class="mobile-menu-item group flex items-center gap-4 text-white px-6 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <span class="text-2xl opacity-80 group-hover:opacity-100 transition-opacity">&#127881;</span>
                        <span class="text-xl font-semibold">Eventos</span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/dar')); ?>" class="mobile-menu-item group flex items-center gap-4 bg-white/20 backdrop-blur-md text-white px-6 py-4 rounded-xl hover:bg-white/30 transition-all duration-300 mt-4">
                        <span class="text-2xl">&#128157;</span>
                        <span class="text-xl font-bold">Dar</span>
                    </a>
                    <?php
                }
                ?>
            </nav>

            <!-- Footer del menú -->
            <div class="pt-6 border-t border-white/20">
                <p class="text-white/60 text-sm text-center">
                    &copy; <?php echo date('Y'); ?> <?php echo esc_html(cfc_get_option('church_name', cfc_default('church_name'))); ?>
                </p>
            </div>
        </div>
    </div>
