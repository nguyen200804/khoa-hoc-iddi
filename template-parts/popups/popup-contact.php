<?php
/**
 * Template part for Contact Popup
 */
$id = get_query_var('p_id', 'popup-contact-default'); // Lấy ID truyền từ hàm cms_display_popup_by_id
?>

<div id="<?php echo esc_attr($id); ?>" class="cms-popup-overlay">
    <div class="cms-popup-content">
        <span class="cms-popup-close close-popup" data-target="<?php echo esc_attr($id); ?>">&times;</span>
        
        <div class="cms-popup-header">
            <h3>Liên hệ với chúng tôi</h3>
        </div>
        
        <?php 
// echo 'Tiêu đề hiện tại: ' . get_the_title();
echo do_shortcode('[contact-form-7 id="ce09c60" title="Tư vấn đăng ký khóa học"]'); 
?>
    </div>
</div>