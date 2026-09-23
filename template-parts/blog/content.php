<?php
/**
 * Template part for displaying posts in the blog/category grid
 */
$cats = get_the_category();
$primary_cat = !empty($cats) ? strtoupper($cats[0]->name) : 'POST';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-page__card'); ?>>
    <div class="blog-page__card-image-wrap">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        <?php else: ?>
            <a href="<?php the_permalink(); ?>">
                <img src="https://via.placeholder.com/600x400" alt="Placeholder">
            </a>
        <?php endif; ?>
        <span class="blog-page__card-badge"><?php echo esc_html($primary_cat); ?></span>
    </div>
    
    <div class="blog-page__card-content">
        <span class="blog-page__card-date"><?php echo get_the_date('Y-m-d H:i'); ?></span>
        <h3 class="blog-page__card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="blog-page__card-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
        </div>
        <div class="blog-page__card-author">
            By <?php echo get_the_author(); ?>
        </div>
    </div>
</article>
