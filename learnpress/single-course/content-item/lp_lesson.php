<?php
/**
 * Template for displaying lesson content.
 * This file overrides the original LearnPress template.
 * Path: learnpress/single-course/content-item/lp_lesson.php
 *
 * @author  IDDI Academy
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

$item = LP_Global::course_item();

if ( ! $item ) {
	return;
}
?>

<div class="iddi-lesson-item content-item-summary content-item-lp_lesson">
    <!-- Bạn có thể tùy chỉnh HTML ở đây -->
    
    <header class="iddi-lesson-header">
        <h1 class="iddi-lesson-title"><?php echo $item->get_title(); ?></h1>
    </header>

    <div class="iddi-lesson-content">
        <?php 
        // Hiển thị nội dung bài học
        echo $item->get_content(); 
        ?>
    </div>

    <?php
    /**
     * Nếu bạn muốn giữ lại các hook mặc định của LearnPress, 
     * hãy giữ lại dòng do_action dưới đây. 
     * Nếu muốn ghi đè hoàn toàn, bạn có thể xóa nó.
     */
    // do_action( 'learn-press/content-item-summary' );
    ?>
</div>
