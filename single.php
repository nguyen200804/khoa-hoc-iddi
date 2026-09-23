<?php get_header(); ?>

<main class="site-main single-post__boxed bg-img-fixed">
	<div class="container">
		<?php
		if ( have_posts() ) :
		while ( have_posts() ) : the_post();
		$cats = get_the_category();
		$primary_cat = !empty($cats) ? $cats[0] : null;
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('single-post__wrapper'); ?>>
			<!-- Breadcrumb -->
			<div class="single-post__breadcrumb">
				<a href="<?php echo esc_url( home_url('/') ); ?>">HOME</a> <svg width="0.7em" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001" xml:space="preserve"><path d="M388.819 239.537 156.092 6.816c-9.087-9.089-23.824-9.089-32.912.002-9.087 9.089-9.087 23.824.002 32.912l216.27 216.266-216.273 216.276c-9.087 9.089-9.087 23.824.002 32.912A23.2 23.2 0 0 0 139.636 512a23.2 23.2 0 0 0 16.457-6.817L388.819 272.45a23.27 23.27 0 0 0 0-32.913"/></svg> 
				<?php 
				$blog_pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'template-blog.php'));
				$blog_url = !empty($blog_pages) ? get_permalink($blog_pages[0]->ID) : home_url('/blog/');
				?>
				<a href="<?php echo esc_url( $blog_url ); ?>">BLOG</a> <svg width="0.7em" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001" xml:space="preserve"><path d="M388.819 239.537 156.092 6.816c-9.087-9.089-23.824-9.089-32.912.002-9.087 9.089-9.087 23.824.002 32.912l216.27 216.266-216.273 216.276c-9.087 9.089-9.087 23.824.002 32.912A23.2 23.2 0 0 0 139.636 512a23.2 23.2 0 0 0 16.457-6.817L388.819 272.45a23.27 23.27 0 0 0 0-32.913"/></svg> 
				<?php if ($primary_cat): ?>
				<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="current"><?php echo esc_html(strtoupper($primary_cat->name)); ?></a>
				<?php endif; ?>
			</div>

			<!-- Header -->
			<header class="single-post__header">
				<?php if ($primary_cat): ?>
				<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="single-post__category-badge"><?php echo esc_html($primary_cat->name); ?></a>
				<?php endif; ?>
				<h1 class="single-post__title"><?php the_title(); ?></h1>
				<div class="single-post__meta-wrapper">
					<div class="single-post__author-info">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'single-post__author-avatar') ); ?>
						<div class="single-post__author-details">
							<span class="single-post__author-name"><?php echo esc_html( get_the_author() ); ?></span>
							<span class="single-post__publish-date">Published <?php echo get_the_date('M j, Y'); ?> • <?php echo iddi_estimate_reading_time(get_the_content()); ?> min read</span>
						</div>
					</div>
					<div class="single-post__actions">
						<button class="single-post__action-btn share" title="Share" aria-label="Share">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 8C19.6569 8 21 6.65685 21 5C21 3.34315 19.6569 2 18 2C16.3431 2 15 3.34315 15 5C15 6.65685 16.3431 8 18 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 15C7.65685 15 9 13.6569 9 12C9 10.3431 7.65685 9 6 9C4.34315 9 3 10.3431 3 12C3 13.6569 4.34315 15 6 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 22C19.6569 22 21 20.6569 21 19C21 17.3431 19.6569 16 18 16C16.3431 16 15 17.3431 15 19C15 20.6569 16.3431 22 18 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.59 13.51L15.42 17.49" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.41 6.51L8.59 10.49" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<button class="single-post__action-btn bookmark" title="Bookmark" aria-label="Bookmark">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 21L12 16L5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
				</div>
			</header>

			<div class="single-post__content-container">
				<!-- Sidebar (TOC) -->
				<aside class="single-post__sidebar">
					<div class="single-post__toc-wrapper">
						<button class="single-post__toc-toggle" id="single-post-toc-toggle" aria-expanded="false" aria-label="Table of contents">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
						</button>
						<div class="single-post__toc-dropdown" id="single-post-toc-dropdown">
                            <div class="single-post__toc-dropdown-header">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
							    <h3 class="single-post__toc-title">Table of contents</h3>
                            </div>
							<div id="single-post-toc-list" class="single-post__toc-list">
								<!-- Populated by JS -->
							</div>
						</div>
					</div>
				</aside>

				<!-- Main Content -->
				<div class="single-post__main-content">

					<!-- 					Xuất Featured Image		 -->
					<!--                             <?php if ( has_post_thumbnail() ) : ?>
<div class="single-post__featured-image">
<?php the_post_thumbnail('full'); ?>
</div>
<?php endif; ?> -->

					<div class="single-post__body entry-content">
						<?php
						the_content();

						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'textdomain' ),
							'after'  => '</div>',
						) );
						?>
					</div>
				</div>
			</div>

			<!-- Related Posts -->
			<section class="single-post__related">
				<h2 class="single-post__related-title">Bài viết liên quan</h2>
				<div class="blog-page__grid" >
					<?php
					$related_args = array(
						'category__in'   => wp_get_post_categories($post->ID),
						'post__not_in'   => array($post->ID),
						'posts_per_page' => 4,
						'orderby'        => 'rand'
					);
					$related_query = new WP_Query($related_args);
					if ( $related_query->have_posts() ) :
					while ( $related_query->have_posts() ) : $related_query->the_post();
					get_template_part('template-parts/blog/content');
					endwhile;
					wp_reset_postdata();
					endif;
					?>
				</div>
			</section>

			<!-- Khóa học liên quan -->
			<section class="single-post__related" style="margin-top: 40px;">
				<h2 class="single-post__related-title">Khóa học liên quan</h2>
				<div class="iddi-events__grid d-grid g-column-4">
					<?php
					$related_events_args = array(
						'post_type'      => 'event',
						'posts_per_page' => 4,
						'orderby'        => 'rand'
					);
					$related_events_query = new WP_Query($related_events_args);
					if ( $related_events_query->have_posts() ) :
					while ( $related_events_query->have_posts() ) : $related_events_query->the_post();
					?>
					<?php get_template_part('template-parts/event/content'); ?>
					<?php
					endwhile;
					wp_reset_postdata();
					endif;
					?>
				</div>
			</section>

		</article>

		<?php
		endwhile;
		endif;
		?>
	</div>
</main>

<?php get_footer(); ?>
