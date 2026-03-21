/**
 * CFC Familiar - Main JavaScript
 * Centro Familiar Cristiano WordPress Theme
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initHeader();
        initMobileMenu();
        initSmoothScroll();
    });

    /**
     * Header scroll effect
     */
    function initHeader() {
        const header = document.getElementById('header');
        if (!header) return;

        let lastScroll = 0;

        function handleScroll() {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            lastScroll = currentScroll;
        }

        // Initial check
        handleScroll();

        // Throttled scroll handler
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    /**
     * Mobile Menu
     */
    function initMobileMenu() {
        const menuButton = document.getElementById('mobile-menu-button');
        const closeButton = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!menuButton || !mobileMenu) return;

        let scrollY = 0;

        function openMenu() {
            scrollY = window.pageYOffset;
            mobileMenu.style.transform = 'translateX(0)';
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + scrollY + 'px';
            document.body.style.width = '100%';
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            mobileMenu.style.transform = 'translateX(100%)';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.overflow = '';
            window.scrollTo(0, scrollY);
        }

        menuButton.addEventListener('click', openMenu);

        if (closeButton) {
            closeButton.addEventListener('click', closeMenu);
        }

        // Close on link click
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.style.transform === 'translateX(0)') {
                closeMenu();
            }
        });
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href === '#') return;

                const target = document.querySelector(href);
                if (!target) return;

                e.preventDefault();

                const headerHeight = document.getElementById('header')?.offsetHeight || 80;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            });
        });
    }

})();
