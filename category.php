<?php get_header(); ?>

<main class="site-main  blog-page__boxed bg-img-fixed">
    <div class="container">
		<div class="blog-page__wrapper">
        <?php get_template_part('template-parts/blog/header'); ?>

        <div class="blog-page__content">
            <?php if ( have_posts() ) : ?>
                <div class="blog-page__grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part('template-parts/blog/content'); ?>
                    <?php endwhile; ?>
                </div>
                
                <div class="iddi__pagination blog-page__pagination">
                    <?php 
                    $pagination = paginate_links( array(
                        'prev_text' => __( '< Trước', 'textdomain' ),
                        'next_text' => __( 'Tiếp >', 'textdomain' ),
                        'type'      => 'list',
                    ) ); 
                    if ($pagination) {
                        echo str_replace("<ul class='page-numbers'>", '<ul class="iddi__nav-links blog-page__nav-links">', $pagination);
                    }
                    ?>
                </div>
            <?php else : ?>
                <p><?php _e( 'Sorry, no posts matched your criteria in this category.', 'textdomain' ); ?></p>
            <?php endif; ?>
        </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
