<?php
/**
 * Template Name: About Us Page
 */
get_header(); ?>

<div class='about-us-wrapper'>
	<section class="iddi-about__intro">
		<div class="iddi__container d-flex flex-ai-center flex-column__md">

			<div class="iddi-about__intro__content">
				<h2 class="iddi-about__intro__title fs-56 fw-400 italic-font text-color-flame-orange fs-36__xl fs-32__lg fs-28__sm">
					<?php the_field('intro_title'); ?>
				</h2>

				<div class="iddi-about__intro__description fs-18 text-color-oxford-blue fs-13__xl fs-12__lg">
					<?php the_field('intro_description'); ?>
				</div>

				<h4 class="iddi-about__intro__brand fs-24 fw-400 text-color-oxford-blue fs-16__xl fs-14__lg">
					<?php the_field('intro_brand_name'); ?>
				</h4>
				<span class="iddi-about__intro__team-label fs-14 fw-400 d-block text-color-oxford-blue fs-12__xl fs-11__xl">TEAM</span>

				<?php 
				// Lấy giá trị từ 2 trường ACF riêng biệt
				$btn_link = get_field('intro_button_link');
				$btn_text = get_field('intro_button_text');

				if ( $btn_link && $btn_text ) : ?>
				<a href="<?php echo esc_url($btn_link); ?>" class="iddi-about__intro__button fs-24 fw-600 text-color-white bg-color_color-flame-orange d-i-block fs-16__xl fs-14__lg">
					<?php echo esc_html($btn_text); ?>
				</a>
				<?php endif; ?>
			</div>

			<div class="iddi-about__intro__media">
				<div class="iddi-about__intro__video-wrapper video-wrapper p-relative">
					<?php 
					// Lấy dữ liệu từ ACF
					$video_src = get_field('intro_video_file'); // Trường loại File hoặc URL (mp4)
					$video_bg  = get_field('intro_video_poster'); // Trường loại Image (URL)
					?>

					<video class="iddi-about__intro__video-element video-element" 
						   loop 
						   poster="<?php echo esc_url($video_bg); ?>">
						<source src="<?php echo esc_url($video_src); ?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>

					<button class="iddi-about__intro__btn-play btn-play reset-button p-absolute">
						<?php echo get_my_svg('play'); ?>
					</button>

					<button class="iddi-about__intro__btn-pause btn-pause reset-button p-absolute">
						<?php echo get_my_svg('pause'); ?>
					</button>
				</div>
			</div>

		</div>
	</section>



	<section class="iddi-about__awards bg-oxford-blue p-relative d-none">
		<svg class="iddi-about__awards-wave p-absolute" width="1920"  viewBox="0 0 1920 103" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 36.4251C0 36.4251 310.486 163.666 984.749 53.7822C1659.01 -56.1013 1920 36.4251 1920 36.4251V103H0V36.4251Z" fill="#0B1226"/>
		</svg>

		<div class="iddi__container">

			<div class="iddi-about__awards__trophy p-relative d-flex flex-ai-center flex-jc-center">

				<div class="iddi-about__awards__trophy-icon">
					<img class="iddi-about__awards__trophy-icon-left" src="/wp-content/uploads/2026/03/la.svg" alt="Wreath">
				</div>



				<div class="iddi-about__awards__trophy-content center-text">
					<span class="iddi-about__awards__trophy-label fs-24 fw-400 fs-16__xl">
						<?php the_field('awards_trophy_label'); // Official Selection ?>
					</span>

					<h2 class="iddi-about__awards__trophy-title fs-48 fw-700 fs-32__xl">
						<?php the_field('awards_trophy_title'); // THE 2021 IDDI AWARDS ?>
					</h2>

					<div class="iddi-about__awards__trophy-year fs-24 fw-400 fs-16__xl">
						<?php the_field('awards_trophy_year'); // 2021 ?>
					</div>
				</div>

				<div class="iddi-about__awards__trophy-icon">
					<img class="iddi-about__awards__trophy-icon  iddi-about__awards__trophy-icon-right" src="/wp-content/uploads/2026/03/la-2.svg" alt="Wreath">
				</div>


			</div>

			<?php if( have_rows('awards_badges_list') ): ?>
			<div class="iddi-about__awards__badges-list d-flex flex-jc-center p-relative d-grid__md g-column-3__md">
				<?php while( have_rows('awards_badges_list') ): the_row(); 
				$badge_text = get_sub_field('badge_text');
				?>
				<div class="iddi-about__awards__badge-item">
					<div class="iddi-about__awards__badge-border-2">
						<div class="iddi-about__awards__badge-border-1">

							<div class="iddi-about__awards__badge-circle">

								<div class="iddi-about__awards__badge-content bg-color_color-oxford-blue">
									<?php 
									$badge_label = get_sub_field('badge_label'); // Ví dụ: "BEST"
									$badge_title = get_sub_field('badge_title'); // Ví dụ: "CLINICAL DIGITAL CASE"
									?>

									<?php if ($badge_label) : ?>
									<span class="iddi-about__awards__badge-label fs-14 fw-300 upper-text fs-10__xl fs-9__lg">
										<?php echo esc_html($badge_label); ?>
									</span>
									<?php endif; ?>

									<?php if ($badge_title) : ?>
									<h4 class="iddi-about__awards__badge-title fs-16 fw-700 upper-text fs-11__xl fs-10__lg">
										<?php echo nl2br(esc_html($badge_title)); ?>
									</h4>
									<?php endif; ?>
								</div>

							</div>
						</div>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>

			<div class="iddi-about__awards__info-wrapper txt-center text-color-white">
				<div class="iddi-about__awards__info-text fs-18 fs-14__xl fs-13__lg">
					<?php the_field('awards_description'); ?>
				</div>
			</div>

		</div>
	</section>



	<section class="iddi-about__future">
		<div class="iddi__container iddi-about__future__container">

			<div class="iddi-about__future__header d-flex flex-column__md">
				<h2 class="iddi-about__future__title fs-56 fw-400 italic-font text-color-flame-orange fs-36__xl fs-32__lg fs-28__sm">
					<?php the_field('future_title'); ?>
				</h2>
				<div class="iddi-about__future__subtitle fs-18 text-color-oxford-blue fs-14__xl fs-12__lg">
					<?php the_field('future_subtitle'); ?>
				</div>
			</div>

			<div class="iddi-about__future__content-wrapper d-flex flex-column__md">

				<div class="iddi-about__future__column">
					<?php if( have_rows('future_left_items') ): ?>
					<?php while( have_rows('future_left_items') ): the_row(); ?>
					<div class="iddi-about__future__item">
						<h3 class="iddi-about__future__item-title fs-24 fw-600 text-color-flame-orange fs-16__xl fs-14__lg">
							<?php the_sub_field('item_title'); ?>
						</h3>
						<p class="iddi-about__future__item-desc fs-14 upper-text text-color-oxford-blue fs-12__xl fs-11__lg">
							<?php the_sub_field('item_description'); ?>
						</p>
					</div>
					<?php endwhile; ?>
					<?php endif; ?>
				</div>

				<div class="iddi-about__future__divider bg-color_color-flame-orange"></div>

				<div class="iddi-about__future__column">
					<?php if( have_rows('future_right_items') ): ?>
					<?php while( have_rows('future_right_items') ): the_row(); ?>
					<div class="iddi-about__future__item">
						<h3 class="iddi-about__future__item-title fs-24 fw-600 text-color-flame-orange fs-16__xl fs-14__lg">
							<?php the_sub_field('item_title'); ?>
						</h3>
						<p class="iddi-about__future__item-desc fs-14 upper-text text-color-oxford-blue fs-12__xl fs-11__lg">
							<?php the_sub_field('item_description'); ?>
						</p>
					</div>
					<?php endwhile; ?>
					<?php endif; ?>
				</div>

			</div>

		</div>
	</section>


	<section class="iddi-about__team d-none">
		<div class="iddi-about__team__container iddi__container">

			<header class="iddi-about__team__header">
				<h2 class="iddi-about__team__title fs-56 fw-400 italic-font text-color-flame-orange center-text fs-36__xl fs-32__lg fs-28__sm">
					<?php the_field('team_title'); ?>
				</h2>
				<p class="iddi-about__team__subtitle fs-20 fw-600 center-text upper-text fs-13__xl fs-12__lg">
					<?php the_field('team_subtitle'); ?>
				</p>
			</header>

			<?php if( have_rows('team_members_list') ): ?>
			<div class="iddi-about__team__grid d-grid g-column-4 g-column-2__md">
				<?php while( have_rows('team_members_list') ): the_row(); 
				$photo = get_sub_field('member_photo');
				?>
				<article class="iddi-about__team__item">
					<div class="iddi-about__team__thumbnail d-flex">
						<img src="<?php echo esc_url($photo); ?>" alt="<?php the_sub_field('member_last_name'); ?>">
					</div>

					<div class="iddi-about__team__content">
						<span class="iddi-about__team__first-name fs-14 fw-700 upper-text text-color-flame-orange fs-10__xl">
							<?php the_sub_field('member_first_name'); ?>
						</span>
						<h3 class="iddi-about__team__last-name fs-40 fw-700 text-color-oxford-blue fs-28__xl fs-24__lg">
							<?php the_sub_field('member_last_name'); ?>
						</h3>
						<p class="iddi-about__team__position fs-20 fw-400 text-color-flame-orange fs-13__xl fs-12__lg">
							<?php the_sub_field('member_position'); ?>
						</p>

						<?php if( have_rows('member_social_list') ): ?>
						<div class="iddi-about__team__socials d-flex flex-wrap">
							<?php while( have_rows('member_social_list') ): the_row(); 
							$icon_data = get_sub_field('icon_social'); 
							$url       = get_sub_field('url_social');
							?>
							<a href="<?php echo esc_url($url); ?>" class="iddi-about__team__social-link bg-color_color-flame-orange d-flex" target="_blank">
								<?php 
								if ( is_numeric($icon_data) ) {
									// 1. Nếu là ID: Lấy đường dẫn file trên server và in nội dung SVG
									$path = get_attached_file($icon_data);
									if ( $path && file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) === 'svg' ) {
										echo file_get_contents($path);
									} else {
										// Fallback nếu không phải file hoặc không phải SVG
										echo wp_get_attachment_image($icon_data, 'full');
									}
								} 
								elseif ( strpos($icon_data, 'http') !== false ) {
									// 2. Nếu là URL: Chuyển URL thành Path để đọc file (Tránh dùng file_get_contents với URL vì chậm/bị chặn)
									$path = str_replace(site_url('/'), ABSPATH, $icon_data);
									if ( file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) === 'svg' ) {
										echo file_get_contents($path);
									} else {
										echo '<img src="' . esc_url($icon_data) . '" />';
									}
								} 
								elseif ( strpos($icon_data, 'dashicons-') !== false ) {
									// 3. Nếu là Dashicons
									echo '<span class="dashicons ' . esc_attr($icon_data) . '"></span>';
								}
								else {
									// 4. Nếu là slug để dùng hàm get_my_svg của bạn
									echo get_my_svg($icon_data); 
								}
								?>
							</a>
							<?php endwhile; ?>
						</div>
						<?php endif; ?>

						<p class="iddi-about__team__degree fs-14 fw-700 upper-text text-color-oxford-blue fs-11__xl">
							<?php the_sub_field('member_degrees'); ?>
						</p>

						<div class="iddi-about__team__bio fs-14 fw-400 upper-text text-color-oxford-blue fs-11__xl">
							<?php the_sub_field('member_bio'); ?>
						</div>
					</div>
				</article>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>

		</div>
	</section>



	<section class="iddi-about__testimonials">
		<div class="iddi-about__testimonials__container iddi__container">

			<header class="iddi-about__testimonials__header center-text italic-font">
				<span class="iddi-about__testimonials__label fs-32 fw-500 text-color-flame-orange fs-20__xl fs-18__lg"><?php the_field('testimonials_label'); ?></span>
				<h2 class="iddi-about__testimonials__title fs-56 fw-300 text-color-oxford-blue fs-36__xl fs-32__lg fs-28__sm"><?php the_field('testimonials_title'); ?></h2>
			</header>

			<?php if( have_rows('testimonials_top_list') ): ?>
			<div class="iddi-about__testimonials__list iddi-about__testimonials__list--top">
				<?php while( have_rows('testimonials_top_list') ): the_row(); ?>
				<article class="iddi-about__testimonials__item">
					<div class="iddi-about__testimonials__item-inner">
						<div class="iddi-about__testimonials__item-header fs-18 fw-700 text-color-oxford-blue fs-12__xl">
<!-- 							<span class="iddi-about__testimonials__to">to</span> -->
							<span class="iddi-about__testimonials__action text-color-flame-orange"><?php the_sub_field('item_action'); ?></span>
						</div>
						<p class="iddi-about__testimonials__item-text fs-18 fw-400 text-color-oxford-blue fs-12__xl"><?php the_sub_field('item_content'); ?></p>
					</div>
				</article>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>

			<div class="iddi-about__testimonials__video-wrapper video-wrapper p-relative">
				<?php 
				// Lấy dữ liệu từ ACF (Đảm bảo tên trường khớp với bảng tổng hợp trước đó)
				$testimonials_video = get_field('testimonials_video_url'); 
				$testimonials_poster = get_field('testimonials_video_poster'); 
				?>

				<video class="iddi-about__testimonials__video-element video-element cover-image" 
					   loop 
					   poster="<?php echo esc_url($testimonials_poster); ?>">
					<source src="<?php echo esc_url($testimonials_video); ?>" type="video/mp4">
					Your browser does not support the video tag.
				</video>

				<button class="iddi-about__testimonials__btn-play btn-play reset-button p-absolute">
					<?php echo get_my_svg('play'); ?>
				</button>

				<button class="iddi-about__testimonials__btn-pause btn-pause reset-button p-absolute">
					<?php echo get_my_svg('pause'); ?>
				</button>
			</div>

			<?php if( have_rows('testimonials_bottom_list') ): ?>
			<div class="iddi-about__testimonials__list iddi-about__testimonials__list--bottom">
				<?php while( have_rows('testimonials_bottom_list') ): the_row(); ?>
				<article class="iddi-about__testimonials__item right-text">
					<div class="iddi-about__testimonials__item-inner">
						<div class="iddi-about__testimonials__item-header fs-18 fw-700 text-color-oxford-blue fs-12__xl">
<!-- 							<span class="iddi-about__testimonials__to">to</span> -->
							<span class="iddi-about__testimonials__action text-color-flame-orange "><?php the_sub_field('item_action'); ?></span>
						</div>
						<p class="iddi-about__testimonials__item-text fs-18 fw-400 text-color-oxford-blue fs-12__xl"><?php the_sub_field('item_content'); ?></p>
					</div>
				</article>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>

		</div>
	</section>

	<section class="iddi-about__contact">
		<div class="iddi-about__contact__container iddi__container">
			<?php get_template_part('template-parts/sections/section-contact'); ?>
		</div>
	</section>




</div>

<?php get_footer(); ?>