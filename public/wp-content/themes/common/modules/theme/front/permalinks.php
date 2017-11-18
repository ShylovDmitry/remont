<?php

add_filter('request', function($query_vars) {
    if (is_admin()) {
        return $query_vars;
    }

    $p_url = parse_url($_SERVER['REQUEST_URI']);
    $p_url['query'] = isset($p_url['query']) ? $p_url['query'] : '';
    parse_str($p_url['query'], $q_url);

    if (isset($_GET['f_switch_catalog'])) {
        $term = get_term((int)$_GET['f_switch_catalog'], 'catalog_master');

        $new_path = $term->slug;
        if ($term->parent) {
            $parent_term = get_term($term->parent, 'catalog_master');
            $new_path = "{$parent_term->slug}/{$new_path}";
        }

        $p_url['path'] = str_replace("/{$query_vars['catalog_master']}/", "/{$new_path}/", $p_url['path']);
        unset($q_url['f_switch_catalog']);
    }

    if (isset($_GET['f_switch_section'])) {
        $section = pror_get_section_by_id((int)$_GET['f_switch_section']);

        $p_url['path'] = str_replace("/{$query_vars['section']}/", "/{$section->slug}/", $p_url['path']);
        unset($q_url['f_switch_section']);
    }

    if (isset($_GET['f_master_type']) && empty($_GET['f_master_type'])) {
        unset($q_url['f_master_type']);
    }

    $new_url = $p_url['path'] . (empty($q_url) ? '' : '?' . http_build_query($q_url));
    if ($new_url != $_SERVER['REQUEST_URI']) {
        wp_redirect($new_url);
        exit;
    }


    if ('/' === parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        || strpos($_SERVER['REQUEST_URI'], '/page/') === 0)
    {
        $url = sprintf('/%s%s',
            pror_get_section()->slug,
            $_SERVER['REQUEST_URI']
        );
        wp_redirect($url);
        exit;
    }

    if (isset($query_vars['section']) && get_page_by_path($query_vars['section'])) {
        $query_vars['pagename'] = $query_vars['section'];
        unset($query_vars['section']);
    }

    return $query_vars;
});

add_action('init', function() {
    add_rewrite_rule('([^/]+)/(.?.+?)(?:/([0-9]+))?/?$','index.php?section=$matches[1]&pagename=$matches[2]&page=$matches[3]','top');
});

add_filter('term_link', function($termlink, $term, $taxonomy) {
    return str_replace('%section%', pror_get_section()->slug, $termlink);
}, 10, 3);

add_filter('post_type_link', function($post_link, $post, $leavename, $sample) {
    if (get_post_type($post) == 'master') {
        $locations = get_the_terms($post, 'location');
        if (isset($locations, $locations[0]) && $locations[0]->term_id) {
            $section = pror_get_section_by_location_id($locations[0]->term_id);
            if ($section) {
                $post_link = str_replace('%section%', $section->slug, $post_link);
            }
        }
    }

    return $post_link;
}, 10, 4);
