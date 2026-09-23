<?php
/**
 * Template Name: Blog Trang Chủ
 * Description: Template dùng cho trang tổng hợp bài viết (Blog).
 */

get_header(); ?>

<main class="site-main blog-page__boxed bg-img-fixed">
	<div class="container">
		<div class="blog-page__wrapper">


			<?php get_template_part('template-parts/blog/header'); ?>

			<div class="blog-page__content">
				<?php 
				// Lấy trang hiện tại cho phân trang
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				// Truy vấn danh sách bài viết
				$args = array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => 8,
					'paged'          => $paged,
				);

				$search_term = get_search_query();
				if ( ! empty( $search_term ) ) {
					$args['s'] = $search_term;
				}

				$blog_query = new WP_Query( $args );

				if ( $blog_query->have_posts() ) : ?>
				<div class="blog-page__grid">
					<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
					<?php get_template_part('template-parts/blog/content'); ?>
					<?php endwhile; ?>
				</div>

				<div class="iddi__pagination blog-page__pagination">
					<?php 
					$pagination = paginate_links( array(
						'total'     => $blog_query->max_num_pages,
						'current'   => $paged,
						'prev_text' => __( '< Trước', 'textdomain' ),
						'next_text' => __( 'Tiếp >', 'textdomain' ),
						'type'      => 'list',
					) ); 
					if ($pagination) {
						echo str_replace("<ul class='page-numbers'>", '<ul class="iddi__nav-links blog-page__nav-links">', $pagination);
					}
					?>
				</div>

				<?php wp_reset_postdata(); ?>
				<?php else : ?>
				<p><?php _e( 'Chưa có bài viết nào.', 'textdomain' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
