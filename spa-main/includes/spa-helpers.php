<?php
/**
 * SPA System MAIN Helpers
 * Helper funkcie pre validáciu a spracovanie dát
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validácia emailovej adresy
 */
function spa_validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validácia telefónneho čísla (základná)
 * Akceptuje formáty: +421, 0, medzery, pomlčky
 */
function spa_validate_phone($phone) {
    $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
    return preg_match('/^(\+421|0)[0-9]{9}$/', $cleaned);
}

/**
 * Sanitizácia telefónneho čísla
 */
function spa_sanitize_phone($phone) {
    return preg_replace('/[^0-9+]/', '', $phone);
}

/**
 * Validácia adresy (GF Address field)
 * Overuje, či sú vyplnené povinné časti adresy
 */
function spa_validate_address($address) {
    if (!is_array($address)) {
        return false;
    }
    
    $required_fields = ['street', 'city', 'zip'];
    
    foreach ($required_fields as $field) {
        if (empty($address[$field])) {
            return false;
        }
    }
    
    return true;
}

/**
 * Získanie hodnoty z GF entry pomocou spa-config mapingu
 */
function spa_get_field_value($entry, $logical_name) {
    $config = spa_load_field_config();
    
    if (!isset($config[$logical_name])) {
        return null;
    }
    
    $field_id = $config[$logical_name];
    
    return rgar($entry, $field_id);
}

/**
 * Kontrola, či je checkbox/consent zaškrtnutý
 */
function spa_is_consent_checked($entry, $consent_name) {
    $value = spa_get_field_value($entry, $consent_name);
    return !empty($value);
}

/**
 * Loguje chybu do WP debug.log (ak je WP_DEBUG aktívny)
 */
function spa_log($message, $data = null) {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        $log_message = '[SPA] ' . $message;
        if ($data !== null) {
            $log_message .= ' | Data: ' . print_r($data, true);
        }
        error_log($log_message);
    }
}

/**
 * AJAX Handler: Vráti HTML obsah infoboxu
 */
add_action('wp_ajax_spa_get_infobox_content', 'spa_ajax_get_infobox_content');
add_action('wp_ajax_nopriv_spa_get_infobox_content', 'spa_ajax_get_infobox_content');

function spa_ajax_get_infobox_content() {
    $state = isset($_POST['state']) ? intval($_POST['state']) : 0;
    $city_name = isset($_POST['city_name']) ? sanitize_text_field($_POST['city_name']) : '';
    $program_name = isset($_POST['program_name']) ? sanitize_text_field($_POST['program_name']) : '';
    $program_age = isset($_POST['program_age']) ? sanitize_text_field($_POST['program_age']) : '';

    error_log('[SPA] Infobox AJAX called | Data: ' . print_r($_POST, true));

    // Ikony pre stavy
    $icons = [
        0 => '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>',
        1 => '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>',
        2 => '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#4caf50" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4" stroke="#4caf50" fill="none"/></svg>'
    ];

    // Obsah pre rôzne stavy
    $content = '';

    switch ($state) {
        case 0:
            $content = '<p style="color: #999;">Vyberte mesto a program</p>';
            break;
        
        case 1:
            $content = '<p style="font-weight: bold; color: #ff9800;">📍 ' . esc_html($city_name) . '</p>';
            $content .= '<p style="color: #999;">Teraz vyberte program</p>';
            break;
        
        case 2:
            $content = '<p style="font-weight: bold; color: #4caf50;">📍 ' . esc_html($city_name) . '</p>';
            $content .= '<p style="color: #333;">🎯 ' . esc_html($program_name) . '</p>';
            $content .= '<p style="color: #666;">👶 Vek: ' . esc_html($program_age) . '</p>';
            break;
    }

    wp_send_json_success([
        'content' => $content,
        'icons' => ['main' => $icons[$state]]
    ]);
}