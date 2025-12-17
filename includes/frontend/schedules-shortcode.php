<?php
/**
 * Shortcode: SPA Schedules List
 *
 * Použitie:
 * [spa_schedules]
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

add_shortcode('spa_schedules', function () {
    ob_start();
    include __DIR__ . '/schedules-list.php';
    return ob_get_clean();
});
