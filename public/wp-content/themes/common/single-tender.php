<?php get_header(); ?>

<?php module_template('breadcrumbs/breadcrumbs'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <?php module_template('tender/detailed'); ?>
        </div>

        <?php module_template('prom/sidebar-col'); ?>
    </div>
</div>

<?php get_footer(); ?>
