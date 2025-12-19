<?php
/**
 * Gravity Forms – Create SPA Child
 *
 * Vytvorí CPT spa_child a priradí ho k rodičovi
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
        return;
    }

    $parent_id  = get_current_user_id();
    $first_name = rgar($entry, '1.3');
    $last_name  = rgar($entry, '1.6');

    $child_name = trim($first_name . ' ' . $last_name);

    if (!$child_name) {
        return;
    }

    $table = $wpdb->get_blog_prefix(5) . 'spa_children';

    $result = $wpdb->insert(
        $table,
        [
            'parent_id'  => (int) $parent_id,
            'name'       => sanitize_text_field($child_name),
            'created_at' => current_time('mysql'),
        ],
        ['%d', '%s', '%s']
    );

    if (!$result) {
        error_log('[SPA CHILD] DB insert failed: ' . $wpdb->last_error);
        return;
    }

    $child_id = $wpdb->insert_id;

    error_log('[SPA CHILD] Created child_id=' . $child_id);

}, 10, 2); // ⬅️ KRITICKÝ RIADOK