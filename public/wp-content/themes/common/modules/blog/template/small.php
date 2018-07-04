<article class="small-post my-3">
    <a class="post-img-link" href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?></a>
    <h5 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a></h5>
    <div class="post-categories"><?php the_terms(get_the_ID(), 'category'); ?></div>
    <div class="post-excerpt"><?php the_excerpt(); ?></div>
    <div class="row">
        <div class="col-6">
            <div class="post-date"><?php echo get_the_date(); ?></div>
        </div>
        <div class="col-6 text-right">
            <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr(get_the_title()); ?>" class="post-more-link"><?php _e('Подробнее &raquo;', 'common'); ?></a>
        </div>
    </div>
</article>
