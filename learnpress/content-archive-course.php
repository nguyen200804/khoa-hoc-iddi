<?php
/**
 * Template for displaying content of archive course page.
 */
defined( 'ABSPATH' ) || exit;
?>
<div id="lp-content-area" class="lp-content-area my-custom-wrapper" style="background: #f0f0f0; padding: 30px; border-radius: 15px;">
    <div class="lp-main-content">
        
        <div style="background: white; padding: 20px; border: 2px solid #007cba;">
            <p>Nội dung này được chèn từ file content-archive-course.php</p>
            
            <div class="lp-list-courses-default">
                <?php 
                if ( LP_Context::is_course_archive() && have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        learn_press_get_template_part( 'content', 'course' );
                    }
                }
                ?>
            </div>
        </div>

    </div>
</div>