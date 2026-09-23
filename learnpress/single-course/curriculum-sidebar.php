<?php
/**
 * Template for displaying course curriculum in sidebar (IDDI Style)
 * Path: iddi_academy/learnpress/single-course/curriculum-sidebar.php
 */

defined( 'ABSPATH' ) || exit;

use LearnPress\Models\CourseModel;
use LearnPress\Models\UserModel;
use LearnPress\Models\UserItems\UserCourseModel;
use LearnPress\Models\UserItems\UserItemModel;

$user_id = get_current_user_id();
$userModel = $user_id ? UserModel::find( $user_id, true ) : null;

global $lp_course_item, $post;
$course_id = $lp_course_item ? $lp_course_item->get_course_id() : ($post->ID ?? 0);

$courseModel = CourseModel::find( $course_id, true );
if ( ! $courseModel ) return;

$section_items = $courseModel->get_section_items();
if ( empty( $section_items ) ) return;

$userCourse = null;
if ( $userModel ) {
    $userCourse = UserCourseModel::find( $userModel->get_id(), $course_id, true );
}

$current_item_id = $lp_course_item ? $lp_course_item->get_id() : ($post->ID ?? 0);

/**
 * Hàm hỗ trợ định dạng thời lượng từ meta _lp_duration
 */
if (!function_exists('iddi_format_duration')) {
    function iddi_format_duration($duration_raw) {
        if (empty($duration_raw)) return '00:00';
        
        // Nếu là số thuần túy (giây)
        if (is_numeric($duration_raw)) {
            $seconds = intval($duration_raw);
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            $s = $seconds % 60;
            return ($h > 0 ? sprintf("%02d:", $h) : "") . sprintf("%02d:%02d", $m, $s);
        }
        
        // LearnPress thường lưu kiểu "10 minute", "2 hour"
        return str_replace(['minutes', 'minute', 'hours', 'hour', 'seconds', 'second'], ['phút', 'phút', 'giờ', 'giờ', 'giây', 'giây'], $duration_raw);
    }
}
?>

<div class="iddi-curriculum-sidebar">
    <div class="iddi-curriculum-list">
        <ul class="iddi-sections">
            <?php foreach ( $section_items as $index => $section ) : 
                $items = $section->items ?? [];
                $has_current = false;
                $completed_in_section = 0;
                $section_total_duration = ''; // Phần này LearnPress không tính sẵn tổng theo section dễ dàng, tạm để trống hoặc lấy theo bài học đầu
                
                foreach($items as $it) { 
                    $it_id = $it->item_id ?? $it->id ?? 0;
                    if($it_id == $current_item_id) $has_current = true;
                    
                    if ($userCourse) {
                        $it_type = $it->item_type ?? $it->type ?? '';
                        $uItem = $userCourse->get_item_attend( $it_id, $it_type );
                        if ($uItem && $uItem->get_status() === UserItemModel::STATUS_COMPLETED) {
                            $completed_in_section++;
                        }
                    }
                }
            ?>
                <li class="iddi-section <?php echo ($has_current || ($index === 0 && !$current_item_id)) ? '' : 'closed'; ?>">
                    <div class="iddi-section-header" onclick="this.parentElement.classList.toggle('closed')">
                        <div class="iddi-section-info">
                            <h3 class="iddi-section-title"><?php echo ($index + 1); ?>. <?php echo esc_html( $section->section_name ); ?></h3>
                            <span class="iddi-section-meta"><?php echo $completed_in_section; ?>/<?php echo count($items); ?> Bài học</span>
                        </div>
                        <span class="iddi-toggle-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
                        </span>
                    </div>
                    
                    <ul class="iddi-section-content">
                        <?php foreach ( $items as $s_index => $item ) : 
                            $item_id = $item->item_id ?? $item->id ?? 0;
                            $item_type = $item->item_type ?? $item->type ?? '';
                            $is_current = ($item_id == $current_item_id) ? 'active' : '';
                            $item_link = $courseModel->get_item_link( $item_id );
                            
                            // Lấy thời lượng thực tế
                            $duration_raw = get_post_meta($item_id, '_lp_duration', true);
                            $duration_display = iddi_format_duration($duration_raw);

                            $is_completed = false;
                            if ( $userCourse ) {
                                $userItem = $userCourse->get_item_attend( $item_id, $item_type );
                                if ( $userItem && $userItem->get_status() === UserItemModel::STATUS_COMPLETED ) {
                                    // Nếu là Quiz, phải đạt (passed) mới hiện tích xanh
                                    if ( $item_type === 'lp_quiz' ) {
                                        if ( $userItem->get_graduation() === 'passed' ) {
                                            $is_completed = true;
                                        }
                                    } else {
                                        $is_completed = true;
                                    }
                                }
                            }
                            $is_locked = iddi_is_item_locked( $item_id, $course_id );
                        ?>
                            <li class="iddi-item <?php echo $is_current; ?> <?php echo $is_completed ? 'completed' : ''; ?> <?php echo $is_locked ? 'is-locked' : ''; ?>">
                                <a href="<?php echo $is_locked ? 'javascript:void(0)' : esc_url( $item_link ); ?>" class="iddi-item-link">
                                    <div class="iddi-item-left">
                                        <div class="iddi-item-title"><?php echo ($index + 1); ?>.<?php echo ($s_index + 1); ?> <?php echo esc_html( $item->title ); ?></div>
                                        <div class="iddi-item-meta">
                                            <?php if ($item_type === LP_QUIZ_CPT) : ?>
                                                <svg class="iddi-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            <?php else : ?>
                                                <svg class="iddi-icon" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg>
                                            <?php endif; ?>
                                            <span><?php echo $duration_display; ?></span>
                                        </div>
                                    </div>
                                    
                                    <?php if ($is_completed) : ?>
                                        <div class="iddi-item-status">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#22C55E"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </div>
                                    <?php elseif ($is_locked) : ?>
                                        <div class="iddi-item-status is-lock-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
