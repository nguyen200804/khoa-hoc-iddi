<?php
/**
 * Template Name: Membership
 * 
 * Description: The main template for the Membership portal (Account Settings).
 */

if (!is_user_logged_in()) {
    $login_page = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/login.php'
    ));
    $login_url = ($login_page) ? get_permalink($login_page[0]->ID) : wp_login_url(get_permalink());
    wp_redirect($login_url);
    exit;
}

add_action('wp_enqueue_scripts', function() {
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'personal-info';
    $allowed_tabs = array('personal-info', 'my-courses', 'certificates', 'order-history', 'support');
    if (!in_array($current_tab, $allowed_tabs)) {
        $current_tab = 'personal-info';
    }
    
    // Enqueue the specific tab's CSS
    wp_enqueue_style(
        'membership-tab-' . $current_tab,
        get_template_directory_uri() . '/assets/css/page-templates/membership-tabs/' . $current_tab . '.css',
        array('membership-style'), // Dependency ensures main membership.css is loaded first
        '1.0.0'
    );
});

get_header(); ?>

<main class="site-main iddi-membership-portal bg-img-fixed">
    <div class="container padding-y-xxl">
        <div class="iddi-membership__layout">
            
            <!-- Sidebar / Portal Menu -->
            <aside class="iddi-membership__sidebar">
                <?php get_template_part('template-parts/membership/sidebar'); ?>
            </aside>

            <!-- Main Content Area -->
            <section class="iddi-membership__content">
                <?php 
                    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'personal-info';
                    $allowed_tabs = array('personal-info', 'my-courses', 'certificates', 'order-history', 'support');
                    
                    if (in_array($current_tab, $allowed_tabs)) {
                        get_template_part('template-parts/membership/tabs/' . $current_tab); 
                    } else {
                        get_template_part('template-parts/membership/tabs/personal-info'); 
                    }
                ?>
            </section>

        </div>
    </div>
</main>

<?php get_footer(); ?>
