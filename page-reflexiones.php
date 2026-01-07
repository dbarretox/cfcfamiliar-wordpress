<?php
/**
 * Template Name: Reflexiones
 * Page template that displays the Reflexiones archive content
 *
 * @package CFC_Familiar
 */

// Verificar que la página esté configurada
cfc_require_page_setup(array('reflexiones_hero_titulo'));

// Include the archive template
include(get_template_directory() . '/archive-cfc_reflexion.php');
