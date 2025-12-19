<?php
/**
 * Gravity Forms – Create SPA Child
 *
 * Vytvorí dieťa v DB tabuľke spa_children + meta údaje
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

add_action('gform_after_submission', function ($entry, $form) {
    global $wpdb;

    // ID formulára – registrácia dieťaťa
    if ((int) $form['id'] !== 4) {
        return;
    }

    if (!is_user_logged_in()) {
        error_log('[SPA CHILD] User not logged in');
        return;
    }

    $parent_id  = get_current_user_id();
    
    // GF Field IDs
    $first_name = rgar($entry, '1.3');
    $last_name  = rgar($entry, '1.6');
    $birthdate_raw = rgar($entry, '3'); // dd.mm.yyyy
    $birth_number = rgar($entry, '6'); // 999999/9999

    $child_name = trim($first_name . ' ' . $last_name);

    if (!$child_name) {
        error_log('[SPA CHILD] Missing child name');
        return;
    }

    // Konvertuj dátum z dd.mm.yyyy na yyyy-mm-dd
    $birthdate = null;
    if ($birthdate_raw && preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $birthdate_raw, $matches)) {
        $birthdate = $matches[3] . '-' . $matches[2] . '-' . $matches[1];
    }

    // Vlož dieťa do DB
    $table = $wpdb->prefix . 'spa_children';

    $result = $wpdb->insert(
        $table,
        [
            'parent_id'  => (int) $parent_id,
            'name'       => sanitize_text_field($child_name),
            'birthdate'  => $birthdate,
            'created_at' => current_time('mysql'),
        ],
        ['%d', '%s', '%s', '%s']
    );

    if (!$result) {
        error_log('[SPA CHILD] DB insert failed: ' . $wpdb->last_error);
        return;
    }

    $child_id = $wpdb->insert_id;

    error_log('[SPA CHILD] Created child_id=' . $child_id . ' (parent_id=' . $parent_id . ')');

    // Ulož rodné číslo do meta tabuľky
    if ($birth_number) {
        spa_update_child_meta($child_id, 'birth_number', sanitize_text_field($birth_number));
        error_log('[SPA CHILD] Saved birth_number for child_id=' . $child_id);
    }

}, 10, 2);

/**
 * Helper: Ulož child meta
 */
function spa_update_child_meta($child_id, $meta_key, $meta_value) {
    global $wpdb;
    
    $table = $wpdb->prefix . 'spa_children_meta';
    
    // Over, či meta už existuje
    $existing = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT meta_id FROM {$table} WHERE child_id = %d AND meta_key = %s",
            $child_id,
            $meta_key
        )
    );
    
    if ($existing) {
        // Update
        $wpdb->update(
            $table,
            ['meta_value' => $meta_value],
            ['child_id' => $child_id, 'meta_key' => $meta_key],
            ['%s'],
            ['%d', '%s']
        );
    } else {
        // Insert
        $wpdb->insert(
            $table,
            [
                'child_id' => $child_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
            ],
            ['%d', '%s', '%s']
        );
    }
}