<?php
/**
 * SPA User Creation
 * Bezpečné vytváranie a párovanie WP userov
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Získanie alebo vytvorenie parent usera
 * 
 * @param array $data Pripravené user dáta zo skeletonu
 * @return int|false User ID alebo false pri chybe
 */
function spa_get_or_create_parent_user($data) {
    // Validácia vstupných dát
    if (empty($data['user_email'])) {
        error_log('[SPA USER CREATE] ERROR: Parent email missing');
        return false;
    }
    
    // Deduplikácia - kontrola existencie
    $existing_user = get_user_by('email', $data['user_email']);
    
    if ($existing_user) {
        error_log('[SPA USER CREATE] Parent exists, user_id=' . $existing_user->ID);
        return $existing_user->ID;
    }
    
    // Vytvorenie nového usera
    $user_id = wp_insert_user($data);
    
    if (is_wp_error($user_id)) {
        error_log('[SPA USER CREATE] ERROR: Parent creation failed - ' . $user_id->get_error_message());
        return false;
    }
    
    error_log('[SPA USER CREATE] Parent created, user_id=' . $user_id);
    
    return $user_id;
}

/**
 * Získanie alebo vytvorenie child usera
 * 
 * @param array $data Pripravené user dáta zo skeletonu
 * @param int $parent_user_id ID rodiča
 * @return int|false User ID alebo false pri chybe
 */
function spa_get_or_create_child_user($data, $parent_user_id) {
    // Validácia vstupných dát
    if (empty($data['user_email'])) {
        error_log('[SPA USER CREATE] ERROR: Child email missing');
        return false;
    }
    
    if (empty($parent_user_id)) {
        error_log('[SPA USER CREATE] ERROR: Parent user_id missing for child');
        return false;
    }
    
    // Deduplikácia - kontrola existencie
    $existing_user = get_user_by('email', $data['user_email']);
    
    if ($existing_user) {
        error_log('[SPA USER CREATE] Child exists, user_id=' . $existing_user->ID);
        
        // Aktualizuj väzbu na parent (pre istotu)
        update_user_meta($existing_user->ID, 'parent_user_id', $parent_user_id);
        
        return $existing_user->ID;
    }
    
    // Vytvorenie nového usera
    $user_id = wp_insert_user($data);
    
    if (is_wp_error($user_id)) {
        error_log('[SPA USER CREATE] ERROR: Child creation failed - ' . $user_id->get_error_message());
        return false;
    }
    
    // Ulož väzbu na parent
    update_user_meta($user_id, 'parent_user_id', $parent_user_id);
    
    error_log('[SPA USER CREATE] Child created, user_id=' . $user_id . ', parent_id=' . $parent_user_id);
    
    return $user_id;
}