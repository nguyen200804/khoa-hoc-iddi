<?php get_header(); ?>

<main>
    <h1>Hello from custom HTML</h1>
    <p>This is a blank theme. Add your custom HTML here.</p>

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>

<?php get_footer(); ?>
