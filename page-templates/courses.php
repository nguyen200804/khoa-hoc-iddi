<?php
/*
Template Name: Courses
*/
get_header(); ?>

<main id="iddi-courses">

	<section class="iddi-courses__why-join">
		<div class="iddi__container">
			<div class="iddi-courses__why-join-heading center-text italic-font">
				<p class="sub-title fs-32 fw-500 text-color-flame-orange">Why join</p>
				<h2 class="title fs-56 fw-300 text-color-oxford-blue">The International Digital Dental Academy?</h2>
			</div>

			<div class="iddi-courses__why-join-hero">
				<div class="video-wrapper p-relative iddi-courses__why-join-video-wrapper center-text">
					<video class="video-element cover-image radius-xl" width="1330" height="759" muted loop poster="/wp-content/uploads/2026/02/education-and-training.webp">
						<source src="/wp-content/uploads/2026/02/video_dentistry.mp4" type="video/mp4">
					</video>
					<button class="btn-play reset-button p-absolute iddi-courses__why-join-video-wrapper-button"><?php echo get_my_svg('play'); ?></button>
					<button class="btn-pause reset-button p-absolute iddi-courses__why-join-video-wrapper-button"><?php echo get_my_svg('pause'); ?></button>
				</div>
			</div>

			<div class="iddi-courses__why-join-content d-flex flex-jc-between">
				<div class="iddi-courses__why-join-col iddi-courses__why-join-col--left d-flex flex-column">
					<article class="iddi-courses__why-join-card"> 
						<div class="iddi-courses__why-join-card-info right-text"> 
							<h4 class="iddi-courses__why-join-card-tag fs-32 fw-600 text-color-oxford-blue">
								Hundreds of <span class="text-color-flame-orange">Online Tutorials & Courses</span>
							</h4>
							<p class="iddi-courses__why-join-card-desc fs-32 fw-300 margin-2xs__t text-color-oxford-blue">High-quality video, activities, lessons, and quizzes. Enjoy flexibility with a variety of payment options</p>
						</div>
					</article>
					<article class="iddi-courses__why-join-card"> 
						<div class="iddi-courses__why-join-card-info right-text"> 
							<h4 class="iddi-courses__why-join-card-tag fs-32 fw-600 text-color-oxford-blue">
								Free or Full <span class="text-color-flame-orange">IDDI Membershop Options</span>
							</h4>
							<p class="iddi-courses__why-join-card-desc fs-32 fw-300 margin-2xs__t text-color-oxford-blue">A VARIETY OF MEMBERSHIP LEVELS INCLUDING FREE CONFERENCES, COURSES AND MORE. ALL WITH CUSTOM CERTIFICATE</p>
						</div>
					</article>
					<div class="iddi-courses__why-join-card-media right-text"> <img class="cover-image radius-l" src="/wp-content/uploads/2026/02/digital-scan.webp" alt="Digital Scan">
					</div>
				</div>

				<div class="iddi-courses__why-join-line-col"></div>

				<div class="iddi-courses__why-join-col iddi-courses__why-join-col--right d-flex flex-column">
					<div class="iddi-courses__why-join-card-media"> <img class="cover-image radius-l" src="/wp-content/uploads/2026/02/classroom.webp" alt="Classroom"></div>
					<article class="iddi-courses__why-join-card"> 
						<div class="iddi-courses__why-join-card-info"> 
							<h4 class="iddi-courses__why-join-card-tag fs-32 fw-600 text-color-oxford-blue">
								MSc <span class="text-color-flame-orange">Specialist Practice of Digital Dentistry</span>
							</h4>
							<p class="iddi-courses__why-join-card-desc fs-32 fw-300 margin-2xs__t text-color-oxford-blue">The MSc Specialist Practice of Digital Dentistry explores diagnostic and treatment planning to prosthesis fabrication. </p>
						</div>
					</article>
					<article class="iddi-courses__why-join-card"> 
						<div class="iddi-courses__why-join-card-info"> 
							<h4 class="iddi-courses__why-join-card-tag fs-32 fw-600 text-color-oxford-blue">
								Discuss with others <span class="text-color-flame-orange">and Learn</span>
							</h4>
							<p class="iddi-courses__why-join-card-desc fs-32 fw-300 margin-2xs__t text-color-oxford-blue">Participate in our online forum, share thoughts and ideas, increase connection, get help with your studies.</p>
						</div>
					</article>
				</div>
			</div>
		</div>
	</section>

	<section class="iddi-courses__online-tutorial-courses">
		<div class="iddi__container">

			<div class="iddi-courses__online-tutorial-courses-header">
				<h2 class="iddi-courses__online-tutorial-courses-title fs-56 fw-300 italic-font text-color-oxford-blue">
					Online <span class="fw-400 text-color-flame-orange">Tutorial Courses</span> Included <br> 
					with <span class="fw-400 text-color-flame-orange">IDDI Membership</span>
				</h2>
			</div>

			<div class="iddi-courses__online-tutorial-courses-filter d-flex flex-column flex-ai-center">

				<div class="iddi-courses__online-tutorial-courses-search">
					<div class="search-bar position-relative">
						<form role="search" method="get" id="iddi-ajax-search-form" class="search-form d-flex flex-ai-center p-relative radius-full hidden-element">
							<input type="search" 
								   id="iddi-ajax-search-input"
								   class="iddi-courses__online-tutorial-courses-search-input full-width no-bg-color no-border no-outline fs-20 padding-l text-color-oxford-blue" 
								   placeholder="Search for courses" 
								   value="<?php echo trim(get_search_query()); ?>" 
								   name="s">

							<button type="submit" class="iddi-courses__online-tutorial-courses-search-submit no-bg-color no-border no-outline p-absolute padding-l">
								<i class="icon-search">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="11" cy="11" r="8"></circle>
										<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
									</svg>
								</i>
							</button>
						</form>
					</div>
				</div>

				<div class="iddi-courses__online-tutorial-courses-filter-tabs margin-1xl__t">
					<div class="filter-tabs d-flex flex-jc-center flex-ai-center">
						<ul class="filter-list d-flex flex-wrap flex-ai-center gap-xl fs-20 fw-400" id="iddi-ajax-filter-list">
							<?php 
							$tabs = [
								'all'          => 'TẤT CẢ',
								'not-enrolled' => 'CHƯA ĐĂNG KÝ',
								'newest'       => 'MỚI NHẤT',
								'popular'      => 'PHỔ BIẾN',
								'free'         => 'MIỄN PHÍ'
							];
							foreach ($tabs as $key => $label) : ?>
							<li class="filter-item <?php echo $key === 'all' ? 'active' : ''; ?>" data-filter="<?php echo $key; ?>">
								<a href="#" class="filter-link text-color-oxford-blue"><?php echo $label; ?></a>
							</li>
							<?php endforeach; ?>

							<li class="filter-item position-relative iddi-author-dropdown-container">
								<a href="#" class="filter-link text-color-oxford-blue d-flex flex-ai-center">
									GIẢNG VIÊN
									<span class="icon-dropdown margin-xs__l">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M6 9l6 6 6-6"/>
										</svg>
									</span>
								</a>

								<ul class="author-dropdown p-absolute bg-color_color-white radius-s shadow-l no-list-style">
									<?php 
									$course_authors = iddi_get_course_authors();
									foreach ($course_authors as $author) : ?>
									<li class="author-item padding-s__v padding-m__h fs-18" data-author-id="<?php echo $author['id']; ?>">
										<a href="#" class="text-color-oxford-blue">
											<?php echo esc_html($author['name']); ?> 
											<span class="text-color-flame-orange">(<?php echo $author['count']; ?>)</span>
										</a>
									</li>
									<?php endforeach; ?>
								</ul>
							</li>

							<style>
								.iddi-author-dropdown-container { cursor: pointer; }
								.author-dropdown { 
									display: none; top: 100%; left: 0; min-width: 200px; z-index: 10;
									border: 1px solid #eee; margin-top: 10px;
								}
								.iddi-author-dropdown-container:hover .author-dropdown { display: block; }
								.author-item:hover { background-color: #f9f9f9; }
								.author-item.active a { color: #ff6600; font-weight: 600; }
							</style>
						</ul>
					</div>
				</div>	
			</div>

			<div id="iddi-ajax-course-result" class="margin-2xl__t">
				<div class="iddi-courses__online-tutorial-courses-list d-grid g-column-3">
					<?php
					$args = array(
						'post_type'      => 'lp_course',
						'posts_per_page' => 9,
						'post_status'    => 'publish',
					);
					$course_query = new WP_Query($args);

					if ($course_query->have_posts()) : 
					<?php get_template_part('learnpress/content-course'); ?>
					<?php endwhile; wp_reset_postdata(); 
					endif; ?>
				</div>
			</div>

			<div class="iddi-courses__online-tutorial-courses-action right-text">
				<a href="<?php echo get_post_type_archive_link('lp_course'); ?>" class="iddi-courses__online-tutorial-courses-btn fs-32 fw-300 italic-font d-i-flex flex-ai-center text-color-oxford-blue">
					<span>All Online Courses</span>
					<?php echo get_my_svg('explore'); ?>
				</a> 
			</div>
		</div>
	</section>

	<section class="iddi-courses__table-price d-none"> 
		<div class="iddi__container">
			<?php get_template_part('template-parts/sections/section-table-price'); ?>
		</div>
	</section>

	<?php 
	/**
 * Lấy dữ liệu từ Options Page
 * Đảm bảo các Field Name trong ACF khớp hoàn toàn với các biến dưới đây
 */
	$t_subtitle  = get_field('testimonials__subtitle', 'option');
	$t_maintitle = get_field('testimonials__maintitle', 'option');
	$t_video_grp = get_field('testimonials__video', 'option'); // Group field
	?>

	<section class="iddi-section-testimomials">
		<div class="iddi__container">

			<div class="iddi-section-testimomials__header center-text italic-font">
				<?php if ($t_subtitle): ?>
				<span class="iddi-section-testimomials__label fs-32 fw-500 text-color-flame-orange">
					<?php echo esc_html($t_subtitle); ?>
				</span>
				<?php endif; ?>

				<?php if ($t_maintitle): ?>
				<h2 class="iddi-section-testimomials__title fs-56 fw-300 text-color-oxford-blue">
					<?php echo nl2br(esc_html($t_maintitle)); ?>
				</h2>
				<?php endif; ?>
			</div>

			<?php 
			if (!empty($t_video_grp['testimonials__video'])): 
			$poster_url = $t_video_grp['testimonials__background_image_video'] ?? '';
			$video_url  = $t_video_grp['testimonials__video'];
			?>
			<div class="video-wrapper p-relative iddi-section-testimomials__video-featured">
				<video class="video-element cover-image radius-xl iddi-section-testimomials__video-featured-video" 
					   width="1330" height="759" loop 
					   poster="<?php echo esc_url($poster_url); ?>">
					<source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
				</video>
				<button class="btn-play reset-button p-absolute iddi-section-testimomials__video-featured-button"><?php echo get_my_svg('play'); ?></button>
				<button class="btn-pause reset-button p-absolute iddi-section-testimomials__video-featured-button"><?php echo get_my_svg('pause'); ?></button>
			</div>
			<?php endif; ?>

			<div class="iddi-section-testimomials__grid d-flex flex-jc-between">

				<div class="iddi-section-testimomials__column d-flex flex-column">
					<?php 
					for ($i = 1; $i <= 2; $i++): 
					$member = get_field("testimonials__member_review_$i", 'option'); // Group field cho từng member
					if ($member && !empty($member['testimonials__member_review_name'])): 
					?>
					<article class="iddi-section-testimomials__card d-flex flex-ai-center gap-xl">
						<div class="iddi-section-testimomials__card-content d-flex flex-column flex-ai-end gap-s right-text">
							<h3 class="iddi-section-testimomials__name fs-24 fw-700 text-color-oxford-blue">
								<?php echo esc_html($member['testimonials__member_review_name']); ?>
							</h3>
							<span class="iddi-section-testimomials__country fs-20 fw-700 text-color-flame-orange">
								<?php echo esc_html($member['testimonials__member_review_country']); ?>
							</span>
							<div class="iddi-section-testimomials__quote fs-24 fw-400 text-color-oxford-blue">
								<?php echo esc_html($member['testimonials__member_review_content_review']); ?>
							</div>
						</div>
						<div class="iddi-section-testimomials__card-avatar">
							<img class="radius-s" 
								 src="<?php echo esc_url($member['testimonials__member_review_avatar']); ?>" 
								 alt="<?php echo esc_attr($member['testimonials__member_review_name']); ?>">
						</div>
					</article>
					<?php endif; endfor; ?>
				</div>

				<div class="iddi-section-testimomials__line-column"></div>

				<div class="iddi-section-testimomials__column d-flex flex-column">
					<?php 
					for ($i = 3; $i <= 4; $i++): 
					$member = get_field("testimonials__member_review_$i", 'option'); 
					if ($member && !empty($member['testimonials__member_review_name'])): 
					?>
					<article class="iddi-section-testimomials__card d-flex flex-ai-center gap-xl">
						<div class="iddi-section-testimomials__card-avatar">
							<img class="radius-s" 
								 src="<?php echo esc_url($member['testimonials__member_review_avatar']); ?>" 
								 alt="<?php echo esc_attr($member['testimonials__member_review_name']); ?>">
						</div>
						<div class="iddi-section-testimomials__card-content d-flex flex-column flex-ai-start gap-s left-text">
							<h3 class="iddi-section-testimomials__name fs-24 fw-700 text-color-oxford-blue">
								<?php echo esc_html($member['testimonials__member_review_name']); ?>
							</h3>
							<span class="iddi-section-testimomials__country fs-20 fw-700 text-color-flame-orange">
								<?php echo esc_html($member['testimonials__member_review_country']); ?>
							</span>
							<div class="iddi-section-testimomials__quote fs-24 fw-400 text-color-oxford-blue">
								<?php echo esc_html($member['testimonials__member_review_content_review']); ?>
							</div>
						</div>
					</article>
					<?php endif; endfor; ?>
				</div>

			</div>
		</div>
	</section>

	<section class="iddi-event-detail__related-events">
		<div class="iddi__container">

			<div class="iddi-event-detail__related-events-header">
				<h2 class="iddi-event-detail__related-events-title fs-48 fw-300 italic-font fs-32__xl fs-24__lg fs-20__sm"><span class="fw-400 text-color-flame-orange">2026</span> - DIGITAL DENTISTRY EVENTS</h2>
			</div>

			<?php
			// 1. Cấu hình Query để lấy post type 'event'
			$args = array(
				'post_type'      => 'event',      // Tên Post Type của bạn
				'posts_per_page' => 2,            // Số lượng bài viết muốn hiển thị
				'post_status'    => 'publish',    // Chỉ lấy bài đã đăng
			);

			$event_query = new WP_Query($args);

			// 2. Kiểm tra nếu có bài viết
			if ($event_query->have_posts()) : ?>
			<div class="iddi-event-detail__related-events-list d-grid g-column-2 g-column-1__sm">

				<?php while ($event_query->have_posts()) : $event_query->the_post(); 
				// Lấy dữ liệu ACF
				$price = get_field('price_event'); // {ACF price_event}
				$date_raw = get_field('date_and_time_event'); // {ACF date_and_time_event}

				// Xử lý định dạng ngày: 27th February 9:00 AM
				// Giả định $date_raw trả về định dạng Y-m-d H:i:s hoặc timestamp từ ACF
				$formatted_date = $date_raw ? date_i18n('j/n/Y g:i A', strtotime($date_raw)) : '';
				?>

				<article class="iddi-event-detail__related-events-card padding-xl d-flex flex-column gap-l radius-l bg-color_color-white gap-m__xl padding-s__xl padding-xs__lg gap-s__lg padding-12__md">

					<div class="iddi-event-detail__related-events-thumb">
						<?php if (has_post_thumbnail()) : ?>
						<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" 
							 alt="<?php echo esc_attr(get_the_title()); ?>" 
							 title="<?php echo esc_attr(get_the_title()); ?>"
							 class="radius-s cover-image">
						<?php endif; ?>
					</div>

					<h3 class="iddi-event-detail__related-events-name fs-32 fw-400 text-color-oxford-blue fs-24__xl fs-20__lg fs-16__md">
						<a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
					</h3>

					<div class="iddi-event-detail__related-events-excerpt fs-24 fw-300 text-color-oxford-blue fs-16__xl fs-14__lg fs-12__md">
						<?php 
						// Lấy nội dung bài viết và giới hạn ký tự để làm excerpt nếu cần
						echo wp_trim_words(get_the_content(), 30, '...'); 
						?>
					</div>

					<div class="d-flex flex-jc-between flex-ai-end">
						<div class="iddi-event-detail__related-events-meta d-flex flex-column gap-m">
							<span class="price d-block fs-32 fw-700 text-color-flame-orange fs-20__xl fs-18__lg fs-16__md">
								<?php echo esc_html($price); ?>
							</span>

							<?php if ($date_raw) : ?>
							<span class="date fs-20 fw-300 text-color-flame-orange fs-16__xl fs-14__lg fs-12__md">
								<?php echo esc_html($date_raw); ?>
							</span>
							<?php endif; ?>
						</div>

						<?php 
						// 1. Lấy URL từ ACF
						$custom_ticket_url = get_field('url_get_your_ticket'); 

						// 2. Kiểm tra: nếu có ACF thì dùng ACF, không thì dùng permalink của bài viết
						$final_enrol_url = $custom_ticket_url ? esc_url($custom_ticket_url) : get_permalink();
						?>

						<a href="<?php echo $final_enrol_url; ?>" 
						   class="iddi-event-detail__related-events-enrol fs-24 fw-600 d-i-block center-text bg-color_color-flame-orange padding-s__v radius-s text-color-white fs-16__xl fs-14__md">
							Đăng ký ngay
						</a>
					</div>

				</article>

				<?php endwhile; wp_reset_postdata(); ?>

			</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="iddi-event-detail__contact">
		<div class="iddi__container">
			<?php get_template_part('template-parts/sections/section-contact'); ?>
		</div>
	</section>
</main>


<?php get_footer(); ?>