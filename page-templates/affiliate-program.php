<?php
/**
 * Template Name: Affiliate Program
 * 
 * @package IDDI_Academy
 */

get_header(); ?>

<main id="primary" class="site-main affiliate-page">

    <!-- Hero Section -->
    <section class="affiliate-hero">
        <div class="container">
            <div class="affiliate-hero__content center-text">
                <h1 class="affiliate-hero__title italic-font ">
                    Join the IDDI <span class="text-color-flame-orange">Affiliate Program</span>
                </h1>
                <p class="affiliate-hero__subtitle upper-text fw-600 ">
                    EARN ONGOING MONTHLY INCOME BY JOINING OUR FREE AFFILIATE PROGRAM
                </p>
                <div class="affiliate-hero__desc">
                    Our faculty staff and lecturers in various topics in Digital Dentistry will work with
                    you during your academic career mentoring you at each step to help you realize
                    your full potential as a Digital Dental Master.
                </div>
                
                <div class="affiliate-hero__actions d-flex flex-center-h gap-m">
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo home_url('/membership/'); ?>" class="iddi-btn iddi-btn--orange">Go to Dashboard</a>
                    <?php else : ?>
                        <a href="javascript:void(0)" onclick="window.openRegisterPopup()" class="iddi-btn iddi-btn--orange">Become an Affiliate</a>
                    <?php endif; ?>
                    <a href="#affiliate-steps" class="iddi-btn iddi-btn--outline">Learn More</a>
                </div>

                <div class="affiliate-hero__badges d-flex flex-center-h gap-xl flex-column__sm gap-m__sm">
                    <div class="trust-badge d-flex flex-ai-center">
                        <svg viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg"><path d="m7.167 12.167 5.875-5.875-1.167-1.167-4.708 4.708-2.375-2.375-1.167 1.167zm1.166 4.5a8.1 8.1 0 0 1-3.25-.657 8.4 8.4 0 0 1-2.645-1.78 8.4 8.4 0 0 1-1.782-2.647A8.1 8.1 0 0 1 0 8.333q0-1.729.656-3.25a8.4 8.4 0 0 1 1.781-2.645A8.4 8.4 0 0 1 5.084.656 8.1 8.1 0 0 1 8.333 0q1.73 0 3.25.656a8.4 8.4 0 0 1 2.646 1.781 8.4 8.4 0 0 1 1.781 2.646 8.1 8.1 0 0 1 .657 3.25 8.1 8.1 0 0 1-.657 3.25 8.4 8.4 0 0 1-1.78 2.646 8.4 8.4 0 0 1-2.647 1.781 8.1 8.1 0 0 1-3.25.657m0-1.667q2.792 0 4.73-1.937Q15 11.125 15 8.333q0-2.79-1.937-4.729-1.938-1.937-4.73-1.937-2.79 0-4.729 1.937-1.937 1.938-1.937 4.73 0 2.79 1.937 4.729Q5.542 15 8.334 15"/></svg>
                        <span>No sign up fees</span>
                    </div>
                    <div class="trust-badge d-flex flex-ai-center">
<!--                         <?php echo get_my_svg('check-circle'); ?> -->
						<svg viewBox="0 0 15 18" xmlns="http://www.w3.org/2000/svg"><path d="M5 1.667V0h5v1.667zm1.667 9.166h1.666v-5H6.667zM7.5 17.5a7.2 7.2 0 0 1-2.906-.594 7.7 7.7 0 0 1-2.386-1.614 7.7 7.7 0 0 1-1.614-2.386A7.2 7.2 0 0 1 0 10q0-1.542.594-2.906a7.7 7.7 0 0 1 1.614-2.386 7.7 7.7 0 0 1 2.386-1.614A7.2 7.2 0 0 1 7.5 2.5q1.292 0 2.48.417 1.186.416 2.228 1.208l1.167-1.167 1.167 1.167-1.167 1.167a8.1 8.1 0 0 1 1.208 2.229Q15 8.709 15 10a7.2 7.2 0 0 1-.594 2.906 7.7 7.7 0 0 1-1.614 2.386 7.7 7.7 0 0 1-2.386 1.614A7.2 7.2 0 0 1 7.5 17.5m0-1.667q2.417 0 4.125-1.708 1.708-1.709 1.708-4.125t-1.708-4.125T7.5 4.167 3.375 5.875Q1.667 7.584 1.667 10t1.708 4.125T7.5 15.833"/></svg>
                        <span>60 day tracking cookies</span>
                    </div>
                    <div class="trust-badge d-flex flex-ai-center">
                        <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg"><path d="M10.833 7.5a2.4 2.4 0 0 1-1.77-.73A2.4 2.4 0 0 1 8.333 5q0-1.042.73-1.77a2.4 2.4 0 0 1 1.77-.73q1.042 0 1.771.73a2.4 2.4 0 0 1 .73 1.77 2.4 2.4 0 0 1-.73 1.77 2.4 2.4 0 0 1-1.77.73M5 10q-.687 0-1.177-.49a1.6 1.6 0 0 1-.49-1.177V1.667q0-.688.49-1.177T5 0h11.667q.687 0 1.177.49.49.489.49 1.177v6.666q0 .688-.49 1.177t-1.177.49zm1.667-1.667H15q0-.687.49-1.177.489-.49 1.177-.49V3.334q-.688 0-1.177-.49A1.6 1.6 0 0 1 15 1.668H6.667q0 .687-.49 1.177T5 3.334v3.333q.687 0 1.177.49.49.489.49 1.176m9.166 5H1.667q-.688 0-1.177-.49A1.6 1.6 0 0 1 0 11.668V2.5h1.667v9.167h14.166zM5 8.333V1.667z"/></svg>
                        <span>Earn commission</span>
                    </div>
                </div>
            </div>
        </div>
		<div class="affiliate-hero-footer-wave d-flex">
			<svg  viewBox="0 0 1920 103" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 36.425s310.486 127.241 984.749 17.357C1659.01-56.1 1920 36.425 1920 36.425V103H0z" fill="#fff"></path>
		</svg>
		</div>
    </section>

    <!-- 3 Steps Section -->
    <section id="affiliate-steps" class="affiliate-steps">
        <div class="container">
            <div class="section-header center-text">
                <span class="section-tag italic-font text-color-flame-orange fs-24 fw-600">3 easy steps to</span>
                <h2 class="section-title italic-font fs-48 fs-32__sm text-color-oxford-blue">Start earning commissions.</h2>
            </div>

            <div class="affiliate-steps__grid d-grid g-column-3 g-column-1__sm gap-l">
                <!-- Step 1 -->
                <div class="step-card">
                    <div class="step-card__number">1</div>
                    <h3 class="step-card__title fs-24 fw-600 text-color-oxford-blue">Sign Up</h3>
                    <p class="step-card__desc fs-16">
                        Complete the quick application form below to get your unique Affiliate ID instantly.
                    </p>
                </div>
                <!-- Step 2 -->
                <div class="step-card">
                    <div class="step-card__number">2</div>
                    <h3 class="step-card__title fs-24 fw-600 text-color-oxford-blue">Share Links</h3>
                    <p class="step-card__desc fs-16">
                        Promote IDDI courses using your custom links on your website, social media, or email list.
                    </p>
                </div>
                <!-- Step 3 -->
                <div class="step-card">
                    <div class="step-card__number">3</div>
                    <h3 class="step-card__title fs-24 fw-600 text-color-oxford-blue">Earn Commissions</h3>
                    <p class="step-card__desc fs-16">
                        Receive a 15% commission for every sale made through your referral links. Paid out now monthly.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="affiliate-faq">
        <div class="container">
            <h2 class="faq-title italic-font center-text fs-48 fs-32__sm text-color-oxford-blue">
                Frequently <span class="text-color-flame-orange">Asked Questions</span>
            </h2>

            <div class="faq-accordion">
                <?php 
                $faqs = [
                    [
                        'q' => 'When do I get paid?',
                        'a' => 'Our faculty staff and lecturers in various topics in Digital Dentistry will work with you during your academic career mentoring you at each step to help you realize your full potential as a Digital Dental Master.'
                    ],
                    [
                        'q' => 'Do I need to be a dentist to join?',
                        'a' => 'While the program is primarily designed for dental professionals, we welcome affiliates who have a strong connection to the dental industry or digital dentistry community.'
                    ],
                    [
                        'q' => 'How are sales tracked?',
                        'a' => 'We use 60-day tracking cookies to ensure you get credited for sales even if the customer doesn\'t purchase immediately after clicking your link.'
                    ]
                ];
                foreach($faqs as $index => $faq): ?>
                <div class="faq-item <?php echo $index === 0 ? 'is-active' : ''; ?>">
                    <div class="faq-item__header d-flex flex-jc-between flex-ai-center">
                        <h3 class="faq-item__question fs-20 fw-500 text-color-oxford-blue"><?php echo $faq['q']; ?></h3>
                        <span class="faq-item__icon"></span>
                    </div>
                    <div class="faq-item__content">
                        <div class="faq-item__text fs-16 fw-300">
                            <?php echo $faq['a']; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const header = item.querySelector('.faq-item__header');
        const content = item.querySelector('.faq-item__content');
        
        // Initial state for active item
        if (item.classList.contains('is-active')) {
            content.style.maxHeight = content.scrollHeight + 'px';
        }

        header.addEventListener('click', () => {
            const isActive = item.classList.contains('is-active');
            
            // Close all items
            faqItems.forEach(i => {
                i.classList.remove('is-active');
                i.querySelector('.faq-item__content').style.maxHeight = null;
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                item.classList.add('is-active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });
});
</script>

<?php get_footer(); ?>
