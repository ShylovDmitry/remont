<?php
$cache_expire = pror_cache_expire(5*60);
$cache_key = pror_cache_key('block', 'section');
$cache_group = 'pror:master:list:front';

$cache = wp_cache_get($cache_key, $cache_group);
if ($cache):
    echo $cache;
else:
ob_start();
?>

<?php
$number_of_masters = 12;
$unique_ids = array();

$pro_masters_query = new WP_Query(array(
    'post_type' => 'master',
    'posts_per_page' => min(6, $number_of_masters),
    'orderby' => 'rand',
    'author__in' => pror_get_query_pro_master_ids(),
    'tax_query' => array(
        array(
            'taxonomy' => 'location',
            'terms' => get_field('locations', pror_get_section()),
            'include_children' => false,
            'operator' => 'IN',
        ),
    ),
));
while ($pro_masters_query->have_posts()) {
    $pro_masters_query->the_post();
    $unique_ids[] = get_the_ID();
}

$rated_masters_query = null;
if (count($unique_ids) < $number_of_masters) {
    $rated_masters_query = new WP_Query(array(
        'post__not_in' => $unique_ids,
        'post_type' => 'master',
        'posts_per_page' => $number_of_masters - count($unique_ids),
        'meta_key' => 'pror-crfp-lower-bound',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'tax_query' => array(
            array(
                'taxonomy' => 'catalog_master',
                'operator' => 'EXISTS',
            ),
            array(
                'taxonomy' => 'location',
                'terms' => get_field('locations', pror_get_section()),
                'include_children' => false,
                'operator' => 'IN',
            ),
        ),
    ));

    while ($rated_masters_query->have_posts()) {
        $rated_masters_query->the_post();
        $unique_ids[] = get_the_ID();
    }
}

$masters_query = null;
if ($rated_masters_query) {
    if (count($unique_ids) < $number_of_masters) {
        $masters_query = new WP_Query(array(
            'post__not_in' => $unique_ids,
            'post_type' => 'master',
            'posts_per_page' => $number_of_masters - count($unique_ids),
            'orderby' => 'rand',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'catalog_master',
                    'operator' => 'EXISTS',
                ),
                array(
                    'taxonomy' => 'location',
                    'terms' => get_field('locations', pror_get_section()),
                    'include_children' => false,
                    'operator' => 'IN',
                ),
            ),
            'custom_query' => 'with_logo',
        ));
    }
}
?>

<div class="master-2columns">
    <div class="row">
        <div class="col-12 mb-3">
            <h3 class="header-underlined">Мастера</h3>
        </div>
    </div>

    <div class="row">
        <?php while ($pro_masters_query->have_posts()): $pro_masters_query->the_post(); ?>
            <div class="col-12">
                <?php module_template('master/item'); ?>
            </div>
        <?php endwhile; ?>

        <?php $pos = 0; ?>
        <?php if ($rated_masters_query): ?>
            <?php while ($rated_masters_query->have_posts()): $rated_masters_query->the_post(); $pos++; ?>
                <div class="col-12">
                    <?php module_template('master/item'); ?>
                </div>

                <?php if ($pos == 1): ?>
                    <div class="col-12 d-lg-none">
                        <div class="master-item">
                            <?php module_template('banner/mobile1'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php if ($masters_query): ?>
            <?php while ($masters_query->have_posts()): $masters_query->the_post(); $pos++; ?>
                <div class="col-12">
                    <?php module_template('master/item'); ?>
                </div>

                <?php if ($pos == 1): ?>
                    <div class="col-12 d-lg-none">
                        <div class="master-item">
                            <?php module_template('banner/mobile1'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
<?php
wp_cache_add($cache_key, ob_get_flush(), $cache_group, $cache_expire);
endif;
?>

<div class="text-center">
    <?php $m_number = pror_catalog_get_count(); ?>
    <a href="<?php echo home_url('/catalog/'); ?>" class="btn masters-see-all">Смотреть <strong><?php echo $m_number; ?></strong> <?php echo pror_declension_words($m_number, ['мастера', 'мастеров', 'мастеров']); ?></a>
</div>
