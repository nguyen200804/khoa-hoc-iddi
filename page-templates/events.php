<?php
/**
 * Template Name: Events Page
 */

get_header();

// 1. Xử lý phân trang chuẩn
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

// 2. Lấy giá trị lọc từ URL
$search_val      = isset($_GET['q_event']) ? sanitize_text_field($_GET['q_event']) : '';
$filter_dia_diem = isset($_GET['dia-diem']) ? sanitize_text_field($_GET['dia-diem']) : '';
$filter_linh_vuc = isset($_GET['linh-vuc']) ? sanitize_text_field($_GET['linh-vuc']) : '';
$filter_date     = isset($_GET['f_date']) ? sanitize_text_field($_GET['f_date']) : ''; // Định dạng YYYY-MM
?>

<main id="iddi-events">
	<section class="iddi-events__container">
		<div class="iddi__container">
			<header class="iddi__page-header events-page__header">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <h1 class="iddi__page-header__title events-page__title">
                    <?php the_title(); ?>
                </h1>
                
                <?php if (get_the_content()) : ?>
                <div class="iddi-events__subtitle fs-20 fw-600 upper-text fs-16__xl fs-14__md" style="margin-bottom: 24px;">
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>
                <?php endwhile; endif; ?>

                <form id="iddi-filter-form" method="get" action="<?php echo esc_url(get_permalink()); ?>">
                    <div class="iddi__page-header__search-form events-page__search-form">
                        <input type="search" class="iddi__page-header__search-input events-page__search-input" placeholder="Tìm kiếm sự kiện..." value="<?php echo esc_attr($search_val); ?>" name="q_event" />
                        <button type="submit" class="iddi__page-header__search-submit events-page__search-submit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>
                    
                    <nav class="iddi__page-header__filter-nav events-page__filter-nav">
                        <ul class="iddi__page-header__filter-list events-page__filter-list">
                            <li>
                                <select name="dia-diem" class="iddi__page-header__filter-select iddi-filter__select auto-submit dropdown-selected">
                                    <option value="">Tất cả địa điểm</option>
                                    <?php 
                                    $terms = get_terms(array('taxonomy' => 'dia-diem', 'hide_empty' => false));
                                    if($terms) : foreach ($terms as $term) : ?>
                                        <option value="<?php echo $term->slug; ?>" <?php selected($filter_dia_diem, $term->slug); ?>><?php echo $term->name; ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </li>
                            <li>
                                <select name="linh-vuc" class="iddi__page-header__filter-select iddi-filter__select auto-submit dropdown-selected">
                                    <option value="">Tất cả lĩnh vực</option>
                                    <?php 
                                    $terms = get_terms(array('taxonomy' => 'linh-vuc', 'hide_empty' => false));
                                    if($terms) : foreach ($terms as $term) : ?>
                                        <option value="<?php echo $term->slug; ?>" <?php selected($filter_linh_vuc, $term->slug); ?>><?php echo $term->name; ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </li>
                            <li class="date-input p-relative">
                                <div class="date-placeholder d-flex align-items-center jc-space-between iddi__page-header__filter-select" style="pointer-events: none; gap: 8px;">
                                    <span>
                                        <?php 
                                        if ( !empty($filter_date) ) {
                                            echo 'Tháng ' . date('m/Y', strtotime($filter_date));
                                        } else {
                                            echo 'Tất cả tháng';
                                        }
                                        ?>
                                    </span>
                                    <svg width="0.8em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 448"><path style="fill-opacity:1;stroke-width:32;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none" d="m384 784.8-37.46-36.438L224 867.555 101.46 748.362 64 784.8l37.426 36.404.034-.034L224 940.362 346.54 821.17l.034.034z" transform="translate(0 -604.362)"/></svg>
                                </div>
                                <input type="month" name="f_date" value="<?php echo esc_attr($filter_date); ?>" 
                                       class="auto-submit p-absolute" 
                                       style="top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer; z-index:2;">
                            </li>
                            <?php if($search_val || $filter_dia_diem || $filter_linh_vuc || $filter_date): ?>
                                <li><a href="<?php echo get_permalink(); ?>" class="iddi__page-header__filter-link" style="text-decoration: underline; text-transform: none; color: #f26522;">Xóa lọc</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </form>
            </header>


		<div class="iddi-events__wrapper d-flex flex-column">
			<div class="iddi-events__grid d-grid g-column-4">
				<?php
				// Cấu hình WP_Query bằng cách lấy danh sách ID sự kiện chưa diễn ra trước để tối ưu hiệu suất (Sử dụng Transient Cache)
				$valid_event_ids = get_transient('iddi_valid_event_ids');
				if ( false === $valid_event_ids ) {
					global $wpdb;
					$today = current_time('Ymd');
					$valid_event_ids = $wpdb->get_col($wpdb->prepare("
						SELECT p.ID 
						FROM {$wpdb->posts} p
						LEFT JOIN {$wpdb->postmeta} pm_start ON (p.ID = pm_start.post_id AND pm_start.meta_key = 'date_range_start-date')
						LEFT JOIN {$wpdb->postmeta} pm_end ON (p.ID = pm_end.post_id AND pm_end.meta_key = 'date_range_end-date')
						WHERE p.post_type = 'event' 
						  AND p.post_status = 'publish'
						  AND COALESCE(NULLIF(pm_end.meta_value, ''), pm_start.meta_value) >= %s
					", $today));
					set_transient('iddi_valid_event_ids', $valid_event_ids, 12 * HOUR_IN_SECONDS);
				}

				$args = array(
					'post_type'      => 'event',
					'posts_per_page' => 8,
					'paged'          => $paged,
					's'              => $search_val, 
					'post__in'       => !empty($valid_event_ids) ? $valid_event_ids : array(0),
					'tax_query'      => array('relation' => 'AND'),

					// SẮP XẾP: Theo ngày bắt đầu từ nhỏ đến lớn (ASC)
					'meta_key'       => 'date_range_start-date',
					'orderby'        => 'meta_value',
					'order'          => 'ASC',
					'meta_type'      => 'DATE',
				);

				$args['meta_query'] = array('relation' => 'AND');

				// Logic lọc theo tháng (Nếu có chọn)
				if ( $filter_date ) {
					$first_day = date('Ym01', strtotime($filter_date));
					$last_day  = date('Ymt', strtotime($filter_date));

					$args['meta_query'][] = array(
						'relation' => 'AND',
						array(
							'key'     => 'date_range_start-date',
							'value'   => $last_day,
							'compare' => '<=',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'date_range_end-date',
							'value'   => $first_day,
							'compare' => '>=',
							'type'    => 'DATE'
						),
					);
				}

				if ($filter_dia_diem) {
					$args['tax_query'][] = array('taxonomy' => 'dia-diem', 'field' => 'slug', 'terms' => $filter_dia_diem);
				}

				if ($filter_linh_vuc) {
					$args['tax_query'][] = array('taxonomy' => 'linh-vuc', 'field' => 'slug', 'terms' => $filter_linh_vuc);
				}

				$events_query = new WP_Query($args);

				if ($events_query->have_posts()) :
				while ($events_query->have_posts()) : $events_query->the_post();
				?>
				<?php get_template_part('template-parts/event/content'); ?>
				<?php endwhile; ?>
			</div>

			<div class="iddi__pagination events-page__pagination">
				<?php 
				$big = 999999999;
				$pagination = paginate_links(array(
					'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
					'format'    => '?paged=%#%', 
					'current'   => max(1, $paged),
					'total'     => $events_query->max_num_pages,
					'prev_text' => __( '< Trước', 'textdomain' ),
					'next_text' => __( 'Tiếp >', 'textdomain' ),
					'type'      => 'list',
					'add_args'  => array(
						'q_event'  => $search_val, 
						'f_date'   => $filter_date, 
						'dia-diem' => $filter_dia_diem, 
						'linh-vuc' => $filter_linh_vuc
					)
				));
				if ($pagination) {
					echo str_replace("<ul class='page-numbers'>", '<ul class="iddi__nav-links events-page__nav-links">', $pagination);
				}
				?>
			</div>
			<?php wp_reset_postdata(); ?>
			<?php else : ?>
			<p class="no-results">Không tìm thấy sự kiện nào phù hợp.</p>
			<?php endif; ?>
		</div>
		</div>
	</section>
</main>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const filterForm = document.getElementById('iddi-filter-form');
		// Tự động submit khi đổi giá trị select hoặc input month
		document.querySelectorAll('.auto-submit').forEach(el => {
			el.addEventListener('change', () => filterForm.submit());
		});

	});
</script>

<?php get_footer(); ?>