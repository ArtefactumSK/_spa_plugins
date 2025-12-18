<?php
/**
 * SPA Feature Flags & Trial logic
 *
 * Riadi CORE vs EXTENDED vrstvy funkcionality
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

/**
 * Inicializácia feature flags (iba ak neexistujú)
 */
function spa_init_feature_flags() {

    if (get_option('spa_features')) {
        return;
    }

    $trial_start = current_time('Y-m-d');
    $trial_end   = date('Y-m-d', strtotime('+30 days'));

    $features = [
        'trial_active'     => true,
        'trial_started_at' => $trial_start,
        'trial_ends_at'    => $trial_end,

        'features' => [
            'attendance_stats'         => 'extended',
            'payments_extended'        => 'extended',
            'messaging_extended'       => 'extended',
            'coach_dashboard_extended' => 'extended',
            'reports_extended'         => 'extended',
            'gps_verification'         => 'extended',
        ]
    ];

    add_option('spa_features', $features);
}

add_action('after_switch_theme', 'spa_init_feature_flags');
add_action('activated_plugin', 'spa_init_feature_flags');


/**
 * SPA Feature Flags & Trial logic
 *
 * Riadi CORE vs EXTENDED vrstvy funkcionality
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

/**
 * Inicializácia feature flags (iba ak neexistujú)
 */
function spa_init_feature_flags() {

    if (get_option('spa_features')) {
        return;
    }

    $trial_start = current_time('Y-m-d');
    $trial_end   = date('Y-m-d', strtotime('+30 days'));

    $features = [
        'trial_active'     => true,
        'trial_started_at' => $trial_start,
        'trial_ends_at'    => $trial_end,

        'features' => [
            'attendance_stats'         => 'extended',
            'payments_extended'        => 'extended',
            'messaging_extended'       => 'extended',
            'coach_dashboard_extended' => 'extended',
            'reports_extended'         => 'extended',
            'gps_verification'         => 'extended',
        ]
    ];

    add_option('spa_features', $features);
}

add_action('after_switch_theme', 'spa_init_feature_flags');
add_action('activated_plugin', 'spa_init_feature_flags');
