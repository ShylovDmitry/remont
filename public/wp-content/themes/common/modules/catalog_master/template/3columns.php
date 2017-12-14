<div class="catalog-mater-3col" id="catalogMenu" data-pror-children=".item">
<!--    <h3 class="text-center mb-4">Каталог - --><?php //echo pror_get_section()->name; ?><!--</h3>-->

<!--            --><?php
//
//                wp_dropdown_categories(array(
//                    'hide_empty' => 0,
//                    'taxonomy' => 'catalog_master',
////                    'selected' => get_queried_object_id(),
//                    'hierarchical' => 1,
//                    'name' => 'f_switch_catalog',
//                    'id' => 'master_search_catalog1',
//                    'class' => 'form-control',
//                    'show_option_none' => 'Выберете категорию',
//                ));
//            ?>


    <?php foreach(array_chunk(pror_get_catalog(), 3) as $catalogs_part): ?>
        <div class="row mt-3 mb-1">
            <?php foreach($catalogs_part as $main_catalog): ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="catalog-title">
                        <a class="pror-collapse" href="#catalogSubcategory_<?php echo $main_catalog->term_id; ?>" data-pror-target="#catalogSubcategory_<?php echo $main_catalog->term_id; ?>" data-pror-parent="#catalogMenu">
                            <span class="icon"><?php module_svg("catalog_master/{$main_catalog->slug}.svg"); ?></span>
                            <span class="text"><span><?php echo $main_catalog->name; ?></span></span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach($catalogs_part as $p => $main_catalog): ?>
            <div class="item item-<?php echo $p%3 + 1; ?> py-3 px-3 mt-4 d-none" id="catalogSubcategory_<?php echo $main_catalog->term_id; ?>">
                <div class="arrow"></div>

                <a class="all" href="<?php echo esc_url( get_term_link($main_catalog) ); ?>">Смотреть все</a> <span class="help-text">из раздела <?php echo $main_catalog->name; ?></span>
                <hr />

                <div class="row">
                    <?php foreach (pror_get_catalog($main_catalog->term_id) as $pos => $sub_catalog): ?>
                        <div class="col-12 col-md-6 my-1"><a href="<?php echo esc_url( get_term_link($sub_catalog) ); ?>"><?php echo $sub_catalog->name; ?></a></div>
                        <?php if (($pos+1) % 2 == 0): ?><div class="w-100"></div><?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
