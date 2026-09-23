<?php
/**
 * Template for displaying offline LearnPress courses
 * File: single-course-offline.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Lấy meta LearnPress
$address = get_post_meta(get_the_ID(), '_lp_offline_address', true);
$price   = get_post_meta(get_the_ID(), '_lp_price', true);
$regular_price = get_post_meta(get_the_ID(), '_lp_regular_price', true);
$sale_price    = get_post_meta(get_the_ID(), '_lp_sale_price', true);

// ACF Fields
$date_time    = get_field('offline_date_time');
$ticket_title = get_field('offline_ticket_title');
$ticket_desc  = get_field('offline_ticket_desc');
$cta_url      = get_field('offline_cta_url');

?>

<main id="main" class="lp-single-course-main py-5 page-course-offline">
    <?php while ( have_posts() ) : the_post(); ?>

    <!-- Hero Section -->
    <section class="course-offline__hero">
        <div class="iddi__container">
            <div class="course-offline__hero-header d-flex flex-column flex-ai-center gap-l">
                <div class="course-offline__hero-time fs-28 fw-500 italic-font text-color-flame-orange"> 
                    <?php echo $date_time ? esc_html($date_time) : 'Upcoming Course'; ?>
                </div>
                <h1 class="course-offline__hero-title fs-56 fw-600 italic-font text-color-oxford-blue center-text"> 
                    <?php the_title(); ?>
                </h1>
                <div class="course-offline__hero-location d-flex fs-24 fw-400 text-color-oxford-blue flex-jc-center gap-m"> 
                    <?php echo get_my_svg('address'); ?>
                    <span><?php echo $address ? esc_html($address) : 'TBA'; ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Speakers Section -->
    <section class="course-offline__speakers">
        <div class="iddi__container">
            <div class="course-offline__speakers-container radius-xl padding-2xl bg-color_color-oxford-blue">
                <div class="course-offline__speakers-header">
                    <h2 class="course-offline__speakers-title fs-32 fw-500 text-color-white upper-text">SPEAKERS</h2>
                </div>
                <div class="course-offline__speakers-list d-grid g-column-3 g-column-2__md g-column-1__sm">
                    <?php if (have_rows('offline_speakers_list')) : ?>
                        <?php while (have_rows('offline_speakers_list')) : the_row(); 
                            $avatar = get_sub_field('speaker_avatar');
                            $name   = get_sub_field('speaker_name');
                            $role   = get_sub_field('speaker_role');
                            $desc   = get_sub_field('speaker_desc');
                        ?>
                        <article class="course-offline__speaker-item">
                            <div class="course-offline__speaker-avatar-wrap">
                                <?php if ($avatar) : ?>
                                    <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($name); ?>" class="course-offline__speaker-avatar circle-image cover-image">
                                <?php endif; ?>
                            </div>
                            <div class="course-offline__speaker-info">
                                <h4 class="course-offline__speaker-name fs-20 fw-700 text-color-white"><?php echo esc_html($name); ?></h4>
                                <div class="course-offline__speaker-role fs-14 fw-400 text-color-flame-orange italic-font"><?php echo esc_html($role); ?></div>
                                <p class="course-offline__speaker-desc fs-14 fw-300 text-color-white opacity-8"><?php echo esc_html($desc); ?></p>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                
                <div class="course-offline__speakers-footer d-flex flex-jc-between flex-ai-center margin-t-2xl">
                    <div class="course-offline__patronage d-flex flex-ai-center gap-m">
                        <span class="fs-18 fw-500 italic-font text-color-white">Under the patronage of:</span>
                        <?php $p_logo = get_field('offline_patronage_logo'); if ($p_logo) : ?>
                            <img src="<?php echo esc_url($p_logo); ?>" alt="Patronage" class="course-offline__patronage-logo">
                        <?php endif; ?>
                    </div>
                    <div class="course-offline__event-logo">
                        <img src="/wp-content/uploads/2026/05/CES-2026-logo.png" alt="CES 2026" class="course-offline__event-logo-img">
                    </div>
                </div>
            </div> 
        </div>
    </section>

    <!-- Pricing/CTA Section -->
    <section class="course-offline__tickets">
        <div class="iddi__container center-text">
            <div class="course-offline__tickets-highlight fs-32 fw-500 italic-font text-color-flame-orange">
                ALL TICKETS INCLUDE the Gala Dinner!
            </div>
            <p class="course-offline__tickets-desc fs-24 fw-300 italic-font text-color-oxford-blue margin-b-xl">
                Meet and connect with international speakers, daily and experience an unforgettable evening of entertainment featuring a live Queen Tribute Band.
            </p>
            
            <div class="course-offline__tickets-action">
                <a href="<?php echo $cta_url ? esc_url($cta_url) : '#'; ?>" class="course-offline__tickets-btn fw-600 bg-color_color-flame-orange d-i-block text-color-white radius-m padding-xl__v padding-2xl__h">
                    <?php 
                        if ($sale_price) {
                            echo '£' . number_format($sale_price, 0) . ' - Get Your Tickets!';
                        } elseif ($price) {
                            echo '£' . number_format($price, 0) . ' - Get Your Tickets!';
                        } else {
                            echo 'Get Your Tickets!';
                        }
                    ?>
                </a>
            </div>
        </div>
    </section>

    <!-- What You Will Gain Section -->
    <section class="course-offline__gain bg-color_color-white padding-v-2xl">
        <div class="iddi__container">
            <h2 class="course-offline__gain-title fs-40 fw-600 italic-font text-color-oxford-blue center-text margin-b-2xl">
                What <span class="text-color-flame-orange">You Will Gain</span>
            </h2>
            <div class="course-offline__gain-grid d-grid g-column-3 g-column-1__sm gap-2xl">
                <div class="course-offline__gain-item center-text">
                    <div class="course-offline__gain-icon margin-b-m">
                        <?php echo get_my_svg('networking'); ?>
                    </div>
                    <h4 class="course-offline__gain-item-title fs-24 fw-600">Networking</h4>
                    <p class="course-offline__gain-item-desc fs-16 fw-300">Share with other international members during dinner time, coffee and breakfast.</p>
                </div>
                <div class="course-offline__gain-item center-text">
                    <div class="course-offline__gain-icon margin-b-m">
                        <?php echo get_my_svg('practical'); ?>
                    </div>
                    <h4 class="course-offline__gain-item-title fs-24 fw-600">Practical Sessions</h4>
                    <p class="course-offline__gain-item-desc fs-16 fw-300">Live demos and sessions that will enhance your practical knowledge in digital workflows.</p>
                </div>
                <div class="course-offline__gain-item center-text">
                    <div class="course-offline__gain-icon margin-b-m">
                        <?php echo get_my_svg('education'); ?>
                    </div>
                    <h4 class="course-offline__gain-item-title fs-24 fw-600">Full Education</h4>
                    <p class="course-offline__gain-item-desc fs-16 fw-300">Learn from top international speakers with a focused and high-quality educational program.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="course-offline__contact">
        <div class="iddi__container">
            <?php get_template_part('template-parts/sections/section-contact'); ?>
        </div>
    </section>

    <?php endwhile; ?>
</main>
<?php get_footer(); ?>