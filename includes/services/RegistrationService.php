<?php
/**
 * SPA Core – Registration Service
 *
 * Zodpovedá výhradne za prácu s DB tabuľkami
 * týkajúcimi sa registrácií.
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPA_Registration_Service {

    /**
     * Vytvorí registráciu v DB
     *
     * @param array $data
     * @return int|WP_Error registration_id
     */
    public static function create(array $data) {
        global $wpdb;

        // POVINNÉ hodnoty (minimum)
        $required = [
            'parent_id',
            'child_id',
            'program_id',
        ];

        foreach ($required as $key) {
            if (empty($data[$key])) {
                return new WP_Error(
                    'spa_registration_missing_field',
                    sprintf('Missing required field: %s', $key)
                );
            }
        }

        $table = $wpdb->prefix . 'spa_registrations';

        $inserted = $wpdb->insert(
            $table,
            [
                'parent_id'   => (int) $data['parent_id'],
                'child_id'    => (int) $data['child_id'],
                'program_id'  => (int) $data['program_id'],
                'status'      => $data['status'] ?? 'pending',
                'created_at'  => current_time('mysql'),
            ],
            [
                '%d',
                '%d',
                '%d',
                '%s',
                '%s',
            ]
        );

        if ($inserted === false) {
            return new WP_Error(
                'spa_registration_db_error',
                $w
