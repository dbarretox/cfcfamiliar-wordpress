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
                    } elseif (current_user_can('edit_theme_options')) {
                        // Admin notice - menu not configured
                        ?>
                        <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="flex items-center gap-2 bg-amber-500/90 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>Configurar Menú Principal</span>
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
                if (has_nav_menu('mobile')) {
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'walker'         => new CFC_Mobile_Menu_Walker(),
                    ));
                } elseif (has_nav_menu('primary')) {
                    // Fallback to primary menu
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'walker'         => new CFC_Mobile_Menu_Walker(),
                    ));
                } elseif (current_user_can('edit_theme_options')) {
                    // Admin notice - menu not configured
                    ?>
                    <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="mobile-menu-item group flex items-center gap-4 bg-amber-500/80 text-white px-6 py-4 rounded-xl hover:bg-amber-500 transition-all duration-300">
                        <span class="text-2xl">&#9888;&#65039;</span>
                        <span class="text-lg font-semibold">Configurar Menú</span>
                    </a>
                    <p class="text-white/60 text-sm px-6 mt-2">Ve a Apariencia → Menús para crear el menú de navegación.</p>
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
