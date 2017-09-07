<?php get_header(); ?>

<div class="jumbotron jumbotron-fluid pt-5 pb-5">
    <div class="container text-center frontpage-title">
        <h1 class="display-3">Все <strong>про ремонт</strong> тут</h1>
        <p class="lead mt-3">Тут ви найдете всю необходимую информацию для создания своего уютного уголока.</p>
    </div>
</div>

<?php $tops = get_field( 'frontpage_tops', 'option' ); ?>
<div class="container colored-box py-3">
<!--    <div class="row">-->
<!--        <div class="col-12 mx-auto mt-3">-->
<!--            <ul class="list-inline list-unstyled text-center mb-0">-->
<!--                <li class="list-inline-item">Вибери своє місто:</li>-->
<!--                <li class="list-inline-item"><a href="#">Київ</a></li>-->
<!--                <li class="list-inline-item"><a href="#">Львів</a></li>-->
<!--                <li class="list-inline-item"><a href="#">Одеса</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="col-2 mx-auto mb-3">-->
<!--            <hr />-->
<!--        </div>-->
<!--    </div>-->

    <div class="row">
    <?php foreach ($tops as $pos => $top) : ?>
        <div class="col-6">
            <h4 class="text-center"><?php echo $top['frontpage_top_title']; ?></h4>

            <ul class="list-unstyled mb-0">
            <?php foreach ($top['frontpage_top_items'] as $top_item) : ?>
                <?php $item = get_post($top_item['frontpage_top_item_id']); ?>

                <li class="media mt-4">
                    <a href="<?php echo esc_url( get_permalink($item) ); ?>">
                        <img class="d-flex mr-3" src="http://via.placeholder.com/64" alt="" width="64" />
                    </a>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1"><a href="<?php echo esc_url( get_permalink($item) ); ?>"><?php the_field('master_type', $item); ?> - <?php echo get_the_title($item); ?></a></h5>
                        <?php echo $top_item['frontpage_top_item_text']; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>

        <?php if (($pos+1) % 2 == 0): ?><div class="w-100 my-3"></div><?php endif; ?>
    <?php endforeach; ?>

    </div>


    <?php $catalog_master_page = get_page_by_template_name('template-catalog_master.php'); ?>
    <hr class="my-5"/>
    <div class="row">
        <div class="col-12">
            <h3 class="text-center mb-4"><a href="<?php echo esc_url( get_permalink($catalog_master_page) ); ?>"><?php echo get_the_title($catalog_master_page) ?></a></h3>
        </div>
    <?php
    $main_catalogs = get_terms(array(
        'parent' => 0,
        'hierarchical' => false,
        'taxonomy' => 'catalog_master',
        'hide_empty' => false,
    ));
    ?>
    <?php foreach ($main_catalogs as $pos => $main_catalog): ?>
        <div class="col-4">
            <h6><a href="<?php echo esc_url( get_term_link($main_catalog) ); ?>"><?php echo $main_catalog->name; ?></a> (<?php echo $main_catalog->count; ?>)</h6>

            <?php
            $sub_catalogs = get_terms(array(
                'parent' => $main_catalog->term_id,
                'hierarchical' => false,
                'taxonomy' => 'catalog_master',
                'hide_empty' => false,
            ));
            ?>
            <ul class="list-unstyled">
            <?php foreach ($sub_catalogs as $sub_catalog): ?>
                <li><a href="<?php echo esc_url( get_term_link($sub_catalog) ); ?>"><?php echo $sub_catalog->name; ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php if (($pos+1) % 3 == 0): ?><div class="w-100"></div><?php endif; ?>
    <?php endforeach; ?>
    </div>


<!--    --><?php //$catalog_shop_page = get_page_by_template_name('template-catalog_shop.php'); ?>
<!--    <hr class="my-5"/>-->
<!--    <div class="row">-->
<!--        <div class="col-12">-->
<!--            <h3 class="text-center mb-4"><a href="--><?php //echo esc_url( get_permalink($catalog_shop_page) ); ?><!--">--><?php //echo get_the_title($catalog_shop_page) ?><!--</a></h3>-->
<!--        </div>-->
<!--        <div class="col-12">-->
<!--            --><?php
//            $main_catalogs = get_terms(array(
//                'parent' => 0,
//                'hierarchical' => false,
//                'taxonomy' => 'catalog_shop',
//                'hide_empty' => false,
//            ));
//            ?>
<!---->
<!--            <ul class="list-unstyled list-inline text-center">-->
<!--            --><?php //foreach ($main_catalogs as $pos => $main_catalog): ?>
<!--                <li class="list-inline-item mx-3"><a href="--><?php //echo esc_url( get_term_link($main_catalog) ); ?><!--">--><?php //echo $main_catalog->name; ?><!--</a> (--><?php //echo $main_catalog->count; ?><!--)</li>-->
<!--            --><?php //endforeach; ?>
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
</div>

<?php get_footer(); ?>
