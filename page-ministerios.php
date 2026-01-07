<?php
/**
 * Template Name: Ministerios
 * Page template that displays the Ministerios archive content
 *
 * @package CFC_Familiar
 */

// Verificar que la página esté configurada
cfc_require_page_setup(array('ministerios_hero_titulo'));

// Include the archive template
include(get_template_directory() . '/archive-cfc_ministerio.php');
