<?php
/**
 * Gravity Forms → SPA Registration Controller
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {

    // Gravity Forms ešte nemusí byť načítaný
    if (!class_exists('GFForms')) {
        $schedule_id = (int) rgar($entry, '28');
        return;
    }

    // REGISTRUJ HOOK AŽ TERAZ
    add_action('gform_after_submission_1', 'spa_handle_registration_form', 10, 2);
});

function spa_handle_registration_form($entry, $form) {

    if (!class_exists('SPA_Registration_Service')) {
        return;
    }

    $schedule_id = (int) rgar($entry, '28');

    $result = SPA_Registration_Service::create([
        'parent_id'   => 1,
        'child_id'    => 1,
        'program_id'  => 1,
        'schedule_id' => $schedule_id,
    ]);
}

