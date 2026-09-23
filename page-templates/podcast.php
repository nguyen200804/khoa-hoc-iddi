<?php
/**
 * Template Name: Podcast
 * 
 * @package IDDI_Academy
 */

get_header(); ?>

<main id="primary" class="site-main podcast-page">

	<!-- Hero Section -->
	<section class="podcast-hero p-relative">
		<div class="container">
			<div class="podcast-hero__wrapper">
				<div class="podcast-hero__content">
					<span class="podcast-hero__subtitle upper-text fw-600 text-color-flame-orange">Featured Episode</span>
					<h1 class="podcast-hero__title italic-font">Dental <span class="text-color-flame-orange">Podcast</span></h1>

					<div class="featured-episode">
						<span class="featured-episode__meta fs-14 fw-500">EPISODE 42</span>
						<h2 class="featured-episode__title fs-32 fw-600 text-color-oxford-blue">AI Integration in Modern Practice</h2>
						<p class="featured-episode__desc fs-16">
							How Artificial Intelligence is revolutionizing diagnosis, treatment planning, and patient communication in digital dentistry.
						</p>
						<div class="featured-episode__actions">
							<a href="#" class="podcast-play-btn">
								<span class="podcast-play-btn__icon">
									<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
								</span>
								<span class="podcast-play-btn__text fw-600">Play Episode</span>
							</a>
						</div>
					</div>

					<div class="podcast-platforms">
						<div class="podcast-platforms__icons">
							<a href="#" class="platform-icon" title="Apple Podcasts"><img src="/wp-content/uploads/2026/05/Apple-Podcasts.png" alt="Apple Podcasts"></a>
							<a href="#" class="platform-icon" title="Anchor"><img src="/wp-content/uploads/2026/05/Anchor.png" alt="Anchor"></a>
							<a href="#" class="platform-icon" title="Spotify"><img src="/wp-content/uploads/2026/05/Spotify.png" alt="Spotify"></a>
							<a href="#" class="platform-icon" title="Podbean"><img src="/wp-content/uploads/2026/05/Podbean.png" alt="Podbean"></a>
							<a href="#" class="platform-icon" title="Overcast"><img src="/wp-content/uploads/2026/05/Overcast.png" alt="Overcast"></a>
							<a href="#" class="platform-icon" title="RadioPublic"><img src="/wp-content/uploads/2026/05/RadioPublic.png" alt="RadioPublic"></a>
							<a href="#" class="platform-icon" title="Google Podcasts"><img src="/wp-content/uploads/2026/05/Google-Podcasts.png" alt="Google Podcasts"></a>
							<a href="#" class="platform-icon" title="RSS Feed"><img src="/wp-content/uploads/2026/05/RSS-Feed.png" alt="RSS Feed"></a>
							<a href="#" class="platform-icon" title="Castbox"><img src="/wp-content/uploads/2026/05/Castbox.png" alt="Castbox"></a>
							<a href="#" class="platform-icon" title="Pocket Casts"><img src="/wp-content/uploads/2026/05/Pocket-Casts.png" alt="Pocket Casts"></a>
						</div>
					</div>
				</div>

				<div class="podcast-hero__visual">
					<div class="soundwave-graphic">
						<div class="soundwave-ring _1">
							<div class="soundwave-dot"></div>
						</div>
						<div class="soundwave-ring _2">
							<div class="soundwave-dot"></div>
						</div>
						<div class="soundwave-ring _3">
							<div class="soundwave-dot"></div>
						</div>
						<div class="soundwave-center">
							<svg width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.33333 20.8V5.2H8V20.8H5.33333ZM10.6667 26V0H13.3333V26H10.6667ZM0 15.6V10.4H2.66667V15.6H0ZM16 20.8V5.2H18.6667V20.8H16ZM21.3333 15.6V10.4H24V15.6H21.3333Z" fill="white"/>
							</svg>

						</div>
					</div>
				</div>
			</div>
		</div>
		<svg class="podcast-hero-footer-wave" viewBox="0 0 1920 103" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 36.425s310.486 127.241 984.749 17.357C1659.01-56.1 1920 36.425 1920 36.425V103H0z" fill="#fff"></path>
		</svg>
	</section>

	<!-- Recent Episodes Carousel -->
	<section class="podcast-featured swiper podcastFeaturedSwiper">

		<div class="swiper-wrapper">
			<?php 
			$args = array(
				'post_type'      => 'video',
				'posts_per_page' => 8,
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			$video_query = new WP_Query($args);

			if ($video_query->have_posts()) :
			while ($video_query->have_posts()) : $video_query->the_post();
			$thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$terms     = get_the_terms(get_the_ID(), 'danh-muc-video');
			$term_name = !empty($terms) && !is_wp_error($terms) ? $terms[0]->name : 'PODCAST';
			$subtitle  = get_field('subtitle'); // ACF field
			?>
			<div class="swiper-slide">
				<div class="episode-card">
					<?php if ($thumb_url) : ?>
					<img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" class="episode-card__bg">
					<?php else : ?>
					<img src="https://picsum.photos/seed/<?php echo get_the_ID(); ?>/800/600" alt="<?php the_title_attribute(); ?>" class="episode-card__bg">
					<?php endif; ?>

					<div class="episode-card__overlay">
						<a href="<?php the_permalink(); ?>" class="episode-card__play-btn">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8" fill="currentColor"></polygon></svg>
						</a>
						<div class="episode-card__info">
							<span class="episode-card__meta"><?php echo esc_html($term_name); ?></span>
							<h3 class="episode-card__title"><?php the_title(); ?></h3>
							<p class="episode-card__subtitle"><?php echo esc_html($subtitle); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php 
			endwhile; 
			wp_reset_postdata(); 
			else: 
			echo '<p class="center-text">No episodes found.</p>';
			endif; 
			?>
		</div>
		<div class="swiper-controls">
			<div class="swiper-button-prev-custom"></div>
			<div class="swiper-scrollbar"></div>
			<div class="swiper-button-next-custom"></div>
		</div>
	</section>

	<!-- Episode Library -->
	<section class="podcast-library">
		<div class="container">
			<div class="section-header d-flex flex-jc-between flex-ai-center">
				<h2 class="section-title text-color-oxford-blue">Episode Library</h2>
				<div class="library-filters d-flex flex-ai-center gap-m">
					<div class="filter-btns d-flex gap-s">
						<button class="filter-btn is-active">All</button>
						<button class="filter-btn">AI</button>
						<button class="filter-btn">Clinical</button>
						<button class="filter-btn">Business</button>
					</div>
					<button class="search-btn reset-button">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					</button>
				</div>
			</div>

			<div class="episode-grid">
				<?php 
				$guests = [
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '10'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '11'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '12'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '13'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '14'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '15'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '16'],
					['name' => 'Dr. Julian Sterling', 'role' => 'Specialist in Restorative and Aesthetic Dentistry', 'img' => '17'],
				];
				foreach($guests as $index => $guest): 
				get_template_part('template-parts/components/card-president', null, [
					'tag'    => 'PODCAST GUEST',
					'img'    => 'https://i.pravatar.cc/400?img=' . $guest['img'],
					'flag'   => '🎙️',
					'role'   => $guest['role'],
					'name'   => $guest['name'],
					'desc'   => 'Specialist in restorative digital workflows and pioneer of integrated clinical practices...',
					'socials' => [
						['icon' => 'facebook', 'url' => '#'],
						['icon' => 'linkedin', 'url' => '#'],
						['icon' => 'explore', 'url' => '#'],
					]
				]);
				endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Newsletter Banner -->
	<section class="podcast-newsletter">
		<div class="container">
			<div class="newsletter-box">
				<h3 class="newsletter-box__title italic-font fs-32">Never Miss an <span class="text-color-flame-orange">Episode</span></h3>
				<p class="newsletter-box__desc fs-16">Join 15,000+ dental professionals. Get notified about ne episodes, clinical downloads, and special event invites.</p>
				<div class="newsletter-form-container">
					<?php echo do_shortcode('[contact-form-7 id="e215dae" title="Never Miss an Episode"]'); ?>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
