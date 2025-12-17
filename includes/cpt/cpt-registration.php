<?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {

    register_post_type('spa_registration', [
        'labels' => [
            'name' => 'Registrácie',
            'singular_name' => 'Registrácia',
        ],
        'public' => false,
        'show_ui' => false,            // zatiaľ skryté v admin
        'show_in_rest' => true,        // FE / API pripravené
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);

});
