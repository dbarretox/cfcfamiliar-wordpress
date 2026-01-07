<?php
/**
 * GitHub Theme Updater
 *
 * Permite actualizar el tema desde GitHub Releases
 *
 * @package CFC_Familiar
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CFC_GitHub_Updater {

    private $slug;
    private $theme_data;
    private $github_username;
    private $github_repo;
    private $github_response;
    private $authorize_token;

    /**
     * Constructor
     */
    public function __construct() {
        $this->slug = 'cfcfamiliar';
        $this->github_username = 'dbarretox';
        $this->github_repo = 'cfcfamiliar-wordpress';
        $this->authorize_token = ''; // Dejar vacío para repos públicos

        $this->theme_data = wp_get_theme($this->slug);

        // Hooks para el sistema de actualizaciones
        add_filter('pre_set_site_transient_update_themes', array($this, 'check_update'));
        add_filter('themes_api', array($this, 'theme_info'), 20, 3);
        add_filter('upgrader_source_selection', array($this, 'fix_folder_name'), 10, 4);

        // Limpiar caché cuando sea necesario
        add_action('upgrader_process_complete', array($this, 'clear_cache'), 10, 2);
    }

    /**
     * Obtener información del release más reciente de GitHub
     */
    private function get_github_release_info() {
        if (!empty($this->github_response)) {
            return $this->github_response;
        }

        // URL de la API de GitHub para obtener el último release
        $url = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            $this->github_username,
            $this->github_repo
        );

        $args = array(
            'timeout' => 10,
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url'),
            ),
        );

        // Agregar token si existe (para repos privados)
        if (!empty($this->authorize_token)) {
            $args['headers']['Authorization'] = 'token ' . $this->authorize_token;
        }

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            return false;
        }

        $this->github_response = json_decode(wp_remote_retrieve_body($response));

        return $this->github_response;
    }

    /**
     * Verificar si hay actualizaciones disponibles
     */
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $release = $this->get_github_release_info();

        if (false === $release || !isset($release->tag_name)) {
            return $transient;
        }

        // Obtener versión del release (remover 'v' si existe)
        $github_version = ltrim($release->tag_name, 'v');
        $current_version = $this->theme_data->get('Version');

        // Comparar versiones
        if (version_compare($github_version, $current_version, '>')) {

            // Buscar el archivo ZIP en los assets del release
            $download_url = $this->get_download_url($release);

            if ($download_url) {
                $transient->response[$this->slug] = array(
                    'theme'       => $this->slug,
                    'new_version' => $github_version,
                    'url'         => $release->html_url,
                    'package'     => $download_url,
                );
            }
        }

        return $transient;
    }

    /**
     * Obtener URL de descarga del ZIP
     */
    private function get_download_url($release) {
        // Primero buscar en assets (archivo .zip subido manualmente al release)
        if (!empty($release->assets)) {
            foreach ($release->assets as $asset) {
                if (strpos($asset->name, '.zip') !== false) {
                    return $asset->browser_download_url;
                }
            }
        }

        // Si no hay asset, usar el zipball generado por GitHub
        if (!empty($release->zipball_url)) {
            return $release->zipball_url;
        }

        return false;
    }

    /**
     * Mostrar información del tema en el popup de detalles
     */
    public function theme_info($result, $action, $args) {
        if ('theme_information' !== $action) {
            return $result;
        }

        if ($this->slug !== $args->slug) {
            return $result;
        }

        $release = $this->get_github_release_info();

        if (false === $release) {
            return $result;
        }

        $github_version = ltrim($release->tag_name, 'v');

        // Screenshot URL from GitHub raw content
        $screenshot_url = sprintf(
            'https://raw.githubusercontent.com/%s/%s/main/screenshot.png',
            $this->github_username,
            $this->github_repo
        );

        $result = (object) array(
            'name'           => $this->theme_data->get('Name') ?: 'CFC Familiar',
            'slug'           => $this->slug,
            'version'        => $github_version,
            'author'         => $this->theme_data->get('Author') ?: 'DBarreto',
            'author_profile' => $this->theme_data->get('AuthorURI') ?: 'https://dbarreto.net',
            'homepage'       => $this->theme_data->get('ThemeURI') ?: 'https://cfcfamiliar.com',
            'requires'       => $this->theme_data->get('RequiresWP') ?: '6.0',
            'requires_php'   => $this->theme_data->get('RequiresPHP') ?: '7.4',
            'downloaded'     => 0,
            'last_updated'   => $release->published_at,
            'screenshot_url' => $screenshot_url,
            'sections'       => array(
                'description' => $this->theme_data->get('Description') ?: 'Tema personalizado para Centro Familiar Cristiano.',
                'changelog'   => $this->parse_changelog($release->body),
            ),
            'download_link'  => $this->get_download_url($release),
        );

        return $result;
    }

    /**
     * Parsear el changelog del release
     */
    private function parse_changelog($body) {
        if (empty($body)) {
            return '<p>No hay notas de esta versión.</p>';
        }

        // Convertir Markdown básico a HTML
        $changelog = esc_html($body);
        $changelog = nl2br($changelog);
        $changelog = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $changelog);
        $changelog = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $changelog);
        $changelog = preg_replace('/^- (.+)$/m', '<li>$1</li>', $changelog);
        $changelog = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $changelog);

        return $changelog;
    }

    /**
     * Corregir nombre de carpeta después de descomprimir
     * GitHub usa formato: usuario-repo-hash, necesitamos: cfcfamiliar
     */
    public function fix_folder_name($source, $remote_source, $upgrader, $hook_extra) {
        global $wp_filesystem;

        // Verificar que sea nuestro tema
        if (!isset($hook_extra['theme']) || $hook_extra['theme'] !== $this->slug) {
            return $source;
        }

        // Nuevo nombre de carpeta correcto
        $corrected_source = trailingslashit($remote_source) . $this->slug;

        // Renombrar la carpeta
        if ($wp_filesystem->move($source, $corrected_source)) {
            return $corrected_source;
        }

        return $source;
    }

    /**
     * Limpiar caché después de actualizar
     */
    public function clear_cache($upgrader, $options) {
        if ('update' === $options['action'] && 'theme' === $options['type']) {
            if (isset($options['themes']) && in_array($this->slug, $options['themes'])) {
                $this->github_response = null;
                delete_transient('cfc_github_release_' . $this->slug);
            }
        }
    }
}

// Inicializar el updater
function cfc_init_github_updater() {
    if (is_admin()) {
        new CFC_GitHub_Updater();
    }
}
add_action('init', 'cfc_init_github_updater');
