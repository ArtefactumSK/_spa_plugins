<?php
/**
 * Shortcode: SPA Attendance
 * Zobrazuje dochádzku pre trénera
 */

if (!defined('ABSPATH')) exit;

add_shortcode('spa_attendance', function () {
    ob_start();
    include __DIR__ . '/attendance.php';
    return ob_get_clean();
});
