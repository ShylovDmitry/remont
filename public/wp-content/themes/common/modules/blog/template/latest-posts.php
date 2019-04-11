<?php
$container_class = isset($__data['container_class']) ? $__data['container_class'] : '';
$limit = isset($__data['limit']) ? $__data['limit'] : 6;
?>

<?php
$cache_expire = pror_cache_expire(0);
$cache_key = pror_cache_key(sprintf('posts-%s-%s', $container_class, $limit) , 'section,lang');
$cache_group = 'pror:blog:list:latest';

$cache = wp_cache_get($cache_key, $cache_group);
if ($cache):
    echo $cache;
else:
ob_start();
?>

<?php
$query = new WP_Query(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $limit,
));
?>

<?php if ($query->have_posts()): ?>
    <div class="<?php echo $container_class; ?>">
        <div class="latest-posts">
            <h4 class="header-underlined"><?php _e('Последние статьи', 'common'); ?></h4>

            <div class="row">
            <?php $pos = 0; ?>
            <?php while ($query->have_posts()): $query->the_post(); $pos++; ?>
                <div class="col-12 col-md-6">
                    <?php module_template('blog/small'); ?>

                    <?php if ($pos % 2 == 0): ?><div class="w-100"></div><?php endif; ?>
                </div>
            <?php endwhile; ?>
            </div>
        </div>

        <div class="see-blog">
            <a href="<?php echo home_url('/blog/'); ?>" class="btn"><?php _e('Смотреть все статьи', 'common'); ?></a>
        </div>
    </div>
<?php endif; ?>

<?php
wp_cache_add($cache_key, ob_get_flush(), $cache_group, $cache_expire);
endif;
?>
