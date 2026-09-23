<?php
/**
 * Template cho trang chi tiết khóa học LearnPress
 * File: single-lp_course.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course_id = get_the_ID();
$is_offline = get_post_meta($course_id, '_lp_offline_course', true);

// Enqueue CSS sớm để kịp vào wp_head
if ($is_offline === 'yes') {
    wp_enqueue_style('iddi-course-offline', get_template_directory_uri() . '/assets/css/page-templates/course-offline.css', array(), '1.0.0');
}

get_header();

// Lấy đối tượng khóa học của LearnPress
$course = learn_press_get_course( $course_id );

// Nếu là khóa học Offline, load template riêng
if ($is_offline === 'yes') {
    include 'single-course-offline.php';
    return;
}
?>

<main id="main" class="lp-single-course-main py-5 page-single-lp-course">
	<?php while ( have_posts() ) : the_post(); ?>




	<section class="iddi-course-details-info">
		<div class="container">

			<div class="iddi-course-details-info__header center-text italic-font">
				<?php 
				$series_title = function_exists('get_field') ? get_field('course_series_title') : '';
				$custom_title = function_exists('get_field') ? get_field('course_custom_title') : '';
				
				if (empty($series_title)) {
					$series_title = 'ULTIMATE SERIES';
				}
				if (empty($custom_title)) {
					$custom_title = get_the_title();
				}
				?>
				<span class="iddi-course-details-info__sub-title text-color-flame-orange"><?php echo esc_html($series_title); ?></span>
				<p class="iddi-course-details-info__title text-color-oxford-blue"><?php echo esc_html($custom_title); ?></p>
			</div>

			

			<div class="iddi-course-details-info__content  d-flex flex-jc-between">

				<div class="iddi-course-details-info__sidebar">
					<article class="iddi-course-details-info__card padding-xl radius-xl border-width-2 d-flex flex-column gap-m">

						<div class="iddi-course-details-info__card-thumb">
							<?php if (has_post_thumbnail()) : ?>
							<img class="full-width radius-s" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>">
							<?php else : ?>
							<img class="full-width radius-s" src="path-to-default-image.jpg" alt="Default Thumbnail">
							<?php endif; ?>
						</div>

						<h1 class="iddi-course-details-info__card-title text-color-oxford-blue">
							<?php echo get_the_title(); ?>
						</h1>

						<div class="iddi-course-details-info__card-excerpt text-color-oxford-blue">
							<?php 
							$excerpt = get_the_excerpt();
							$excerpt = preg_replace( '/\[\s*(\.|\x{2026}|&hellip;)*\s*\]/u', '', $excerpt );
							echo esc_html( trim( $excerpt ) );
							?>
						</div>

						<div class="iddi-course-details-info__card-footer d-flex flex-ai-end">

							<div class="iddi-course-details-info__card-meta">
								<?php
								// 1. Lấy ID khóa học hiện tại
								$course_id = get_the_ID();

								// 2. Lấy giá trị thô từ meta key
								$regular_price = get_post_meta($course_id, '_lp_regular_price', true);
								$sale_price    = get_post_meta($course_id, '_lp_sale_price', true);
								$raw_duration  = get_post_meta($course_id, '_lp_duration', true);
								$duration      = function_exists('iddi_format_course_duration') ? iddi_format_course_duration($raw_duration) : ($raw_duration ? $raw_duration : 'Khóa học Online');

								/**
 * Hàm hỗ trợ định dạng: Xóa số 0 thừa sau dấu phẩy
 */
								function iddi_format_clean_price($price) {
									if ( empty($price) || $price == 0 ) return 0;

									// Gọi hàm định dạng của LearnPress để lấy giá có Currency (£, $, VNĐ)
									$formatted_price = learn_press_format_price($price, true);

									// Logic: Nếu có dấu thập phân, xóa các số 0 ở cuối, sau đó xóa dấu chấm dư (nếu có)
									// Lưu ý: Tùy vào định dạng Currency là dấu '.' hay ',' mà ta xử lý.
									// Dưới đây xử lý cho dấu chấm (phổ biến nhất).
									if (strpos($formatted_price, '.') !== false) {
										$formatted_price = rtrim(rtrim($formatted_price, '0'), '.');
									}

									return $formatted_price;
								}

								$reg_clean  = floatval($regular_price);
								$sale_clean = floatval($sale_price);

								// 3. Logic hiển thị
								if ( empty($reg_clean) || $reg_clean == 0 ) { 
									echo '<span class="iddi-course-details-info__card-price free-price">' . esc_html( function_exists('iddi_tr') ? iddi_tr('Free') : 'Free' ) . '</span>';

								} elseif ( !empty($sale_clean) && $sale_clean < $reg_clean ) { 
								?>
								<span class="iddi-course-details-info__card-price">
									<span class="text-color-flame-orange">
										<?php echo iddi_format_clean_price($sale_clean); ?>
									</span>
									<del>
										<?php echo iddi_format_clean_price($reg_clean); ?>
									</del>
								</span>
								<?php 

								} else { 
								?>
								<span class="iddi-course-details-info__card-price">
									<?php echo iddi_format_clean_price($reg_clean); ?>
								</span>
								<?php 
								} 
								?>

								<span class="iddi-course-details-info__card-duration"><?php echo esc_html($duration); ?></span>
							</div>

							<div class="iddi-course-details-info__card-btn-wrapper">
    <?php
    $is_enrolled = false;
    $is_completed = false;
    $first_item_link = '';
    $progress = 0;
    $is_free = false;

    if ( is_user_logged_in() && isset( $course ) && $course ) {
        $user_id = get_current_user_id();
        $course_id = $course->get_id();
        $lp_user = learn_press_get_user( $user_id );
        
        // Kiểm tra xem khóa học có phải khóa học miễn phí (Free) hay không
        $course_reg_price = get_post_meta($course_id, '_lp_regular_price', true);
        $is_free = ( empty($course_reg_price) || floatval($course_reg_price) == 0 );

        if ( $lp_user ) {
            $is_enrolled = $lp_user->has_enrolled_course( $course_id );
            $course_data = $lp_user->get_course_data( $course_id );
            if ( $course_data ) {
                if ( method_exists( $course_data, 'is_completed' ) && $course_data->is_completed() ) {
                    $is_completed = true;
                } else {
                    $course_results = $course_data->get_results( false );
                    $progress = isset($course_results['result']) ? absint( $course_results['result'] ) : 0;
                    if ( $progress >= 100 ) {
                        $is_completed = true;
                    }
                }
            }
        }

        // DB Fallback if not completed/enrolled yet according to LearnPress API
        if ( ! $is_completed || ! $is_enrolled ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'learnpress_user_items';
            $db_item = $wpdb->get_row( $wpdb->prepare(
                "SELECT status FROM $table_name WHERE user_id = %d AND item_id = %d AND item_type = %s ORDER BY user_item_id DESC LIMIT 1",
                $user_id,
                $course_id,
                'lp_course'
            ) );
            if ( $db_item ) {
                if ( in_array( $db_item->status, array('completed', 'passed', 'finished') ) ) {
                    $is_completed = true;
                    $is_enrolled = true;
                }
                if ( in_array( $db_item->status, array('enrolled', 'in-progress') ) ) {
                    $is_enrolled = true;
                }
            }
        }

        if ( $is_enrolled || $is_completed || $is_free ) {
            // Lấy link bài học/quiz đầu tiên
            $curriculum = $course->get_curriculum();
            if ( $curriculum ) {
                foreach ( $curriculum as $section ) {
                    $items = $section->get_items();
                    if ( $items ) {
                        $first_item = reset( $items );
                        if ( $first_item ) {
                            $first_item_link = $first_item->get_permalink();
                            break;
                        }
                    }
                }
            }
        }
    }

    if ( $is_completed && ! empty( $first_item_link ) ) {
        ?>
        <a href="<?php echo esc_url( $first_item_link ); ?>" class="iddi-course-details-info__card-btn padding-s__v center-text text-color-white radius-s bg-color_color-flame-orange d-block">
            <?php iddi_tr_e('Xem lại bài học'); ?>
        </a>
        <?php
    } elseif ( ( $is_enrolled || $is_free ) && ! empty( $first_item_link ) ) {
        $button_text = ( $progress > 0 ) ? iddi_tr('Tiếp tục học') : iddi_tr('Bắt đầu học');
        ?>
        <a href="<?php echo esc_url( $first_item_link ); ?>" class="iddi-course-details-info__card-btn padding-s__v center-text text-color-white radius-s bg-color_color-flame-orange d-block">
            <?php echo esc_html( $button_text ); ?>
        </a>
        <?php
    } elseif ( ! is_user_logged_in() ) {
        ?>
        <button type="button" onclick="window.openLoginPopup()" class="iddi-course-details-info__card-btn reset-button" style="cursor: pointer;">
            <?php iddi_tr_e('Đăng nhập để học'); ?>
        </button>
        <?php
    } elseif ( isset( $course ) && $course ) {
        // Sử dụng template button mặc định của LearnPress để đảm bảo đầy đủ chức năng (AJAX, chuyển hướng...)
        // Hàm này sẽ tự động hiển thị nút Enrol, Purchase, hoặc Continue dựa trên trạng thái người dùng
        do_action( 'learn-press/course-buttons' );
    } else {
        // Fallback: Nếu không lấy được object $course, hiển thị nút tĩnh nhưng trỏ tới link mua
        ?>
        <a href="?purchase-course=<?php echo get_the_ID(); ?>" class="iddi-course-details-info__card-btn padding-s__v center-text text-color-white radius-s bg-color_color-flame-orange d-block">
            <?php iddi_tr_e('Đăng ký học'); ?>
        </a>
        <?php
    }
    ?>
</div>

						</div>

					</article>
				</div>

				<div class="iddi-course-details-info__main-text text-color-oxford-blue">
					<?php the_content(); ?>
				</div>

			</div>

		</div>
	</section>



	<section class="iddi-course-details-content">
		<div class="container">
			<div class="iddi-course-details-content__header italic-font">
				<?php 
				$structure_title    = function_exists('get_field') ? get_field('course_structure_title') : '';
				$structure_subtitle = function_exists('get_field') ? get_field('course_structure_subtitle') : '';
				
				if (empty($structure_title)) {
					$structure_title = 'Course Structure';
				}
				if (empty($structure_subtitle)) {
					$structure_subtitle = 'Diploma in Digital Orthodontics & clear aligner treatment';
				}
				?>
				<h2 class="iddi-course-details-content__title text-color-oxford-blue"><?php echo esc_html($structure_title); ?></h2>
				<p class="iddi-course-details-content__sub-title text-color-flame-orange"><?php echo esc_html($structure_subtitle); ?></p>
			</div>


			<div class="iddi-course-details-content__list d-flex flex-column">
				<?php
				$course_id = get_the_ID(); 
				$course = learn_press_get_course($course_id);

				if ($course) {
					$curriculum = $course->get_curriculum();
					if ($curriculum) {
						$section_index = 1;
						foreach ($curriculum as $section) {
							$section_title = $section->get_title();
							$items = $section->get_items();
				?>

				<div class="iddi-accordion-item">
					<div class="iddi-accordion-header">
						<span class="section-number text-color-oxford-blue"><?php echo sprintf('%02d', $section_index); ?></span>
						<h3 class="section-title text-color-oxford-blue"><?php echo esc_html($section_title); ?></h3>
						<span class="accordion-icon ">
							<?php echo get_my_svg('double-chevron-down'); ?>
						</span>
					</div>

					<div class="iddi-accordion-content">
						<div class="iddi-accordion-inner">
							<?php if ($items) : ?>
							<ul class="lesson-list">
								<?php foreach ($items as $item) : 
							$item_id    = $item->get_id();
							$item_title = $item->get_title();
							$item_type  = $item->get_item_type();

							// Lấy Permalink của bài học hoặc trắc nghiệm
							$item_link  = $item->get_permalink(); 

							$raw_item_dur = get_post_meta($item_id, '_lp_duration', true);
							$duration     = function_exists('iddi_format_course_duration') ? iddi_format_course_duration($raw_item_dur) : $raw_item_dur;

							// Xác định icon dựa trên loại bài viết và sự hiện diện của video
							$lesson_icon_html = '';
							if ( $item_type === 'lp_quiz' ) {
								$lesson_icon_html = '<svg fill="currentColor" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path d="M512 0C229.232 0 0 229.232 0 512c0 282.784 229.232 512 512 512 282.784 0 512.017-229.216 512.017-512C1024.017 229.232 794.785 0 512 0m0 961.008c-247.024 0-448-201.984-448-449.01 0-247.024 200.976-448 448-448s448.017 200.977 448.017 448S759.025 961.009 512 961.009zm-47.056-160.529h80.512v-81.248h-80.512zm46.112-576.944c-46.88 0-85.503 12.64-115.839 37.889-30.336 25.263-45.088 75.855-44.336 117.775l1.184 2.336h73.44c0-25.008 8.336-60.944 25.008-73.84 16.656-12.88 36.848-19.328 60.56-19.328 27.328 0 48.336 7.424 63.073 22.271 14.72 14.848 22.063 36.08 22.063 63.664 0 23.184-5.44 42.976-16.368 59.376-10.96 16.4-29.328 39.841-55.088 70.322-26.576 23.967-42.992 43.231-49.232 57.807-6.256 14.592-9.504 40.768-9.744 78.512h76.96c0-23.68 1.503-41.136 4.496-52.336 2.975-11.184 11.504-23.823 25.568-37.888 30.224-29.152 54.496-57.664 72.88-85.551 18.336-27.857 27.52-58.593 27.52-92.193 0-46.88-14.176-83.408-42.577-109.568-28.416-26.176-68.272-39.248-119.568-39.248"/></svg>';
							} else {
								$video_embed = get_post_meta( $item_id, '_lp_video_embed_code', true );
								if ( empty( $video_embed ) && function_exists( 'get_field' ) ) {
									$video_embed = get_field( 'video_embed', $item_id );
								}
								if ( ! empty( $video_embed ) ) {
									$lesson_icon_html = '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="1.5"/><path d="M9 17V7l8 5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
								} else {
									$lesson_icon_html = '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 305.406 305.406" xml:space="preserve"><path d="M123.901 137.703a9 9 0 0 0 9 9h80c4.971 0 9-4.029 9-9s-4.029-9-9-9h-80a9 9 0 0 0-9 9m89 39.983h-80a9 9 0 0 0-9 9 9 9 0 0 0 9 9h80a9 9 0 0 0 9-9 9 9 0 0 0-9-9m0 48.983h-80c-4.971 0-9 4.029-9 9s4.029 9 9 9h80c4.971 0 9-4.029 9-9s-4.029-9-9-9"/><circle cx="101.901" cy="137.703" r="9.396"/><circle cx="101.901" cy="186.686" r="9.396"/><circle cx="101.901" cy="235.669" r="9.396"/><path d="M267.067 70.613 199.091 2.636A9 9 0 0 0 192.727 0H44.703a9 9 0 0 0-9 9v287.406a9 9 0 0 0 9 9h216a9 9 0 0 0 9-9V76.977a9 9 0 0 0-2.636-6.364m-67.34-41.885 41.249 41.249h-41.249zm51.976 258.678h-198V18h128.023v60.977a9 9 0 0 0 9 9h60.977z"/></svg>';
								}
							}
								?>
								<li class="lesson-item">
									<div class="lesson-link d-flex justify-content-between align-items-center w-100">
										<div class="lesson-info">
											<span class="lesson-icon"><?php echo $lesson_icon_html; ?></span>
											<span class="lesson-name"><?php echo esc_html($item_title); ?></span>
										</div>
										<?php if($duration): ?>
										<span class="lesson-duration"><?php echo esc_html($duration); ?></span>
										<?php endif; ?>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
							<?php else : ?>
							<p class="no-content"><?php iddi_tr_e('Chương này chưa có nội dung.'); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php
							$section_index++;
						}
					}
				}
				?>
			</div>
		</div>
	</section>






	<section class="iddi-courses__online-tutorial-courses">
		<div class="container">

			<div class="iddi-courses__online-tutorial-courses-header">
				<h2 class="iddi-courses__online-tutorial-courses-title text-color-oxford-blue">
					<span class="italic-font">Other</span> <span class="text-color-flame-orange">IDDI Courses</span>
				</h2>
			</div>

			<?php
			$current_course_id = get_the_ID();

			// 1. Lấy danh sách ID của các danh mục mà khóa học hiện tại đang thuộc về
			$terms = get_the_terms($current_course_id, 'course_category');
			$term_ids = array();

			if ($terms && !is_wp_error($terms)) {
				foreach ($terms as $term) {
					$term_ids[] = $term->term_id;
				}
			}

			// 2. Cấu hình Query
			$args = array(
				'post_type'      => 'lp_course',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				'post__not_in'   => array($current_course_id), // Loại trừ khóa học hiện tại đang xem
			);

			// 3. Nếu có danh mục, thêm tax_query để lọc bài cùng chuyên mục
			if (!empty($term_ids)) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'course_category',
						'field'    => 'term_id',
						'terms'    => $term_ids,
						'operator' => 'IN',
					),
				);
			}

			$course_query = new WP_Query($args);

			if ($course_query->have_posts()) : ?>
			<div class="iddi-courses__online-tutorial-courses-list d-grid g-column-3">

				<?php while ($course_query->have_posts()) : $course_query->the_post(); ?>
					<?php get_template_part('learnpress/content-course'); ?>
				<?php endwhile; wp_reset_postdata(); ?>

			</div>
			<?php else : ?>
			<p><?php iddi_tr_e('Không tìm thấy khóa học liên quan.'); ?></p>
			<?php endif; ?>

			<div class="iddi-courses__online-tutorial-courses-action right-text">
				<a href="#" class="iddi-courses__online-tutorial-courses-btn italic-font d-i-flex flex-ai-center text-color-oxford-blue">
					<span><?php iddi_tr_e('All Online Courses'); ?></span>
					<?php echo get_my_svg('explore'); ?>
				</a> 
			</div>

		</div>
	</section>





	<section class="iddi-courses__table-price"> 
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






	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const headers = document.querySelectorAll('.iddi-accordion-header');

			headers.forEach(header => {
				header.addEventListener('click', function() {
					const content = this.nextElementSibling; // Lấy thẻ .iddi-accordion-content ngay sau

					// Đóng các mục khác (Nếu muốn kiểu Accordion truyền thống)
					headers.forEach(otherHeader => {
						if (otherHeader !== this && otherHeader.classList.contains('active')) {
							otherHeader.classList.remove('active');
							otherHeader.nextElementSibling.style.maxHeight = null;
						}
					});

					// Toggle class active cho header hiện tại
					this.classList.toggle('active');

					if (this.classList.contains('active')) {
						// Nếu đang mở: Gán max-height bằng đúng chiều cao nội dung (scrollHeight)
						content.style.maxHeight = content.scrollHeight + "px";
					} else {
						// Nếu đang đóng: Trả về 0
						content.style.maxHeight = null;
					}
				});
			});
		});
	</script>


	<?php endwhile; ?>
</main>

<?php
get_footer();