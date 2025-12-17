<?php
/**
 * Frontend výpis rozvrhov pre mesto
 *
 * Zobrazuje:
 * - rozvrhy (spa_schedule)
 * - priradený program
 * - miesto (spa_venue)
 * - kapacitu a obsadenosť
 *
 * Použitie: cez shortcode
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

global $wpdb;

// slug mesta z URL (?city=malacky)
$city_slug = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : '';

if (!$city_slug) {
    echo '<p>Chýba mesto.</p>';
    return;
}

// získa term ID mesta
$city = get_term_by('slug', $city_slug, 'spa_city');

if (!$city) {
    echo '<p>Mesto neexistuje.</p>';
    return;
}

// query rozvrhov pre mesto
$schedules = get_posts([
    'post_type' => 'spa_schedule',
    'post_status' => 'publish',
    'numberposts' => -1,
    'tax_query' => [
        [
            'taxonomy' => 'spa_city',
            'field'    => 'term_id',
            'terms'    => $city->term_id,
        ]
    ]
]);

if (!$schedules) {
    echo '<p>Pre toto mesto nie sú rozvrhy.</p>';
    return;
}

echo '<h2>Rozvrhy – ' . esc_html($city->name) . '</h2>';

echo '<ul style="list-style:none;padding:0;">';

foreach ($schedules as $schedule) {

    $schedule_id = $schedule->ID;

    // program
    $program_id = get_post_meta($schedule_id, '_spa_program_id', true);
    $program_title = $program_id ? get_the_title($program_id) : '—';

    // miesto
    $venue_id = get_post_meta($schedule_id, '_spa_venue_id', true);
    $venue_title = $venue_id ? get_the_title($venue_id) : '—';

    // kapacita
    $capacity = (int) get_post_meta($schedule_id, '_spa_capacity', true);

    // obsadenosť
    $registered = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}spa_registrations WHERE schedule_id = %d",
            $schedule_id
        )
    );

    $available = ($capacity > 0) ? max(0, $capacity - $registered) : '—';

    echo '<li style="margin-bottom:12px;padding:12px;border:1px solid #ddd;">';
    echo '<strong>' . esc_html($schedule->post_title) . '</strong><br>';
    echo 'Program: ' . esc_html($program_title) . '<br>';
    echo 'Miesto: ' . esc_html($venue_title) . '<br>';

    if ($capacity > 0) {
        echo 'Voľné miesta: ' . esc_html($available) . '<br>';
        echo ($available > 0)
            ? '<span style="color:green;font-weight:bold;">Voľné</span>'
            : '<span style="color:red;font-weight:bold;">Plné</span>';
    } else {
        echo 'Kapacita: neobmedzená';
    }

    echo '</li>';
}

echo '</ul>';
