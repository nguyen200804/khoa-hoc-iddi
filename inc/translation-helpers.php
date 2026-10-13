<?php
/**
 * Multi-language & Translation Helpers for IDDI Academy (GTranslate Integration)
 * File: inc/translation-helpers.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Safe translation helper
 * Trả về chuỗi gốc hoặc qua gettext / fallback.
 * Giữ nguyên để tương thích với tất cả các template hiện tại của theme.
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
 * In trực tiếp chuỗi dịch
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
 * 2. Định dạng thời lượng khóa học tiếng Việt chuẩn
 *
 * @param string $duration Thời lượng gốc từ meta LearnPress (vd: "10 hours", "5 weeks")
 * @return string
 */
if ( ! function_exists( 'iddi_format_course_duration' ) ) {
	function iddi_format_course_duration( $duration ) {
		if ( empty( $duration ) ) {
			return iddi_tr( 'Khóa học Online' );
		}

		$search_vals  = array( 'minutes', 'minute', 'hours', 'hour', 'days', 'day', 'weeks', 'week', 'months', 'month', 'years', 'year' );
		$replace_vals = array( 'phút', 'phút', 'giờ', 'giờ', 'ngày', 'ngày', 'tuần', 'tuần', 'tháng', 'tháng', 'năm', 'năm' );
		return str_ireplace( $search_vals, $replace_vals, $duration );
	}
}

/**
 * 3. Render bộ chuyển đổi ngôn ngữ GTranslate (Language Switcher)
 * Hỗ trợ shortcode [gtranslate] của plugin GTranslate và fallback widget tự động.
 */
if ( ! function_exists( 'iddi_render_language_switcher' ) ) {
	function iddi_render_language_switcher( $args = array() ) {
		// 1. Nếu plugin GTranslate đã đăng ký shortcode
		if ( shortcode_exists( 'gtranslate' ) ) {
			echo do_shortcode( '[gtranslate]' );
			return;
		}

		// 2. Thử gọi do_shortcode phòng trường hợp plugin đăng ký muộn
		$output = do_shortcode( '[gtranslate]' );
		if ( ! empty( $output ) && $output !== '[gtranslate]' ) {
			echo $output;
			return;
		}

		// 3. Fallback Cloud GTranslate Widget: Hoạt động ngay cả khi chưa cài plugin
		?>
		<div class="gtranslate_wrapper" id="iddi-gtranslate"></div>
		<script>
			window.gtranslateSettings = window.gtranslateSettings || {
				"default_language": "vi",
				"languages": ["vi", "en"],
				"wrapper_selector": "#iddi-gtranslate",
				"flag_size": 18,
				"switcher_horizontal_position": "inline"
			};
		</script>
		<script src="https://cdn.gtranslate.net/widgets/latest/dropdown.js" defer></script>
		<?php
	}
}

/**
 * 4. Helper lấy ACF Option an toàn (ACF Options Helper)
 *
 * @param string $selector Tên trường ACF option
 * @param bool $fallback_default
 * @return mixed
 */
if ( ! function_exists( 'iddi_get_field_option' ) ) {
	function iddi_get_field_option( $selector, $fallback_default = true ) {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}
		return get_field( $selector, 'option' );
	}
}
