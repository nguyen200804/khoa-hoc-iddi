<?php
/**
 * Polylang Integration & Multi-language Helpers for IDDI Academy
 * File: inc/polylang-helpers.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Helper function để lấy chuỗi dịch an toàn (Safe translation helper)
 * Trả về chuỗi đã dịch nếu Polylang khả dụng, hoặc chuỗi gốc nếu chưa dịch / Polylang chưa bật.
 *
 * @param string $string Chuỗi cần dịch
 * @param string $context Ngữ cảnh / Domain
 * @return string
 */
if ( ! function_exists( 'iddi_tr' ) ) {
	function iddi_tr( $string, $context = 'iddi-academy' ) {
		if ( function_exists( 'pll__' ) ) {
			return pll__( $string );
		}
		return __( $string, $context );
	}
}

/**
 * Helper function để in trực tiếp chuỗi dịch
 *
 * @param string $string Chuỗi cần dịch
 * @param string $context Ngữ cảnh / Domain
 */
if ( ! function_exists( 'iddi_tr_e' ) ) {
	function iddi_tr_e( $string, $context = 'iddi-academy' ) {
		echo iddi_tr( $string, $context );
	}
}

/**
 * 2. Đăng ký các chuỗi tĩnh giao diện với Polylang (Polylang String Registration)
 * Chuỗi sẽ xuất hiện trong menu Languages -> Translations trong WP-Admin.
 */
function iddi_register_polylang_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	// --- Header & Navigation ---
	pll_register_string( 'Header CTA Button', 'Đăng ký ngay', 'IDDI Header' );
	pll_register_string( 'Header Hotline Label', 'Hotline', 'IDDI Header' );
	pll_register_string( 'Header Search Label', 'Tìm kiếm', 'IDDI Header' );
	pll_register_string( 'Header Search Placeholder', 'Nhập từ khóa tìm kiếm...', 'IDDI Header' );
	pll_register_string( 'Header Login Button', 'Đăng nhập', 'IDDI Header' );
	pll_register_string( 'Header My Account', 'Tài khoản của tôi', 'IDDI Header' );
	pll_register_string( 'Header Logout', 'Đăng xuất', 'IDDI Header' );

	// --- Footer ---
	pll_register_string( 'Footer About Title', 'Về IDDI Academy', 'IDDI Footer' );
	pll_register_string( 'Footer Links Title', 'Liên kết hữu ích', 'IDDI Footer' );
	pll_register_string( 'Footer Contact Title', 'Thông tin liên hệ', 'IDDI Footer' );
	pll_register_string( 'Footer Copyright', 'Copyright © 2026 IDDI Academy. All rights reserved.', 'IDDI Footer' );

	// --- LearnPress / Courses Common Strings ---
	pll_register_string( 'Course All Courses', 'Tất cả khóa học', 'IDDI LearnPress' );
	pll_register_string( 'Course View Details', 'Xem chi tiết', 'IDDI LearnPress' );
	pll_register_string( 'Course Enroll Now', 'Đăng ký khóa học', 'IDDI LearnPress' );
	pll_register_string( 'Course Continue Learning', 'Tiếp tục học', 'IDDI LearnPress' );
	pll_register_string( 'Course Start Learning', 'Bắt đầu học', 'IDDI LearnPress' );
	pll_register_string( 'Course Review Lesson', 'Xem lại bài học', 'IDDI LearnPress' );
	pll_register_string( 'Course Login to Learn', 'Đăng nhập để học', 'IDDI LearnPress' );
	pll_register_string( 'Course Enroll Now Btn', 'Đăng ký học', 'IDDI LearnPress' );
	pll_register_string( 'Course Empty Section', 'Chương này chưa có nội dung.', 'IDDI LearnPress' );
	pll_register_string( 'Course Online Course', 'Khóa học Online', 'IDDI LearnPress' );
	pll_register_string( 'Course Free Label', 'Free', 'IDDI LearnPress' );
	pll_register_string( 'Course All Online Courses', 'All Online Courses', 'IDDI LearnPress' );
	pll_register_string( 'Course Curriculum', 'Nội dung khóa học', 'IDDI LearnPress' );
	pll_register_string( 'Course Instructor', 'Giảng viên', 'IDDI LearnPress' );
	pll_register_string( 'Course Duration', 'Thời lượng', 'IDDI LearnPress' );
	pll_register_string( 'Course Lessons', 'Bài học', 'IDDI LearnPress' );
	pll_register_string( 'Course Certificate', 'Chứng chỉ', 'IDDI LearnPress' );
	pll_register_string( 'Course Price', 'Học phí', 'IDDI LearnPress' );
	pll_register_string( 'Course Free', 'Miễn phí', 'IDDI LearnPress' );
	pll_register_string( 'Course Overview', 'Tổng quan', 'IDDI LearnPress' );
	pll_register_string( 'Course Search Placeholder', 'Tìm kiếm khóa học', 'IDDI LearnPress' );
	pll_register_string( 'Course Filter All', 'TẤT CẢ', 'IDDI LearnPress' );
	pll_register_string( 'Course Filter Not Enrolled', 'CHƯA ĐĂNG KÝ', 'IDDI LearnPress' );
	pll_register_string( 'Course Filter Newest', 'MỚI NHẤT', 'IDDI LearnPress' );
	pll_register_string( 'Course Filter Popular', 'PHỔ BIẾN', 'IDDI LearnPress' );
	pll_register_string( 'Course Filter Free', 'MIỄN PHÍ', 'IDDI LearnPress' );
	pll_register_string( 'Course Filter Lecturer', 'GIẢNG VIÊN', 'IDDI LearnPress' );
	pll_register_string( 'Course No Match Found', 'Không tìm thấy khóa học nào phù hợp với lựa chọn của bạn.', 'IDDI LearnPress' );

	// --- Events Strings ---
	pll_register_string( 'Event Speakers Title', 'Báo cáo viên', 'IDDI Event' );
	pll_register_string( 'Event Day Label', 'Ngày', 'IDDI Event' );
	pll_register_string( 'Event Morning Label', 'Sáng', 'IDDI Event' );
	pll_register_string( 'Event Afternoon Label', 'Chiều', 'IDDI Event' );
	pll_register_string( 'Event Contact Price', 'Liên hệ', 'IDDI Event' );
	pll_register_string( 'Event No Related Events', 'Chưa có sự kiện liên quan.', 'IDDI Event' );

	// --- Homepage & Sections ---
	pll_register_string( 'Homepage Tooth Label 1', 'Tiêu chuẩn đào tạo Quốc Tế', 'IDDI Homepage' );
	pll_register_string( 'Homepage Tooth Label 2', 'Đào tạo chuyên nghiệp từ Giáo sư đầu ngành', 'IDDI Homepage' );
	pll_register_string( 'Homepage Tooth Label 3', 'Dựa trên bằng chứng khoa học', 'IDDI Homepage' );
	pll_register_string( 'Homepage Tooth Label 4', 'Tiên phong công nghệ trong chẩn đoán và điều trị', 'IDDI Homepage' );
	pll_register_string( 'Homepage Get in Touch', 'Liên hệ', 'IDDI Homepage' );
	pll_register_string( 'Contact Form Shortcode', '[contact-form-7 id="7d92c07" title="Liên hệ ngay"]', 'IDDI Contact Form' );

	// --- Common Buttons & Labels ---
	pll_register_string( 'Common Read More', 'Xem thêm', 'IDDI Common' );
	pll_register_string( 'Common Close', 'Đóng', 'IDDI Common' );
	pll_register_string( 'Common Back', 'Quay lại', 'IDDI Common' );
	pll_register_string( 'Common Send', 'Gửi thông tin', 'IDDI Common' );
	pll_register_string( 'Common Success', 'Thành công', 'IDDI Common' );
	pll_register_string( 'Pagination Prev', '< Trước', 'IDDI Common' );
	pll_register_string( 'Pagination Next', 'Tiếp >', 'IDDI Common' );
}
add_action( 'after_setup_theme', 'iddi_register_polylang_strings' );

/**
 * Định dạng và dịch thời lượng khóa học phù hợp với ngôn ngữ hiện tại
 *
 * @param string $duration Thời lượng gốc từ meta LearnPress (vd: "10 hours", "5 weeks")
 * @return string
 */
if ( ! function_exists( 'iddi_format_course_duration' ) ) {
	function iddi_format_course_duration( $duration ) {
		if ( empty( $duration ) ) {
			return iddi_tr( 'Khóa học Online' );
		}

		$current_lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'vi';

		// Nếu là tiếng Việt thì dịch đơn vị thời gian
		if ( $current_lang === 'vi' ) {
			$search_vals  = array( 'minutes', 'minute', 'hours', 'hour', 'days', 'day', 'weeks', 'week', 'months', 'month', 'years', 'year' );
			$replace_vals = array( 'phút', 'phút', 'giờ', 'giờ', 'ngày', 'ngày', 'tuần', 'tuần', 'tháng', 'tháng', 'năm', 'năm' );
			return str_ireplace( $search_vals, $replace_vals, $duration );
		}

		// Nếu là tiếng Anh thì giữ nguyên
		return $duration;
	}
}

/**
 * 3. Render bộ chuyển đổi ngôn ngữ (Language Switcher)
 * Hiển thị dạng Dropdown hoặc Switcher chuẩn giao diện IDDI Academy.
 */
function iddi_render_language_switcher( $args = array() ) {
	// Kiểm tra nếu Polylang đang hoạt động
	if ( function_exists( 'pll_the_languages' ) ) {
		$languages = pll_the_languages( array(
			'raw'          => 1,
			'hide_empty'   => 0,
			'force_home'   => 0,
		) );

		if ( ! empty( $languages ) && is_array( $languages ) ) {
			// Tìm ngôn ngữ hiện tại
			$current_lang = null;
			foreach ( $languages as $lang ) {
				if ( ! empty( $lang['current_lang'] ) ) {
					$current_lang = $lang;
					break;
				}
			}

			// Nếu không tìm thấy, lấy ngôn ngữ đầu tiên
			if ( ! $current_lang ) {
				$current_lang = reset( $languages );
			}

			$current_slug = strtoupper( $current_lang['slug'] );
			?>
			<div class="iddi-header__lang-dropdown" id="iddi-lang-dropdown">
				<button type="button" class="iddi-header__lang-toggle reset-button" aria-haspopup="true" aria-expanded="false" title="<?php echo esc_attr( $current_lang['name'] ); ?>">
					<?php if ( ! empty( $current_lang['flag'] ) ) : ?>
						<span class="iddi-header__lang-flag">
							<img src="<?php echo esc_url( $current_lang['flag'] ); ?>" alt="<?php echo esc_attr( $current_lang['name'] ); ?>" width="18" height="12" loading="eager" />
						</span>
					<?php endif; ?>
					<span class="iddi-header__lang-code"><?php echo esc_html( $current_slug ); ?></span>
					<span class="iddi-header__lang-arrow">
						<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</button>
				<ul class="iddi-header__lang-menu">
					<?php foreach ( $languages as $slug => $lang ) : 
						$is_active = ! empty( $lang['current_lang'] );
						?>
						<li class="iddi-header__lang-item <?php echo $is_active ? 'is-active' : ''; ?>">
							<a href="<?php echo esc_url( $lang['url'] ); ?>" class="iddi-header__lang-link" <?php echo $is_active ? 'aria-current="true"' : ''; ?>>
								<?php if ( ! empty( $lang['flag'] ) ) : ?>
									<span class="iddi-header__lang-flag">
										<img src="<?php echo esc_url( $lang['flag'] ); ?>" alt="<?php echo esc_attr( $lang['name'] ); ?>" width="18" height="12" loading="lazy" />
									</span>
								<?php endif; ?>
								<span class="iddi-header__lang-name"><?php echo esc_html( $lang['name'] ); ?></span>
								<span class="iddi-header__lang-tag"><?php echo esc_html( strtoupper( $slug ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
			return;
		}
	}

	// Fallback khi Polylang chưa kích hoạt hoặc đang khởi tạo
	?>
	<div class="iddi-header__lang-dropdown iddi-header__lang-fallback" id="iddi-lang-dropdown">
		<button type="button" class="iddi-header__lang-toggle reset-button" aria-haspopup="true" aria-expanded="false">
			<span class="iddi-header__lang-code">VI</span>
			<span class="iddi-header__lang-arrow">
				<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</button>
		<ul class="iddi-header__lang-menu">
			<li class="iddi-header__lang-item is-active">
				<a href="#" class="iddi-header__lang-link">
					<span class="iddi-header__lang-name">Tiếng Việt</span>
					<span class="iddi-header__lang-tag">VI</span>
				</a>
			</li>
			<li class="iddi-header__lang-item">
				<a href="#" class="iddi-header__lang-link">
					<span class="iddi-header__lang-name">English</span>
					<span class="iddi-header__lang-tag">EN</span>
				</a>
			</li>
		</ul>
	</div>
	<?php
}

/**
 * 4. Tích hợp ACF Options Page đa ngôn ngữ (ACF Multi-language Options)
 * Tự động phân tách dữ liệu Theme Options theo ngôn ngữ đang chọn trong Polylang.
 */
add_filter( 'acf/settings/current_language', function( $lang ) {
	if ( function_exists( 'pll_current_language' ) ) {
		$current = pll_current_language( 'slug' );
		if ( $current ) {
			return $current;
		}
	}
	return $lang;
} );

/**
 * Helper lấy ACF Option an toàn, tự động fallback về ngôn ngữ mặc định nếu bản dịch chưa được nhập
 * Giúp giao diện (Footer, Logo, Contact, Testimonials) không bị biến mất khi chưa kịp dịch Option
 *
 * @param string $selector Tên trường ACF option
 * @param bool $fallback_default Có fallback về ngôn ngữ mặc định nếu rỗng hay không
 * @return mixed
 */
if ( ! function_exists( 'iddi_get_field_option' ) ) {
	function iddi_get_field_option( $selector, $fallback_default = true ) {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$value = get_field( $selector, 'option' );

		// Nếu rỗng và được phép fallback
		if ( empty( $value ) && $fallback_default && function_exists( 'pll_default_language' ) && function_exists( 'pll_current_language' ) ) {
			$current = pll_current_language( 'slug' );
			$default = pll_default_language( 'slug' );

			if ( $current && $default && $current !== $default ) {
				// Tạm thời bỏ filter current_language để lấy option của ngôn ngữ mặc định
				add_filter( 'acf/settings/current_language', '__return_false', 999 );
				$value = get_field( $selector, 'option' );
				remove_filter( 'acf/settings/current_language', '__return_false', 999 );
			}
		}

		return $value;
	}
}

/**
 * 5. Tự động gán ngôn ngữ mặc định (Tiếng Việt) cho các bài viết / khóa học chưa có ngôn ngữ
 * Khắc phục hiện tượng cột cờ Polylang bị trắng hoàn toàn (thiếu dấu tick, dấu (+) và bút chỉnh sửa)
 */
add_action( 'load-edit.php', function() {
	if ( ! function_exists( 'pll_default_language' ) || ! function_exists( 'pll_set_post_language' ) ) {
		return;
	}

	$screen = get_current_screen();
	$post_type = $screen ? $screen->post_type : '';
	if ( ! $post_type ) {
		return;
	}

	$supported_types = array( 'lp_course', 'lp_lesson', 'lp_quiz', 'post', 'page', 'event', 'giang-vien' );
	if ( ! in_array( $post_type, $supported_types, true ) ) {
		return;
	}

	$default_lang = pll_default_language( 'slug' );
	if ( ! $default_lang ) {
		$default_lang = 'vi';
	}

	// Lấy các bài viết thuộc post type hiện tại chưa có ngôn ngữ
	$unassigned_posts = get_posts( array(
		'post_type'      => $post_type,
		'posts_per_page' => 100,
		'post_status'    => 'any',
		'tax_query'      => array(
			array(
				'taxonomy' => 'language',
				'operator' => 'NOT EXISTS',
			),
		),
		'fields'         => 'ids',
		'no_found_rows'  => true,
	) );

	if ( ! empty( $unassigned_posts ) ) {
		foreach ( $unassigned_posts as $p_id ) {
			pll_set_post_language( $p_id, $default_lang );
		}
	}
} );

add_action( 'load-post.php', function() {
	if ( ! function_exists( 'pll_default_language' ) || ! function_exists( 'pll_set_post_language' ) || ! function_exists( 'pll_get_post_language' ) ) {
		return;
	}
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( $post_id && ! pll_get_post_language( $post_id ) ) {
		$default_lang = pll_default_language( 'slug' ) ?: 'vi';
		pll_set_post_language( $post_id, $default_lang );
	}
} );

