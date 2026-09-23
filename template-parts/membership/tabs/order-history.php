<div class="iddi-membership-tab iddi-membership-tab--order-history">
    
    <div class="iddi-membership-tab__header">
        <h1 class="iddi-membership-tab__title">Order History</h1>
        <p class="iddi-membership-tab__subtitle">Review your previous academic purchases and download invoices for your records.</p>
    </div>

    <?php
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        global $wpdb;

        // Calculate Total Spent
        $total_spent = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(pm1.meta_value) FROM $wpdb->postmeta pm1
            JOIN $wpdb->posts p ON p.ID = pm1.post_id
            JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id
            WHERE p.post_type = 'lp_order' 
            AND p.post_status IN ('lp-completed', 'completed')
            AND pm1.meta_key = '_order_total' 
            AND pm2.meta_key = '_user_id' AND pm2.meta_value = %d",
            $user_id
        ));

        // Calculate Courses Purchased (from user items where ref_type is order)
        $courses_purchased = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT item_id) FROM {$wpdb->prefix}learnpress_user_items 
             WHERE user_id = %d AND item_type = 'lp_course' AND ref_type = 'lp_order'",
            $user_id
        ));

        ?>
        <!-- Overview Stats -->
        <div class="iddi-membership-order-stats">
            <div class="iddi-membership-order-stat-card">
                <div class="iddi-membership-order-stat-card__label">Total Spent</div>
                <div class="iddi-membership-order-stat-card__value">
                    <?php 
                        if (function_exists('learn_press_format_price')) {
                            echo learn_press_format_price( $total_spent ? $total_spent : 0, true );
                        } elseif (function_exists('wc_price')) {
                            echo wc_price( $total_spent ? $total_spent : 0 );
                        } else {
                            echo '$' . number_format(floatval($total_spent), 2);
                        }
                    ?>
                </div>
            </div>
            <div class="iddi-membership-order-stat-card">
                <div class="iddi-membership-order-stat-card__label">Courses Purchased</div>
                <div class="iddi-membership-order-stat-card__value"><?php echo intval($courses_purchased); ?></div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="iddi-membership-table-wrap">
            <table class="iddi-membership-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Item Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( isset($_GET['paged']) ? absint($_GET['paged']) : 1 );
                    $posts_per_page = 10;

                    $args = array(
                        'post_type'      => 'lp_order',
                        'posts_per_page' => $posts_per_page,
                        'paged'          => $paged,
                        'post_status'    => 'any',
                        'meta_query'     => array(
                            array(
                                'key'     => '_user_id',
                                'value'   => $user_id,
                                'compare' => '='
                            )
                        )
                    );

                    $orders_query = new WP_Query( $args );

                    if ( $orders_query->have_posts() ) {
                        while ( $orders_query->have_posts() ) {
                            $orders_query->the_post();
                            $order_id = get_the_ID();
                            
                            $order = false;
                            if ( function_exists('learn_press_get_order') ) {
                                $order = learn_press_get_order( $order_id );
                            }

                            // Order Number
                            $order_number = $order ? $order->get_order_number() : '#' . $order_id;
                            
                            // Date
                            $order_date = get_the_date('M d, Y');
                            
                            // Total
                            $order_total = $order ? $order->get_formatted_order_total() : get_post_meta($order_id, '_order_total', true);
                            if (!$order && function_exists('learn_press_format_price')) {
                                $order_total = learn_press_format_price($order_total, true);
                            }

                            // Status
                            $status_obj = get_post_status_object( get_post_status() );
                            $status_label = $status_obj ? $status_obj->label : ucfirst(str_replace('lp-', '', get_post_status()));
                            $status_slug = str_replace('lp-', '', get_post_status());

                            $status_class = 'iddi-membership-order-status--pending';
                            if ( $status_slug === 'completed' ) $status_class = 'iddi-membership-order-status--completed';
                            if ( in_array($status_slug, ['failed', 'cancelled']) ) $status_class = 'iddi-membership-order-status--failed';

                            // Items
                            $item_names = array();
                            $item_cats = array();
                            if ( $order ) {
                                $items = $order->get_items();
                                if ( !empty($items) ) {
                                    foreach ($items as $item) {
                                        $course_id = $item['course_id'];
                                        $item_names[] = get_the_title($course_id);
                                        $terms = get_the_terms($course_id, 'course_category');
                                        if ( $terms && !is_wp_error($terms) ) {
                                            $item_cats[] = $terms[0]->name;
                                        }
                                    }
                                }
                            }
                            
                            $main_item = !empty($item_names) ? $item_names[0] : 'Multiple Items';
                            $main_cat = !empty($item_cats) ? $item_cats[0] : 'Course';
                            
                            ?>
                            <tr>
                                <td data-label="Order ID">
                                    <div class="iddi-membership-order-id"><?php echo esc_html($order_number); ?></div>
                                </td>
                                <td data-label="Date">
                                    <div class="iddi-membership-order-date"><?php echo esc_html($order_date); ?></div>
                                </td>
                                <td data-label="Item Name">
                                    <div class="iddi-membership-order-item-title"><?php echo esc_html($main_item); ?></div>
                                    <div class="iddi-membership-order-item-cat"><?php echo esc_html($main_cat); ?></div>
                                </td>
                                <td data-label="Amount">
                                    <div class="iddi-membership-order-amount"><?php echo wp_kses_post($order_total); ?></div>
                                </td>
                                <td data-label="Status">
                                    <span class="iddi-membership-order-status <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></span>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748B;">You have no order history yet.</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <?php
            // Pagination Footer
            if ( $orders_query->max_num_pages > 1 ) {
                $total_items = $orders_query->found_posts;
                $current_start = ( $paged - 1 ) * $posts_per_page + 1;
                $current_end = min( $paged * $posts_per_page, $total_items );
                ?>
                <div class="iddi-membership-table-footer">
                    <div class="iddi-membership-table-info">
                        Showing <?php echo $current_start; ?> to <?php echo $current_end; ?> of <?php echo $total_items; ?> orders
                    </div>
                    <div class="iddi-membership-table-nav">
                        <?php 
                        $prev_url = $paged > 1 ? add_query_arg('paged', $paged - 1) : '#';
                        $next_url = $paged < $orders_query->max_num_pages ? add_query_arg('paged', $paged + 1) : '#';
                        ?>
                        <a href="<?php echo esc_url($prev_url); ?>" class="iddi-membership-table-btn" <?php if($paged <= 1) echo 'disabled onclick="return false;"'; ?>>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </a>
                        <a href="<?php echo esc_url($next_url); ?>" class="iddi-membership-table-btn" <?php if($paged >= $orders_query->max_num_pages) echo 'disabled onclick="return false;"'; ?>>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
            ?>
        </div>

        <?php
    } else {
        echo '<div class="iddi-membership-empty">Please log in to view your order history.</div>';
    }
    ?>
</div>
