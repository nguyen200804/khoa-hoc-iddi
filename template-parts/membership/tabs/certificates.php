<div class="iddi-membership-tab iddi-membership-tab--certificates">
    
    <div class="iddi-membership-tab__header">
        <h1 class="iddi-membership-tab__title">My Certificates</h1>
        <p class="iddi-membership-tab__subtitle">Manage and download your officially recognized academic achievements. These credentials reflect your dedication and mastery of clinical expertise.</p>
    </div>

    <!-- Certificates List -->
    <div class="iddi-membership-cert-list">
        <?php
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            global $wpdb;

            $table_name = $wpdb->prefix . 'learnpress_user_items';
            // Lấy tất cả khóa học đã đăng ký
            $enrolled_items = $wpdb->get_results($wpdb->prepare(
                "SELECT item_id, end_time, status FROM $table_name WHERE user_id = %d AND item_type = %s ORDER BY item_id DESC",
                $user_id,
                'lp_course'
            ));

            $completed_items = array();
            $lp_user = learn_press_get_user( $user_id );

            if ( ! empty($enrolled_items) ) {
                foreach ($enrolled_items as $item) {
                    $is_completed = false;
                    
                    // 1. Kiểm tra trạng thái Database
                    if ( in_array( $item->status, array('completed', 'passed', 'finished') ) ) {
                        $is_completed = true;
                    } 
                    // 2. Nếu trạng thái chưa hoàn thành, kiểm tra xem tiến độ (Progress) có đạt 100% không
                    else if ( $lp_user ) {
                        $course_data = $lp_user->get_course_data( $item->item_id );
                        if ( $course_data ) {
                            $course_results = $course_data->get_results( false );
                            $progress = isset($course_results['result']) ? absint( $course_results['result'] ) : 0;
                            if ( $progress >= 100 ) {
                                $is_completed = true;
                                if ( empty($item->end_time) || $item->end_time == '0000-00-00 00:00:00' ) {
                                    $item->end_time = current_time('mysql'); // Lấy tạm thời gian hiện tại nếu chưa có ngày kết thúc
                                }
                            }
                        }
                    }

                    if ( $is_completed ) {
                        $completed_items[] = $item;
                    }
                }
            }

            if ( ! empty($completed_items) ) {
                foreach ( $completed_items as $index => $item ) {
                    $course_id = $item->item_id;
                    $end_time = $item->end_time;
                    $course_title = get_the_title($course_id);
                    $course_excerpt = wp_trim_words(get_the_excerpt($course_id), 20, '...');
                    if ( empty($course_excerpt) ) {
                        $post_obj = get_post($course_id);
                        $course_excerpt = wp_trim_words($post_obj->post_content, 20, '...');
                    }
                    
                    $date_issued = date_i18n('M d, Y', strtotime($end_time));
                    
                    // Simple logic to alternate thumbnail colors/styles for mockup purposes
                    $colors = ['a3c1ad', 'd5cec4', 'c5b8d1', 'f3d5b5'];
                    $color_index = $index % count($colors);
                    $bg_color = $colors[$color_index];
                    $text_color = ($bg_color == 'd5cec4') ? '333333' : 'ffffff';
                    
                    $thumb_url = "https://placehold.co/400x300/{$bg_color}/{$text_color}?text=CERTIFICATE";
                    
                    // Lấy Category đầu tiên làm Badge
                    $terms = get_the_terms($course_id, 'course_category');
                    $badge = 'COMPLETED COURSE';
                    $badge_class = '';
                    if ( $terms && !is_wp_error($terms) ) {
                        $badge = $terms[0]->name;
                        // Alternate badge color for visual variety
                        if ($index % 2 != 0) {
                            $badge_class = 'iddi-membership-cert-card__badge--red';
                        }
                    }

                    // Print URL
                    $print_url = add_query_arg(array(
                        'print_cert' => $course_id,
                    ), home_url('/'));

                    ?>
                    <div class="iddi-membership-cert-card">
                        <div class="iddi-membership-cert-card__thumb">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="Certificate for <?php echo esc_attr($course_title); ?>">
                        </div>
                        <div class="iddi-membership-cert-card__content">
                            <div class="iddi-membership-cert-card__header">
                                <span class="iddi-membership-cert-card__badge <?php echo $badge_class; ?>"><?php echo esc_html($badge); ?></span>
                                <span class="iddi-membership-cert-card__date">Issued: <?php echo $date_issued; ?></span>
                            </div>
                            <h3 class="iddi-membership-cert-card__title"><?php echo esc_html($course_title); ?></h3>
                            <div class="iddi-membership-cert-card__excerpt">
                                <?php echo esc_html($course_excerpt); ?>
                            </div>
                            <div class="iddi-membership-cert-card__actions">
                                <a href="<?php echo esc_url($print_url); ?>" target="_blank" class="iddi-membership-cert-card__btn iddi-membership-cert-card__btn--view">View</a>
                                <a href="<?php echo esc_url($print_url); ?>&download=true" class="iddi-membership-cert-card__btn iddi-membership-cert-card__btn--download">
                                    Download / Print 
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="iddi-membership-empty">You have not earned any certificates yet. Complete a course to earn your first certificate!</div>';
            }
        } else {
            echo '<div class="iddi-membership-empty">Please log in to view your certificates.</div>';
        }
        ?>
    </div>

</div>
