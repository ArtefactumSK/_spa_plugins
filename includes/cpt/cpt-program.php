<?php
/**
 * SPA Core – CPT Program (Tréningový program)
 *
 * Definuje tréningový program s pravidelným rozvrchom
 * 
 * @package SPA Core
 */

if (!defined('ABSPATH')) exit;

// Registrácia CPT
add_action('init', function () {

    register_post_type('spa_program', [
        'labels' => [
            'name'               => 'SPA Programy',
            'singular_name'      => 'Program',
            'add_new'            => 'Pridať program',
            'add_new_item'       => 'Pridať nový program',
            'edit_item'          => 'Upraviť program',
            'new_item'           => 'Nový program',
            'view_item'          => 'Zobraziť program',
            'search_items'       => 'Hľadať programy',
            'not_found'          => 'Nenašli sa žiadne programy',
        ],
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => false,  // Classic Editor
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'taxonomies'          => ['spa_category', 'post_tag'],
        'capability_type'     => 'post',
        'menu_icon'           => 'dashicons-awards',
        'menu_position'       => 24,
        'has_archive'         => true,
        'rewrite'             => ['slug' => 'programy'],
    ]);

    // Registrácia taxonomie - kategórie (vekové skupiny)
    register_taxonomy('spa_category', 'spa_program', [
        'labels' => [
            'name'          => 'Kategórie',
            'singular_name' => 'Kategória',
            'add_new_item'  => 'Pridať kategóriu',
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'kategoria'],
    ]);

});

// Meta boxy
add_action('add_meta_boxes', function () {
    
    add_meta_box(
        'spa_program_venue',
        'Miesto konania',
        'spa_program_venue_meta_box',
        'spa_program',
        'side',
        'high'
    );

    add_meta_box(
        'spa_program_capacity',
        'Kapacita a vek',
        'spa_program_capacity_meta_box',
        'spa_program',
        'side',
        'default'
    );

    add_meta_box(
        'spa_program_details',
        'Ikona a farba',
        'spa_program_details_meta_box',
        'spa_program',
        'side',
        'default'
    );

    add_meta_box(
        'spa_program_description',
        'Popis programu',
        'spa_program_description_meta_box',
        'spa_program',
        'normal',
        'high'
    );

    add_meta_box(
        'spa_program_pricing',
        'Cenník',
        'spa_program_pricing_meta_box',
        'spa_program',
        'normal',
        'default'
    );

    add_meta_box(
        'spa_program_schedule',
        'Rozvrh tréningov',
        'spa_program_schedule_meta_box',
        'spa_program',
        'normal',
        'high'
    );

    add_meta_box(
        'spa_program_stats',
        'Štatistiky',
        'spa_program_stats_meta_box',
        'spa_program',
        'side',
        'default'
    );

});

// Meta box: Miesto
function spa_program_venue_meta_box($post) {
    wp_nonce_field('spa_program_meta', 'spa_program_nonce');

    $venue_id = get_post_meta($post->ID, '_spa_venue_id', true);

    $venues = get_posts([
        'post_type' => 'spa_venue',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    ?>
    <p>
        <label for="spa_venue_id"><strong>Vyberte miesto:</strong></label>
        <select id="spa_venue_id" name="spa_venue_id" style="width:100%;">
            <option value="">-- Vyberte miesto --</option>
            <?php foreach ($venues as $venue): ?>
                <option value="<?php echo $venue->ID; ?>" <?php selected($venue_id, $venue->ID); ?>>
                    <?php echo esc_html($venue->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

// Meta box: Ikona a farba
function spa_program_details_meta_box($post) {
    $icon = get_post_meta($post->ID, '_spa_icon', true);
    $level = get_post_meta($post->ID, '_spa_level', true);
    $color = get_post_meta($post->ID, '_spa_color', true);

    // Predvolené farby
    $preset_colors = [
        '#FF1439' => 'Červená (základná)',
        '#0072CE' => 'Modrá',
        '#00A651' => 'Zelená',
        '#FF6B35' => 'Oranžová',
        '#9B59B6' => 'Fialová',
        '#E91E63' => 'Ružová',
        '#00BCD4' => 'Tyrkysová',
        '#FFC107' => 'Žltá',
    ];

    ?>
    <p>
        <label for="spa_icon"><strong>Ikona (SVG):</strong></label><br>
        <input type="text" id="spa_icon" name="spa_icon" value="<?php echo esc_attr($icon); ?>" style="width:100%;" placeholder="napr. spa-child-pastel_blue.svg">
        <span class="description">Názov SVG súboru z /uploads/spa-icons/<br>Príklady: spa-child-pastel_blue.svg, spa-gymnast.svg</span>
    </p>

    <?php if ($icon): 
        $svg_path = WP_CONTENT_DIR . '/uploads/spa-icons/' . $icon;
        if (file_exists($svg_path)): ?>
            <div style="text-align:center; padding:15px; background:#f9f9f9; border-radius:8px; margin:10px 0;">
                <div style="width:80px; height:80px; margin:0 auto;">
                    <?php echo file_get_contents($svg_path); ?>
                </div>
                <small style="color:#666;">Náhľad ikony</small>
            </div>
        <?php else: ?>
            <p style="color:#d63638;"><small>⚠️ Súbor neexistuje</small></p>
        <?php endif; 
    endif; ?>

    <p>
        <label for="spa_color"><strong>Farba programu:</strong></label><br>
        <select id="spa_color_preset" style="width:100%; margin-bottom:8px;">
            <option value="">-- Vyberte predvolenú farbu --</option>
            <?php foreach ($preset_colors as $hex => $label): ?>
                <option value="<?php echo $hex; ?>" <?php selected($color, $hex); ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input type="color" id="spa_color" name="spa_color" value="<?php echo esc_attr($color ?: '#FF1439'); ?>" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px; cursor:pointer;">
        <span class="description">Vyberte farbu alebo použite predvolenú</span>
    </p>

    <script>
    document.getElementById('spa_color_preset').addEventListener('change', function() {
        if (this.value) {
            document.getElementById('spa_color').value = this.value;
        }
    });
    </script>

    <p>
        <label for="spa_level"><strong>Úroveň:</strong></label><br>
        <select id="spa_level" name="spa_level" style="width:100%;">
            <option value="">-- Vyberte úroveň --</option>
            <option value="beginner" <?php selected($level, 'beginner'); ?>>Začiatočník</option>
            <option value="intermediate" <?php selected($level, 'intermediate'); ?>>Pokročilý</option>
            <option value="advanced" <?php selected($level, 'advanced'); ?>>Expert</option>
        </select>
    </p>
    <?php
}

// Meta box: Popis programu
function spa_program_description_meta_box($post) {
    $description = get_post_meta($post->ID, '_spa_description', true);

    ?>
    <p class="description" style="margin-bottom:10px;">
        Krátky popis, ktorý sa zobrazí na karte programu (HTML je povolené)
    </p>
    
    <?php
    wp_editor($description, 'spa_description', [
        'textarea_name' => 'spa_description',
        'media_buttons' => false,
        'textarea_rows' => 5,
        'teeny' => true,
        'quicktags' => false,
    ]);
}

// Meta box: Kapacita a vek
function spa_program_capacity_meta_box($post) {
    $capacity = get_post_meta($post->ID, '_spa_capacity', true);
    $age_min = get_post_meta($post->ID, '_spa_age_min', true);
    $age_max = get_post_meta($post->ID, '_spa_age_max', true);

    ?>
    <p>
        <label for="spa_capacity"><strong>Kapacita (celkovo):</strong></label><br>
        <input type="number" id="spa_capacity" name="spa_capacity" value="<?php echo esc_attr($capacity); ?>" min="1" max="200" style="width:100px;">
        <span class="description">Max. detí na program</span>
    </p>

    <p>
        <label for="spa_age_min"><strong>Minimálny vek:</strong></label><br>
        <input type="text" id="spa_age_min" name="spa_age_min" value="<?php echo esc_attr($age_min); ?>" placeholder="napr. 1.8" style="width:80px;">
        <span class="description">roky</span>
    </p>

    <p>
        <label for="spa_age_max"><strong>Maximálny vek:</strong></label><br>
        <input type="text" id="spa_age_max" name="spa_age_max" value="<?php echo esc_attr($age_max); ?>" placeholder="napr. 3" style="width:80px;">
        <span class="description">roky</span>
    </p>
    <?php
}

// Meta box: Cenník
function spa_program_pricing_meta_box($post) {
    $price = get_post_meta($post->ID, '_spa_price', true);
    $price_1x = get_post_meta($post->ID, '_spa_price_1x_weekly', true);
    $price_2x = get_post_meta($post->ID, '_spa_price_2x_weekly', true);
    $price_monthly = get_post_meta($post->ID, '_spa_price_monthly', true);
    $price_semester = get_post_meta($post->ID, '_spa_price_semester', true);

    ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <th><label for="spa_price">Základná cena:</label></th>
            <td>
                <input type="number" id="spa_price" name="spa_price" value="<?php echo esc_attr($price); ?>" min="0" step="0.01" style="width:100px;">
                <span class="description">€</span>
            </td>
        </tr>
        <tr>
            <th><label for="spa_price_1x">Cena 1x týždenne:</label></th>
            <td>
                <input type="number" id="spa_price_1x" name="spa_price_1x_weekly" value="<?php echo esc_attr($price_1x); ?>" min="0" step="0.01" style="width:100px;">
                <span class="description">€</span>
            </td>
        </tr>
        <tr>
            <th><label for="spa_price_2x">Cena 2x týždenne:</label></th>
            <td>
                <input type="number" id="spa_price_2x" name="spa_price_2x_weekly" value="<?php echo esc_attr($price_2x); ?>" min="0" step="0.01" style="width:100px;">
                <span class="description">€</span>
            </td>
        </tr>
        <tr>
            <th><label for="spa_price_monthly">Cena mesačne:</label></th>
            <td>
                <input type="number" id="spa_price_monthly" name="spa_price_monthly" value="<?php echo esc_attr($price_monthly); ?>" min="0" step="0.01" style="width:100px;">
                <span class="description">€</span>
            </td>
        </tr>
        <tr>
            <th><label for="spa_price_semester">Cena semester:</label></th>
            <td>
                <input type="number" id="spa_price_semester" name="spa_price_semester" value="<?php echo esc_attr($price_semester); ?>" min="0" step="0.01" style="width:100px;">
                <span class="description">€</span>
            </td>
        </tr>
    </table>
    <?php
}

// Meta box: Rozvrh
function spa_program_schedule_meta_box($post) {
    $schedule_times = get_post_meta($post->ID, '_spa_schedule_times', true);
    
    // Default prázdne pole
    if (!$schedule_times || !is_array($schedule_times)) {
        $schedule_times = [['day' => '', 'from' => '', 'to' => '']];
    }

    $days = [
        'monday' => 'Pondelok',
        'tuesday' => 'Utorok',
        'wednesday' => 'Streda',
        'thursday' => 'Štvrtok',
        'friday' => 'Piatok',
        'saturday' => 'Sobota',
        'sunday' => 'Nedeľa',
    ];

    ?>
    <div id="spa-schedule-wrapper">
        <p><strong>Definujte pravidelný týždenný rozvrh:</strong></p>
        
        <div id="spa-schedule-rows">
            <?php foreach ($schedule_times as $index => $slot): ?>
                <div class="spa-schedule-row" style="margin-bottom: 10px; padding: 10px; background: #f9f9f9; border: 1px solid #ddd; border-radius:4px;">
                    <label>Deň:</label>
                    <select name="spa_schedule_times[<?php echo $index; ?>][day]" style="width: 150px;">
                        <option value="">-- Vyberte deň --</option>
                        <?php foreach ($days as $day_key => $day_label): ?>
                            <option value="<?php echo $day_key; ?>" <?php selected($slot['day'], $day_key); ?>>
                                <?php echo $day_label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Od:</label>
                    <input type="time" name="spa_schedule_times[<?php echo $index; ?>][from]" value="<?php echo esc_attr($slot['from'] ?? ''); ?>" style="width: 100px;">

                    <label>Do:</label>
                    <input type="time" name="spa_schedule_times[<?php echo $index; ?>][to]" value="<?php echo esc_attr($slot['to'] ?? ''); ?>" style="width: 100px;">

                    <button type="button" class="button spa-remove-schedule-row">Odstrániť</button>
                </div>
            <?php endforeach; ?>
        </div>

        <p>
            <button type="button" id="spa-add-schedule-row" class="button">+ Pridať ďalší tréning</button>
        </p>
    </div>

    <script>
    jQuery(document).ready(function($) {
        let rowIndex = <?php echo count($schedule_times); ?>;

        $('#spa-add-schedule-row').on('click', function() {
            const newRow = `
                <div class="spa-schedule-row" style="margin-bottom: 10px; padding: 10px; background: #f9f9f9; border: 1px solid #ddd; border-radius:4px;">
                    <label>Deň:</label>
                    <select name="spa_schedule_times[${rowIndex}][day]" style="width: 150px;">
                        <option value="">-- Vyberte deň --</option>
                        <?php foreach ($days as $day_key => $day_label): ?>
                            <option value="<?php echo $day_key; ?>"><?php echo $day_label; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Od:</label>
                    <input type="time" name="spa_schedule_times[${rowIndex}][from]" style="width: 100px;">

                    <label>Do:</label>
                    <input type="time" name="spa_schedule_times[${rowIndex}][to]" style="width: 100px;">

                    <button type="button" class="button spa-remove-schedule-row">Odstrániť</button>
                </div>
            `;
            $('#spa-schedule-rows').append(newRow);
            rowIndex++;
        });

        $(document).on('click', '.spa-remove-schedule-row', function() {
            $(this).closest('.spa-schedule-row').remove();
        });
    });
    </script>
    <?php
}

// Meta box: Štatistiky
function spa_program_stats_meta_box($post) {
    global $wpdb;

    // Počet registrácií na tento program
    $registrations_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}spa_registrations WHERE program_id = %d",
        $post->ID
    ));

    // Počet vygenerovaných schedules
    $schedules_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'spa_schedule' AND post_parent = %d",
        $post->ID
    ));

    ?>
    <table class="form-table" style="margin: 0;">
        <tr>
            <td style="padding: 0;">
                <p><strong>Registrácie:</strong> <?php echo $registrations_count; ?></p>
                <p><strong>Vygenerované rozvrhy:</strong> <?php echo $schedules_count; ?></p>
                <p><strong>Kapacita:</strong> <?php echo get_post_meta($post->ID, '_spa_capacity', true) ?: '∞'; ?></p>
            </td>
        </tr>
    </table>
    <?php
}

// Uloženie meta údajov
add_action('save_post_spa_program', function ($post_id) {
    if (!isset($_POST['spa_program_nonce']) || !wp_verify_nonce($_POST['spa_program_nonce'], 'spa_program_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Uloženie venue
    if (isset($_POST['spa_venue_id'])) {
        update_post_meta($post_id, '_spa_venue_id', absint($_POST['spa_venue_id']));
    }

    // Uloženie ikony, farby, úrovne
    if (isset($_POST['spa_icon'])) {
        update_post_meta($post_id, '_spa_icon', sanitize_text_field($_POST['spa_icon']));
    }

    if (isset($_POST['spa_color'])) {
        update_post_meta($post_id, '_spa_color', sanitize_hex_color($_POST['spa_color']));
    }

    if (isset($_POST['spa_level'])) {
        update_post_meta($post_id, '_spa_level', sanitize_text_field($_POST['spa_level']));
    }

    // Uloženie popisu
    if (isset($_POST['spa_description'])) {
        update_post_meta($post_id, '_spa_description', wp_kses_post($_POST['spa_description']));
    }

    // Uloženie kapacity a veku
    if (isset($_POST['spa_capacity'])) {
        update_post_meta($post_id, '_spa_capacity', absint($_POST['spa_capacity']));
    }

    if (isset($_POST['spa_age_min'])) {
        update_post_meta($post_id, '_spa_age_min', sanitize_text_field($_POST['spa_age_min']));
    }

    if (isset($_POST['spa_age_max'])) {
        update_post_meta($post_id, '_spa_age_max', sanitize_text_field($_POST['spa_age_max']));
    }

    // Uloženie cenníka
    if (isset($_POST['spa_price'])) {
        update_post_meta($post_id, '_spa_price', floatval($_POST['spa_price']));
    }

    if (isset($_POST['spa_price_1x_weekly'])) {
        update_post_meta($post_id, '_spa_price_1x_weekly', floatval($_POST['spa_price_1x_weekly']));
    }

    if (isset($_POST['spa_price_2x_weekly'])) {
        update_post_meta($post_id, '_spa_price_2x_weekly', floatval($_POST['spa_price_2x_weekly']));
    }

    if (isset($_POST['spa_price_monthly'])) {
        update_post_meta($post_id, '_spa_price_monthly', floatval($_POST['spa_price_monthly']));
    }

    if (isset($_POST['spa_price_semester'])) {
        update_post_meta($post_id, '_spa_price_semester', floatval($_POST['spa_price_semester']));
    }

    // Uloženie rozvrhu (JSON)
    if (isset($_POST['spa_schedule_times']) && is_array($_POST['spa_schedule_times'])) {
        $schedule_times = array_filter($_POST['spa_schedule_times'], function($slot) {
            return !empty($slot['day']) && !empty($slot['from']) && !empty($slot['to']);
        });
        update_post_meta($post_id, '_spa_schedule_times', $schedule_times);
    }
});

// Vlastné stĺpce v admin liste
add_filter('manage_spa_program_posts_columns', function ($columns) {
    $new_columns = [
        'cb' => $columns['cb'],
        'title' => 'Názov',
        'taxonomy-spa_category' => 'Kategória',
        'venue' => 'Miesto',
        'capacity' => 'Kapacita',
        'schedule_days' => 'Tréningové dni',
        'date' => 'Vytvorené',
    ];
    return $new_columns;
});

add_action('manage_spa_program_posts_custom_column', function ($column, $post_id) {
    switch ($column) {
        case 'venue':
            $venue_id = get_post_meta($post_id, '_spa_venue_id', true);
            if ($venue_id) {
                echo get_the_title($venue_id);
            } else {
                echo '—';
            }
            break;

        case 'capacity':
            $capacity = get_post_meta($post_id, '_spa_capacity', true);
            echo $capacity ?: '∞';
            break;

        case 'schedule_days':
            $schedule_times = get_post_meta($post_id, '_spa_schedule_times', true);
            if ($schedule_times && is_array($schedule_times)) {
                $days_map = [
                    'monday' => 'PO',
                    'tuesday' => 'UT',
                    'wednesday' => 'ST',
                    'thursday' => 'ŠT',
                    'friday' => 'PI',
                    'saturday' => 'SO',
                    'sunday' => 'NE',
                ];
                $output = [];
                foreach ($schedule_times as $slot) {
                    $day_short = $days_map[$slot['day']] ?? $slot['day'];
                    $output[] = $day_short . ' ' . $slot['from'];
                }
                echo implode(', ', $output);
            } else {
                echo '—';
            }
            break;
    }
}, 10, 2);