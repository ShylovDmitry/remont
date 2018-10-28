<?php get_header(); ?>

<?php module_template('breadcrumbs/breadcrumbs'); ?>

<div class="container">
    <?php if (pror_user_has_role('master')): ?>
        <?php module_template('profile/profile-master'); ?>
    <?php else: ?>
        <?php module_template('profile/profile-subscriber'); ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
