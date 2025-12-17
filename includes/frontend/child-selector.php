<?php
/**
 * Frontend – výber dieťaťa pre registráciu
 *
 * - zobrazí deti prihláseného rodiča
 * - klik nastaví child_id + parent_id
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    echo '<p>Pre registráciu sa prosím prihláste.</p>';
    return;
}

$current_user = wp_get_current_user();
$parent_id = (int) $current_user->ID;

// Predpoklad: deti sú CPT spa_child s meta _spa_parent_id
$children = get_posts([
    'post_type'   => 'spa_child',
    'post_status' => 'publish',
    'numberposts' => -1,
    'meta_query'  => [
        [
            'key'   => '_spa_parent_id',
            'value' => $parent_id,
        ]
    ]
]);

if (!$children) {
    echo '<p>Zatiaľ nemáte pridané žiadne dieťa.</p>';
    return;
}

echo '<h3>Vyber dieťa</h3>';
echo '<div class="spa-children">';

foreach ($children as $child) {
    echo '<button type="button"
        class="spa-child-btn"
        data-child-id="' . esc_attr($child->ID) . '"
        data-parent-id="' . esc_attr($parent_id) . '">
        ' . esc_html($child->post_title) . '
    </button>';
}

echo '</div>';
