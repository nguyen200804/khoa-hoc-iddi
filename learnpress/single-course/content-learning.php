<?php
/**
 * Template for displaying content of learning course.
 * This is the ACTUAL wrapper for the learning page in LearnPress 4.
 *
 * Path: learnpress/single-course/content-learning.php
 *
 * @author  IDDI Academy
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="popup-course" class="course-summary">
    <h1>
		Chào mừng đến với bài học (Content Learning)
	</h1>
    
    <div id="popup-header">
        <?php
        /**
         * @hooked learn_press_content_item_summary_header - 10
         */
        do_action( 'learn-press/single-item-summary-header' );
        ?>
    </div>

    <div id="popup-sidebar">
        <?php
        /**
         * @hooked learn_press_content_item_summary_sidebar - 10
         */
        do_action( 'learn-press/single-item-summary-sidebar' );
        ?>
    </div>

    <div id="popup-content">
        <div id="learn-press-content-item">
            <?php
            /**
             * @hooked learn_press_content_item_summary_content - 10
             */
            do_action( 'learn-press/single-item-summary-content' );
            ?>
        </div>
    </div>

    <div id="popup-footer">
        <?php
        /**
         * @hooked learn_press_content_item_summary_footer - 10
         */
        do_action( 'learn-press/single-item-summary-footer' );
        ?>
    </div>

</div>
