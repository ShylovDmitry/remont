<?php

add_action('init', function() {

    register_post_type('master', array(
        'labels' => array(
            'name' => __('Masters'),
            'singular_name' => __('Master'),
            'add_new_item' => __('Add New Master'),
            'edit_item' => __('Edit Master'),
        ),
        'public' => true,
        'rewrite' => array(
            'with_front' => false,
            'slug' => '%section%/master'
        ),
        'supports' => array(
            'title',
            'excerpt',
            'editor',
            'comments',
            'revisions',
        ),
    ));

    register_taxonomy('catalog_master', 'master', array(
        'labels' => array(
            'name' => __('Catalog'),
            'singular_name' => __('Catalog'),
            'add_new_item' => __('Add New Catalog'),
            'edit_item' => __('Edit Catalog'),
        ),
        'hierarchical' => true,
        'rewrite' => array(
            'with_front' => false,
            'slug' => '%section%/mastera',
            'hierarchical' => true,
        ),
        'public' => true,
    ));

    register_taxonomy('section', 'master', array(
        'labels' => array(
            'name' => __('Sections'),
            'singular_name' => __('Section'),
            'add_new_item' => __('Add New Section'),
            'edit_item' => __('Edit Section'),
        ),
        'public' => true,
    ));

    register_taxonomy('location', 'master', array(
        'labels' => array(
            'name' => __('Locations'),
            'singular_name' => __('Location'),
            'add_new_item' => __('Add New Location'),
            'edit_item' => __('Edit Location'),
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => false,
    ));

});