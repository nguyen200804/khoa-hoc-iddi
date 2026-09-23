<div class="iddi-membership-tab iddi-membership-tab--my-courses">
    
    <div class="iddi-membership-tab__header">
        <h1 class="iddi-membership-tab__title">My Courses</h1>
        <p class="iddi-membership-tab__subtitle">Manage your enrolled courses and track your learning progress.</p>
    </div>

    <!-- Course Search & Filters -->
    <div class="iddi-membership-filters d-flex gap-m margin-bottom-l">
        <div class="iddi-membership-search">
            <form action="" method="GET" class="iddi-membership-search__form">
                <input type="hidden" name="tab" value="my-courses">
                <input type="text" name="course_search" placeholder="Tìm kiếm khóa học" class="iddi-membership-search__input" value="<?php echo isset($_GET['course_search']) ? esc_attr($_GET['course_search']) : ''; ?>">
                <button type="submit" class="iddi-membership-search__btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </form>
        </div>
        <div class="iddi-membership-filter-btns d-flex">
            <?php $current_filter = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : ''; ?>
            <a href="?tab=my-courses" class="iddi-membership-btn <?php echo empty($current_filter) ? 'iddi-membership-btn--active' : ''; ?>">Tất cả khóa học</a>
            <a href="?tab=my-courses&filter=in-progress" class="iddi-membership-btn <?php echo $current_filter === 'in-progress' ? 'iddi-membership-btn--active' : ''; ?>">Đang tiến hành</a>
            <a href="?tab=my-courses&filter=completed" class="iddi-membership-btn <?php echo $current_filter === 'completed' ? 'iddi-membership-btn--active' : ''; ?>">Hoàn thành</a>
        </div>
    </div>

    <!-- Course Grid -->
    <div class="iddi-membership-course-grid">
        <?php
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            global $wpdb;

            // Fetch enrolled course IDs directly to avoid conflicts
            $table_name = $wpdb->prefix . 'learnpress_user_items';
            $enrolled_ids = $wpdb->get_col($wpdb->prepare(
                "SELECT item_id FROM $table_name WHERE user_id = %d AND item_type = %s",
                $user_id,
                'lp_course'
            ));

            if ( !empty($enrolled_ids) ) {
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                if ( isset($_GET['paged']) ) {
                    $paged = absint($_GET['paged']);
                }

                $args = array(
                    'post_type'      => 'lp_course',
                    'post__in'       => $enrolled_ids,
                    'posts_per_page' => 4,
                    'paged'          => $paged,
                    'post_status'    => 'publish',
                );

                // Handle search
                if ( !empty($_GET['course_search']) ) {
                    $args['s'] = sanitize_text_field($_GET['course_search']);
                }

                $course_query = new WP_Query( $args );

                if ( $course_query->have_posts() ) {
                    $lp_user = learn_press_get_user( $user_id );
                    
                    while ( $course_query->have_posts() ) {
                        $course_query->the_post();
                        $course_id = get_the_ID();
                        
                        // Get progress and completion status
                        $progress = 0;
                        $is_completed = false;
                        if ( $lp_user ) {
                            $course_data = $lp_user->get_course_data( $course_id );
                            if ( $course_data ) {
                                if ( method_exists( $course_data, 'is_completed' ) && $course_data->is_completed() ) {
                                    $is_completed = true;
                                }
                                $course_results = $course_data->get_results( false );
                                $progress = isset($course_results['result']) ? absint( $course_results['result'] ) : 0;
                                if ( $progress >= 100 ) {
                                    $is_completed = true;
                                }
                            }
                        }

                        // DB Fallback for completion check
                        if ( ! $is_completed ) {
                            $db_item = $wpdb->get_row( $wpdb->prepare(
                                "SELECT status FROM $table_name WHERE user_id = %d AND item_id = %d AND item_type = %s ORDER BY user_item_id DESC LIMIT 1",
                                $user_id,
                                $course_id,
                                'lp_course'
                            ) );
                            if ( $db_item && in_array( $db_item->status, array('completed', 'passed', 'finished') ) ) {
                                $is_completed = true;
                                $progress = 100;
                            }
                        }

                        // Filter by progress status if needed
                        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
                        if ( $filter === 'completed' && ! $is_completed ) continue;
                        if ( $filter === 'in-progress' && $is_completed ) continue;

                        ?>
                        <div class="iddi-membership-course-card">
                            <div class="iddi-membership-course-card__thumb">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ); ?>" alt="<?php the_title_attribute(); ?>">
                                    </a>
                                <?php else: ?>
                                    <div class="iddi-membership-course-card__thumb-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            <div class="iddi-membership-course-card__content">
                                <h3 class="iddi-membership-course-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="iddi-membership-course-card__excerpt">
                                    <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                                </div>
                                
                                <div class="iddi-membership-course-card__progress-wrap">
                                    <div class="iddi-membership-course-card__progress-header d-flex flex-jc-between">
                                        <span class="label">Progress</span>
                                        <span class="value"><?php echo $progress; ?>%</span>
                                    </div>
                                    <div class="iddi-membership-course-card__progress-bar">
                                        <div class="iddi-membership-course-card__progress-fill" style="width: <?php echo $progress; ?>%;"></div>
                                    </div>
                                </div>

                                <?php
                                $button_link = get_permalink();
                                $button_text = 'Continue Learning';

                                if ( $is_completed ) {
                                    $button_text = 'Xem lại bài học';
                                    $course = learn_press_get_course( $course_id );
                                    if ( $course ) {
                                        $curriculum = $course->get_curriculum();
                                        if ( $curriculum ) {
                                            foreach ( $curriculum as $section ) {
                                                $items = $section->get_items();
                                                if ( $items ) {
                                                    $first_item = reset( $items );
                                                    if ( $first_item ) {
                                                        $button_link = $first_item->get_permalink();
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                                <a href="<?php echo esc_url( $button_link ); ?>" class="iddi-membership-course-card__btn">
                                    <?php echo esc_html( $button_text ); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </a>
                            </div>
                        </div>
                        <?php
                    }

                    // Pagination
                    $total_pages = $course_query->max_num_pages;
                    if ($total_pages > 1) {
                        echo '<div class="iddi__pagination my-courses-page__pagination">';
                        $pagination = paginate_links(array(
                            'base'      => add_query_arg('paged', '%#%'),
                            'format'    => '',
                            'prev_text' => __( '< Trước', 'textdomain' ),
                            'next_text' => __( 'Tiếp >', 'textdomain' ),
                            'total'     => $total_pages,
                            'current'   => $paged,
                            'type'      => 'list',
                        ));
                        if ($pagination) {
                            echo str_replace("<ul class='page-numbers'>", '<ul class="iddi__nav-links my-courses-page__nav-links">', $pagination);
                        }
                        echo '</div>';
                    }

                    wp_reset_postdata();

                } else {
                    echo '<p class="iddi-membership-empty">No courses found.</p>';
                }

            } else {
                echo '<p class="iddi-membership-empty">You have not enrolled in any courses yet.</p>';
            }

        } else {
            echo '<p class="iddi-membership-empty">Please log in to view your courses.</p>';
        }
        ?>
    </div>
</div>
