<?php
/**
 * Shortcode: SPA Child Selector
 *
 * Zobrazuje výber dieťaťa pre prihláseného rodiča alebo trénera
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

    global $wpdb;
    $table = $wpdb->prefix . 'spa_children';

    // Ak je tréner/manager/owner/admin → zobraz VŠETKY deti
    $privileged_roles = ['spa_trainer', 'spa_manager', 'spa_owner', 'administrator'];
    $is_privileged = !empty(array_intersect($privileged_roles, (array) $current_user->roles));

    if ($is_privileged) {
        // Tréner vidí všetky deti
        $children = $wpdb->get_results(
            "SELECT c.id, c.name, c.birthdate, u.user_email as parent_email
            FROM {$table} c
            LEFT JOIN {$wpdb->users} u ON c.parent_id = u.ID
            ORDER BY c.name"
        );
        
        error_log('[SPA CHILD SELECTOR] Privileged user (roles: ' . implode(', ', $current_user->roles) . ') → showing ALL children: ' . count($children));
    } else {
        // Rodič vidí len svoje deti
        $children = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, name, birthdate FROM {$table} WHERE parent_id = %d ORDER BY name",
                $parent_id
            )
        );
        
        error_log('[SPA CHILD SELECTOR] Parent user ID=' . $parent_id . ' → showing OWN children: ' . count($children));
    }

    if (!$children) {
        return '<p>Zatiaľ nemáte pridané žiadne dieťa.</p>';
    }

    ob_start();

    echo '<h3>Vyber dieťa</h3>';
    echo '<div class="spa-children">';

    foreach ($children as $child) {
        // Zostaviť info text
        $info_parts = [];
        if (!empty($child->birthdate)) {
            $info_parts[] = '🎂 ' . date('d.m.Y', strtotime($child->birthdate));
        }
        if ($is_privileged && !empty($child->parent_email)) {
            $info_parts[] = '👤 ' . $child->parent_email;
        }
        $info_text = !empty($info_parts) ? '<br><small>' . implode(' | ', $info_parts) . '</small>' : '';

        echo '<button type="button"
            class="spa-child-btn"
            data-child-id="' . esc_attr($child->id) . '"
            data-parent-id="' . esc_attr($parent_id) . '">
            ' . esc_html($child->name) . $info_text . '
        </button>';
    }

    echo '</div>';

    // Feedback div (vyplní ho JavaScript)
    echo '<div class="spa-child-feedback"></div>';

    return ob_get_clean();
});