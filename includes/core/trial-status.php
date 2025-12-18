<?php
/**
 * SPA Trial Status Helper
 *
 * Poskytuje informácie o stave trial verzie
 * POUŽÍVA ROVNAKÚ ŠTRUKTÚRU AKO feature-flags.php!
 */

if (!defined('ABSPATH')) exit;

function spa_get_trial_status(): array {

    $options = get_option('spa_features');

    $is_active = false;
    $expires = null;

    if ($options && !empty($options['trial_active'])) {
        $today = current_time('Y-m-d');
        
        // Ak existuje trial_ends_at a ešte je platný
        if (!empty($options['trial_ends_at'])) {
            $is_active = ($today <= $options['trial_ends_at']);
            $expires = $options['trial_ends_at'];
        }
    }

    return [
        'enabled' => (bool) (!empty($options['trial_active'])),
        'active'  => $is_active,
        'expires' => $expires,
    ];
}