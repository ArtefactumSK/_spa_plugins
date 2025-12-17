<?php
/**
 * SPA Core – DB Install
 * 
 * Vytvára databázové tabuľky pri aktivácii pluginu.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once __DIR__ . '/schema.php';

/**
 * Spustí vytvorenie DB tabuliek
 */
function spa_core_install_db() {
    global $wpdb;

    $sql = spa_core_get_schema_sql();

    if (empty($sql)) {
        error_log('[SPA CORE] DB schema SQL is empty.');
        return;
    }

    dbDelta($sql);

    // Označenie verzie DB (pre budúce migrácie)
    update_option('spa_core_db_version', '1.0.0');
}
