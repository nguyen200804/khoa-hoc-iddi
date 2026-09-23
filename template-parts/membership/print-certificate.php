<?php
/**
 * Template for printing a certificate.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! is_user_logged_in() ) {
    wp_die( 'Please log in to view this certificate.' );
}

$course_id = isset( $_GET['print_cert'] ) ? intval( $_GET['print_cert'] ) : 0;
$user_id = get_current_user_id();

// Verify that the user has completed this course
global $wpdb;
$table_name = $wpdb->prefix . 'learnpress_user_items';
// Sắp xếp theo user_item_id DESC để lấy bản ghi mới nhất của khóa học này
$item = $wpdb->get_row( $wpdb->prepare(
    "SELECT end_time, status FROM $table_name WHERE user_id = %d AND item_id = %d AND item_type = %s ORDER BY user_item_id DESC LIMIT 1",
    $user_id,
    $course_id,
    'lp_course'
) );

$is_completed = false;
$date_issued = '';

// Thử lấy thông qua LearnPress User API trước để chính xác nhất
$lp_user = learn_press_get_user( $user_id );
if ( $lp_user ) {
    $course_data = $lp_user->get_course_data( $course_id );
    if ( $course_data ) {
        if ( method_exists( $course_data, 'is_completed' ) && $course_data->is_completed() ) {
            $is_completed = true;
        } else {
            $course_results = $course_data->get_results( false );
            $progress = isset($course_results['result']) ? absint( $course_results['result'] ) : 0;
            if ( $progress >= 100 ) {
                $is_completed = true;
            }
        }
        
        if ( $is_completed ) {
            $end_time = method_exists( $course_data, 'get_end_time' ) ? $course_data->get_end_time() : null;
            if ( $end_time ) {
                $date_issued = is_a($end_time, 'LP_Datetime') ? $end_time->toSql() : strval($end_time);
            }
        }
    }
}

// Fallback sang truy vấn trực tiếp DB nếu chưa xác định được hoặc end_time trống
if ( $item ) {
    if ( in_array( $item->status, array('completed', 'passed', 'finished') ) ) {
        $is_completed = true;
    }
    if ( empty($date_issued) && !empty($item->end_time) && $item->end_time != '0000-00-00 00:00:00' ) {
        $date_issued = $item->end_time;
    }
}

// Nếu tất cả đều trống thì mới lấy thời gian hiện tại làm dự phòng cuối
if ( empty($date_issued) || $date_issued == '0000-00-00 00:00:00' ) {
    $date_issued = current_time('mysql');
}

if ( ! $is_completed ) {
    wp_die( 'You have not completed this course or the course does not exist.' );
}

$course_title = get_the_title( $course_id );
$user_info = get_userdata( $user_id );
$display_name = $user_info->display_name;
if ( ! empty( $user_info->first_name ) && ! empty( $user_info->last_name ) ) {
    $display_name = $user_info->last_name . ' ' . $user_info->first_name;
}

$date_issued_formatted = date_i18n( 'F d, Y', strtotime( $date_issued ) );

$auto_print = isset( $_GET['download'] ) && $_GET['download'] === 'true';

// Get Custom Certificate Meta Data
$cert_bg = get_post_meta($course_id, '_iddi_cert_bg', true);
$cert_logo = get_post_meta($course_id, '_iddi_cert_logo', true);
$cert_logo_width = get_post_meta($course_id, '_iddi_cert_logo_width', true);
$cert_title = get_post_meta($course_id, '_iddi_cert_title', true);
$cert_instructor = get_post_meta($course_id, '_iddi_cert_instructor', true);
$cert_instructor_title = get_post_meta($course_id, '_iddi_cert_instructor_title', true);
$cert_signature = get_post_meta($course_id, '_iddi_cert_signature', true);

if (empty($cert_title)) $cert_title = 'Certificate of Completion';
if (empty($cert_instructor)) $cert_instructor = 'Instructor';
if (empty($cert_instructor_title)) $cert_instructor_title = 'Course Director';

// Fallback to Global LearnPress Settings if course-specific logo is not set
if (empty($cert_logo)) {
    $global_logo_id = get_option('learn_press_iddi_cert_logo');
    if ($global_logo_id) {
        $cert_logo = wp_get_attachment_image_url($global_logo_id, 'full');
    }
}

// Fallback to Global Logo Width if course-specific width is not set
if (empty($cert_logo_width)) {
    $global_logo_width = get_option('learn_press_iddi_cert_logo_width');
    $cert_logo_width = !empty($global_logo_width) ? $global_logo_width : '150px';
}

if (is_numeric($cert_logo_width)) {
    $cert_logo_width .= 'px';
}

// Certificate styling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo esc_attr( $course_title ); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background-color: #525659; /* PDF viewer background look */
            font-family: 'Roboto', sans-serif;
        }

        body {
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
        }

        /* A4 Landscape Dimensions in PX for Screen zoom scalability */
        .certificate-wrapper {
            width: 1120px;
            height: 792px;
            background-color: #FFFFFF;
            <?php if (empty($cert_bg)) : ?>
            background-image: url('data:image/svg+xml,%3Csvg width="100%25" height="100%25" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="p" width="100" height="100" patternUnits="userSpaceOnUse"%3E%3Cpath d="M0 100 V 50 Q 25 25 50 50 t 50 50 V 0 H 0" fill="none" stroke="%23f1f5f9" stroke-width="1" /%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23p)" /%3E%3C/svg%3E');
            border: 8px solid #1A2B4E;
            outline: 38px solid #FFFFFF;
            outline-offset: -38px;
            <?php else: ?>
            background-image: url('<?php echo esc_url($cert_bg); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: none;
            outline: none;
            <?php endif; ?>
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            position: relative;
            padding: 75px;
            box-sizing: border-box;
            text-align: center;
        }

        .certificate-border-inner {
            <?php if (empty($cert_bg)) : ?>
            border: 1px solid #1A2B4E;
            <?php else: ?>
            border: none;
            <?php endif; ?>
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            margin: 38px;
            width: calc(100% - 76px);
            height: calc(100% - 76px);
            pointer-events: none;
        }

        .cert-header {
            margin-bottom: 57px;
        }

        .cert-logo {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1A2B4E;
}

        .cert-title {
            font-family: 'Playfair Display', serif;
            font-size: 55px;
            text-transform: uppercase;
            color: #ED6C32;
            letter-spacing: 4px;
            margin: 0;
        }

        .cert-subtitle {
            font-size: 16px;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .cert-body {
            margin-bottom: 75px;
        }

        .cert-text-present {
            font-size: 18px;
            color: #334155;
            margin-bottom: 15px;
            font-style: italic;
            font-family: 'Playfair Display', serif;
        }

        .cert-name {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #1A2B4E;
            margin: 0;
            border-bottom: 2px solid #CBD5E1;
            display: inline-block;
            padding-bottom: 10px;
            min-width: 60%;
        }

        .cert-text-reason {
            font-size: 18px;
            color: #334155;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .cert-course {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #1A2B4E;
            margin: 0;
        }

        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            position: absolute;
            bottom: 75px;
            left: 113px;
            right: 113px;
        }

        .cert-signature, .cert-date {
            text-align: center;
            width: 200px;
        }

        .cert-line {
            border-bottom: 1px solid #1A2B4E;
            margin-bottom: 10px;
            height: 40px;
        }

        .cert-label {
            font-size: 14px;
            text-transform: uppercase;
            color: #64748B;
            letter-spacing: 1px;
        }

        .cert-badge-img {
            width: 80px;
            height: 80px;
            background-color: #ED6C32;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 30px;
            box-shadow: 0 4px 10px rgba(237, 108, 50, 0.4);
            border: 4px solid #FFF3EE;
            align-self: center; /* Canh giữa theo trục dọc của footer để không đè lên text */
        }

        /* Print Settings */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body, html {
                background-color: #FFFFFF;
                padding: 0;
                margin: 0;
            }
            .certificate-wrapper {
                box-shadow: none;
                border-color: #1A2B4E !important; /* Force print border */
                width: 297mm;
                height: 210mm;
                padding: 20mm;
                border: 2mm solid #1A2B4E;
                outline: 10mm solid #FFFFFF;
                outline-offset: -10mm;
                margin: 0;
            }
            .certificate-border-inner {
                margin: 10mm;
                width: calc(100% - 20mm);
                height: calc(100% - 20mm);
            }
            .cert-header {
                margin-bottom: 15mm;
            }
            .cert-body {
                margin-bottom: 20mm;
            }
            .cert-footer {
                bottom: 20mm;
                left: 30mm;
                right: 30mm;
            }
            /* Hide print dialog buttons if any */
            .no-print {
                display: none !important;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #1A2B4E;
            color: #FFFFFF;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: background 0.3s;
            z-index: 1000;
        }

        .print-btn:hover {
            background-color: #0F172A;
        }
    </style>
</head>
<body>

    <button class="print-btn no-print" onclick="window.print()">🖨️ Print Certificate</button>

    <div class="certificate-wrapper">
            <div class="certificate-border-inner"></div>
            
            <div class="cert-header">
                <div class="cert-logo">
                    <?php if ( ! empty( $cert_logo ) ) : ?>
                        <img src="<?php echo esc_url( $cert_logo ); ?>" style="max-width: <?php echo esc_attr( $cert_logo_width ); ?>; height: auto; display: inline-block; vertical-align: middle;" alt="Logo">
                    <?php else : ?>
                        IDDI ACADEMY
                    <?php endif; ?>
                </div>
                <h1 class="cert-title"><?php echo esc_html( $cert_title ); ?></h1>
                <div class="cert-subtitle">This certificate is proudly presented to</div>
            </div>

            <div class="cert-body">
                <h2 class="cert-name"><?php echo esc_html( $display_name ); ?></h2>
                <div class="cert-text-reason">for successfully completing the course</div>
                <h3 class="cert-course"><?php echo esc_html( $course_title ); ?></h3>
            </div>

            <div class="cert-footer">
                <div class="cert-date">
                    <div class="cert-line" style="display: flex; align-items: flex-end; justify-content: center; font-family: 'Playfair Display', serif; font-size: 18px; color: #1A2B4E; padding-bottom: 5px;">
                        <?php echo esc_html( $date_issued_formatted ); ?>
                    </div>
                    <div class="cert-label">Date Issued</div>
                </div>

                <div class="cert-badge-img">★</div>

                <div class="cert-signature">
                    <div class="cert-line" style="display: flex; align-items: flex-end; justify-content: center; font-family: 'Playfair Display', serif; font-style: italic; font-size: 24px; color: #1A2B4E;">
                        <?php if (!empty($cert_signature)) : ?>
                            <img src="<?php echo esc_url($cert_signature); ?>" style="max-height: 60px; margin-bottom: -10px; mix-blend-mode: multiply;" alt="Signature">
                        <?php else: ?>
                            <?php echo esc_html( $cert_instructor ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="cert-label"><?php echo esc_html( $cert_instructor_title ); ?></div>
                </div>
            </div>
        </div>

    <?php if ( $auto_print ) : ?>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
    <?php endif; ?>

</body>
</html>
