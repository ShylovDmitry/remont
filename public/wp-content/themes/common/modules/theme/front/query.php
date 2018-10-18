<?php

add_filter('posts_clauses', function($clauses, $query) {
    if (is_admin() || (!$query->is_main_query() && $query->query['post_type'] != 'master')) {
        return $clauses;
    }

    global $wpdb;

    if (isset($_GET['f_master_type']) && $_GET['f_master_type']) {
        $join = &$clauses['join'];
        if (!empty($join)) $join .= ' ';
        $join .= "JOIN {$wpdb->prefix}usermeta custom_um ON custom_um.user_id = {$wpdb->posts}.post_author AND custom_um.meta_key = 'master_type'";

        $where = &$clauses['where'];
        $where .= " AND custom_um.meta_value = '" . esc_sql($_GET['f_master_type']) . "'";
    }

    if (isset($query->query['custom_query'])) {
        if ($query->query['custom_query'] == 'with_logo') {
            $join = &$clauses['join'];
            if (!empty($join)) $join .= ' ';
            $join .= "JOIN {$wpdb->prefix}usermeta custom_um_logo ON custom_um_logo.user_id = {$wpdb->posts}.post_author AND custom_um_logo.meta_key = 'master_logo'";

            $where = &$clauses['where'];
            $where .= " AND custom_um_logo.meta_value != ''";
        }
    }

    return $clauses;
}, 10, 2);

global $save_prev_section_value;
add_action('pre_get_posts', function($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_tax('catalog_master')) {
        return;
    }

    // Use default language slug
    $tax = get_term_by('slug', $query->get('catalog_master'), 'catalog_master');
    $default_tax = get_term(pll_get_term($tax->term_id, pll_default_language()));
    $query->set('catalog_master', $default_tax->slug);

    // Display records from specific region
    $query->set('tax_query', array(
        array(
            'taxonomy' => 'location',
            'terms' => get_field('locations', pror_get_section()),
            'include_children' => false,
            'operator' => 'IN',
        )
    ));

    global $save_prev_section_value;
    $save_prev_section_value = $query->get('section');
    $query->set('section', null);
});

add_filter('the_posts', function($posts, $query) {
    global $save_prev_section_value;
    if ($save_prev_section_value) {
        $query->set('section', $save_prev_section_value);
        unset($save_prev_section_value);
    }
    return $posts;
}, 10 ,2);

