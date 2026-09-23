<?php get_header(); ?>

<main class="site-main">
    <header class="page-header">
        <h1 class="page-title">
            <?php
            if ( is_category() ) {
                single_cat_title();
            } elseif ( is_tag() ) {
                single_tag_title();
            } elseif ( is_author() ) {
                the_post();
                echo 'Author Archives: ' . get_the_author();
                rewind_posts();
            } elseif ( is_day() ) {
                echo 'Daily Archives: ' . get_the_date();
            } elseif ( is_month() ) {
                echo 'Monthly Archives: ' . get_the_date('F Y');
            } elseif ( is_year() ) {
                echo 'Yearly Archives: ' . get_the_date('Y');
            } else {
                echo 'Archives';
            }
            ?>
        </h1>
    </header>

    <div class="archive-content">
        <?php if ( have_posts() ) : ?>
            <div class="post-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </header>
                        
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="pagination">
                <?php 
                echo paginate_links( array(
                    'prev_text' => __( '« Previous', 'textdomain' ),
                    'next_text' => __( 'Next »', 'textdomain' ),
                ) ); 
                ?>
            </div>
        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.', 'textdomain' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
