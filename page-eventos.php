<?php
/**
 * Template Name: Eventos
 * Page template that displays the Eventos archive content
 *
 * @package CFC_Familiar
 */

// Verificar que la página esté configurada
cfc_require_page_setup(array('eventos_hero_titulo', 'eventos_calendar_embed'));

// Include the archive template
include(get_template_directory() . '/archive-cfc_evento.php');
