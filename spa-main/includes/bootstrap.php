<?php
/**
 * SPA System MAIN  Bootstrap
 * Inicializácia pluginu a načítanie závislostí
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definovanie konštánt pluginu
define('SPA_VERSION', '1.0.0');
define('SPA_PLUGIN_DIR', plugin_dir_path(dirname(__FILE__)));
define('SPA_PLUGIN_URL', plugin_dir_url(dirname(__FILE__)));
define('SPA_CONFIG_DIR', SPA_PLUGIN_DIR . 'spa-config/');

/**
 * Načítanie konfigurácie field mappingu
 * Vracia array s mapovaním logických názvov na GF input_ID
 */
function spa_load_field_config() {
    $config_file = SPA_CONFIG_DIR . 'fields.php';
    
    if (!file_exists($config_file)) {
        return [];
    }
    
    return include $config_file;
}

/**
 * Kontrola závislostí
 * Overuje, či je Gravity Forms aktívny
 */
function spa_check_dependencies() {
    if (!class_exists('GFForms')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>SPA System:</strong> Plugin vyžaduje aktívny Gravity Forms.';
            echo '</p></div>';
        });
        return false;
    }
    return true;
}

/**
 * Inicializácia pluginu
 */
function spa_init() {
    // Kontrola závislostí
    if (!spa_check_dependencies()) {
        return;
    }
    
    // Načítanie core súborov
    require_once SPA_PLUGIN_DIR . 'includes/spa-helpers.php';
    require_once SPA_PLUGIN_DIR . 'includes/spa-core.php';
    require_once SPA_PLUGIN_DIR . 'includes/spa-registration.php';
    
    // Inicializácia registračného modulu
    spa_registration_init();
}

add_action('plugins_loaded', 'spa_init');