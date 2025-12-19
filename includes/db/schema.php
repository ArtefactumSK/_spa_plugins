<?php
/**
 * SPA Core – Database Schema
 * 
 * Tento súbor obsahuje LEN definície SQL tabuliek.
 * Nespúšťa sa sám. Používa sa v install.php cez dbDelta().
 */

if (!defined('ABSPATH')) {
    exit;
}

function spa_core_get_schema_sql() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $prefix = $wpdb->prefix;

    $sql = "CREATE TABLE {$prefix}spa_parents (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY user_id (user_id)
    ) $charset_collate;

    CREATE TABLE {$prefix}spa_children (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        parent_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(191) NOT NULL,
        birthdate DATE NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY parent_id (parent_id)
    ) $charset_collate;

    CREATE TABLE {$prefix}spa_programs (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(191) NOT NULL,
        active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;

    CREATE TABLE {$prefix}spa_registrations (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        parent_id BIGINT UNSIGNED NOT NULL,
        child_id BIGINT UNSIGNED NOT NULL,
        program_id BIGINT UNSIGNED NOT NULL,
        schedule_id BIGINT UNSIGNED NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'pending',
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY parent_id (parent_id),
        KEY child_id (child_id),
        KEY program_id (program_id),
        KEY schedule_id (schedule_id)
    ) $charset_collate;

    CREATE TABLE {$prefix}spa_children_meta (
        meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        child_id BIGINT UNSIGNED NOT NULL,
        meta_key VARCHAR(191) NOT NULL,
        meta_value LONGTEXT NULL,
        PRIMARY KEY (meta_id),
        KEY child_id (child_id),
        KEY meta_key (meta_key)
    ) $charset_collate;

    CREATE TABLE {$prefix}spa_registrations_meta (
        meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        registration_id BIGINT UNSIGNED NOT NULL,
        meta_key VARCHAR(191) NOT NULL,
        meta_value LONGTEXT NULL,
        PRIMARY KEY (meta_id),
        KEY registration_id (registration_id),
        KEY meta_key (meta_key)
    ) $charset_collate;

    CREATE TABLE {$prefix}spa_attendance (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        schedule_id BIGINT UNSIGNED NOT NULL,
        registration_id BIGINT UNSIGNED NOT NULL,
        attendance_date DATE NOT NULL,
        attended TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY unique_attendance (schedule_id, registration_id, attendance_date),
        KEY schedule_id (schedule_id),
        KEY registration_id (registration_id)
    ) $charset_collate;";

    return $sql;
}