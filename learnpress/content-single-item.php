<?php
/**
 * Template for displaying content of single course item.
 * Path: learnpress/content-single-item.php
 */

defined( 'ABSPATH' ) || exit;

// Nạp Header và CSS của LearnPress
if ( ! wp_is_block_theme() ) {
	do_action( 'learn-press/template-header' );
}

do_action( 'learn-press/before-main-content' );
do_action( 'learn-press/before-single-item' );
?>

<div id="popup-course" class="course-summary">

	<div class="container">
		<?php
		// Lấy thông tin khóa học và bài học hiện tại
		$course = learn_press_get_course();
		$item   = LP_Global::course_item();
		?>

		<?php if ( $course && $item ) : ?>
		<div class="iddi-breadcrumb margin-bottom-l">
			<a href="<?php echo esc_url( home_url() ); ?>">Home</a>
			<span class="sep">›</span>
			<a href="<?php echo esc_url( learn_press_get_page_link( 'courses' ) ); ?>">Courses</a>
			<span class="sep">›</span>
			<a href="<?php echo esc_url( get_the_permalink( $course->get_id() ) ); ?>"><?php echo get_the_title( $course->get_id() ); ?></a>
			<span class="sep">›</span>
			<span class="current"><?php echo $item->get_title(); ?></span>
		</div>
		<?php endif; ?>

		<div class="course-wrapper">
			<article class="course-content">
                <?php
                // Kiểm tra an toàn trước khi xử lý
                $user = learn_press_get_current_user();
                $can_view = false;

                if ( $course && $item ) {
                    // Kiểm tra xem người dùng đã đăng ký khóa học chưa, hoặc bài học có phải là xem trước (Preview) không
                    $can_view = $user->has_enrolled_course( $course->get_id() ) || $item->is_preview();

                    // KIỂM TRA KHÓA TUẦN TỰ
                    $is_locked = iddi_is_item_locked( $item->get_id(), $course->get_id() );
                    if ( $is_locked ) {
                        $can_view = false;
                    }
                }

                // Lấy mã nhúng video từ trường ACF 'video_embed'
                $video_embed = ( $item ) ? get_field( 'video_embed', $item->get_id() ) : '';
                
                if ( $can_view && ! empty( $video_embed ) ) {
                    // Hiển thị mã nhúng ACF
                    // Chúng ta bọc trong các class cũ để giữ tính năng chặn tua (JS) và Responsive (CSS)
                    echo '<div class="iddi-lesson-video-custom iddi-lesson-video-embed">' . $video_embed . '</div>';
                }
                ?>

                <?php
                if ( $is_locked ) {
                    // Lấy ID bài trước đó để tạo nút Quay lại
                    $prev_item_id = 0;
                    $items_ids = $course->get_item_ids();
                    $current_idx = array_search($item->get_id(), $items_ids);
                    if ($current_idx > 0) {
                        $prev_item_id = $items_ids[$current_idx - 1];
                    }
                    ?>
                    <div class="iddi-locked-message">
                        <div class="locked-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <h2>Bài học này đang bị khóa</h2>
                        <p>Bạn cần hoàn thành bài học trước đó để có thể tiếp tục nội dung này.</p>
                        <div class="locked-action">
                            <?php if ($prev_item_id) : ?>
                                <a href="<?php echo esc_url($course->get_item_link($prev_item_id)); ?>" class="lp-button">Quay lại bài trước</a>
                            <?php else : ?>
                                <button class="lp-button" onclick="location.reload()">Kiểm tra lại trạng thái</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                } else {
                    LearnPress::instance()->template( 'course' )->popup_content();
                }
                ?>
            </article>
			
		
			<aside class="course-sidebar">
                <?php
                // Lấy dữ liệu tiến độ học tập
                $user = learn_press_get_current_user();
                $course_data = $user->get_course_data( $course->get_id() );
                $percentage = 0;
                $completed_items = 0;
                $total_items = 0;

                if ( $course_data ) {
                    $total_items = $course->count_items();
                    $completed_items = 0;
                    
                    // Duyệt qua tất cả bài học để đếm thực tế (Khớp với logic Sidebar)
                    $items_ids = $course->get_item_ids();
                    if ( $items_ids ) {
                        foreach ( $items_ids as $it_id ) {
                            $uItem = $course_data->get_item( $it_id );
                            if ( $uItem && $uItem->get_status() === \LearnPress\Models\UserItems\UserItemModel::STATUS_COMPLETED ) {
                                $it_type = get_post_type( $it_id );
                                if ( $it_type === 'lp_quiz' ) {
                                    if ( $uItem->get_graduation() === 'passed' ) {
                                        $completed_items++;
                                    }
                                } else {
                                    $completed_items++;
                                }
                            }
                        }
                    }
                    $percentage = ( $total_items > 0 ) ? ( $completed_items / $total_items ) * 100 : 0;
                } else {
                    $total_items = $course->count_items();
                }
                ?>

                <div class="iddi-course-progress margin-bottom-l">
                    <h3 class="progress-title">Course Curriculum</h3>
                    <div class="progress-info d-flex flex-jc-between">
                        <span class="percent"><?php echo number_format($percentage, 0); ?>% Complete</span>
                        <span class="count"><?php echo $completed_items; ?>/<?php echo $total_items; ?> Lessons</span>
                    </div>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                </div>

                <?php LearnPress::instance()->template( 'course' )->popup_sidebar(); ?>
            </aside>
		



		<!--     <div id="popup-footer">
<?php LearnPress::instance()->template( 'course' )->popup_footer(); ?>
</div> -->
		</div>
	</div>


</div>

<?php
do_action( 'learn-press/after-main-content' );
do_action( 'learn-press/after-single-course' );

// Nạp Footer và JS của LearnPress
if ( ! wp_is_block_theme() ) {
	do_action( 'learn-press/template-footer' );
}
