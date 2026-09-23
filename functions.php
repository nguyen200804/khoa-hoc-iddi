<?php
// add_theme_support('title-tag');
// add_theme_support('post-thumbnails');

// register_nav_menus(array(
//     'primary' => 'Primary Menu',
// ));
// 
// 
// 
function iddi_theme_support() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'iddi_theme_support');


function homenest_enqueue_styles() {
    wp_enqueue_style('my-style', get_stylesheet_uri(), array(), time());
}
add_action('wp_enqueue_scripts', 'homenest_enqueue_styles');

// Nạp các helper đa ngôn ngữ Polylang cho IDDI Academy
require_once get_template_directory() . '/inc/polylang-helpers.php';







/* 1. Tạo hàm xử lý duplicate bài viết */
function gemini_duplicate_post_as_draft() {
	global $wpdb;

	// Kiểm tra ID bài viết và quyền hạn
	if (! (isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'gemini_duplicate_post_as_draft' == $_REQUEST['action']))) {
		wp_die('Không có bài viết nào để nhân bản!');
	}

	// Lấy ID bài viết gốc
	$post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
	$post = get_post($post_id);

	// Nếu bài viết tồn tại, tiến hành nhân bản
	if (isset($post) && $post != null) {
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		// Thiết lập dữ liệu bài viết mới
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft', // Luôn để ở dạng nháp (draft)
			'post_title'     => $post->post_title . ' (Copy)',
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		// Chèn bài viết mới vào database
		$new_post_id = wp_insert_post($args);

		// Sao chép toàn bộ Taxonomy (Category, Tags, Custom Taxonomies)
		$taxonomies = get_object_taxonomies($post->post_type);
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}

		// Sao chép toàn bộ Meta Data (Custom Fields)
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos) != 0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if ($meta_key == '_wp_old_slug') continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query .= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}

		// Chuyển hướng về trang danh sách bài viết sau khi xong
		wp_redirect(admin_url('edit.php?post_type=' . $post->post_type));
		exit;
	} else {
		wp_die('Tạo bản sao thất bại, không tìm thấy bài viết gốc: ' . $post_id);
	}
}
add_action('admin_action_gemini_duplicate_post_as_draft', 'gemini_duplicate_post_as_draft');

/* 2. Thêm nút "Duplicate" vào danh sách bài viết (tất cả post type) */
function gemini_duplicate_post_link($actions, $post) {
	if (current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=gemini_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Nhân bản bài viết này" rel="permalink">Duplicate</a>';
	}
	return $actions;
}

// Áp dụng cho bài viết thường
add_filter('post_row_actions', 'gemini_duplicate_post_link', 10, 2);
// Áp dụng cho các trang (pages)
add_filter('page_row_actions', 'gemini_duplicate_post_link', 10, 2);








// ===========================================
// BẮT ĐẦU - ĐĂNG KÝ TÀI NGUYÊN CSS & JS (ASSETS)
// ===========================================
function my_theme_enqueue_assets() {

	// --- PHẦN 1: CSS (Stylesheets) ---

	// 1. Tải Font trước tiên
	wp_enqueue_style('theme-fonts', get_template_directory_uri() . '/assets/css/fonts.css', array(), '1.0.0');

	// 2. Tải file mặc định (Phụ thuộc vào Fonts)
	wp_enqueue_style('default-style', get_template_directory_uri() . '/assets/css/default.css', array('theme-fonts'), '1.0.0');

	// 3. Tải CSS bình luận dùng chung
	wp_enqueue_style('comment-style', get_template_directory_uri() . '/assets/css/comments.css', array('default-style'), '1.0.0');

	// 4. Tải tài nguyên Layout (Phụ thuộc vào file Default)
	wp_enqueue_style('header-style', get_template_directory_uri() . '/assets/css/layout/header.css', array('default-style'), time());
	wp_enqueue_style('footer-style', get_template_directory_uri() . '/assets/css/layout/footer.css', array('default-style'), '1.0.0');


	// --- PHẦN 2: Thư viện chung ---
	wp_enqueue_style('components-style', get_template_directory_uri() . '/assets/css/components.css', array('default-style'), '1.0.0');

	// 1. Tải file JS mặc định (Global Scripts)
	// File này chứa các logic dùng chung cho toàn bộ website
	wp_enqueue_script('default-script', get_template_directory_uri() . '/assets/js/default.js', array(), '1.0.0', true);

	// 2. Tải JS cho Header & Footer (Phụ thuộc vào jQuery và default-script)
    wp_enqueue_script(
        'header-script', 
        get_template_directory_uri() . '/assets/js/layout/header.js', 
        array('jquery', 'default-script'), 
        time(), 
        true
    );

    wp_enqueue_script(
        'footer-script', 
        get_template_directory_uri() . '/assets/js/layout/footer.js', 
        array('jquery', 'default-script'), 
        '1.1.0', 
        true
    );


	// --- PHẦN 3: Tải tài nguyên riêng cho từng Page Template ---

	$template = get_page_template_slug( get_queried_object_id() );

	if ( $template ) {
		$name = str_replace( array('page-templates/', '.php'), '', $template );

		// CSS dành cho trang cụ thể
		wp_enqueue_style($name . '-style', get_template_directory_uri() . '/assets/css/page-templates/' . $name . '.css', array('default-style'), '1.0.0');

		// JS dành cho trang cụ thể (Phụ thuộc vào default-script)
		$deps = array('default-script');
		if ($name === 'podcast') {
			$deps[] = 'swiper-js';
		}
		wp_enqueue_script($name . '-script', get_template_directory_uri() . '/assets/js/page-templates/' . $name . '.js', $deps, '1.0.0', true);
	}

	// --- PHẦN 4: CSS riêng cho Blog & Category & Search ---
	if ( is_category() || is_search() || is_page_template('template-blog.php') || is_singular('post') ) {
		wp_enqueue_style('category-style', get_template_directory_uri() . '/assets/css/category.css', array('default-style'), '1.0.0');
	}
	
	if ( is_search() ) {
		wp_enqueue_style('search-style', get_template_directory_uri() . '/assets/css/search.css', array('default-style'), '1.0.0');
	}
	
	if ( is_post_type_archive('lp_course') || is_tax('course_category') || is_singular('lp_course') || is_page_template('page-templates/courses.php') || is_search() ) {
		wp_enqueue_style('content-course-style', get_template_directory_uri() . '/assets/css/learnpress/content-course.css', array('default-style'), '1.0.0');
	}

	if ( is_post_type_archive('lp_course') || is_tax('course_category') ) {
		wp_enqueue_script(
			'iddi-archive-course-script',
			get_template_directory_uri() . '/assets/js/learnpress/archive-course.js',
			array(),
			'1.0.0',
			true
		);
	}
	
	if ( is_singular('post') ) {
		wp_enqueue_style('single-post-style', get_template_directory_uri() . '/assets/css/single.css', array('category-style'), '1.0.0');
		wp_enqueue_script('single-post-script', get_template_directory_uri() . '/assets/js/single.js', array('jquery'), '1.0.0', true);
	}
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');

/**
 * Estimate reading time for a text
 */
function iddi_estimate_reading_time($content) {
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil($word_count / 200); // 200 words per minute
    if ($reading_time == 0) $reading_time = 1;
    return $reading_time;
}
// ===========================================
// KẾT THÚC - ĐĂNG KÝ TÀI NGUYÊN CSS & JS
// ===========================================






// ===========================================
// HỖ TRỢ ĐỊNH DẠNG FILE SVG TRONG MEDIA
// ===========================================

// 1. Cho phép tải lên file SVG vào Thư viện Media
add_filter('upload_mimes', function($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

// 2. Sửa lỗi không hiển thị hình xem trước (Thumbnail) của file SVG
add_filter('wp_prepare_attachment_for_js', function($response, $attachment, $meta) {
    if ($response['mime'] === 'image/svg+xml' && empty($response['sizes'])) {
        $response['sizes'] = [
            'full' => [
                'url'         => $response['url'],
                'width'       => 0,
                'height'      => 0,
                'orientation' => 'landscape',
            ]
        ];
    }
    return $response;
}, 10, 3);

// ===========================================
// KẾT THÚC CẤU HÌNH SVG
// ===========================================










function get_my_svg($name) {
	$path = get_template_directory() . '/assets/icons/' . $name . '.svg';
	if (file_exists($path)) {
		return file_get_contents($path);
	}
	return '';
}







// ===========================================
// BẮT ĐẦU - THIẾT LẬP GIAO DIỆN (THEME SETUP)
// ===========================================
function homenest_theme_setup() {

	// 1. Kích hoạt tính năng Menu tùy chỉnh
	add_theme_support('nav-menus');

	// 2. Đăng ký các vị trí hiển thị Menu trên Website
	register_nav_menus(array(
		'primary-menu' => __('Menu Chính (Header)', 'iddi-academy'),
		'mobile-menu'  => __('Menu Mobile (Popup)', 'iddi-academy'),
		'footer-menu'  => __('Menu Chân Trang (Footer)', 'iddi-academy'),
	));

	// 3. Kích hoạt tính năng Ảnh đại diện (Thumbnail) cho bài viết/trang
	add_theme_support('post-thumbnails');

}
add_action('after_setup_theme', 'homenest_theme_setup');
// ===========================================
// KẾT THÚC - THIẾT LẬP GIAO DIỆN
// ===========================================






// ===========================================
// START - SECTION ASSETS LOADER
// ===========================================

/**
 * Hàm hỗ trợ nạp CSS/JS cho section chỉ khi section đó được gọi
 * @param string $name Tên của section (ví dụ: 'hero')
 * @param bool $has_js Section đó có file JS hay không
 */
function enqueue_section_assets($name, $has_js = false) {
	// Nạp CSS cho section
	$css_path = '/assets/css/sections/' . $name . '.css';
	if (file_exists(get_template_directory() . $css_path)) {
		wp_enqueue_style(
			'section-' . $name, 
			get_template_directory_uri() . $css_path, 
			array('default-style'), 
			'1.0.0'
		);
	}

	// Nạp JS cho section (nếu có)
	if ($has_js) {
		$js_path = '/assets/js/sections/' . $name . '.js';
		if (file_exists(get_template_directory() . $js_path)) {
			wp_enqueue_script(
				'section-' . $name . '-js', 
				get_template_directory_uri() . $js_path, 
				array('default-script'), 
				'1.0.0', 
				true
			);
		}
	}
}

// ===========================================
// END - SECTION ASSETS LOADER
// ===========================================









// ===========================================
// START - DISABLE GUTENBERG (CLASSIC EDITOR)
// ===========================================

// Tắt trình soạn thảo Block cho toàn bộ các Post Types
add_filter('use_block_editor_for_post', '__return_false', 10);

// Tắt các file CSS mặc định của Gutenberg ở ngoài Frontend (giúp load nhanh hơn)
function homenest_remove_wp_block_library_css(){
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('wc-block-style'); // Tắt luôn CSS của WooCommerce blocks nếu có
}
add_action('wp_enqueue_scripts', 'homenest_remove_wp_block_library_css', 100);

// ===========================================
// END - DISABLE GUTENBERG
// ===========================================








/**
 * Nạp CSS riêng cho trang chi tiết Sự kiện
 */
function homenest_enqueue_event_styles() {
	// Kiểm tra nếu đúng là trang single của post type 'event'
	if ( is_singular('event') ) {
		wp_enqueue_style(
			'homenest-event-style', 
			get_template_directory_uri() . '/assets/css/single-event.css', 
			array(), 
			'1.0.0'
		);
	}
}
add_action('wp_enqueue_scripts', 'homenest_enqueue_event_styles');








// ===========================================
// START - KÍCH HOẠT CHỨC NĂNG CUSTOM SITE LOGO
// ===========================================
function homenest_setup_custom_logo() {
	$defaults = array(
		'height'               => 100,         // Chiều cao logo (pixel)
		'width'                => 300,         // Chiều rộng logo (pixel)
		'flex-height'          => true,        // Cho phép thay đổi chiều cao linh hoạt
		'flex-width'           => true,         // Cho phép thay đổi chiều rộng linh hoạt
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => false,       // Nếu true, logo ở trang chủ sẽ không có link
	);

	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'homenest_setup_custom_logo' );
// ===========================================
// END - KÍCH HOẠT CHỨC NĂNG CUSTOM SITE LOGO
// ===========================================





// ===========================================
// START - ENABLED CUSTOM MENU
// ===========================================
function homenest_register_menus() {
	register_nav_menus( array(
		'primary-menu' => __( 'Primary Menu', 'homenest' ),
	) );
}
add_action( 'after_setup_theme', 'homenest_register_menus' );
// ===========================================
// END - ENABLED CUSTOM MENU
// ===========================================






/**
 * Nạp file CSS chuyên biệt cho trang chi tiết Khóa học (LearnPress)
 */
function homenest_enqueue_course_styles() {
	// Kiểm tra nếu đang ở trang chi tiết của loại bài viết 'lp_course'
	if ( is_singular('lp_course') ) {
		
		// 1. Nạp CSS cho trang giới thiệu khóa học mặc định
		wp_enqueue_style(
			'homenest-course-style', 
			get_template_directory_uri() . '/assets/css/learnpress/single-course.css', 
			array('default-style'),
			'1.1.0' 
		);

        // 1.1. Nạp CSS cho phần tài liệu (Course Materials)
        wp_enqueue_style(
            'homenest-course-materials', 
            get_template_directory_uri() . '/assets/css/learnpress/materials.css', 
            array('homenest-course-style'),
            '1.0.0' 
        );

		// 2. Kiểm tra nếu là trang ĐANG HỌC (Learning Page)
		$is_learning_page = get_query_var( 'course-item' ) || get_query_var( 'lesson' ) || get_query_var( 'quiz' );
		if ( $is_learning_page ) {
			wp_enqueue_style(
				'homenest-learning-style', 
				get_template_directory_uri() . '/assets/css/learnpress/learning.css', 
				array('homenest-course-style'), // Nạp sau file single-course
				'1.0.1' 
			);

            // Nạp CSS cho thanh bên (Sidebar) của giao diện học tập
            wp_enqueue_style(
				'homenest-popup-sidebar-style',
				get_template_directory_uri() . '/assets/css/learnpress/popup-sidebar.css',
				array( 'homenest-learning-style' ),
				'1.0.0'
			);

            // Nạp Script kiểm soát hoàn thành bài học
            wp_enqueue_script(
                'homenest-lesson-requirements',
                get_template_directory_uri() . '/assets/js/learnpress/lesson-requirements.js',
                array('jquery'),
                '1.0.0',
                true
            );
		}
	}
}
add_action('wp_enqueue_scripts', 'homenest_enqueue_course_styles');







// ===========================================
// BẮT ĐẦU - XỬ LÝ GHI ĐÈ TEMPLATE LEARNPRESS
// ===========================================
add_filter( 'template_include', 'homenest_force_only_course_template', 99 );

function homenest_force_only_course_template( $template ) {
	// 1. Chỉ xử lý nếu là trang chi tiết khóa học (lp_course)
	if ( is_singular( 'lp_course' ) ) {

		$is_learning_page = get_query_var( 'course-item' ) || get_query_var( 'lesson' ) || get_query_var( 'quiz' );

		// 2. Nếu là trang GIỚI THIỆU khóa học (không phải đang học)
		if ( ! $is_learning_page ) {
			$new_template = locate_template( array( 'learnpress/single-course.php' ) );
			if ( $new_template ) {
				return $new_template;
			}
		} 
		
		// 3. Nếu là trang ĐANG HỌC (Learning Page)
		// Ta để LearnPress tự quyết định để nó có thể nhận diện các file override trong thư mục theme.
	}

	return $template;
}

/**
 * QUAN TRỌNG: Bật tính năng cho phép ghi đè template từ Theme
 * LearnPress 4 mặc định tắt tính năng này (return false).
 */
add_filter( 'learn-press/override-templates', '__return_true' );

/**
 * Ép LearnPress tìm kiếm template trong thư mục /learnpress/ của theme
 */
add_filter( 'learn_press_template_path', function() {
	return 'learnpress';
} );

/**
 * TỰ TẠO TRƯỜNG NHẬP VIDEO VÀ CÀI ĐẶT BẮT BUỘC HOÀN THÀNH
 */
add_filter( 'lp/metabox/lesson/lists', function( $fields ) {
    // 1. Checkbox Bật/Tắt bắt buộc hoàn thành
    if ( class_exists( 'LP_Meta_Box_Checkbox_Field' ) ) {
        $fields['_lp_enforce_requirements'] = new LP_Meta_Box_Checkbox_Field(
            'Bắt buộc xem hết',
            'Học viên phải xem video mới được nhấn Hoàn thành',
            'no'
        );
    }

    // 2. Ô nhập % cần xem
    if ( class_exists( 'LP_Meta_Box_Number_Field' ) ) {
        $fields['_lp_video_completion_percent'] = new LP_Meta_Box_Number_Field(
            '% Video cần xem',
            'Học viên phải xem đạt bao nhiêu % video (ví dụ: 80 hoặc 90)',
            50,
            array( 'min' => 1, 'max' => 100 )
        );
    }
    
    return $fields;
} );

/**
 * TRUYỀN CẤU HÌNH SANG JAVASCRIPT
 */
add_action( 'wp_enqueue_scripts', function() {
    if ( learn_press_is_course() || learn_press_is_learning_course() ) {
        $item = LP_Global::course_item();
        if ( $item ) {
            $enforce = get_post_meta( $item->get_id(), '_lp_enforce_requirements', true );
            $percent = get_post_meta( $item->get_id(), '_lp_video_completion_percent', true );
            if ( ! $percent ) $percent = 50; // Mặc định 50%

            wp_enqueue_script(
                'homenest-lesson-requirements',
                get_template_directory_uri() . '/assets/js/learnpress/lesson-requirements.js',
                array('jquery'),
                '1.0.1',
                true
            );

            wp_localize_script( 'homenest-lesson-requirements', 'iddi_lesson_config', array(
                'enforce' => ( $enforce === 'yes' ) ? true : false,
                'percent' => (float)$percent / 100
            ) );
        }
    }
}, 20 );
/**
 * KIỂM TRA BÀI HỌC CÓ BỊ KHÓA THEO TRÌNH TỰ KHÔNG
 * @param int $item_id ID bài học hiện tại
 * @param int $course_id ID khóa học
 * @param int $user_id ID người dùng
 * @return bool True nếu bị khóa, False nếu được phép xem
 */
function iddi_is_item_locked( $item_id, $course_id = 0, $user_id = 0 ) {
    if ( current_user_can( 'administrator' ) ) return false;

    if ( ! $user_id ) $user_id = get_current_user_id();
    if ( ! $user_id ) return true;

    if ( ! $course_id ) {
        $course = learn_press_get_course();
        $course_id = $course ? $course->get_id() : 0;
    }
    if ( ! $course_id ) return false;

    // Lấy Model khóa học
    $courseModel = \LearnPress\Models\CourseModel::find( $course_id, true );
    if ( ! $courseModel ) return false;

    $userCourse = \LearnPress\Models\UserItems\UserCourseModel::find( $user_id, $course_id, true );
    if ( ! $userCourse ) return false;

    // Lấy danh sách ID bài học bằng cách duyệt qua các section để đảm bảo độ tương thích
    $items = [];
    $section_items = $courseModel->get_section_items();
    if ( $section_items ) {
        foreach ( $section_items as $section ) {
            if ( ! empty( $section->items ) ) {
                foreach ( $section->items as $it ) {
                    $items[] = (int)($it->item_id ?? $it->id ?? 0);
                }
            }
        }
    }

    if ( empty($items) ) return false;

    $current_index = array_search( (int)$item_id, $items );

    // Nếu là bài đầu tiên hoặc không tìm thấy trong giáo trình -> Không khóa
    if ( $current_index === false || $current_index === 0 ) {
        return false;
    }

    // Kiểm tra bài học ngay phía trước
    $prev_item_id = $items[ $current_index - 1 ];
    $prev_item_type = get_post_type( $prev_item_id );
    
    // Kiểm tra trạng thái hoàn thành của bài trước
    $uItem = $userCourse->get_item_attend( $prev_item_id, $prev_item_type );
    
    // NẾU BÀI TRƯỚC LÀ QUIZ: Phải đạt (passed) thì mới mở bài tiếp theo
    if ( $prev_item_type === 'lp_quiz' ) {
        if ( ! $uItem || $uItem->get_status() !== \LearnPress\Models\UserItems\UserItemModel::STATUS_COMPLETED || $uItem->get_graduation() !== 'passed' ) {
            return true;
        }
    } else {
        // NẾU LÀ BÀI HỌC THƯỜNG: Chỉ cần hoàn thành (completed)
        if ( ! $uItem || $uItem->get_status() !== \LearnPress\Models\UserItems\UserItemModel::STATUS_COMPLETED ) {
            return true;
        }
    }

    return false;
}

/**
 * CHO PHÉP LƯU ĐẦY ĐỦ THUỘC TÍNH IFRAME TRONG ADMIN
 */
add_filter( 'wp_kses_allowed_html', function ( $allowedposttags, $context ) {
    if ( isset( $allowedposttags['iframe'] ) ) {
        $allowedposttags['iframe']['allow'] = true;
        $allowedposttags['iframe']['allowfullscreen'] = true;
        $allowedposttags['iframe']['allowtransparency'] = true;
        $allowedposttags['iframe']['frameborder'] = true;
        $allowedposttags['iframe']['scrolling'] = true;
        $allowedposttags['iframe']['style'] = true;
        $allowedposttags['iframe']['class'] = true;
        $allowedposttags['iframe']['name'] = true;
    }
    // Cho phép cả thẻ div có style (Spotlightr wrapper)
    if ( isset( $allowedposttags['div'] ) ) {
        $allowedposttags['div']['style'] = true;
    }
    return $allowedposttags;
}, 10, 2 );

/**
 * ÉP LƯU NGUYÊN VĂN MÃ NHÚNG (KHÔNG LỌC THUỘC TÍNH)
 */
add_filter( 'sanitize_post_meta__lp_video_embed_code', function( $meta_value, $meta_key, $object_type ) {
    return $meta_value; // Trả về giá trị gốc, không chạy qua bất kỳ hàm làm sạch nào
}, 10, 3 );

// ===========================================
// KẾT THÚC - XỬ LÝ GHI ĐÈ TEMPLATE LEARNPRESS
// ===========================================
// KẾT THÚC - ÉP SỬ DỤNG TEMPLATE KHÓA HỌC RIÊNG
// ===========================================













/*
function custom_learnpress_filter_bar() {
	// Lấy URL hiện tại để giữ các tham số khác nếu có
	$current_url = home_url(add_query_arg(array(), $GLOBALS['wp']->request));
	$current_filter = isset($_GET['lp-filter']) ? $_GET['lp-filter'] : 'all';

?>
<div class="lp-course-filter-bar">
	<a href="<?php echo esc_url(remove_query_arg('lp-filter')); ?>" class="<?php echo $current_filter == 'all' ? 'active' : ''; ?>">ALL</a>
	<a href="<?php echo esc_url(add_query_arg('lp-filter', 'not-enrolled')); ?>" class="<?php echo $current_filter == 'not-enrolled' ? 'active' : ''; ?>">NOT ENROLLED</a>
	<a href="<?php echo esc_url(add_query_arg('lp-filter', 'newest')); ?>" class="<?php echo $current_filter == 'newest' ? 'active' : ''; ?>">NEWEST</a>
	<a href="<?php echo esc_url(add_query_arg('lp-filter', 'popular')); ?>" class="<?php echo $current_filter == 'popular' ? 'active' : ''; ?>">POPULAR</a>
	<a href="<?php echo esc_url(add_query_arg('lp-filter', 'free')); ?>" class="<?php echo $current_filter == 'free' ? 'active' : ''; ?>">FREE</a>
</div>

<style>
	.lp-course-filter-bar { display: flex; gap: 20px; margin-bottom: 20px; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 10px; }
	.lp-course-filter-bar a { text-decoration: none; color: #2c3e50; font-size: 14px; text-transform: uppercase; }
	.lp-course-filter-bar a.active { color: #ff6600; border-bottom: 2px solid #ff6600; }
</style>
<?php
}

// Hiển thị thanh lọc phía trên danh sách khóa học
add_action('learn-press/before-courses-loop', 'custom_learnpress_filter_bar');
*/

/**
 * Tích hợp bộ lọc khóa học LearnPress vào Query chính của WordPress
 */
add_action('pre_get_posts', function($query) {
	// 1. Kiểm tra điều kiện: Không chạy trong Admin, phải là Query chính, 
	// và phải ở trang danh sách khóa học hoặc danh mục khóa học.
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (!is_post_type_archive('lp_course') && !is_tax('course_category')) {
		return;
	}

	// 2. Lấy giá trị lọc từ URL (?lp-filter=...)
	$filter = isset($_GET['lp-filter']) ? sanitize_text_field($_GET['lp-filter']) : '';

	if (empty($filter)) {
		return;
	}

	switch ($filter) {
		case 'newest':
			$query->set('orderby', 'date');
			$query->set('order', 'DESC');
			break;

		case 'popular':
			// Sử dụng meta_key chuẩn của LearnPress để lấy khóa học phổ biến
			$query->set('meta_key', 'count_enrolled_users');
			$query->set('orderby', 'meta_value_num');
			$query->set('order', 'DESC');
			break;

		case 'free':
			// Lọc các khóa học có giá bằng 0 hoặc trống
			$query->set('meta_query', array(
				array(
					'key'     => '_lp_price',
					'value'   => array('', '0'),
					'compare' => 'IN',
				)
			));
			break;

		case 'not-enrolled':
			if (is_user_logged_in()) {
				$user_id = get_current_user_id();

				/**
                 * FIX XUNG ĐỘT: Truy vấn trực tiếp vào DB để lấy ID khóa học đã tham gia.
                 * Tránh dùng hàm $user->get_enrolled_courses() trong pre_get_posts 
                 * vì dễ gây lỗi khởi tạo object LearnPress quá sớm.
                 */
				global $wpdb;
				$table_name = $wpdb->prefix . 'learnpress_user_items';

				$enrolled_ids = $wpdb->get_col($wpdb->prepare(
					"SELECT item_id FROM $table_name WHERE user_id = %d AND item_type = %s",
					$user_id,
					'lp_course'
				));

				if (!empty($enrolled_ids)) {
					$enrolled_ids = array_map('intval', array_unique($enrolled_ids));
					$query->set('post__not_in', $enrolled_ids);
				}
			}
			break;

		case 'authors':
			// Nếu bạn muốn lọc theo tác giả, có thể thêm logic ở đây
			// Ví dụ: $query->set('author', $author_id);
			break;
	}
});

/**
 * Hàm hỗ trợ hiển thị Thanh Lọc (Giao diện)
 * Bạn có thể gọi hàm này trong file template của mình: <?php iddi_display_course_filter(); ?>
 */
function iddi_display_course_filter() {
	$current_filter = isset($_GET['lp-filter']) ? $_GET['lp-filter'] : 'all';

	$filters = [
		'all'          => 'ALL',
		'not-enrolled' => 'NOT ENROLLED',
		'newest'       => 'NEWEST',
		'popular'      => 'POPULAR',
		'free'         => 'FREE',
	];

	echo '<div class="iddi-course-filter-bar d-flex gap-xl margin-bottom-l">';
	foreach ($filters as $key => $label) {
		$url = ($key === 'all') ? remove_query_arg('lp-filter') : add_query_arg('lp-filter', $key);
		$active_class = ($current_filter === $key) ? 'is-active' : '';

		printf(
			'<a href="%s" class="filter-item %s">%s</a>',
			esc_url($url),
			esc_attr($active_class),
			esc_html($label)
		);
	}
	echo '</div>';

	// Thêm CSS trực tiếp hoặc di chuyển vào file style.css của bạn
?>
<style>
	.iddi-course-filter-bar { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 30px; display: flex; gap: 30px; }
	.iddi-course-filter-bar a { text-decoration: none; color: #1a2b4e; font-weight: 600; font-size: 14px; position: relative; transition: 0.3s; }
	.iddi-course-filter-bar a.is-active { color: #ff6600; }
	.iddi-course-filter-bar a.is-active::after { content: ''; position: absolute; bottom: -16px; left: 0; width: 100%; height: 2px; background: #ff6600; }
	.iddi-course-filter-bar a:hover { color: #ff6600; }
</style>
<?php
}

// Đồng bộ lại số lượng học viên nếu cần (Add vào functions.php)
add_action('init', function() {
	if (isset($_GET['update_lp_meta'])) { // Chạy bằng cách thêm ?update_lp_meta=1 vào URL
		$courses = get_posts(array('post_type' => 'lp_course', 'posts_per_page' => -1));
		foreach ($courses as $course_post) {
			$course = learn_press_get_course($course_post->ID);
			$count = $course->get_users_enrolled();
			update_post_meta($course_post->ID, 'count_enrolled_users', $count);
		}
		echo "Updated LearnPress Popular Meta!";
		exit;
	}
});








// 1. Xử lý AJAX để lọc khóa học
add_action('wp_ajax_iddi_filter_courses', 'iddi_filter_courses_callback');
add_action('wp_ajax_nopriv_iddi_filter_courses', 'iddi_filter_courses_callback');

function iddi_get_course_authors() {
    // Lấy tất cả giảng viên đã xuất bản
    $giang_viens = get_posts(array(
        'post_type'      => 'giang-vien',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));

    $list = [];
    foreach ($giang_viens as $gv) {
        // Đếm số khóa học có giảng viên này (thông qua meta_query)
        $courses = new WP_Query(array(
            'post_type'      => 'lp_course',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => array(
                array(
                    'key'     => 'giang_vien_khoa_hoc',
                    'value'   => $gv->ID,
                    'compare' => '='
                )
            )
        ));
        $course_count = $courses->found_posts;

        if ( $course_count > 0 ) {
            $list[] = [
                'id'    => $gv->ID,
                'name'  => $gv->post_title,
                'count' => $course_count
            ];
        }
    }
    return $list;
}

/**
 * Cập nhật hàm xử lý AJAX iddi_filter_courses_callback (Thêm phần lọc Giảng viên)
 */
function iddi_filter_courses_callback() {
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'all';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $author_id = isset($_POST['author_id']) ? absint($_POST['author_id']) : 0; // Đây là ID của giảng viên (giang-vien)

    $args = array(
        'post_type'      => 'lp_course',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
        's'              => $search
    );

    $args['meta_query'] = array('relation' => 'AND');

    // Nếu có chọn giảng viên cụ thể
    if ($author_id > 0) {
        $args['meta_query'][] = array(
            'key'     => 'giang_vien_khoa_hoc',
            'value'   => $author_id,
            'compare' => '='
        );
    }

    // Áp dụng logic lọc
    switch ($filter) {
        case 'newest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'popular':
            $args['meta_key'] = 'count_enrolled_users';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'free':
            $args['meta_query'][] = array(
                'key'     => '_lp_price',
                'value'   => array('', '0'),
                'compare' => 'IN'
            );
            break;
        case 'not-enrolled':
            if (is_user_logged_in()) {
                global $wpdb;
                $enrolled_ids = $wpdb->get_col($wpdb->prepare(
                    "SELECT item_id FROM {$wpdb->prefix}learnpress_user_items WHERE user_id = %d AND item_type = %s",
                    get_current_user_id(), 'lp_course'
                ));
                if (!empty($enrolled_ids)) $args['post__not_in'] = array_unique($enrolled_ids);
            }
            break;
    }

    $course_query = new WP_Query($args);

    if ($course_query->have_posts()) :
        while ($course_query->have_posts()) : $course_query->the_post();
            get_template_part('learnpress/content-course');
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No courses found.</p>';
    endif;
    die();
}

// 2. Truyền tham số admin-ajax.php vào file JS
add_action('wp_enqueue_scripts', function() {
    wp_localize_script('default-script', 'iddi_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'login_nonce' => wp_create_nonce("iddi-login-nonce"),
        'register_nonce' => wp_create_nonce("iddi-register-nonce")
    ));
});









/**
 * Quản lý Template và Assets cho trang Archive LearnPress
 * Gộp việc ghi đè file template và nhúng file CSS vào cùng một logic kiểm tra
 */
add_action( 'template_redirect', 'homenest_handle_learnpress_archive' );

function homenest_handle_learnpress_archive() {
    // 1. Kiểm tra nếu đang ở trang danh sách khóa học, danh mục hoặc thẻ của LearnPress
    if ( is_post_type_archive( 'lp_course' ) || is_tax( 'course_category' ) || is_tax( 'course_tag' ) ) {
        
        // --- PHẦN 1: NHÚNG CSS ---
        add_action( 'wp_enqueue_scripts', function() {
            $relative_css_path = '/assets/css/learnpress/archive-course.css';
            $full_css_path = get_template_directory() . $relative_css_path;

            if ( file_exists( $full_css_path ) ) {
                wp_enqueue_style( 
                    'homenest-lp-archive-custom', 
                    get_template_directory_uri() . $relative_css_path, 
                    array(), 
                    filemtime( $full_css_path ) 
                );
            }
        });

        // --- PHẦN 2: GHI ĐÈ TEMPLATE ---
        add_filter( 'template_include', function( $template ) {
            $custom_template = get_template_directory() . '/learnpress/archive-course.php';
            
            if ( file_exists( $custom_template ) ) {
                return $custom_template;
            }
            return $template;
        }, 99 );
    }
}









/**
 * Đăng ký và nạp thư viện Swiper cho trang chủ
 */
function iddi_enqueue_swiper_assets() {
    // Nạp Swiper cho trang chủ hoặc trang Podcast
    if ( is_front_page() || is_page_template('page-templates/podcast.php') ) {
        
        // 1. Nạp CSS của Swiper (Sử dụng CDN để tối ưu tốc độ)
        wp_enqueue_style(
            'swiper-css', 
            'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', 
            array(), 
            '12.0.0'
        );

        // 2. Nạp JS của Swiper
        wp_enqueue_script(
            'swiper-js', 
            'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', 
            array(), 
            '12.0.0', 
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'iddi_enqueue_swiper_assets');

/**
 * Preload Swiper assets for Podcast page
 */
function iddi_preload_swiper_assets() {
    if (is_page_template('page-templates/podcast.php')) {
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" as="style">';
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js" as="script">';

        // Preload first 3 video featured images (Above the fold)
        $args = array(
            'post_type'      => 'video',
            'posts_per_page' => 3,
            'fields'         => 'ids',
            'orderby'        => 'date',
            'order'          => 'DESC'
        );
        $video_ids = get_posts($args);
        foreach ($video_ids as $id) {
            $img_url = get_the_post_thumbnail_url($id, 'full');
            if ($img_url) {
                echo '<link rel="preload" href="' . esc_url($img_url) . '" as="image">';
            }
        }
    }
}
add_action('wp_head', 'iddi_preload_swiper_assets', 1);








// ===========================================
// START - Custom Function to Display Popup by ID
// ===========================================
function cms_display_popup_by_id($popup_name, $popup_id) {
    static $printed_css = array();

    // 1. Nạp CSS & JS
    $css_path = '/assets/css/popups/popup-' . $popup_name . '.css';
    
    // Nếu là login hoặc register, kiểm tra xem có file CSS riêng không, nếu không thì dùng chung popup-auth.css
    if (in_array($popup_name, ['login', 'register'])) {
        if (!file_exists(wp_normalize_path(get_template_directory() . $css_path))) {
            $css_path = '/assets/css/popups/popup-auth.css';
        }
    }
    
    $js_handle = 'popup-script-' . $popup_name;
    $js_path  = '/assets/js/popups/popup-' . $popup_name . '.js';

    // Nếu là login hoặc register, bắt buộc dùng chung popup-auth.js và chung script handle để tránh nạp trùng/nạp đè
    if (in_array($popup_name, ['login', 'register'])) {
        $js_handle = 'popup-script-auth';
        $js_path = '/assets/js/popups/popup-auth.js';
    }

    $normalized_css_path = wp_normalize_path(get_template_directory() . $css_path);
    if (file_exists($normalized_css_path) && !in_array($css_path, $printed_css)) {
        echo '<link rel="stylesheet" href="' . get_template_directory_uri() . $css_path . '" type="text/css" media="all" />';
        $printed_css[] = $css_path;
    }
    
    $normalized_js_path = wp_normalize_path(get_template_directory() . $js_path);
    if (file_exists($normalized_js_path)) {
        wp_enqueue_script($js_handle, get_template_directory_uri() . $js_path, array('jquery', 'default-script'), null, true);
    }

    // 2. Nạp HTML và truyền biến ID vào file template
    // Sử dụng set_query_var để file .php nhận được biến $p_id
    set_query_var('p_id', $popup_id);
    get_template_part('template-parts/popups/popup', $popup_name);
}
// ===========================================
// END - Custom Function to Display Popup by ID
// ===========================================







// ===========================================
// START - Hiển thị Popup trên toàn trang
// ===========================================
add_action('wp_footer', function() {
    // Gọi popup liên hệ (Dựa trên file popup-contact.php bạn vừa tạo)
    cms_display_popup_by_id('contact', 'popup-contact-global'); 

    // Popup Đăng nhập
    cms_display_popup_by_id('login', 'popup-login-global'); 
    
    // Popup Đăng ký
    cms_display_popup_by_id('register', 'popup-register-global'); 
});
// ===========================================
// END - Hiển thị Popup trên toàn trang
// ===========================================







/**
 * TỰ ĐỘNG CHỌN EVENT TRONG CONTACT FORM 7
 * Chức năng: Tự động khớp tiêu đề trang hiện tại với danh sách Post Type 'event' trong dropdown
 * Hỗ trợ: Xử lý ký tự đặc biệt (dấu gạch ngang, khoảng trắng), hỗ trợ Form trong Popup/Elementor
 */

add_filter( 'wpcf7_form_tag', 'custom_dynamic_select_event', 10, 2 );

function custom_dynamic_select_event( $tag, $unused ) {
    if ( $tag['name'] != 'event-select' ) return $tag;

    // 1. Lấy danh sách Event cho Dropdown
    $posts = get_posts(['post_type' => 'event', 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC']);
    if ( ! $posts ) return $tag;

    $tag['raw_values'] = $tag['values'] = $tag['labels'] = array();
    foreach ( $posts as $post ) {
        $title = trim($post->post_title);
        $tag['raw_values'][] = $tag['values'][] = $tag['labels'][] = $title;
    }

    // 2. Lấy tiêu đề bài viết hiện tại (mặc định)
    $current_page_title = wp_specialchars_decode(get_the_title(get_queried_object_id()));

    add_action('wp_footer', function() use ($current_page_title) {
        ?>
        <script>
        (function($) {
            /**
             * Hàm chuẩn hóa văn bản để so sánh chính xác
             */
            function normalizeText(text) {
                const span = document.createElement('span');
                span.innerHTML = text;
                return span.textContent.trim()
                    .replace(/[\u2010-\u2015]/g, "-") 
                    .replace(/\s+/g, ' ');
            }

            /**
             * Hàm ép chọn giá trị trong Select
             */
            function setSelectValue(targetTitle) {
                const selectEl = document.querySelector('select[name="event-select"]');
                if (!selectEl) return;

                const normalizedTarget = normalizeText(targetTitle);
                
                for (let option of selectEl.options) {
                    if (normalizeText(option.text) === normalizedTarget) {
                        option.selected = true;
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                        break;
                    }
                }
            }

            $(document).ready(function() {
                // Xử lý khi click vào nút "Đăng ký ngay" ở danh sách liên quan
                $(document).on('click', '.iddi-event-detail__related-events-enrol', function(e) {
                    e.preventDefault();
                    
                    // Lấy tiêu đề từ thuộc tính data-event-title
                    const eventTitle = $(this).attr('data-event-title');
                    
                    if (eventTitle) {
                        setSelectValue(eventTitle);
                    } else {
                        // Nếu không có data (nút mặc định), dùng tiêu đề trang hiện tại
                        setSelectValue(<?php echo json_encode($current_page_title); ?>);
                    }

                    // Mở popup
                    $('#popup-contact-global').css('display', 'flex').hide().fadeIn();
                });

                // Hỗ trợ MutationObserver cho các trường hợp Form load động
                const observer = new MutationObserver(() => {
                    const selectExists = document.querySelector('select[name="event-select"]');
                    if (selectExists && !selectExists.dataset.init) {
                        // Tự động chọn tiêu đề trang hiện tại khi form vừa xuất hiện
                        setSelectValue(<?php echo json_encode($current_page_title); ?>);
                        selectExists.dataset.init = "true";
                    }
                });
                observer.observe(document.body, { childList: true, subtree: true });
            });
        })(jQuery);
        </script>
        <?php
    }, 999);

    return $tag;
}

// HẾT ĐOẠN CODE TỰ ĐỘNG CHỌN EVENT

// ===========================================
// START - CHỨC NĂNG IN CHỨNG CHỈ (CERTIFICATE)
// ===========================================
add_action('template_redirect', 'homenest_print_certificate_endpoint');
function homenest_print_certificate_endpoint() {
    // Nếu có tham số ?print_cert trên URL
    if ( isset($_GET['print_cert']) && !empty($_GET['print_cert']) ) {
        $template_path = get_template_directory() . '/template-parts/membership/print-certificate.php';
        if ( file_exists($template_path) ) {
            require_once $template_path;
            exit; // Dừng lại ở đây, không load theme mặc định
        }
    }
}
// ===========================================
// END - CHỨC NĂNG IN CHỨNG CHỈ
// ===========================================

// ===========================================
// TÙY CHỈNH CHỨNG CHỈ (CERTIFICATE META BOX)
// ===========================================
require_once get_template_directory() . '/inc/cert-meta-box.php';
require_once get_template_directory() . '/inc/class-lp-emails-custom.php';


// ===========================================
// START - AJAX AUTHENTICATION (LOGIN & REGISTER)
// ===========================================

/**
 * Xử lý đăng nhập qua AJAX
 */
add_action('wp_ajax_iddi_ajax_login', 'iddi_ajax_login_handler');
add_action('wp_ajax_nopriv_iddi_ajax_login', 'iddi_ajax_login_handler');

function iddi_ajax_login_handler() {
    check_ajax_referer('iddi-login-nonce', 'security');

    $info = array();
    $info['user_login'] = sanitize_user($_POST['username']);
    $info['user_password'] = $_POST['password'];
    $info['remember'] = $_POST['remember'] === 'true' ? true : false;

    $user_signon = wp_signon($info, false);

    if (is_wp_error($user_signon)) {
        wp_send_json_error(array('message' => 'Wrong username or password.'));
    } else {
        wp_set_current_user($user_signon->ID);
        wp_set_auth_cookie($user_signon->ID);
        wp_send_json_success(array(
            'message' => 'Login successful! Redirecting...',
            'redirect' => home_url('/membership/')
        ));
    }
    die();
}

/**
 * Xử lý đăng ký qua AJAX
 */
add_action('wp_ajax_iddi_ajax_register', 'iddi_ajax_register_handler');
add_action('wp_ajax_nopriv_iddi_ajax_register', 'iddi_ajax_register_handler');

function iddi_ajax_register_handler() {
    check_ajax_referer('iddi-register-nonce', 'security');

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    if (username_exists($username)) {
        wp_send_json_error(array('message' => 'Username already exists.'));
    }

    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Email already registered.'));
    }

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    } else {
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name  = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $display_name = trim($last_name . ' ' . $first_name);

        $update_data = array(
            'ID' => $user_id,
        );
        if ( ! empty($first_name) ) {
            $update_data['first_name'] = $first_name;
        }
        if ( ! empty($last_name) ) {
            $update_data['last_name'] = $last_name;
        }
        if ( ! empty($display_name) ) {
            $update_data['display_name'] = $display_name;
            $update_data['nickname']     = $display_name;
        }

        if ( count($update_data) > 1 ) {
            wp_update_user($update_data);
        }

        // Tự động đăng nhập sau khi đăng ký
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_send_json_success(array(
            'message' => 'Registration successful! Welcome to IDDI Academy.',
            'redirect' => home_url('/membership/')
        ));
    }
    die();
}

// ===========================================
// END - AJAX AUTHENTICATION
// ===========================================

// ===========================================
// START - CUSTOM COMMENT CALLBACK
// ===========================================
function iddi_comment_callback($comment, $args, $depth) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    $is_staff = false;
    $user = get_userdata($comment->user_id);
    if ($user && (in_array('administrator', $user->roles) || in_array('lp_teacher', $user->roles) || in_array('editor', $user->roles))) {
        $is_staff = true;
    }
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body d-flex">
            <div class="comment-author-avatar">
                <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            </div>

            <div class="comment-content-wrap">
                <header class="comment-meta d-flex flex-ai-center">
                    <div class="comment-author-name"><?php comment_author(); ?></div>
                    <?php if ($is_staff) : ?>
                        <span class="staff-badge">STAFF</span>
                    <?php endif; ?>
                    <div class="comment-metadata">
                        <span class="comment-date">
                            <?php printf( _x( '%s ago', '%s = human-readable time difference', 'iddi' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
                        </span>
                    </div>
                </header>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-actions d-flex flex-ai-center">
                    <button class="comment-like-btn reset-button">
                        <span class="icon-like">👍</span> <span class="like-count">0</span>
                    </button>
                    <?php
                    comment_reply_link( array_merge( $args, array(
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '<span class="reply">',
                        'after'     => '</span>'
                    ) ) );
                    ?>
                </div>
            </div>
        </article>
    <?php
}
// ===========================================
// START - CUSTOM COURSE MATERIALS
// ===========================================

/**
 * Ngắt kết nối giao diện tài liệu mặc định của LearnPress
 */
add_action('init', function() {
    if (class_exists('LearnPress\TemplateHooks\Course\CourseMaterialTemplate')) {
        remove_action('learn-press/course-material/layout', array(\LearnPress\TemplateHooks\Course\CourseMaterialTemplate::instance(), 'sections'));
    }
});

/**
 * Ghi đè giao diện tài liệu bằng template trong theme
 */
add_action('learn-press/course-material/layout', 'iddi_custom_course_material_layout');

function iddi_custom_course_material_layout() {
    learn_press_get_template('single-course/materials.php');
}

// ===========================================
// END - CUSTOM COURSE MATERIALS
// ===========================================


/**
 * Tùy biến nút Download trong phần Tài liệu (Course Materials)
 */
add_filter( 'learn-press/course-material/file-link', function( $html ) {
    // Biểu tượng SVG mới
    $new_icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>';
    
    // Thay thế biểu tượng mặc định bằng biểu tượng mới
    $html = str_replace('<i class="lp-icon-file-download btn-download-material"></i>', $new_icon, $html);
    
    return $html;
}, 10, 1 );

// ===========================================
// START - EVENT TO COURSE MIGRATION TOOL
// ===========================================

/**
 * Đăng ký trang quản trị công cụ di chuyển
 */
add_action('admin_menu', 'iddi_register_migration_tool_page');
function iddi_register_migration_tool_page() {
    add_management_page(
        'Migrate Events to Courses',
        'Migrate Events',
        'manage_options',
        'iddi-migrate-events',
        'iddi_migration_tool_page_html'
    );
}

/**
 * Giao diện trang quản trị công cụ di chuyển
 */
function iddi_migration_tool_page_html() {
    ?>
    <div class="wrap iddi-migration-wrap">
        <h1 class="wp-heading-inline">Công cụ di chuyển dữ liệu: Event → LP Course</h1>
        <hr class="wp-header-end">

        <div class="card iddi-migration-card">
            <h2>Bắt đầu di chuyển</h2>
            <p>Công cụ này sẽ thực hiện các việc sau:</p>
            <ul class="ul-disc">
                <li>Sao chép Tiêu đề, Nội dung, Ảnh đại diện từ <code>event</code> sang <code>lp_course</code>.</li>
                <li>Sao chép toàn bộ các trường ACF (Day Sessions, Price, Address, Speaker,...).</li>
                <li><strong>Bước 2:</strong> Tự động chuyển đổi liên kết từ <code>related_event</code> sang <code>related_course</code> tương ứng.</li>
            </ul>
            
            <div id="migration-status" class="migration-status-box">
                Sẵn sàng thực hiện di chuyển...
            </div>

            <div class="migration-progress-bar-wrap" style="display:none;">
                <div id="migration-progress-bar" class="migration-progress-bar"></div>
            </div>

            <p class="submit">
                <button type="button" id="start-migration-btn" class="button button-primary button-hero">Bắt đầu di chuyển ngay</button>
            </p>
        </div>

        <style>
            .iddi-migration-card { max-width: 800px; margin-top: 20px; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
            .iddi-migration-card h2 { color: #1d2327; margin-top: 0; }
            .migration-status-box { background: #f0f0f1; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2271b1; font-family: monospace; min-height: 40px; }
            .migration-progress-bar-wrap { background: #e0e0e0; height: 10px; border-radius: 5px; margin-bottom: 20px; overflow: hidden; }
            .migration-progress-bar { background: #2271b1; height: 100%; width: 0%; transition: width 0.3s ease; }
            .ul-disc { margin-left: 20px; list-style: disc; }
            #start-migration-btn.loading { opacity: 0.7; pointer-events: none; }
        </style>

        <script>
            jQuery(document).ready(function($) {
                $('#start-migration-btn').on('click', function() {
                    if (!confirm('Bạn có chắc chắn muốn bắt đầu di chuyển? Quá trình này sẽ tạo các bài viết mới.')) return;

                    var $btn = $(this);
                    var $status = $('#migration-status');
                    var $progressWrap = $('.migration-progress-bar-wrap');
                    var $progressBar = $('#migration-progress-bar');

                    $btn.addClass('loading').text('Đang xử lý...');
                    $progressWrap.show();
                    $progressBar.css('width', '10%');
                    $status.html('Đang khởi chạy Bước 1: Tạo bài viết và copy dữ liệu...');

                    function runMigration(step) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'iddi_migrate_events',
                                step: step
                            },
                            success: function(response) {
                                if (response.success) {
                                    $status.append('<br>' + response.data.message);
                                    if (response.data.next_step > 0) {
                                        $progressBar.css('width', '50%');
                                        runMigration(response.data.next_step);
                                    } else {
                                        $progressBar.css('width', '100%');
                                        $status.append('<br><strong>HOÀN TẤT THÀNH CÔNG!</strong>');
                                        $btn.removeClass('loading').text('Hoàn tất').prop('disabled', true);
                                    }
                                } else {
                                    $status.append('<br><span style="color:red;">LỖI: ' + response.data + '</span>');
                                    $btn.removeClass('loading').text('Thử lại');
                                }
                            },
                            error: function() {
                                $status.append('<br><span style="color:red;">LỖI KẾT NỐI SERVER.</span>');
                                $btn.removeClass('loading').text('Thử lại');
                            }
                        });
                    }

                    runMigration(1);
                });
            });
        </script>
    </div>
    <?php
}

/**
 * Xử lý di chuyển dữ liệu qua AJAX
 */
add_action('wp_ajax_iddi_migrate_events', 'iddi_ajax_migrate_events');
function iddi_ajax_migrate_events() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Quyền truy cập bị từ chối.');
    }

    $step = isset($_POST['step']) ? intval($_POST['step']) : 1;

    if ($step === 1) {
        $events = get_posts(array(
            'post_type' => 'event',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ));

        if (empty($events)) {
            wp_send_json_success(array('message' => 'Không tìm thấy bài viết event nào.', 'next_step' => 2));
        }

        $count = 0;
        foreach ($events as $event) {
            // Kiểm tra xem đã migrate chưa để tránh tạo trùng
            $existing = get_posts(array(
                'post_type' => 'lp_course',
                'meta_key' => '_migrated_from_event_id',
                'meta_value' => $event->ID,
                'posts_per_page' => 1
            ));

            if ($existing) continue;

            $new_post = array(
                'post_title'   => $event->post_title,
                'post_content' => $event->post_content,
                'post_status'  => $event->post_status,
                'post_author'  => $event->post_author,
                'post_type'    => 'lp_course',
                'post_date'    => $event->post_date,
            );

            $course_id = wp_insert_post($new_post);

            if ($course_id) {
                update_post_meta($course_id, '_migrated_from_event_id', $event->ID);
                update_post_meta($course_id, '_lp_offline_course', 'yes');

                // Featured Image
                $thumb_id = get_post_thumbnail_id($event->ID);
                if ($thumb_id) {
                    set_post_thumbnail($course_id, $thumb_id);
                }

                // ACF Fields
                $fields_to_copy = array(
                    'day_sessions',
                    'address_event',
                    'date_range',
                    'date_and_time_event',
                    'url_get_your_ticket',
                    'price_event',
                    'event_ticket_title',
                    'event_ticket_description',
                    'giang_vien'
                );

                foreach ($fields_to_copy as $field_name) {
                    // Lấy giá trị thô (không định dạng) để bảo toàn cấu trúc ACF
                    $value = get_field($field_name, $event->ID, false);
                    if ($value !== null && $value !== false) {
                        update_field($field_name, $value, $course_id);
                    }
                }

                // --- SAO CHÉP TAXONOMY ---
                
                // 1. dia-diem -> dia-diem
                $dia_diem_terms = wp_get_object_terms($event->ID, 'dia-diem', array('fields' => 'ids'));
                if (!is_wp_error($dia_diem_terms) && !empty($dia_diem_terms)) {
                    wp_set_object_terms($course_id, array_map('intval', $dia_diem_terms), 'dia-diem');
                }

                // 2. linh-vuc -> course_category
                $linh_vuc_terms = wp_get_object_terms($event->ID, 'linh-vuc', array('fields' => 'slugs'));
                if (!is_wp_error($linh_vuc_terms) && !empty($linh_vuc_terms)) {
                    wp_set_object_terms($course_id, $linh_vuc_terms, 'course_category');
                }

                $count++;
            }
        }
        wp_send_json_success(array('message' => "Đã tạo $count bài viết lp_course mới. Đang chuyển sang Bước 2...", 'next_step' => 2));

    } elseif ($step === 2) {
        $courses = get_posts(array(
            'post_type' => 'lp_course',
            'meta_query' => array(
                array(
                    'key' => '_migrated_from_event_id',
                    'compare' => 'EXISTS'
                )
            ),
            'posts_per_page' => -1
        ));

        $count = 0;
        foreach ($courses as $course) {
            $old_event_id = get_post_meta($course->ID, '_migrated_from_event_id', true);
            if (!$old_event_id) continue;

            // Xử lý related_event sang related_course
            $related_events = get_field('related_event', $old_event_id, false);
            if ($related_events) {
                $related_courses = array();
                if (!is_array($related_events)) $related_events = array($related_events);

                foreach ($related_events as $rel_event_id) {
                    $new_course_id = iddi_find_course_by_event_id($rel_event_id);
                    if ($new_course_id) {
                        $related_courses[] = (int)$new_course_id;
                    }
                }

                if (!empty($related_courses)) {
                    update_field('related_course', $related_courses, $course->ID);
                    $count++;
                }
            }
        }
        wp_send_json_success(array('message' => "Đã cập nhật liên kết related_course cho $count khóa học.", 'next_step' => 0));
    }
}

/**
 * Tìm ID khóa học mới dựa trên ID sự kiện cũ
 */
function iddi_find_course_by_event_id($event_id) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_migrated_from_event_id' AND meta_value = %d LIMIT 1",
        $event_id
    ));
}

// ===========================================
// END - EVENT TO COURSE MIGRATION TOOL
// ===========================================

// ===========================================
// START - CUSTOM LEARNPRESS BUTTON TEXTS
// ===========================================
add_filter( 'learn-press/purchase-course-button-text', function() { return 'Đăng ký học'; } );
add_filter( 'learn-press/enroll-course-button-text', function() { return 'Đăng ký học'; } );
add_filter( 'learn-press/start-course-button-text', function() { return 'Bắt đầu học'; } );
add_filter( 'learn-press/continue-course-button-text', function() { return 'Tiếp tục học'; } );

add_filter( 'gettext', 'iddi_custom_learnpress_button_text', 99, 3 );
function iddi_custom_learnpress_button_text( $translated_text, $text, $domain ) {
    if ( $domain === 'learnpress' ) {
        $lower_text = strtolower( trim( $text ) );
        $lower_translated = strtolower( trim( $translated_text ) );
        
        // 1. Đăng ký học (Enroll / Enrol Now / Purchase / Buy Course)
        if ( in_array( $lower_text, array( 'enroll', 'enrol', 'enrol now', 'enroll now', 'purchase', 'buy this course', 'buy course' ) ) 
             || strpos( $lower_translated, 'đăng ký' ) !== false 
             || strpos( $lower_translated, 'mua khóa học' ) !== false ) {
            return 'Đăng ký học';
        }
        
        // 2. Bắt đầu học (Start Now / Start learning / Start Course / Start)
        if ( in_array( $lower_text, array( 'start now', 'start learning', 'start course', 'start' ) ) 
             || strpos( $lower_translated, 'bắt đầu' ) !== false ) {
            return 'Bắt đầu học';
        }
        
        // 3. Tiếp tục học (Continue / Continue learning)
        if ( in_array( $lower_text, array( 'continue', 'continue learning' ) ) 
             || strpos( $lower_translated, 'tiếp tục' ) !== false ) {
            return 'Tiếp tục học';
        }
    }
    return $translated_text;
}
// ===========================================
// END - CUSTOM LEARNPRESS BUTTON TEXTS
// ===========================================

// ===========================================
// BẮT ĐẦU - TÙY BIẾN TRUY VẤN TÌM KIẾM ĐA POST-TYPE & HIGHLIGHT & AJAX LOADMORE
// ===========================================

/**
 * Đăng ký 'type' thành biến truy vấn (query var) hợp lệ của WordPress
 */
add_filter( 'query_vars', function( $vars ) {
    $vars[] = 'type';
    return $vars;
} );

/**
 * Tích hợp tìm kiếm đa post-types (lp_course, post, event) vào Query chính
 */
function iddi_customize_search_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        // Hỗ trợ cả parameter 'type' theo định dạng mới và 'post_type'
        $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : get_query_var('type');
        if ( empty( $type ) ) {
            $type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
        }
        
        $allowed = array( 'lp_course', 'post', 'event' );
        if ( ! empty( $type ) && in_array( $type, $allowed ) ) {
            $query->set( 'post_type', $type );
        } else {
            // Mặc định tìm kiếm cả 3 post types khi chọn Tất cả hoặc không truyền type
            $query->set( 'post_type', $allowed );
        }
    }
}
add_action( 'pre_get_posts', 'iddi_customize_search_query' );

/**
 * Hàm highlight từ khóa tìm kiếm trong kết quả hiển thị (hỗ trợ cả AJAX)
 */
function iddi_highlight_search_term( $text, $custom_query = '' ) {
    $query = ! empty( $custom_query ) ? $custom_query : get_search_query();
    if ( ! empty( $query ) ) {
        $escaped_query = preg_quote( $query, '/' );
        $text = preg_replace( '/(' . $escaped_query . ')/iu', '<span class="search-highlight">$1</span>', $text );
    }
    return $text;
}

/**
 * AJAX xử lý tải thêm kết quả tìm kiếm (Load More)
 */
function iddi_ajax_load_more_search() {
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    $allowed = array( 'lp_course', 'post', 'event' );
    $post_type = ( ! empty( $type ) && in_array( $type, $allowed ) ) ? $type : $allowed;
    
    $args = array(
        's'              => $search,
        'post_type'      => $post_type,
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged'          => $paged,
        'post_status'    => 'publish',
    );
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            ?>
            <div class="search-page__result-item">
                <div class="search-page__result-breadcrumb">
                    <?php 
                    $breadcrumbs = array('Home');
                    $curr_type = get_post_type();
                    if ( $curr_type === 'lp_course' ) {
                        $breadcrumbs[] = 'Khóa học';
                    } elseif ( $curr_type === 'event' ) {
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
                        echo iddi_highlight_search_term( $title, $search ); 
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
                    echo iddi_highlight_search_term( $excerpt, $search );
                    ?>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    }
    wp_die();
}
add_action( 'wp_ajax_iddi_load_more_search', 'iddi_ajax_load_more_search' );
add_action( 'wp_ajax_nopriv_iddi_load_more_search', 'iddi_ajax_load_more_search' );

// ===========================================
// KẾT THÚC - TÙY BIẾN TRUY VẤN TÌM KIẾM ĐA POST-TYPE & HIGHLIGHT & AJAX LOADMORE
// ===========================================

/**
 * Ép sử dụng template-blog.php khi tìm kiếm từ trang blog (/blog/?s=...)
 */
function iddi_force_blog_template_for_blog_search( $template ) {
    if ( is_search() ) {
        $request_uri = $_SERVER['REQUEST_URI'];
        if ( strpos( $request_uri, '/blog/' ) !== false ) {
            $new_template = locate_template( array( 'template-blog.php' ) );
            if ( $new_template ) {
                return $new_template;
            }
        }
    }
    return $template;
}
add_filter( 'template_include', 'iddi_force_blog_template_for_blog_search', 99 );

/**
 * Sửa lỗi 404 khi truy cập /blog/?s={từ_khóa}
 * Bằng cách gỡ bỏ pagename/page/name khỏi query_vars để WordPress nhận diện đây là câu truy vấn tìm kiếm thuần túy.
 */
function iddi_fix_blog_search_404( $query_vars ) {
    if ( isset( $query_vars['s'] ) ) {
        $request_uri = $_SERVER['REQUEST_URI'];
        if ( strpos( $request_uri, '/blog/' ) !== false ) {
            unset( $query_vars['pagename'] );
            unset( $query_vars['page'] );
            unset( $query_vars['name'] );
            unset( $query_vars['attachment'] );
        }
    }
    return $query_vars;
}
add_filter( 'request', 'iddi_fix_blog_search_404' );

// =========================================================================
// TÍCH HỢP PHƯƠNG THỨC THANH TOÁN CHUYỂN KHOẢN NGÂN HÀNG (VIETQR) TỰ ĐỘNG
// =========================================================================

add_filter( 'learn_press_payment_gateways', 'iddi_register_bank_payment_gateway' );
add_filter( 'learn-press/payment-gateways', 'iddi_register_bank_payment_gateway' );
add_filter( 'learn-press/payment-methods', 'iddi_register_bank_payment_gateway' );
add_filter( 'learn_press_payment_method', 'iddi_register_bank_payment_gateway' );
function iddi_register_bank_payment_gateway( $gateways ) {
    require_once get_template_directory() . '/inc/class-lp-gateway-iddi-bank.php';
    $gateways['iddi_bank'] = 'LP_Gateway_IDDI_Bank';
    return $gateways;
}

/**
 * 2. Hàm loại bỏ dấu tiếng Việt
 */
function iddi_remove_vietnamese_tones($str) {
    $unicode = array(
        'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd'=>'đ|Đ',
        'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
        'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u'=>'ú|à|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|À|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D'=>'Đ',
        'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
        'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U'=>'Ú|À|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );
    foreach($unicode as $nonUnicode=>$uni){
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = preg_replace('/[^a-zA-Z0-9\s\-]/', '', $str);
    return $str;
}

/**
 * 2b. Lấy mã BIN 6 số của ngân hàng từ mã viết tắt (Short Name) của VietQR
 */
function iddi_get_bank_bin( $bank_code ) {
    $bank_code = strtoupper( trim( $bank_code ) );
    
    // Nếu mã ngân hàng đã là 6 số thì trả về luôn
    if ( preg_match( '/^\d{6}$/', $bank_code ) ) {
        return $bank_code;
    }
    
    // Thử lấy danh sách ngân hàng từ cache WordPress Transient
    $banks = get_transient( 'iddi_vietqr_banks' );
    if ( ! is_array( $banks ) ) {
        $response = wp_remote_get( 'https://api.vietqr.io/v2/banks' );
        if ( ! is_wp_error( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $json = json_decode( $body, true );
            if ( isset( $json['code'] ) && $json['code'] === '00' && isset( $json['data'] ) ) {
                $banks = array();
                foreach ( $json['data'] as $b ) {
                    $banks[ strtoupper( $b['code'] ) ] = $b['bin'];
                    $banks[ strtoupper( $b['shortName'] ) ] = $b['bin'];
                    if ( isset( $b['short_name'] ) ) {
                        $banks[ strtoupper( $b['short_name'] ) ] = $b['bin'];
                    }
                }
                set_transient( 'iddi_vietqr_banks', $banks, DAY_IN_SECONDS * 7 ); // Lưu cache 7 ngày
            }
        }
    }
    
    if ( is_array( $banks ) && isset( $banks[ $bank_code ] ) ) {
        return $banks[ $bank_code ];
    }
    
    // Danh sách dự phòng các ngân hàng phổ biến nếu API lỗi hoặc không phản hồi
    $fallback_mapping = array(
        'BIDV' => '970418',
        'VCB' => '970436',
        'VIETCOMBANK' => '970436',
        'TCB' => '970407',
        'TECHCOMBANK' => '970407',
        'MB' => '970422',
        'MBB' => '970422',
        'MBBANK' => '970422',
        'ACB' => '970416',
        'ICB' => '970415',
        'VIETINBANK' => '970415',
        'VPB' => '970432',
        'VPBANK' => '970432',
        'TPB' => '970423',
        'TPBANK' => '970423',
        'SHB' => '970443',
        'HDB' => '970437',
        'HDBANK' => '970437',
        'STB' => '970403',
        'SACOMBANK' => '970403',
        'VIB' => '970441',
        'MSB' => '970426',
        'SGB' => '970429',
        'SAIGONBANK' => '970429',
        'ABB' => '970425',
        'ABBANK' => '970425',
        'VAB' => '970427',
        'VIETABANK' => '970427',
        'NAB' => '970428',
        'NAMABANK' => '970428',
        'BAB' => '970409',
        'BACABANK' => '970409',
        'VBA' => '970405',
        'AGRIBANK' => '970405',
        'LPB' => '970449',
        'LPBANK' => '970449',
        'LIENVIETPOSTBANK' => '970449',
        'EIB' => '970431',
        'EXIMBANK' => '970431',
        'SEAB' => '970468',
        'SEABANK' => '970468',
        'OCB' => '970448',
        'KLB' => '970452',
        'KIENLONGBANK' => '970452',
    );
    
    return isset( $fallback_mapping[ $bank_code ] ) ? $fallback_mapping[ $bank_code ] : '';
}

/**
 * 2c. Tính toán CRC16-CCITT chuẩn EMVCo/NAPAS
 */
function iddi_crc16_ccitt( $str ) {
    $crc = 0xFFFF;
    $len = strlen( $str );
    for ( $i = 0; $i < $len; $i++ ) {
        $crc ^= ( ord( $str[$i] ) << 8 );
        for ( $j = 0; $j < 8; $j++ ) {
            if ( ( $crc & 0x8000 ) != 0 ) {
                $crc = ( $crc << 1 ) ^ 0x1021;
            } else {
                $crc <<= 1;
            }
        }
    }
    $crc = $crc & 0xFFFF;
    return str_pad( strtoupper( dechex( $crc ) ), 4, '0', STR_PAD_LEFT );
}

/**
 * 2d. Tạo chuỗi VietQR chuẩn EMVCo hoàn toàn offline để làm mã QR dự phòng
 */
function iddi_generate_vietqr_emvco( $bank_code, $acc_no, $amount, $memo, $acc_name = '' ) {
    $bin = iddi_get_bank_bin( $bank_code );
    if ( ! $bin ) {
        return '';
    }
    
    // Loại bỏ mọi ký tự phi số trong số tài khoản
    $acc_no = preg_replace( '/[^0-9]/', '', $acc_no );
    
    // Cấu trúc Tag 38 (Merchant Account Information)
    // Sub-tag 00: GUID của NAPAS (cố định: A000000727)
    $guid_str = "0010A000000727";
    
    // Sub-tag 01: Beneficiary Info (BIN + Số tài khoản thụ hưởng)
    // sub-sub-tag 00: Acquirer BIN
    $bin_str = "00" . str_pad( strlen( $bin ), 2, '0', STR_PAD_LEFT ) . $bin;
    // sub-sub-tag 01: Merchant Account Number
    $acc_str = "01" . str_pad( strlen( $acc_no ), 2, '0', STR_PAD_LEFT ) . $acc_no;
    
    $beneficiary_content = $bin_str . $acc_str;
    $beneficiary_str = "01" . str_pad( strlen( $beneficiary_content ), 2, '0', STR_PAD_LEFT ) . $beneficiary_content;
    
    // Sub-tag 02: Service Code (Chuyển khoản nhanh Napas247 - mặc định QRIBFTTA)
    $service_str = "0208QRIBFTTA";
    
    $tag38_content = $guid_str . $beneficiary_str . $service_str;
    $tag38 = "38" . str_pad( strlen( $tag38_content ), 2, '0', STR_PAD_LEFT ) . $tag38_content;
    
    // Danh sách TLV cơ bản
    $tlv = array();
    $tlv['00'] = '000201'; // Payload Format Indicator (Phiên bản định dạng)
    $tlv['01'] = '010212'; // Point of Initiation Method (12: QR động có số tiền cố định)
    $tlv['38'] = $tag38;   // Merchant Account Info
    $tlv['53'] = '5303704'; // Currency Code (704: VNĐ)
    
    if ( $amount > 0 ) {
        $amount_str = strval( intval( $amount ) );
        $tlv['54'] = '54' . str_pad( strlen( $amount_str ), 2, '0', STR_PAD_LEFT ) . $amount_str;
    }
    
    $tlv['58'] = '5802VN'; // Country Code (VN)
    
    if ( ! empty( $acc_name ) ) {
        $clean_name = strtoupper( iddi_remove_vietnamese_tones( $acc_name ) );
        // Giới hạn tên Merchant tối đa 25 ký tự theo đặc tả kỹ thuật EMVCo/NAPAS
        $clean_name = substr( $clean_name, 0, 25 );
        $tlv['59'] = '59' . str_pad( strlen( $clean_name ), 2, '0', STR_PAD_LEFT ) . $clean_name;
    }
    
    if ( ! empty( $memo ) ) {
        $clean_memo = strtoupper( iddi_remove_vietnamese_tones( $memo ) );
        // Sub-tag 08 (Payment Reference) trong Tag 62 (Additional Data Field Template)
        $memo_str = "08" . str_pad( strlen( $clean_memo ), 2, '0', STR_PAD_LEFT ) . $clean_memo;
        $tlv['62'] = '62' . str_pad( strlen( $memo_str ), 2, '0', STR_PAD_LEFT ) . $memo_str;
    }
    
    // Gộp tất cả các tag lại theo thứ tự tăng dần
    $base_str = '';
    ksort( $tlv );
    foreach ( $tlv as $tag => $val ) {
        $base_str .= $val;
    }
    
    // Nối Tag 63 (mã CRC) độ dài 4 ký tự
    $base_str .= '6304';
    
    // Tính CRC-16
    $crc = iddi_crc16_ccitt( $base_str );
    
    return $base_str . $crc;
}

/**
 * 3. Hiển thị khối thông tin chuyển khoản VietQR ở trang cám ơn
 */
add_action( 'learn-press/order/received', 'iddi_bank_render_order_received_details', 10, 1 );
function iddi_bank_render_order_received_details( $order ) {
    if ( ! $order ) {
        return;
    }
    
    if ( method_exists( $order, 'get_payment_method' ) && $order->get_payment_method() !== 'iddi_bank' ) {
        return;
    }
    
    // Lấy thông tin cấu hình từ Cổng thanh toán
    $gateways = LP_Gateways::instance()->get_gateways();
    $gateway = isset( $gateways['iddi_bank'] ) ? $gateways['iddi_bank'] : null;
    
    $acc_name  = $gateway ? $gateway->settings->get( 'account_name', 'Công Ty Cổ Phần Học Viện Quốc Tế Nha Khoa Và Đổi Mới Số' ) : 'Công Ty Cổ Phần Học Viện Quốc Tế Nha Khoa Và Đổi Mới Số';
    $bank_name = $gateway ? $gateway->settings->get( 'bank_name', 'BIDV Chợ Lớn' ) : 'BIDV Chợ Lớn';
    $bank_code = $gateway ? $gateway->settings->get( 'bank_code', 'bidv' ) : 'bidv';
    $acc_no    = $gateway ? $gateway->settings->get( 'account_no', '8640080519' ) : '8640080519';
    
    $amount = 0;
    if ( method_exists( $order, 'get_total' ) ) {
        $amount = $order->get_total();
    } elseif ( isset( $order->order_total ) ) {
        $amount = $order->order_total;
    }
    
    $order_id = $order->get_id();
    
    // Lấy tên display name của user mua khóa học và tên các khóa học
    $display_name = 'Guest';
    $user_id = $order->get_user_id();
    $user = get_userdata( $user_id );
    if ( $user ) {
        $display_name = $user->display_name;
    }
    
    $item_names = array();
    $items = $order->get_items();
    if ( ! empty( $items ) ) {
        foreach ( $items as $item ) {
            $course_id = isset( $item['course_id'] ) ? $item['course_id'] : 0;
            if ( $course_id ) {
                $item_names[] = get_the_title( $course_id );
            }
        }
    }
    $course_name = ! empty( $item_names ) ? implode( ', ', $item_names ) : 'Khoa hoc';
    
    $clean_display_name = iddi_remove_vietnamese_tones( $display_name );
    $clean_course_name = iddi_remove_vietnamese_tones( $course_name );
    
    $raw_memo = $clean_display_name . ' ' . strtoupper( $clean_course_name );
    $memo = substr( $raw_memo, 0, 90 );
    $encoded_memo = rawurlencode( $memo );
    
    $clean_acc_name = strtoupper( iddi_remove_vietnamese_tones( $acc_name ) );
    $encoded_acc_name = rawurlencode( $clean_acc_name );
    
    // Làm sạch tuyệt đối bank_code và acc_no để tạo URL chính xác
    $clean_bank_code = preg_replace( '/[^a-zA-Z0-9]/', '', $bank_code );
    $clean_acc_no = preg_replace( '/[^0-9]/', '', $acc_no );
    
    // Tạo chuỗi mã hóa VietQR chuẩn EMVCo dự phòng
    $emvco_string = iddi_generate_vietqr_emvco( $clean_bank_code, $clean_acc_no, $amount, $memo, $acc_name );
    
    // Link mã QR động dựa trên cấu hình admin
    $qr_url = "https://img.vietqr.io/image/" . sanitize_title( $clean_bank_code ) . "-" . sanitize_text_field( $clean_acc_no ) . "-compact2.png?amount=" . intval( $amount ) . "&addInfo=" . $encoded_memo . "&accountName=" . $encoded_acc_name;
    
    ?>
    <style>
        .iddi-payment-container {
            margin: 40px auto;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(229, 231, 235, 0.8);
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        .iddi-payment-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%);
            padding: 30px;
            color: #ffffff;
            text-align: center;
        }
        .iddi-payment-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: #ffffff !important;
        }
        .iddi-payment-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
            color: #ffffff;
        }
        .iddi-payment-content {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 30px;
            padding: 40px;
        }
        @media (max-width: 768px) {
            .iddi-payment-content {
                grid-template-columns: 1fr;
                padding: 25px;
            }
        }
        .iddi-payment-details {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .iddi-detail-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e5e7eb;
        }
        .iddi-detail-row:last-child {
            border-bottom: none;
        }
        .iddi-detail-label {
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-align: left;
        }
        .iddi-detail-value-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .iddi-detail-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
            text-align: left;
        }
        .iddi-detail-value.highlight {
            color: #ef4444;
            font-size: 18px;
            font-weight: 700;
        }
        .iddi-copy-btn {
            background: #f3f4f6;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            color: #4b5563;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .iddi-copy-btn:hover {
            background: #e5e7eb;
            color: #111827;
        }
        .iddi-copy-btn.copied {
            background: #d1fae5;
            color: #065f46;
        }
        .iddi-payment-qr {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            text-align: center;
        }
        .iddi-qr-wrapper {
            position: relative;
            background: #ffffff;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: inline-block;
            overflow: hidden;
        }
        .iddi-qr-image {
            display: block;
            max-width: 200px;
            height: auto;
        }
        .iddi-qr-tip {
            margin-top: 15px;
            font-size: 13px;
            color: #64748b;
            line-height: 1.4;
        }
        .iddi-qr-tip strong {
            color: #1e3a8a;
        }
        .iddi-payment-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 40px;
            text-align: center;
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }
    </style>
    
    <div class="iddi-payment-container">
        <div class="iddi-payment-header">
            <h2>THÔNG TIN THANH TOÁN CHUYỂN KHOẢN</h2>
            <p>Khóa học sẽ tự động kích hoạt ngay sau khi hệ thống nhận được giao dịch chuyển khoản từ quý bác sĩ.</p>
        </div>
        <div class="iddi-payment-content">
            <div class="iddi-payment-details">
                <div class="iddi-detail-row">
                    <span class="iddi-detail-label">Chủ tài khoản</span>
                    <div class="iddi-detail-value-wrapper">
                        <span class="iddi-detail-value"><?php echo esc_html( $acc_name ); ?></span>
                    </div>
                </div>
                <div class="iddi-detail-row">
                    <span class="iddi-detail-label">Ngân hàng</span>
                    <div class="iddi-detail-value-wrapper">
                        <span class="iddi-detail-value"><?php echo esc_html( $bank_name ); ?></span>
                    </div>
                </div>
                <div class="iddi-detail-row">
                    <span class="iddi-detail-label">Số tài khoản</span>
                    <div class="iddi-detail-value-wrapper">
                        <span class="iddi-detail-value" id="iddi-stk"><?php echo esc_html( $acc_no ); ?></span>
                        <button class="iddi-copy-btn" onclick="iddiCopyText('iddi-stk', this)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            Sao chép
                        </button>
                    </div>
                </div>
                <div class="iddi-detail-row">
                    <span class="iddi-detail-label">Số tiền</span>
                    <div class="iddi-detail-value-wrapper">
                        <span class="iddi-detail-value highlight" id="iddi-amount"><?php echo number_format( $amount, 0, ',', '.' ); ?> VNĐ</span>
                        <button class="iddi-copy-btn" onclick="iddiCopyText('iddi-amount', this)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            Sao chép
                        </button>
                    </div>
                </div>
                <div class="iddi-detail-row">
                    <span class="iddi-detail-label">Nội dung chuyển khoản (Bắt buộc đúng)</span>
                    <div class="iddi-detail-value-wrapper">
                        <span class="iddi-detail-value" id="iddi-memo" style="color: #0d9488; font-family: monospace; font-size: 18px;"><?php echo esc_html( $memo ); ?></span>
                        <button class="iddi-copy-btn" onclick="iddiCopyText('iddi-memo', this)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            Sao chép
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="iddi-payment-qr">
                <div class="iddi-qr-wrapper">
                    <div class="iddi-qr-scanner-line"></div>
                    <img class="iddi-qr-image" id="iddi-qr-img" 
                         src="<?php echo esc_url( $qr_url ); ?>" 
                         data-emvco="<?php echo esc_attr( $emvco_string ); ?>" 
                         alt="VietQR BIDV IDDI Payment" 
                         onerror="iddiHandleQrError(this)">
                </div>
                <div class="iddi-qr-tip" id="iddi-qr-tip-container">
                    <p>Quét mã <strong>VietQR</strong> để điền thông tin chuyển khoản tự động và chính xác.</p>
                </div>
            </div>
        </div>
        
        <div class="iddi-payment-footer">
            <p>Hệ thống sẽ tự động kích hoạt tài khoản học tập của bác sĩ trong 1-3 phút sau khi chuyển khoản thành công. Nếu có trục trặc gì xin liên hệ Hotline/Zalo để được hỗ trợ tức thì.</p>
        </div>
    </div>
    
    <script>
        // Cơ chế dự phòng thông minh khi VietQR API bị quá tải hoặc lỗi kết nối
        function iddiHandleQrError(img) {
            console.warn("[IDDI Payment] VietQR image load failed. Attempting fallback...");
            
            var tried = img.getAttribute('data-fallback-tried') || '0';
            
            if (tried === '0') {
                img.setAttribute('data-fallback-tried', '1');
                // Lớp 1: Thử đổi sang tên miền dự phòng api.vietqr.io thay vì img.vietqr.io
                var currentSrc = img.src;
                if (currentSrc.indexOf('img.vietqr.io') !== -1) {
                    var fallbackSrc = currentSrc.replace('img.vietqr.io', 'api.vietqr.io');
                    console.log("[IDDI Payment] Trying Fallback 1: " + fallbackSrc);
                    img.src = fallbackSrc;
                    return;
                }
            }
            
            if (tried === '1' || tried === '0') {
                img.setAttribute('data-fallback-tried', '2');
                // Lớp 2: Sử dụng chuỗi EMVCo gốc để tạo mã QR thông qua API qrserver.com
                var emvco = img.getAttribute('data-emvco');
                if (emvco) {
                    var fallbackSrc = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(emvco);
                    console.log("[IDDI Payment] Trying Fallback 2 (qrserver.com): " + fallbackSrc);
                    img.src = fallbackSrc;
                    
                    var tipContainer = document.getElementById('iddi-qr-tip-container');
                    if (tipContainer) {
                        tipContainer.innerHTML = '<p style="color: #d97706; font-weight: 500; margin-top: 10px; font-size: 13px;">⚠️ Đang sử dụng mã QR dự phòng do hệ thống VietQR bị quá tải. Quý khách vẫn quét thanh toán bình thường.</p>';
                    }
                    return;
                }
            }
            
            if (tried === '2') {
                img.setAttribute('data-fallback-tried', '3');
                // Lớp 3: Sử dụng API quickchart.io làm dự phòng tiếp theo
                var emvco = img.getAttribute('data-emvco');
                if (emvco) {
                    var fallbackSrc = "https://quickchart.io/qr?size=250&text=" + encodeURIComponent(emvco);
                    console.log("[IDDI Payment] Trying Fallback 3 (quickchart.io): " + fallbackSrc);
                    img.src = fallbackSrc;
                    return;
                }
            }
            
            // Nếu tất cả các cách trên đều lỗi, ẩn mã QR đi và hiện thông báo lỗi thân thiện
            console.error("[IDDI Payment] All QR generation fallbacks failed.");
            img.style.display = 'none';
            var qrWrapper = img.parentElement;
            if (qrWrapper) {
                var scanner = qrWrapper.querySelector('.iddi-qr-scanner-line');
                if (scanner) scanner.style.display = 'none';
                
                var errorDiv = document.createElement('div');
                errorDiv.style.padding = '20px';
                errorDiv.style.color = '#ef4444';
                errorDiv.style.fontSize = '14px';
                errorDiv.style.fontWeight = '600';
                errorDiv.style.lineHeight = '1.5';
                errorDiv.innerHTML = 'Không thể tự động tải mã QR thanh toán. Xin quý bác sĩ vui lòng thực hiện chuyển khoản thủ công theo thông tin chi tiết bên cạnh hoặc <a href="javascript:location.reload()" style="text-decoration: underline; color: #3b82f6;">bấm vào đây để tải lại trang</a>.';
                qrWrapper.appendChild(errorDiv);
            }
        }

        function iddiCopyText(elementId, btn) {
            var text = document.getElementById(elementId).innerText;
            if (elementId === 'iddi-amount') {
                text = text.replace(' VNĐ', '').replace(/\./g, '');
            }
            var tempInput = document.createElement("input");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999);
            try {
                document.execCommand("copy");
                var oldText = btn.innerHTML;
                btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #059669;"><polyline points="20 6 9 17 4 12"></polyline></svg> Đã chép';
                btn.classList.add('copied');
                setTimeout(function() {
                    btn.innerHTML = oldText;
                    btn.classList.remove('copied');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
            document.body.removeChild(tempInput);
        }

        // Tự động kiểm tra trạng thái thanh toán và in ra console.log để debug
        (function() {
            var orderId = <?php echo intval( $order_id ); ?>;
            console.log("[IDDI Payment] Bat dau kiem tra tu dong trang thai thanh toan don hang #" + orderId);
            
            var checkInterval = setInterval(function() {
                console.log("[IDDI Payment] Dang kiem tra trang thai thanh toan cua don hang #" + orderId + "...");
                fetch("/wp-json/iddi/v1/order-status?order_id=" + orderId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("HTTP error " + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("[IDDI Payment] Phan hoi tu server:", data);
                        if (data.status === "success" && data.order_status === "completed") {
                            console.log("[IDDI Payment] THANH TOÁN THÀNH CÔNG! Dang tu dong chuyen huong...");
                            clearInterval(checkInterval);
                            
                            // Hien thi hop thoai thong bao va chuyen huong vao bai hoc dau tien
                            alert("Cảm ơn quý bác sĩ! Giao dịch chuyển khoản đã thành công. Khóa học đã được kích hoạt.");
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        } else {
                            console.log("[IDDI Payment] Don hang hien tai dang o trang thai: \"" + (data.order_status || "unknown") + "\"");
                        }
                    })
                    .catch(error => {
                        console.error("[IDDI Payment] Loi khi kiem tra trang thai don hang:", error);
                    });
            }, 3000); // Kiem tra moi 3 giay
        })();
    </script>
    <?php
}

/**
 * 4. Đăng ký WordPress REST API Endpoint Webhook nhận dữ liệu giao dịch
 * Route: /wp-json/iddi/v1/payment-webhook
 */
add_action( 'rest_api_init', 'iddi_register_payment_webhook' );
function iddi_register_payment_webhook() {
    register_rest_route( 'iddi/v1', '/payment-webhook', array(
        'methods'             => array( 'GET', 'POST' ),
        'callback'            => 'iddi_handle_payment_webhook',
        'permission_callback' => '__return_true',
    ) );
}

/**
 * 5. Xử lý dữ liệu Webhook từ Casso/Sepay/PayOS
 */
function iddi_handle_payment_webhook( WP_REST_Request $request ) {
    $params = $request->get_json_params();
    if ( empty( $params ) ) {
        $params = $request->get_body_params();
    }
    if ( empty( $params ) ) {
        $params = $request->get_query_params();
    }
    $headers = $request->get_headers();
    
    // --- GHI LOG YÊU CẦU ---
    $log_data = array(
        'time'    => date('Y-m-d H:i:s'),
        'ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
        'get'     => $_GET,
        'headers' => $headers,
        'params'  => $params,
    );
    file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', print_r( $log_data, true ) . "\n-------------------\n", FILE_APPEND );
    
    // --- BẢO MẬT: Kiểm tra Token xác thực (API Key) từ Cổng thanh toán ---
    $gateways = LP_Gateways::instance()->get_gateways();
    $gateway = isset( $gateways['iddi_bank'] ) ? $gateways['iddi_bank'] : null;
    $auth_key = $gateway ? $gateway->settings->get( 'webhook_token', 'iddi_sec_token_2026' ) : 'iddi_sec_token_2026';
    
    $received_token = '';
    
    // 1. Kiểm tra trong Headers (WP_REST_Request chuẩn hóa key về chữ thường)
    if ( isset( $headers['secure_token'][0] ) ) {
        $received_token = sanitize_text_field( $headers['secure_token'][0] );
    } elseif ( isset( $headers['secure-token'][0] ) ) {
        $received_token = sanitize_text_field( $headers['secure-token'][0] );
    } elseif ( isset( $headers['x-api-key'][0] ) ) {
        $received_token = sanitize_text_field( $headers['x-api-key'][0] );
    } elseif ( isset( $headers['authorization'][0] ) ) {
        $auth_header = trim( $headers['authorization'][0] );
        if ( preg_match( '/Apikey\s+(.*)$/i', $auth_header, $matches ) ) {
            $received_token = sanitize_text_field( trim( $matches[1] ) );
        } elseif ( preg_match( '/Bearer\s+(.*)$/i', $auth_header, $matches ) ) {
            $received_token = sanitize_text_field( trim( $matches[1] ) );
        } else {
            $received_token = sanitize_text_field( $auth_header );
        }
    }
    
    // 2. Kiểm tra trong Query string hoặc Params body nếu headers không có
    if ( empty( $received_token ) ) {
        if ( isset( $_GET['token'] ) ) {
            $received_token = sanitize_text_field( $_GET['token'] );
        } elseif ( isset( $params['secure_token'] ) ) {
            $received_token = sanitize_text_field( $params['secure_token'] );
        } elseif ( isset( $params['token'] ) ) {
            $received_token = sanitize_text_field( $params['token'] );
        }
    }
    
    if ( empty( $received_token ) || $received_token !== $auth_key ) {
        // Ghi log lỗi xác thực token đầy đủ hơn để phục vụ debug
        $err_msg = "Auth Error at " . date('Y-m-d H:i:s') . ": Received token [" . $received_token . "] does not match expected Auth Key [" . $auth_key . "]\n";
        $err_msg .= "All Request Headers: " . print_r( $headers, true ) . "\n";
        $err_msg .= "All Request Params: " . print_r( $params, true ) . "\n";
        file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', $err_msg . "-------------------\n", FILE_APPEND );
        return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Unauthorized token.' ), 401 );
    }
    
    // --- PARSE GIAO DỊCH CHUẨN HÓA CHO SEPAY VÀ CASSO ---
    $transactions = array();
    
    // Trường hợp Casso Webhook V1 / V2
    if ( isset( $params['data'] ) ) {
        if ( is_array( $params['data'] ) ) {
            if ( isset( $params['data'][0] ) ) {
                $transactions = $params['data'];
            } else {
                $transactions[] = $params['data'];
            }
        }
    } 
    // Trường hợp SePay (gửi trực tiếp object giao dịch ở cấp root)
    elseif ( isset( $params['content'] ) || isset( $params['transferAmount'] ) ) {
        $transactions[] = $params;
    }
    // Dự phòng trường hợp khác (transactions gửi dưới dạng mảng hoặc root object)
    else {
        if ( isset( $params['transactions'] ) && is_array( $params['transactions'] ) ) {
            $transactions = $params['transactions'];
        } else {
            $transactions[] = $params;
        }
    }
    
    $activated_orders = array();
    
    foreach ( $transactions as $transaction ) {
        // Lấy nội dung chuyển khoản (ưu tiên content của SePay, sau đó đến description của Casso)
        $description = '';
        if ( isset( $transaction['content'] ) ) {
            $description = $transaction['content'];
        } elseif ( isset( $transaction['description'] ) ) {
            $description = $transaction['description'];
        }
        
        // Lấy số tiền (ưu tiên transferAmount của SePay, sau đó đến amount của Casso)
        $amount = 0;
        if ( isset( $transaction['transferAmount'] ) ) {
            $amount = floatval( $transaction['transferAmount'] );
        } elseif ( isset( $transaction['amount'] ) ) {
            $amount = floatval( $transaction['amount'] );
        }
        
        file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "Processing transaction: Desc: \"" . $description . "\" | Amount: " . $amount . "\n", FILE_APPEND );
        
        if ( empty( $description ) ) {
            continue;
        }
        
        // Khớp IDDI theo sau bởi khoảng trắng, gạch ngang, gạch dưới tuỳ ý và ID đơn hàng
        if ( preg_match( '/IDDI\s*[-_]*\s*(\d+)/i', $description, $matches ) ) {
            $order_id = intval( $matches[1] );
            $order = learn_press_get_order( $order_id );
            
            if ( ! $order ) {
                file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "Match found for Order ID: " . $order_id . " but Order does not exist in system.\n", FILE_APPEND );
                continue;
            }
            
            $status = $order->get_status();
            file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "Order ID: " . $order_id . " found | Status: \"" . $status . "\"\n", FILE_APPEND );
            
            if ( $status === 'pending' || $status === 'processing' ) {
                $order_total = 0;
                if ( method_exists( $order, 'get_total' ) ) {
                    $order_total = floatval( $order->get_total() );
                } elseif ( isset( $order->order_total ) ) {
                    $order_total = floatval( $order->order_total );
                }
                
                file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "Order Total: " . $order_total . " | Received: " . $amount . "\n", FILE_APPEND );
                
                if ( abs( $amount - $order_total ) < 500 ) {
                    file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "SUCCESS: Amount matched. (Auto-completion is disabled per request. Admin must manually set status to Completed).\n", FILE_APPEND );
                    
                    // Đã tắt tự động kích hoạt theo yêu cầu của Admin để duyệt thủ công
                    // $order->payment_complete();
                    // $order->update_status( 'completed' );
                    
                    $activated_orders[] = $order_id;
                } else {
                    file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "FAILED: Amount mismatched (difference >= 500).\n", FILE_APPEND );
                }
            } else {
                file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "FAILED: Order is not pending or processing (Current status: \"" . $status . "\").\n", FILE_APPEND );
            }
        } else {
            file_put_contents( get_template_directory() . '/iddi_webhook_log.txt', "FAILED: No Order ID match found in description.\n", FILE_APPEND );
        }
    }
    
    return new WP_REST_Response( array( 
        'success' => true,
        'status' => 'success', 
        'message' => 'Processed webhook successfully.', 
        'activated_orders' => $activated_orders 
    ), 200 );
}

/**
 * Endpoint phụ trợ để Admin xem nhanh log chẩn đoán trực tiếp từ trình duyệt
 * Route: /wp-json/iddi/v1/view-logs?token=[secure_token]
 */
add_action( 'rest_api_init', 'iddi_register_view_logs_route' );
function iddi_register_view_logs_route() {
    register_rest_route( 'iddi/v1', '/view-logs', array(
        'methods'             => 'GET',
        'callback'            => 'iddi_handle_view_logs',
        'permission_callback' => '__return_true',
    ) );
}

function iddi_handle_view_logs( WP_REST_Request $request ) {
    $gateways = LP_Gateways::instance()->get_gateways();
    $gateway = isset( $gateways['iddi_bank'] ) ? $gateways['iddi_bank'] : null;
    $auth_key = $gateway ? $gateway->settings->get( 'webhook_token', 'iddi_sec_token_2026' ) : 'iddi_sec_token_2026';
    
    $received_token = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';
    if ( empty( $received_token ) || $received_token !== $auth_key ) {
        return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Unauthorized.' ), 401 );
    }
    
    $log_file = get_template_directory() . '/iddi_webhook_log.txt';
    if ( ! file_exists( $log_file ) ) {
        return new WP_REST_Response( array( 'status' => 'success', 'message' => 'File log chua ton tai. Webhook chua tung duoc goi den.' ), 200 );
    }
    
    $log_content = file_get_contents( $log_file );
    // Trả về tối đa 150 dòng log cuối cùng để dễ đọc
    $lines = explode( "\n", $log_content );
    $last_lines = array_slice( $lines, -150 );
    $logs = implode( "\n", $last_lines );
    
    return new WP_REST_Response( array( 
        'status' => 'success', 
        'logs' => $logs 
    ), 200 );
}

/**
 * Endpoint kiểm tra trạng thái đơn hàng thời gian thực phục vụ client-side polling
 * Route: /wp-json/iddi/v1/order-status?order_id=[order_id]
 */
add_action( 'rest_api_init', 'iddi_register_order_status_route' );
function iddi_register_order_status_route() {
    register_rest_route( 'iddi/v1', '/order-status', array(
        'methods'             => 'GET',
        'callback'            => 'iddi_get_order_status',
        'permission_callback' => '__return_true',
    ) );
}

function iddi_get_order_status( WP_REST_Request $request ) {
    $order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;
    if ( empty( $order_id ) ) {
        return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Mã đơn hàng không hợp lệ.' ), 400 );
    }
    
    $order = learn_press_get_order( $order_id );
    if ( ! $order ) {
        return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Không tìm thấy đơn hàng.' ), 404 );
    }
    
    $status = $order->get_status();
    $redirect_url = '';
    
    if ( $status === 'completed' ) {
        $items = $order->get_items();
        if ( $items ) {
            $first_item = reset( $items );
            $course_id = isset( $first_item['course_id'] ) ? intval( $first_item['course_id'] ) : 0;
            if ( $course_id ) {
                $course = learn_press_get_course( $course_id );
                if ( $course ) {
                    $first_item_id = $course->get_first_item_id();
                    if ( $first_item_id ) {
                        $redirect_url = $course->get_item_link( $first_item_id );
                    } else {
                        $redirect_url = get_permalink( $course_id );
                    }
                }
            }
        }
    }
    
    return new WP_REST_Response( array( 
        'status' => 'success', 
        'order_status' => $status,
        'redirect_url' => $redirect_url
    ), 200 );
}






/**
 * Chặn truy cập trang chi tiết của custom post type 'giang-vien'
 * và chuyển hướng 301 về trang chủ.
 */
add_action('template_redirect', 'wp_block_giang_vien_detail');

function wp_block_giang_vien_detail() {
    if ( is_singular('giang-vien') ) {
        wp_safe_redirect( home_url(), 301 );
        exit;
    }
}

/**
 * Tự động xóa cache transient danh sách ID sự kiện khi thêm/sửa/xóa bài viết event
 */
add_action( 'save_post_event', 'iddi_clear_events_transient', 10, 1 );
add_action( 'deleted_post', 'iddi_clear_events_transient', 10, 1 );
function iddi_clear_events_transient( $post_id ) {
    if ( get_post_type( $post_id ) === 'event' ) {
        delete_transient( 'iddi_valid_event_ids' );
    }
}

/**
 * Tự động thêm thuộc tính defer cho các script được enqueued (trừ jquery) để tối ưu tốc độ tải trang
 */
add_filter('script_loader_tag', 'iddi_add_defer_attribute', 10, 2);
function iddi_add_defer_attribute($tag, $handle) {
    // Danh sách các script KHÔNG thêm defer để tránh lỗi xung đột (như jquery)
    $exclude_handles = array(
        'jquery',
        'jquery-core',
        'jquery-migrate',
        'admin-bar'
    );

    // Bỏ qua nếu ở trong trang admin hoặc script nằm trong danh sách loại trừ
    if (is_admin() || in_array($handle, $exclude_handles, true) || strpos($handle, 'jquery') !== false) {
        return $tag;
    }

    // Chỉ thêm defer nếu thẻ script chưa có defer hoặc async
    if (strpos($tag, ' defer') === false && strpos($tag, ' async') === false) {
        $tag = str_replace(' src=', ' defer src=', $tag);
    }

    return $tag;
}

/**
 * Gỡ bỏ (Dequeue) các file CSS không cần thiết của Ultimate Member ở trang chủ để tối ưu tốc độ
 */
add_action('wp_enqueue_scripts', 'iddi_dequeue_unnecessary_styles_on_home', 9999);
function iddi_dequeue_unnecessary_styles_on_home() {
    if (is_front_page() || is_home()) {
        $styles_to_dequeue = array(
            'um_style',
            'um_ui',
            'um_fonticons',
            'um_crop',
            'um_default',
            'um_datetime',
            'um_members',
            'um_profile',
            'um_account',
            'um_misc',
            'um_modal',
            'um_confirm',
            'um_common',
            'um_jquery_ui',
            'um_cropper',
            'um-misc',
            'um-profile',
            'um-common',
            'um-confirm'
        );

        foreach ($styles_to_dequeue as $style) {
            wp_dequeue_style($style);
            wp_deregister_style($style);
        }
    }
}

/**
 * Tối ưu hóa tải CSS không chặn hiển thị (Render-blocking CSS) bằng rel="preload" cho các CSS không quan trọng
 */
add_filter('style_loader_tag', 'iddi_async_non_critical_styles', 10, 4);
function iddi_async_non_critical_styles($tag, $handle, $href, $media) {
    // Không can thiệp trong trang Admin
    if (is_admin()) {
        return $tag;
    }

    // Danh sách các handle CSS quan trọng cần tải đồng bộ (Synchronous) để tránh vỡ giao diện (FOUC) lúc đầu
    $critical_handles = array(
        'style',
        'theme',
        'homepage',
        'components',
        'footer',
        'header',
        'main',
        'iddi'
    );

    foreach ($critical_handles as $critical) {
        if (strpos($handle, $critical) !== false) {
            return $tag; // Giữ nguyên tải đồng bộ
        }
    }

    // Chuyển đổi sang preload để tải bất đồng bộ (Asynchronous) cho các CSS không thuộc layout chính
    $tag = "<link rel='preload' id='" . esc_attr($handle) . "-css' href='" . esc_url($href) . "' as='style' onload=\"this.onload=null;this.rel='stylesheet'\" media='" . esc_attr($media) . "'>\n";
    $tag .= "<noscript><link rel='stylesheet' id='" . esc_attr($handle) . "-fallback-css' href='" . esc_url($href) . "' media='" . esc_attr($media) . "'></noscript>\n";

    return $tag;
}

/**
 * Tự động đăng ký khóa học miễn phí cho học viên khi họ truy cập vào bài học/quiz của khóa học đó.
 */
add_action( 'template_redirect', 'iddi_auto_enroll_free_course_on_lesson_access', 5 );
function iddi_auto_enroll_free_course_on_lesson_access() {
    if ( ! is_user_logged_in() ) {
        return;
    }

    $course = learn_press_get_course();
    if ( ! $course ) {
        return;
    }

    $item = LP_Global::course_item();
    if ( ! $item ) {
        return;
    }

    $course_id = $course->get_id();
    $user_id = get_current_user_id();
    $lp_user = learn_press_get_user( $user_id );

    if ( $lp_user && ! $lp_user->has_enrolled_course( $course_id ) ) {
        // Kiểm tra xem khóa học có phải khóa học miễn phí (Free) hay không
        $regular_price = get_post_meta($course_id, '_lp_regular_price', true);
        $reg_clean  = floatval($regular_price);

        if ( empty($reg_clean) || $reg_clean == 0 ) {
            // Tự động đăng ký học viên vào khóa học
            $lp_user->enroll( $course_id );
        }
    }
}


