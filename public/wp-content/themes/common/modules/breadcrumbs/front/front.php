<?php

add_filter('wpseo_breadcrumb_links', function($crumbs) {
    $section = pror_get_section();
    if ($section && $section->slug) {
        $crumbs[0]['url'] .= "{$section->slug}/";
    }

    return $crumbs;
});


add_filter('wp_seo_get_bc_title', function($text, $id) {
    if (get_post_type($id) == 'master') {
        $text = sprintf('%s &laquo;%s&raquo;', get_field('master_type'), $text);
    }

    return $text;
}, 10, 2);
