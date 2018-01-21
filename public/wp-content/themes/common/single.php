<?php get_header(); ?>

<?php module_template('breadcrumbs/breadcrumbs'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="colored-box p-3">
                <h1><?php the_title(); ?></h1>
                <div><?php echo get_the_date(); ?></div>
                <hr />
                <?php the_content(); ?>
            </div>
        </div>

        <?php module_template('banner/sidebar-col'); ?>
    </div>
</div>

<?php get_footer(); ?>
