<?php
if ( ! is_user_logged_in() ) {
    echo '<div class="iddi-membership-empty">Please log in to view your personal info.</div>';
    return;
}

$user_id = get_current_user_id();
$current_user = wp_get_current_user();

// User Meta
$display_name = $current_user->display_name;
$email = $current_user->user_email;
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$legal_name = trim($first_name . ' ' . $last_name);
if ( empty($legal_name) ) {
    $legal_name = $display_name;
}

$phone = get_user_meta($user_id, 'billing_phone', true);
if (empty($phone)) $phone = get_user_meta($user_id, 'phone', true);

$current_role = get_user_meta($user_id, 'current_role', true);
$clinic_name = get_user_meta($user_id, 'clinic_name', true);
$office_address = get_user_meta($user_id, 'office_address', true);

$avatar_url = get_avatar_url($user_id, ['size' => 100]);

// Handle form submission
$update_msg = '';
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile' ) {
    if ( isset($_POST['update_profile_field']) && wp_verify_nonce($_POST['update_profile_field'], 'update_profile_nonce') ) {
        
        if ( isset($_POST['display_name']) ) {
            wp_update_user( array( 'ID' => $user_id, 'display_name' => sanitize_text_field($_POST['display_name']) ) );
        }
        
        if ( isset($_POST['legal_name']) ) {
            $name_parts = explode(' ', sanitize_text_field($_POST['legal_name']), 2);
            update_user_meta($user_id, 'first_name', isset($name_parts[0]) ? $name_parts[0] : '');
            update_user_meta($user_id, 'last_name', isset($name_parts[1]) ? $name_parts[1] : '');
        }

        if ( isset($_POST['phone_number']) ) update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['phone_number']));
        if ( isset($_POST['current_role']) ) update_user_meta($user_id, 'current_role', sanitize_text_field($_POST['current_role']));
        if ( isset($_POST['clinic_name']) ) update_user_meta($user_id, 'clinic_name', sanitize_text_field($_POST['clinic_name']));
        if ( isset($_POST['office_address']) ) update_user_meta($user_id, 'office_address', sanitize_text_field($_POST['office_address']));
        
        $update_msg = '<div class="iddi-membership-notice iddi-membership-notice--success" style="padding: 15px; background: #ECFDF5; color: #10B981; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">Profile updated successfully!</div>';
        
        // Refresh user object after update
        $current_user = wp_get_current_user();
        $display_name = $current_user->display_name;
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);
        $legal_name = trim($first_name . ' ' . $last_name);
        if ( empty($legal_name) ) $legal_name = $display_name;
        $phone = get_user_meta($user_id, 'billing_phone', true);
        $current_role = get_user_meta($user_id, 'current_role', true);
        $clinic_name = get_user_meta($user_id, 'clinic_name', true);
        $office_address = get_user_meta($user_id, 'office_address', true);
    }
}

global $wpdb;

// Calculate Stats
$enrolled_courses_count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(DISTINCT item_id) FROM {$wpdb->prefix}learnpress_user_items WHERE user_id = %d AND item_type = 'lp_course'",
    $user_id
));

$certificates_count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(DISTINCT item_id) FROM {$wpdb->prefix}learnpress_user_items WHERE user_id = %d AND item_type = 'lp_course' AND status IN ('completed', 'passed', 'finished')",
    $user_id
));

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

?>
<div class="iddi-membership-tab iddi-membership-tab--personal-info">
    
    <div class="iddi-membership-tab__header">
        <h1 class="iddi-membership-tab__title">Account Settings</h1>
        <p class="iddi-membership-tab__subtitle">Refine your profile information and professional identity within the dental community.</p>
    </div>

    <!-- Stats Cards -->
    <div class="iddi-membership-stats">
        <div class="iddi-membership-stats__card">
            <div class="iddi-membership-stats__icon iddi-membership-stats__icon--courses">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <div class="iddi-membership-stats__content">
                <span class="iddi-membership-stats__value"><?php echo intval($enrolled_courses_count); ?></span>
                <span class="iddi-membership-stats__label">Enrolled Courses</span>
            </div>
        </div>
        <div class="iddi-membership-stats__card">
            <div class="iddi-membership-stats__icon iddi-membership-stats__icon--certificates">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
            </div>
            <div class="iddi-membership-stats__content">
                <span class="iddi-membership-stats__value"><?php echo intval($certificates_count); ?></span>
                <span class="iddi-membership-stats__label">Certificates</span>
            </div>
        </div>
        <div class="iddi-membership-stats__card">
            <div class="iddi-membership-stats__icon iddi-membership-stats__icon--invested">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
            </div>
            <div class="iddi-membership-stats__content">
                <span class="iddi-membership-stats__value">
                    <?php 
                    if (function_exists('learn_press_format_price')) {
                        echo learn_press_format_price( $total_spent ? $total_spent : 0, true );
                    } elseif (function_exists('wc_price')) {
                        echo wc_price( $total_spent ? $total_spent : 0 );
                    } else {
                        echo '$' . number_format(floatval($total_spent), 2);
                    }
                    ?>
                </span>
                <span class="iddi-membership-stats__label">Invested</span>
            </div>
        </div>
    </div>

    <!-- Main Content Form Box -->
    <div class="iddi-membership-box">
        
        <!-- Profile Avatar Section -->
        <div class="iddi-membership-profile">
            <div class="iddi-membership-profile__avatar-wrap">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="Avatar" class="iddi-membership-profile__avatar">
                <button type="button" class="iddi-membership-profile__avatar-edit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                </button>
            </div>
            <div class="iddi-membership-profile__info">
                <h3 class="iddi-membership-profile__name"><?php echo esc_html($display_name); ?></h3>
                <p class="iddi-membership-profile__role"><?php echo esc_html($current_role ?: 'Student'); ?> <?php if($clinic_name) echo '• ' . esc_html($clinic_name); ?></p>
                <button type="button" class="iddi-membership-profile__btn">Update Avatar</button>
            </div>
        </div>

        <?php if (!empty($update_msg)) echo $update_msg; ?>
        <form action="" method="POST" class="iddi-membership-form">
            <?php wp_nonce_field('update_profile_nonce', 'update_profile_field'); ?>
            <input type="hidden" name="action" value="update_profile">
            
            <!-- Personal Information Section -->
            <div class="iddi-membership-form__section">
                <h4 class="iddi-membership-form__section-title">Personal Information</h4>
                <div class="iddi-membership-form__grid">
                    <div class="iddi-membership-form__group">
                        <label for="legal_name" class="iddi-membership-form__label">Legal Full Name</label>
                        <input type="text" id="legal_name" name="legal_name" class="iddi-membership-form__input" value="<?php echo esc_attr($legal_name); ?>">
                    </div>
                    <div class="iddi-membership-form__group">
                        <label for="display_name" class="iddi-membership-form__label">Display Name</label>
                        <input type="text" id="display_name" name="display_name" class="iddi-membership-form__input" value="<?php echo esc_attr($display_name); ?>">
                    </div>
                    <div class="iddi-membership-form__group">
                        <label for="primary_email" class="iddi-membership-form__label">Primary Email <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></label>
                        <input type="email" id="primary_email" name="primary_email" class="iddi-membership-form__input" value="<?php echo esc_attr($email); ?>" disabled>
                    </div>
                    <div class="iddi-membership-form__group">
                        <label for="phone_number" class="iddi-membership-form__label">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" class="iddi-membership-form__input" value="<?php echo esc_attr($phone); ?>">
                    </div>
                </div>
            </div>

            <!-- Professional Background Section -->
            <div class="iddi-membership-form__section">
                <h4 class="iddi-membership-form__section-title">Professional Background</h4>
                <div class="iddi-membership-form__grid">
                    <div class="iddi-membership-form__group">
                        <label for="current_role" class="iddi-membership-form__label">Current Role</label>
                        <select id="current_role" name="current_role" class="iddi-membership-form__select">
                            <option value="">Select Role</option>
                            <option value="General Dentist" <?php selected($current_role, 'General Dentist'); ?>>General Dentist</option>
                            <option value="Orthodontist" <?php selected($current_role, 'Orthodontist'); ?>>Orthodontist</option>
                            <option value="Periodontist" <?php selected($current_role, 'Periodontist'); ?>>Periodontist</option>
                            <option value="Student" <?php selected($current_role, 'Student'); ?>>Student</option>
                        </select>
                    </div>
                    <div class="iddi-membership-form__group">
                        <label for="clinic_name" class="iddi-membership-form__label">Clinic / Practice Name</label>
                        <input type="text" id="clinic_name" name="clinic_name" class="iddi-membership-form__input" value="<?php echo esc_attr($clinic_name); ?>">
                    </div>
                    <div class="iddi-membership-form__group iddi-membership-form__group--full">
                        <label for="office_address" class="iddi-membership-form__label">Office Address</label>
                        <input type="text" id="office_address" name="office_address" class="iddi-membership-form__input" value="<?php echo esc_attr($office_address); ?>">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="iddi-membership-form__actions">
                <button type="button" class="iddi-membership-form__btn-cancel">Cancel</button>
                <button type="submit" class="iddi-membership-form__btn-submit">
                    Save Profile Changes
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </button>
            </div>

        </form>
    </div>

</div>
