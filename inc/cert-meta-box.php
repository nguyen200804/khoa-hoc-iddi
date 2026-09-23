<?php
/**
 * Certificate Customization Meta Box for LearnPress Courses
 */

// Register Meta Box
function iddi_add_cert_meta_box() {
    add_meta_box(
        'iddi_cert_meta_box', // ID
        'Tùy chỉnh Chứng chỉ', // Title
        'iddi_cert_meta_box_html', // Callback
        'lp_course', // Post type
        'normal', // Context
        'default' // Priority
    );
}
add_action('add_meta_boxes', 'iddi_add_cert_meta_box');

// Enqueue Media Uploader Scripts
function iddi_cert_admin_scripts($hook) {
    global $post_type;
    if ('post.php' == $hook || 'post-new.php' == $hook) {
        if ('lp_course' == $post_type) {
            wp_enqueue_media();
            
            // Inline script for handling media uploader
            $custom_js = "
            jQuery(document).ready(function($){
                $('.iddi-upload-image-button').click(function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var inputField = button.siblings('.iddi-image-url-input');
                    var preview = button.siblings('.iddi-image-preview');
                    
                    var mediaUploader = wp.media.frames.file_frame = wp.media({
                        title: 'Chọn hình ảnh',
                        button: { text: 'Sử dụng ảnh này' },
                        multiple: false
                    });
                    
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        inputField.val(attachment.url);
                        if(preview.length) {
                            preview.attr('src', attachment.url).show();
                        }
                    });
                    mediaUploader.open();
                });
                
                $('.iddi-remove-image-button').click(function(e) {
                    e.preventDefault();
                    var button = $(this);
                    button.siblings('.iddi-image-url-input').val('');
                    button.siblings('.iddi-image-preview').attr('src', '').hide();
                });
            });
            ";
            wp_add_inline_script('jquery', $custom_js);
        }
    }
}
add_action('admin_enqueue_scripts', 'iddi_cert_admin_scripts');

// Render Meta Box HTML
function iddi_cert_meta_box_html($post) {
    wp_nonce_field('iddi_cert_meta_box_nonce_action', 'iddi_cert_meta_box_nonce');
    
    $cert_bg = get_post_meta($post->ID, '_iddi_cert_bg', true);
    $cert_logo = get_post_meta($post->ID, '_iddi_cert_logo', true);
    $cert_logo_width = get_post_meta($post->ID, '_iddi_cert_logo_width', true);
    $cert_title = get_post_meta($post->ID, '_iddi_cert_title', true);
    $cert_instructor = get_post_meta($post->ID, '_iddi_cert_instructor', true);
    $cert_instructor_title = get_post_meta($post->ID, '_iddi_cert_instructor_title', true);
    $cert_signature = get_post_meta($post->ID, '_iddi_cert_signature', true);

    if (empty($cert_title)) $cert_title = 'Certificate of Completion';
    if (empty($cert_instructor)) $cert_instructor = 'Instructor';
    if (empty($cert_instructor_title)) $cert_instructor_title = 'Course Director';
    if (empty($cert_logo_width)) $cert_logo_width = '150px';

    echo '<style>
        .iddi-cert-field { margin-bottom: 20px; }
        .iddi-cert-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .iddi-cert-field input[type="text"] { width: 100%; max-width: 500px; }
        .iddi-cert-field img.iddi-image-preview { display: block; max-width: 200px; margin-top: 10px; border: 1px solid #ccc; }
    </style>';

    // Notice & Preview Link
    echo '<div style="margin-bottom: 20px; padding: 12px; background: #fff3ee; border-left: 4px solid #ed6c32;">';
    echo '<strong style="display:block; margin-bottom:8px;">💡 Hướng dẫn:</strong>';
    echo 'Bạn có thể chỉnh sửa các thông tin bên dưới để tùy biến lại chứng chỉ cho khóa học này. Nhấn nút <strong>Cập nhật</strong> khóa học để lưu lại, sau đó bấm nút xem trước bên dưới.<br><br>';
    echo '<a href="' . site_url('?print_cert=' . $post->ID) . '" target="_blank" class="button button-primary">👀 Xem trước Chứng chỉ hiện tại</a>';
    echo '</div>';

    // BG Image
    echo '<div class="iddi-cert-field">';
    echo '<label>Hình nền chứng chỉ (Khuyên dùng khổ A4 Ngang - Landscape)</label>';
    echo '<input type="text" name="iddi_cert_bg" class="iddi-image-url-input" value="' . esc_attr($cert_bg) . '" style="width: 60%; margin-right: 10px;" placeholder="https://...">';
    echo '<button class="button iddi-upload-image-button">Chọn ảnh</button> ';
    echo '<button class="button iddi-remove-image-button">Xóa ảnh</button>';
    echo '<p class="description">Để trống nếu bạn muốn sử dụng giao diện khung viền mặc định của hệ thống.</p>';
    echo '<img class="iddi-image-preview" src="' . esc_attr($cert_bg) . '" style="' . (empty($cert_bg) ? 'display:none;' : '') . '">';
    echo '</div>';

    // Logo Image
    echo '<div class="iddi-cert-field">';
    echo '<label>Logo chứng chỉ riêng cho khóa này (Khuyên dùng PNG trong suốt)</label>';
    echo '<input type="text" name="iddi_cert_logo" class="iddi-image-url-input" value="' . esc_attr($cert_logo) . '" style="width: 60%; margin-right: 10px;" placeholder="https://...">';
    echo '<button class="button iddi-upload-image-button">Chọn ảnh</button> ';
    echo '<button class="button iddi-remove-image-button">Xóa ảnh</button>';
    echo '<p class="description">Để trống nếu bạn muốn sử dụng Logo chung đã cấu hình tại <strong>LearnPress > Cấu hình > Tổng quan</strong> (hoặc chữ "IDDI ACADEMY" mặc định nếu cấu hình chung trống).</p>';
    echo '<img class="iddi-image-preview" src="' . esc_attr($cert_logo) . '" style="' . (empty($cert_logo) ? 'display:none;' : '') . ' max-height: 100px;">';
    echo '</div>';

    // Logo Width
    echo '<div class="iddi-cert-field">';
    echo '<label>Chiều rộng Logo riêng cho khóa này (ví dụ: 150px, 120px, 20%)</label>';
    echo '<input type="text" name="iddi_cert_logo_width" value="' . esc_attr($cert_logo_width) . '" placeholder="150px">';
    echo '<p class="description">Điều chỉnh chiều rộng logo riêng cho khóa học này. Để trống nếu bạn muốn sử dụng Chiều rộng Logo chung trong <strong>LearnPress > Cấu hình > Tổng quan</strong>.</p>';
    echo '</div>';
    
    // Title
    echo '<div class="iddi-cert-field">';
    echo '<label>Tiêu đề chứng chỉ</label>';
    echo '<input type="text" name="iddi_cert_title" value="' . esc_attr($cert_title) . '">';
    echo '<p class="description">Ví dụ: Certificate of Completion, Chứng nhận hoàn thành khóa học...</p>';
    echo '</div>';
    
    // Instructor Name
    echo '<div class="iddi-cert-field">';
    echo '<label>Tên Giảng viên (Người ký)</label>';
    echo '<input type="text" name="iddi_cert_instructor" value="' . esc_attr($cert_instructor) . '">';
    echo '</div>';
    
    // Instructor Title
    echo '<div class="iddi-cert-field">';
    echo '<label>Chức danh Giảng viên</label>';
    echo '<input type="text" name="iddi_cert_instructor_title" value="' . esc_attr($cert_instructor_title) . '">';
    echo '<p class="description">Ví dụ: Course Director, Giám đốc học viện, Giảng viên chính...</p>';
    echo '</div>';

    // Signature Image
    echo '<div class="iddi-cert-field">';
    echo '<label>Hình chữ ký (Khuyên dùng file PNG không nền)</label>';
    echo '<input type="text" name="iddi_cert_signature" class="iddi-image-url-input" value="' . esc_attr($cert_signature) . '" style="width: 60%; margin-right: 10px;" placeholder="https://...">';
    echo '<button class="button iddi-upload-image-button">Chọn ảnh</button> ';
    echo '<button class="button iddi-remove-image-button">Xóa ảnh</button>';
    echo '<img class="iddi-image-preview" src="' . esc_attr($cert_signature) . '" style="' . (empty($cert_signature) ? 'display:none;' : '') . ' max-height: 100px;">';
    echo '</div>';
}

// Save Meta Box Data
function iddi_save_cert_meta_box_data($post_id) {
    if (!isset($_POST['iddi_cert_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['iddi_cert_meta_box_nonce'], 'iddi_cert_meta_box_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['iddi_cert_bg'])) {
        update_post_meta($post_id, '_iddi_cert_bg', sanitize_text_field($_POST['iddi_cert_bg']));
    }
    if (isset($_POST['iddi_cert_logo'])) {
        update_post_meta($post_id, '_iddi_cert_logo', sanitize_text_field($_POST['iddi_cert_logo']));
    }
    if (isset($_POST['iddi_cert_logo_width'])) {
        update_post_meta($post_id, '_iddi_cert_logo_width', sanitize_text_field($_POST['iddi_cert_logo_width']));
    }
    if (isset($_POST['iddi_cert_title'])) {
        update_post_meta($post_id, '_iddi_cert_title', sanitize_text_field($_POST['iddi_cert_title']));
    }
    if (isset($_POST['iddi_cert_instructor'])) {
        update_post_meta($post_id, '_iddi_cert_instructor', sanitize_text_field($_POST['iddi_cert_instructor']));
    }
    if (isset($_POST['iddi_cert_instructor_title'])) {
        update_post_meta($post_id, '_iddi_cert_instructor_title', sanitize_text_field($_POST['iddi_cert_instructor_title']));
    }
    if (isset($_POST['iddi_cert_signature'])) {
        update_post_meta($post_id, '_iddi_cert_signature', sanitize_text_field($_POST['iddi_cert_signature']));
    }
}
add_action('save_post_lp_course', 'iddi_save_cert_meta_box_data');

/**
 * Add Global Certificate Logo settings to LearnPress General Settings Tab
 */
function iddi_cert_add_general_settings_fields( $fields ) {
    $new_fields = array();
    
    foreach ( $fields as $field ) {
        // If we hit the 'Other' title, let's insert a title for Certificate settings first!
        if ( isset( $field['title'] ) && $field['title'] === esc_html__( 'Other', 'learnpress' ) ) {
            $new_fields[] = array(
                'title' => esc_html__( 'Certificate Logo Settings', 'learnpress' ),
                'type'  => 'title',
            );
            $new_fields[] = array(
                'title'   => esc_html__( 'Global Certificate Logo', 'learnpress' ),
                'id'      => 'iddi_cert_logo',
                'default' => '',
                'type'    => 'image',
                'desc'    => esc_html__( 'Chọn logo chung áp dụng cho tất cả chứng chỉ (Khuyên dùng PNG trong suốt).', 'learnpress' ),
            );
            $new_fields[] = array(
                'title'   => esc_html__( 'Global Certificate Logo Width', 'learnpress' ),
                'id'      => 'iddi_cert_logo_width',
                'default' => '150px',
                'type'    => 'text',
                'desc'    => esc_html__( 'Chiều rộng mặc định của logo chung trên chứng chỉ (ví dụ: 150px, 120px, 20%). Nếu chỉ nhập số, tự động thêm px.', 'learnpress' ),
            );
            $new_fields[] = array(
                'type' => 'sectionend',
            );
        }
        $new_fields[] = $field;
    }
    
    return $new_fields;
}
add_filter( 'learn-press/general-settings-fields', 'iddi_cert_add_general_settings_fields', 10, 1 );
