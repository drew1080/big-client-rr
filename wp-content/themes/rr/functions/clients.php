<?php

/*-----------------------------------------------------------------------------------*/
/* create custom post => infocolumns
/*-----------------------------------------------------------------------------------*/
if (!function_exists('create_clients')) {
    function create_clients()
    {
        $clients_args = array(
            'label' => __('Clients', 'framework'),
            'singular_label' => __('Client', 'framework'),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => true,
            'supports' => array('title', 'thumbnail'));
        register_post_type('clients', $clients_args);
    }

    add_action('init', 'create_clients');
}
