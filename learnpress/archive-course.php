<?php
/**
 * Template for displaying archive course content.
 * Fixed by Gemini - Removed extra brace and improved tax_query logic.
 */
defined( 'ABSPATH' ) || exit;

get_header();

/**
 * @since 4.0.0
 * @see LP_Template_General::template_header()
 */
if ( ! wp_is_block_theme() ) {
	do_action( 'learn-press/template-header' );
}

/**
 * LP Hook
 */
do_action( 'learn-press/before-main-content' );

$page_title = learn_press_page_title( false );
?>

<main id="" class="archive_course-category_page bg-img-fixed">
	<section class="iddi-taxonomy-course-catetory-courses">
		<div class="container">

			<header class="iddi__page-header lp-course-page__header">
				<h1 class="iddi__page-header__title lp-course-page__title">
					<?php 
					if ( is_tax() ) {
						single_term_title();
					} else {
						echo esc_html( $page_title );
					}
					?>
				</h1>
				<?php
				if ( is_tax( 'course_category' ) ) {
					$term_desc = term_description();
					if ( ! empty( $term_desc ) ) {
						echo '<div class="iddi-events__subtitle fs-20 fw-600 upper-text fs-16__xl fs-14__md" style="margin-bottom: 24px;">' . wp_kses_post( $term_desc ) . '</div>';
					}
				}
				?>
				
				<?php
				$filter        = isset($_GET['lp-filter']) ? sanitize_text_field($_GET['lp-filter']) : 'all';
				$giang_vien_id = isset($_GET['f-giang-vien']) ? intval($_GET['f-giang-vien']) : 0;
				$search        = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
				$search        = trim($search); 
				?>

				<form role="search" method="get" id="iddi-search-form" class="iddi__page-header__search-form lp-course-page__search-form">
					<input type="search" id="iddi-search-input" class="iddi__page-header__search-input lp-course-page__search-input" placeholder="<?php echo esc_attr( function_exists('iddi_tr') ? iddi_tr('Tìm kiếm khóa học') : 'Search for courses' ); ?>" value="<?php echo esc_attr($search); ?>" name="s" />
					<button type="submit" class="iddi__page-header__search-submit lp-course-page__search-submit">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					</button>
				</form>

				<nav class="iddi__page-header__filter-nav lp-course-page__filter-nav">
					<ul class="iddi__page-header__filter-list lp-course-page__filter-list">
						<?php 
						$tabs = [
							'all'          => function_exists('iddi_tr') ? iddi_tr('TẤT CẢ') : 'TẤT CẢ',
							'not-enrolled' => function_exists('iddi_tr') ? iddi_tr('CHƯA ĐĂNG KÝ') : 'CHƯA ĐĂNG KÝ',
							'newest'       => function_exists('iddi_tr') ? iddi_tr('MỚI NHẤT') : 'MỚI NHẤT',
							'popular'      => function_exists('iddi_tr') ? iddi_tr('PHỔ BIẾN') : 'PHỔ BIẾN',
							'free'         => function_exists('iddi_tr') ? iddi_tr('MIỄN PHÍ') : 'MIỄN PHÍ'
						];
						foreach ($tabs as $key => $label) : 
						$args_url = array('lp-filter' => $key);
						if(!empty($search)) $args_url['s'] = $search;
						if($giang_vien_id > 0) $args_url['f-giang-vien'] = $giang_vien_id;

						$url = ($key === 'all') ? remove_query_arg(array('lp-filter', 'f-giang-vien', 's')) : add_query_arg($args_url);
						$is_active = ($filter === $key);
						if($key === 'all' && ($filter !== 'all' || $giang_vien_id > 0)) $is_active = false;
						$active_class = $is_active ? ' is-active' : '';
						?>
						<li>
							<a href="<?php echo esc_url($url); ?>" class="iddi__page-header__filter-link lp-course-page__filter-link<?php echo $active_class; ?>">
								<?php echo esc_html($label); ?>
							</a>
						</li>
						<?php endforeach; ?>

						<li>
							<select class="iddi__page-header__filter-select" onchange="location = this.value;" style="cursor: pointer;">
								<option value="<?php echo esc_url(remove_query_arg(array('f-giang-vien', 'paged'))); ?>"><?php echo esc_html( function_exists('iddi_tr') ? iddi_tr('GIẢNG VIÊN') : 'GIẢNG VIÊN' ); ?></option>
								<?php
								$giang_viens = get_posts(array(
									'post_type'      => 'giang-vien',
									'posts_per_page' => -1,
									'post_status'    => 'publish',
									'orderby'        => 'title',
									'order'          => 'ASC',
								));
								if ( ! empty( $giang_viens ) ) {
									foreach ( $giang_viens as $gv ) {
										$gv_id = $gv->ID;
										$gv_name = $gv->post_title;
										$gv_url_args = array('f-giang-vien' => $gv_id);
										if(!empty($search)) $gv_url_args['s'] = $search;
										if($filter !== 'all') $gv_url_args['lp-filter'] = $filter;
										$gv_url = add_query_arg($gv_url_args);
										$gv_url = remove_query_arg('paged', $gv_url);
										$selected = ($giang_vien_id == $gv_id) ? 'selected' : '';
										echo '<option value="' . esc_url( $gv_url ) . '" ' . $selected . '>' . esc_html( $gv_name ) . '</option>';
									}
								}
								?>
							</select>
						</li>
					</ul>
				</nav>
			</header>

				<?php
				$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
				$current_term = get_queried_object();

				$args = array(
					'post_type'      => 'lp_course',
					'posts_per_page' => 9,
					'paged'          => $paged,
				);

				// FIX: Chỉ thêm tax_query nếu đang ở trang category cụ thể
				if ( is_tax('course_category') && isset($current_term->term_id) ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'course_category',
							'field'    => 'term_id',
							'terms'    => $current_term->term_id,
						),
					);
				}

				if ( !empty($search) ) $args['s'] = $search;
				
				if ( $giang_vien_id > 0 ) {
					$args['meta_query'] = array('relation' => 'AND');
					$args['meta_query'][] = array(
						'key'     => 'giang_vien_khoa_hoc',
						'value'   => $giang_vien_id,
						'compare' => '='
					);
				}

				switch ($filter) {
					case 'newest':
						$args['orderby'] = 'date';
						$args['order']   = 'DESC';
						break;
					case 'popular':
						$args['meta_key'] = 'count_enrolled_users';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'DESC';
						break;
					case 'free':
						$args['meta_query'][] = array(
							'key'     => '_lp_price',
							'value'   => array('', '0'),
							'compare' => 'IN',
						);
						break;
					case 'not-enrolled':
						if (is_user_logged_in()) {
							$user_id = get_current_user_id();
							global $wpdb;
							$enrolled_ids = $wpdb->get_col($wpdb->prepare(
								"SELECT item_id FROM {$wpdb->prefix}learnpress_user_items WHERE user_id = %d AND item_type = %s",
								$user_id, 'lp_course'
							));
							if (!empty($enrolled_ids)) $args['post__not_in'] = array_map('intval', array_unique($enrolled_ids));
						}
						break;
				}

				$course_query = new WP_Query($args);

				if ($course_query->have_posts()) : ?>
					<div class="iddi-taxonomy-course-catetory-courses__grid d-grid g-column-3 margin-2xl__t">
						<?php while ($course_query->have_posts()) : $course_query->the_post(); 
							$course = learn_press_get_course(get_the_ID()); 
							if ( ! $course ) continue;
							?>
							<?php get_template_part('learnpress/content-course'); ?>
						<?php endwhile; ?>
					</div>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="center-text full-width" style="margin-top: 40px;"><?php iddi_tr_e('Không tìm thấy khóa học nào phù hợp với lựa chọn của bạn.'); ?></p>
				<?php endif; ?>

			<?php if ( $course_query->max_num_pages > 1 ) : ?>
			<div class="iddi__pagination lp-course-page__pagination">
				<?php
				$paginate_args = array('lp-filter' => ($filter !== 'all' ? $filter : ''));
				if ($giang_vien_id > 0) $paginate_args['f-giang-vien'] = $giang_vien_id;
				if (!empty($search)) $paginate_args['s'] = $search;

				$pagination = paginate_links(array(
					'total'     => $course_query->max_num_pages,
					'current'   => $paged,
					'format'    => '?paged=%#%',
					'add_args'  => $paginate_args,
					'show_all'  => false,
					'type'      => 'list',
					'prev_next' => true,
					'prev_text' => function_exists('iddi_tr') ? iddi_tr('< Trước') : '< Trước',
					'next_text' => function_exists('iddi_tr') ? iddi_tr('Tiếp >') : 'Tiếp >',
					'end_size'  => 1,
					'mid_size'  => 2,
				));
				if ($pagination) {
					echo str_replace("<ul class='page-numbers'>", '<ul class="iddi__nav-links lp-course-page__nav-links">', $pagination);
				}
				?>
			</div>
			<?php endif; ?>

		</div>
	</section>

	<section class="iddi-courses__table-price d-none"> 
		<div class="container">
			<?php get_template_part('template-parts/sections/section-table-price'); ?>
		</div>
	</section>

	<?php get_template_part('template-parts/sections/section-testimonials'); ?>

	<section class="iddi-event-detail__contact">
		<div class="container">
			<?php get_template_part('template-parts/sections/section-contact'); ?>
		</div>
	</section>
</main>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const searchForm = document.getElementById('iddi-search-form');
		const searchInput = document.getElementById('iddi-search-input');

		if (searchForm && searchInput) {
			searchForm.addEventListener('submit', function(e) {
				if (!searchInput.value.trim()) {
					e.preventDefault(); 
					const currentUrl = new URL(window.location.href);
					currentUrl.searchParams.delete('s');
					window.location.href = currentUrl.pathname + currentUrl.search;
				}
			});
		}


	});
</script>

<?php
/**
 * LP Hook
 */
do_action( 'learn-press/after-main-content' );

get_footer();
// FIXED: Removed the extra closing brace that was here