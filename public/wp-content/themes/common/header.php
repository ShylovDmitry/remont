<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php wp_title('&ndash;', true, 'right'); ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <link href="<?php echo get_template_directory_uri(); ?>/css/open-iconic/css/open-iconic-bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css">
    <link href="<?php echo get_template_directory_uri(); ?>/css/slick-lightbox.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/common.css">

    <?php wp_head(); ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
    <div class="container">
        <a class="navbar-brand" href="<?php echo home_url('/'); ?>">ProRemont.UA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="form-inline my-2 my-lg-0 ml-5 mr-auto">
                <div class="input-group">
                    <input type="text" size="40" class="form-control form-control-sm" placeholder="Ремонт санузла">
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-outline-success" type="button">Поиск</button>
                    </span>
                </div>
            </form>

            <?php wp_nav_menu(array(
                    'container' => false,
                    'menu_class' => 'navbar-nav',
                    'theme_location' => 'header_main',
            )); ?>
        </div>
    </div>
</nav>

<div class="container mb-1">
    <div class="row">
        <div class="col">
            <?php echo pror_adrotate_group_by_name('leaderboard'); ?>
        </div>
    </div>
</div>