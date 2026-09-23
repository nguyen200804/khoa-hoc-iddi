<?php get_header(); ?>

<main class="site-main search-page bg-img-fixed">
    <div class="container">
        <div class="search-page__boxed">
            <div class="search-page__wrapper">
                
                <header class="search-page__header">
                    <h1 class="search-page__title">KẾT QUẢ TÌM KIẾM</h1>
                    
                    <?php
                    // Lấy từ khóa và phân loại
                    $search_query = get_search_query();
                    $selected_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : get_query_var('type');
                    ?>
                    
                    <form role="search" method="get" class="search-page__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-page__controls">
                            <div class="search-page__input-container">
                                <span class="search-page__icon">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </span>
                                <input type="search" class="search-page__input" placeholder="Tìm kiếm..." value="<?php echo esc_attr($search_query); ?>" name="s" />
                            </div>
                            <div class="search-page__select-container">
                                <select name="type" class="search-page__select" onchange="this.form.submit()">
                                    <option value="" <?php selected( '', $selected_type ); ?>>Tất cả</option>
                                    <option value="lp_course" <?php selected( 'lp_course', $selected_type ); ?>>Khóa học</option>
                                    <option value="post" <?php selected( 'post', $selected_type ); ?>>Bài viết</option>
                                    <option value="event" <?php selected( 'event', $selected_type ); ?>>Sự kiện</option>
                                </select>
                                <span class="search-page__select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </form>
                    
                    <div class="search-page__count">
                        (<?php echo $wp_query->found_posts; ?>) Kết quả
                    </div>
                </header>

                <div class="search-page__content">
                    <?php if ( have_posts() ) : ?>
                        <div class="search-page__results-list">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <div class="search-page__result-item">
                                    <div class="search-page__result-breadcrumb">
                                        <?php 
                                        $breadcrumbs = array('Home');
                                        $post_type = get_post_type();
                                        if ( $post_type === 'lp_course' ) {
                                            $breadcrumbs[] = 'Khóa học';
                                        } elseif ( $post_type === 'event' ) {
                                            $breadcrumbs[] = 'Sự kiện';
                                        } else {
                                            $categories = get_the_category();
                                            if ( ! empty( $categories ) ) {
                                                $breadcrumbs[] = $categories[0]->name;
                                            } else {
                                                $breadcrumbs[] = 'Bài viết';
                                            }
                                        }
                                        echo esc_html( implode( ' / ', $breadcrumbs ) );
                                        ?>
                                    </div>
                                    <h3 class="search-page__result-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php 
                                            $title = get_the_title();
                                            echo iddi_highlight_search_term( $title ); 
                                            ?>
                                        </a>
                                    </h3>
                                    <div class="search-page__result-excerpt">
                                        <?php 
                                        if ( has_excerpt() ) {
                                            $excerpt = wp_trim_words( get_the_excerpt(), 40, ' [...]' );
                                        } else {
                                            $excerpt = wp_trim_words( get_the_content(), 40, ' [...]' );
                                        }
                                        echo iddi_highlight_search_term( $excerpt );
                                        ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        
                        <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                            <div class="search-page__loadmore-container">
                                <button id="search-page__loadmore-btn" 
                                        data-search="<?php echo esc_attr( $search_query ); ?>" 
                                        data-type="<?php echo esc_attr( $selected_type ); ?>" 
                                        data-current-page="1" 
                                        data-max-pages="<?php echo $wp_query->max_num_pages; ?>"
                                        class="search-page__loadmore-btn">
                                    Xem thêm
                                </button>
                            </div>
                        <?php endif; ?>
                        
                    <?php else : ?>
                        <div style="text-align: center; padding: 60px 0;">
                            <p style="font-size: 18px; color: #6a7b92; margin-bottom: 20px;"><?php _e( 'Không tìm thấy kết quả phù hợp.', 'textdomain' ); ?></p>
                            <p style="font-size: 16px; color: #192954;"><?php _e( 'Vui lòng thử tìm kiếm lại với từ khóa khác.', 'textdomain' ); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    // Xử lý AJAX click "Xem thêm"
    $('#search-page__loadmore-btn').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var search = btn.data('search');
        var type = btn.data('type');
        var currentPage = parseInt(btn.data('current-page'));
        var maxPages = parseInt(btn.data('max-pages'));
        
        var nextPage = currentPage + 1;
        
        btn.text('Đang tải...').prop('disabled', true);
        
        $.ajax({
            url: '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>',
            type: 'POST',
            data: {
                action: 'iddi_load_more_search',
                search: search,
                type: type,
                page: nextPage
            },
            success: function(response) {
                if ($.trim(response) !== '') {
                    $('.search-page__results-list').append(response);
                    btn.data('current-page', nextPage);
                    btn.text('Xem thêm').prop('disabled', false);
                    
                    if (nextPage >= maxPages) {
                        btn.parent().remove();
                    }
                } else {
                    btn.parent().remove();
                }
            },
            error: function() {
                btn.text('Xem thêm').prop('disabled', false);
            }
        });
    });
});
</script>

<?php get_footer(); ?>
