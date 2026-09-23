<header class="iddi__page-header blog-page__header">
    <?php
    $header_title = 'IDDI Insights';
    if ( is_category() ) {
        $header_title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $header_title = single_tag_title( '', false );
    } elseif ( is_search() ) {
        $header_title = 'Kết quả tìm kiếm';
    } elseif ( is_page() ) {
        $header_title = get_the_title();
    } elseif ( is_home() ) {
        $blog_page_id = get_option('page_for_posts');
        $header_title = $blog_page_id ? get_the_title($blog_page_id) : 'Blog';
    } elseif ( is_archive() ) {
        $header_title = get_the_archive_title();
    }
    ?>
    <h1 class="iddi__page-header__title blog-page__title"><?php echo wp_kses_post( $header_title ); ?></h1>
    
    <form role="search" method="get" class="iddi__page-header__search-form blog-page__search-form " action="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
        <input type="search" class="iddi__page-header__search-input blog-page__search-input" placeholder="Tìm kiếm bài viết..." value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="iddi__page-header__search-submit blog-page__search-submit">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
    </form>
    
    <nav class="iddi__page-header__filter-nav blog-page__filter-nav">
        <ul class="iddi__page-header__filter-list blog-page__filter-list">
            <?php
            $is_all_active = ( !is_category() && !is_search() ) ? ' is-active' : '';
            ?>
            <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="iddi__page-header__filter-link blog-page__filter-link<?php echo $is_all_active; ?>">Tất cả</a></li>
            <?php
            $current_cat_id = is_category() ? get_queried_object_id() : 0;
            $categories = get_categories(array('hide_empty' => 1));
            foreach($categories as $category) {
                $active_class = ($category->term_id == $current_cat_id) ? ' is-active' : '';
                echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '" class="iddi__page-header__filter-link blog-page__filter-link' . $active_class . '">' . esc_html(strtoupper($category->name)) . '</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>
