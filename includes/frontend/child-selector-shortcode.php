<?php
/**
 * Shortcode: SPA Child Selector
 *
 * Zobrazuje výber dieťaťa pre prihláseného rodiča
 *
 * Použitie:
 * [spa_child_selector]
 *
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

add_shortcode('spa_child_selector', function () {

    if (!is_user_logged_in()) {
        return '<p>Pre registráciu sa prosím prihláste.</p>';
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
        return '<p>Zatiaľ nemáte pridané žiadne dieťa.</p>';
    }

    ob_start();

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

    return ob_get_clean();
});
