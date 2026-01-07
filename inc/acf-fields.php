<?php
/**
 * ACF Fields Documentation
 *
 * Este archivo documenta los campos ACF necesarios para el tema CFC Familiar.
 * Para usar estas funciones, instala el plugin Advanced Custom Fields PRO.
 *
 * @package CFC_Familiar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================================================
 * INSTRUCCIONES PARA CREAR CAMPOS ACF
 * ============================================================================
 *
 * Después de instalar ACF PRO, crea los siguientes grupos de campos:
 *
 * ----------------------------------------------------------------------------
 * 1. OPCIONES DEL TEMA (Options Page: CFC Opciones)
 * ----------------------------------------------------------------------------
 *
 * Grupo: "Información de la Iglesia"
 * - church_name (Text) - Nombre de la iglesia
 * - church_address (Text) - Dirección
 * - church_phone (Text) - Teléfono
 * - church_email (Email) - Correo electrónico
 * - church_whatsapp (Text) - Número de WhatsApp (sin +)
 * - google_maps_url (URL) - Enlace a Google Maps
 *
 * Grupo: "Horarios de Servicio"
 * - service_day (Text) - Día del servicio principal (ej: "Domingo")
 * - service_time (Text) - Hora del servicio (ej: "10:00 AM")
 *
 * Grupo: "Redes Sociales"
 * - facebook_url (URL) - URL de Facebook
 * - instagram_url (URL) - URL de Instagram
 * - youtube_channel (URL) - URL del canal de YouTube
 * - youtube_live_url (URL) - URL de transmisión en vivo
 *
 * Grupo: "Hero Homepage"
 * - hero_video_url (URL) - URL del video de fondo
 * - hero_image (Image) - Imagen de fondo alternativa
 *
 * ----------------------------------------------------------------------------
 * 2. CAMPOS PARA EVENTOS (Post Type: cfc_evento)
 * ----------------------------------------------------------------------------
 *
 * Grupo: "Detalles del Evento"
 * - fecha_evento (Date Picker) - Fecha del evento
 * - hora_evento (Text) - Hora del evento (ej: "7:00 PM")
 * - ubicacion_evento (Text) - Lugar del evento
 * - registro_url (URL) - URL de registro/inscripción
 * - evento_destacado (True/False) - ¿Es evento destacado?
 *
 * ----------------------------------------------------------------------------
 * 3. CAMPOS PARA REFLEXIONES (Post Type: cfc_reflexion)
 * ----------------------------------------------------------------------------
 *
 * Grupo: "Detalles de la Reflexión"
 * - tipo_reflexion (Select) - Tipo: video, podcast, articulo, devocional
 * - video_url (URL) - URL del video de YouTube
 * - duracion (Text) - Duración (ej: "45 min", "5 min lectura")
 *
 * ----------------------------------------------------------------------------
 * 4. CAMPOS PARA MINISTERIOS (Post Type: cfc_ministerio)
 * ----------------------------------------------------------------------------
 *
 * Grupo: "Detalles del Ministerio"
 * - lider_ministerio (Text) - Nombre del líder
 * - whatsapp_contacto (Text) - WhatsApp de contacto
 * - horario_reunion (Text) - Día y hora de reunión
 *
 * ============================================================================
 */

/**
 * Ejemplo de cómo usar los campos en los templates:
 *
 * // En Options Pages:
 * $church_name = cfc_get_option('church_name', 'Centro Familiar Cristiano');
 *
 * // En posts individuales:
 * $fecha = cfc_get_field('fecha_evento', get_the_ID(), '');
 *
 * // Las funciones cfc_get_field() y cfc_get_option() ya incluyen fallbacks
 * // para funcionar aunque ACF no esté instalado.
 */

/**
 * Valores por defecto cuando ACF no está instalado
 * Estos valores se usan automáticamente si no hay ACF
 */
function cfc_acf_defaults() {
    return array(
        // Información de la iglesia
        'church_name'       => 'Centro Familiar Cristiano',
        'church_address'    => 'Av. Principal #123, Ciudad de Panamá',
        'church_phone'      => '+507 6999-3772',
        'church_email'      => 'info@cfcfamiliar.com',
        'church_whatsapp'   => '50769993772',
        'google_maps_url'   => 'https://maps.google.com',

        // Horarios
        'service_day'       => 'Domingo',
        'service_time'      => '10:00 AM',

        // Redes sociales
        'youtube_channel'   => 'https://youtube.com/@cfcfamiliar',
        'facebook_url'      => 'https://facebook.com/cfcfamiliar',
        'instagram_url'     => 'https://instagram.com/cfcfamiliar',
        'youtube_live_url'  => 'https://youtube.com/@cfcfamiliar/live',

        // Hero
        'hero_video_url'    => '',
        'hero_image'        => '',
    );
}

/**
 * JSON para importar en ACF (opcional)
 *
 * Si prefieres importar los campos automáticamente, puedes crear un archivo
 * acf-json/group_cfc_options.json en la carpeta del tema con la configuración.
 *
 * Para habilitar la sincronización local de ACF:
 * 1. Crea la carpeta: /wordpress/acf-json/
 * 2. ACF guardará automáticamente los grupos de campos como JSON
 */
