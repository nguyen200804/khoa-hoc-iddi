<?php
/**
 * Section: Testimonials
 */
enqueue_section_assets('section-testimonials', true); 

$t_subtitle  = get_field('testimonials__subtitle', 'option');
$t_maintitle = get_field('testimonials__maintitle', 'option');
$t_video_grp = get_field('testimonials__video', 'option'); 
$default_img = '/wp-content/uploads/2026/03/default-image.jpg';
?>

<section class="iddi-section-testimomials">
	<div class="iddi__container">
		<div class="iddi-section-testimomials__header center-text italic-font">
			<?php if ($t_subtitle): ?>
			<span class="iddi-section-testimomials__label fs-32 fw-500 text-color-flame-orange fs-24__xl fs-20__lg fs-18__md fs-16__sm">
				<?php echo esc_html($t_subtitle); ?>
			</span>
			<?php endif; ?>

			<?php if ($t_maintitle): ?>
			<h2 class="iddi-section-testimomials__title fs-56 fw-300 text-color-oxford-blue fs-36__xl fs-32__lg fs-28__md fs-24__sm">
				<?php echo nl2br(esc_html($t_maintitle)); ?>
			</h2>
			<?php endif; ?>
		</div>

		<?php 
		if (!empty($t_video_grp['testimonials__video'])): 
			// Xử lý lấy Poster: Nếu trống thì lấy ảnh mặc định
			$raw_poster = $t_video_grp['testimonials__background_image_video'] ?? '';
			$poster_url = !empty($raw_poster) ? (is_array($raw_poster) ? $raw_poster['url'] : $raw_poster) : $default_img;
			
			// Xử lý lấy Video URL
			$video_data = $t_video_grp['testimonials__video'];
			$video_url  = is_array($video_data) ? $video_data['url'] : $video_data;
		?>
		<div class="video-wrapper p-relative iddi-section-testimomials__video-featured">
			<video class="video-element cover-image radius-xl iddi-section-testimomials__video-featured-video" 
				   width="1330"  loop 
				   poster="<?php echo esc_url($poster_url); ?>">
				<source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
			</video>
			<button class="btn-play reset-button p-absolute iddi-section-testimomials__video-featured-button"><?php echo function_exists('get_my_svg') ? get_my_svg('play') : ''; ?></button>
			<button class="btn-pause reset-button p-absolute iddi-section-testimomials__video-featured-button"><?php echo function_exists('get_my_svg') ? get_my_svg('pause') : ''; ?></button>
		</div>
		<?php endif; ?>

		<div class="iddi-section-testimomials__grid d-flex flex-jc-between flex-column__md">
			<div class="iddi-section-testimomials__column d-flex flex-column">
				<?php 
				for ($i = 1; $i <= 2; $i++): 
				$member = get_field("testimonials__member_review_$i", 'option'); 
				if ($member && !empty($member['testimonials__member_review_name'])): 
					$avatar = $member['testimonials__member_review_avatar'];
					$avatar_url = is_array($avatar) ? $avatar['url'] : $avatar;
				?>
				<article class="iddi-section-testimomials__card d-flex flex-ai-center gap-xl">
					<div class="iddi-section-testimomials__card-content d-flex flex-column flex-ai-end gap-s right-text">
						<h3 class="iddi-section-testimomials__name fs-24 fw-700 text-color-oxford-blue fs-16__xl">
							<?php echo esc_html($member['testimonials__member_review_name']); ?>
						</h3>
						<span class="iddi-section-testimomials__country fs-20 fw-700 text-color-flame-orange fs-14__xl">
							<?php echo esc_html($member['testimonials__member_review_country']); ?>
						</span>
						<div class="iddi-section-testimomials__quote fs-24 fw-400 text-color-oxford-blue fs-14__xl">
							<?php echo esc_html($member['testimonials__member_review_content_review']); ?>
						</div>
					</div>
					<div class="iddi-section-testimomials__card-avatar">
						<img class="radius-s" 
							 src="<?php echo esc_url($avatar_url); ?>" 
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
					$avatar = $member['testimonials__member_review_avatar'];
					$avatar_url = is_array($avatar) ? $avatar['url'] : $avatar;
				?>
				<article class="iddi-section-testimomials__card d-flex flex-ai-center gap-xl">
					<div class="iddi-section-testimomials__card-avatar">
						<img class="radius-s" 
							 src="<?php echo esc_url($avatar_url); ?>" 
							 alt="<?php echo esc_attr($member['testimonials__member_review_name']); ?>">
					</div>
					<div class="iddi-section-testimomials__card-content d-flex flex-column flex-ai-start gap-s left-text">
						<h3 class="iddi-section-testimomials__name fs-24 fw-700 text-color-oxford-blue fs-16__xl">
							<?php echo esc_html($member['testimonials__member_review_name']); ?>
						</h3>
						<span class="iddi-section-testimomials__country fs-20 fw-700 text-color-flame-orange fs-14__xl">
							<?php echo esc_html($member['testimonials__member_review_country']); ?>
						</span>
						<div class="iddi-section-testimomials__quote fs-24 fw-400 text-color-oxford-blue fs-14__xl">
							<?php echo esc_html($member['testimonials__member_review_content_review']); ?>
						</div>
					</div>
				</article>
				<?php endif; endfor; ?>
			</div>
		</div>
	</div>
</section>