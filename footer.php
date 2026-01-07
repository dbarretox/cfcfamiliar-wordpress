    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-6">
            <!-- Grid de 4 columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <!-- Columna 1: Logo y descripción -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-cfc-white.svg'); ?>"
                                 alt="CFC"
                                 class="w-full h-full object-contain">
                        </div>
                        <span class="font-bold text-xl">CFC</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-6 leading-relaxed">
                        <?php echo esc_html(cfc_get_option('footer_description', cfc_default('footer_description'))); ?>
                    </p>
                    <!-- Redes Sociales -->
                    <div class="flex items-center gap-4">
                        <?php $facebook = cfc_get_option('facebook_url', cfc_default('facebook_url')); ?>
                        <?php if ($facebook && $facebook !== '#') : ?>
                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-primary transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php $instagram = cfc_get_option('instagram_url', cfc_default('instagram_url')); ?>
                        <?php if ($instagram && $instagram !== '#') : ?>
                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-gradient-to-br hover:from-purple-500 hover:to-pink-500 transition-all" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php $youtube = cfc_get_option('youtube_channel', cfc_default('youtube_channel')); ?>
                        <?php if ($youtube && $youtube !== '#') : ?>
                        <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-red-600 transition-colors" aria-label="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Columna 2: Enlaces rápidos -->
                <div>
                    <h3 class="font-bold text-lg mb-6">Enlaces Rápidos</h3>
                    <nav class="space-y-3">
                        <?php
                        if (has_nav_menu('footer')) {
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'container'      => false,
                                'items_wrap'     => '%3$s',
                                'walker'         => new CFC_Footer_Menu_Walker(),
                            ));
                        } elseif (has_nav_menu('primary')) {
                            // Fallback to primary menu
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'container'      => false,
                                'items_wrap'     => '%3$s',
                                'walker'         => new CFC_Footer_Menu_Walker(),
                            ));
                        } elseif (current_user_can('edit_theme_options')) {
                            ?>
                            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="block text-amber-400 hover:text-amber-300 transition-colors">
                                &#9888;&#65039; Configurar Menú
                            </a>
                            <?php
                        }
                        ?>
                    </nav>
                </div>

                <!-- Columna 3: Horarios -->
                <div>
                    <h3 class="font-bold text-lg mb-6">Horarios de Servicio</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-lg">&#9728;&#65039;</span>
                            </div>
                            <div>
                                <p class="font-semibold text-white"><?php echo esc_html(cfc_get_option('service_day', cfc_default('service_day'))); ?></p>
                                <p class="text-gray-400 text-sm"><?php echo esc_html(cfc_get_option('service_time', cfc_default('service_time'))); ?></p>
                                <p class="text-gray-500 text-xs">Servicio Principal</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna 4: Contacto -->
                <div>
                    <h3 class="font-bold text-lg mb-6">Contacto</h3>
                    <div class="space-y-4">
                        <!-- Dirección -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm"><?php echo esc_html(cfc_get_option('church_address', cfc_default('church_address'))); ?></p>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <div>
                                <a href="tel:<?php echo esc_attr(cfc_get_option('church_phone', cfc_default('church_phone'))); ?>" class="text-gray-400 text-sm hover:text-white transition-colors">
                                    <?php echo esc_html(cfc_get_option('church_phone', cfc_default('church_phone'))); ?>
                                </a>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div>
                                <a href="mailto:<?php echo esc_attr(cfc_get_option('church_email', cfc_default('church_email'))); ?>" class="text-gray-400 text-sm hover:text-white transition-colors">
                                    <?php echo esc_html(cfc_get_option('church_email', cfc_default('church_email'))); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-gray-500 text-sm text-center md:text-left">
                        &copy; <?php echo date('Y'); ?> <?php echo esc_html(cfc_get_option('church_name', cfc_default('church_name'))); ?>. Todos los derechos reservados.
                    </p>
                    <div class="flex items-center gap-6 text-sm text-gray-500">
                        <a href="<?php echo esc_url(home_url('/privacidad')); ?>" class="hover:text-white transition-colors">Privacidad</a>
                        <a href="<?php echo esc_url(home_url('/terminos')); ?>" class="hover:text-white transition-colors">Términos</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
