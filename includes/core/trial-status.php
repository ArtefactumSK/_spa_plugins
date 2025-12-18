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

    $today = current_time('Y-m-d');

    // Default
    $status  = 'core';
    $expires = null;

    // Ak je trial definovaný
    if (!empty($options['trial_active']) && !empty($options['trial_ends_at'])) {

        $expires = $options['trial_ends_at'];

        if ($today <= $options['trial_ends_at']) {
            $status = 'trial';
        }
    }

    return [
        'status'  => $status,   // core | trial | extended (extended rieši feature flag)
        'expires' => $expires,
    ];
}
