<?php
$container_class = isset($__data['container_class']) ? $__data['container_class'] : '';
?>

<?php
$cache_expire = pror_cache_expire(5*60);
$cache_key = pror_cache_key(sprintf('block-%s', $container_class) , 'section');
$cache_group = 'pror:partner:list:front';

$cache = wp_cache_get($cache_key, $cache_group);
if ($cache):
    echo $cache;
else:
ob_start();
?>

<?php
$query = new WP_Query(array(
    'post_type' => 'partner',
    'posts_per_page' => 12,
    'orderby' => 'rand',
    'order' => 'DESC',
));
?>

<?php if ($query->have_posts()): ?>
    <div class="<?php echo $container_class; ?>">
        <div class="list">
            <div class="row">
                <div class="col-12 mb-1">
                    <h3 class="header-underlined">Партнеры</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="partner-wrapper mb-3">
                        <div class="partner-carousel">
                            <?php while ($query->have_posts()): $query->the_post(); ?>
                                <?php module_template('partner/slide'); ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-1">
            <a href="<?php echo home_url('/partners/'); ?>" class="btn partners-see-all">Смотреть всех партнеров</a>
        </div>
    </div>
<?php endif; ?>

<?php
wp_cache_add($cache_key, ob_get_flush(), $cache_group, $cache_expire);
endif;
?>
