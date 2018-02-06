<?php

add_action('login_enqueue_scripts', function() {
    wp_enqueue_style('custom-login', get_module_css('master/admin-login.css'), array(), dlv_get_ver());
});

add_action('init', function() {
    if (pror_current_user_has_role('master') || pror_current_user_has_role('subscriber')) {
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
        show_admin_bar(false);
    }
});

add_action('user_register', function($user_id) {
    pror_update_master_info($user_id);
});

add_action('profile_update', function ($user_id, $old_user_data) {
    pror_update_master_info($user_id);
}, 10, 2);


function pror_update_master_info($user_id) {
    if (!pror_user_has_role($user_id, 'master') && !pror_user_has_role($user_id, 'subscriber')) {
        return;
    }

    $user = get_user_by('id', $user_id);

    update_user_meta($user_id, 'show_admin_bar_front', false);

    $should_be_master = get_field('master_should_be', "user_{$user_id}");
    $caps = ($should_be_master == 'yes') ? array('master' => true) : array('subscriber' => true);
    update_user_meta($user_id, 'wp_capabilities', $caps);

    if ($should_be_master != 'yes') {
        $master_post_id = pror_get_master_post_id($user_id);
        if ($master_post_id) {
            wp_update_post(array(
                'ID' => $master_post_id,
                'post_status' => 'draft',
            ));
        }
        return;
    }

    $master_excerpt = get_field('master_excerpt', "user_{$user_id}");
    $master_excerpt = preg_replace('/\s+/', ' ', $master_excerpt);
    update_user_meta($user_id, 'master_excerpt', $master_excerpt);

    $master_post_id = pror_get_master_post_id($user_id, $user->data->user_login);
    if ($master_post_id) {
        $title = get_field('master_title', "user_{$user_id}");

        wp_update_post(array(
            'ID' => $master_post_id,
            'post_title' => $title,
            'post_name' => sanitize_title(pror_master_rus2translit($title)),
            'post_excerpt' => $master_excerpt,
            'post_content' => get_field('master_text', "user_{$user_id}"),
            'comment_status' => 'open',
        ));

        $catalog_terms = get_field('master_catalog', "user_{$user_id}");
        wp_set_post_terms($master_post_id, $catalog_terms, 'catalog_master');

        $location_terms = get_field('master_location', "user_{$user_id}");
        wp_set_post_terms($master_post_id, $location_terms, 'location');

        $logo_id = get_field('master_logo', "user_{$user_id}");
        update_post_meta($master_post_id, '_thumbnail_id', $logo_id);

        wp_update_post(array(
            'ID' => $master_post_id,
            'post_status' => ($title && $catalog_terms && $location_terms) ? 'publish' : 'draft',
        ));
    }
}

add_action('delete_user', function($user_id) {
    $master_post_id = pror_get_master_post_id($user_id);
    if ($master_post_id) {
        wp_delete_post($master_post_id, true);
    }
});


function pror_get_master_post_id($user_id, $title = null) {
    $posts = get_posts(array(
        'author' => $user_id,
        'posts_per_page' => 1,
        'post_type' => 'master',
        'post_status' => 'any',
    ));

    $post_id = isset($posts, $posts[0], $posts[0]->ID) ? $posts[0]->ID : false;
    if ($post_id) {
        return $post_id;
    }
    if (!$title) {
        return false;
    }

    return wp_insert_post(array(
        'post_author' => $user_id,
        'post_title' => $title,
        'post_type' => 'master',
    ));
}

add_action('admin_menu', function() {
    if (pror_current_user_has_role('master')) {
        $master_post_id = pror_get_master_post_id(get_current_user_id());
        if ($master_post_id) {
            global $submenu;
            $submenu['profile.php'][] = array('Перейти на Вашу страницу', 'read', get_permalink($master_post_id), null, 'self-page-link');
            $submenu['profile.php'][] = array('PRO-аккаунт', 'read', home_url('/pro-akkaunt-dlya-masterov/?utm_source=adminpanel&utm_medium=side_menu&utm_campaign=link'), null, 'pro-account-link');
        }
    }
});


function pror_master_rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        'і' => 'i',   'ї' => 'yi',  'є' => 'e',


        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        'І' => 'I',   'Ї' => 'Yi',  'Є' => 'E',
    );
    return strtr($string, $converter);
}