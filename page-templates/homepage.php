<?php
/*
Template Name: Homepage
*/

// Preload ảnh banner đầu tiên với hỗ trợ Responsive Srcset để tối ưu điểm LCP
$image_id = get_post_meta(get_queried_object_id(), 'banner-section__gallery_slider_0_banner-section__gallery_slider__image', true);
$first_banner_url = '';
$srcset = '';
$sizes = '';

if ($image_id) {
	if (is_numeric($image_id)) {
		$first_banner_url = wp_get_attachment_image_url($image_id, 'full');
		$srcset = wp_get_attachment_image_srcset($image_id, 'full');
		$sizes = wp_get_attachment_image_sizes($image_id, 'full');
	} else {
		$first_banner_url = $image_id;
	}
}

add_action('wp_head', function() use ($first_banner_url, $srcset, $sizes) {
	if ($first_banner_url) {
		$preload_tag = '<link rel="preload" fetchpriority="high" as="image" href="' . esc_url($first_banner_url) . '"';
		if ($srcset) {
			$preload_tag .= ' imagesrcset="' . esc_attr($srcset) . '"';
		}
		if ($sizes) {
			$preload_tag .= ' imagesizes="' . esc_attr($sizes) . '"';
		}
		$preload_tag .= '>';
		echo $preload_tag . "\n";
	}

	// Preload phông chữ Work Sans để tránh giật văn bản (FOUT) gây ra CLS
	$theme_uri = get_template_directory_uri();
	$fonts = array(
		'/assets/fonts/work-sans/WorkSans-Regular.woff2',
		'/assets/fonts/work-sans/WorkSans-SemiBold.woff2',
		'/assets/fonts/work-sans/WorkSans-Medium.woff2',
		'/assets/fonts/work-sans/WorkSans-Light.woff2',
	);
	foreach ($fonts as $font) {
		echo '<link rel="preload" href="' . esc_url($theme_uri . $font) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
}, 1);
?>
<?php get_header(); ?>

<main id="iddi-homepage">
	<h1 class="d-none"><?php bloginfo('name'); ?></h1>

	<section class="iddi-homepage__hero swiper iddiHeroSwiper">
		<div class="swiper-wrapper">
			<?php 
			if (have_rows('banner-section__gallery_slider')) : 
			$slide_index = 0;
			while (have_rows('banner-section__gallery_slider')) : the_row(); 
			$image = get_sub_field('banner-section__gallery_slider__image');
			$url = get_sub_field('banner-section__gallery_slider__url');

			$image_url = '';
			if (is_array($image)) {
				$image_url = $image['url'];
			} elseif (is_numeric($image)) {
				$image_url = wp_get_attachment_image_url($image, 'full');
			} else {
				$image_url = $image;
			}
			?>
			<div class="swiper-slide">
				<?php if ($url) : ?>
				<a href="<?php echo esc_url($url); ?>" aria-label="<?php echo esc_attr('Xem chi tiết banner quảng cáo số ' . ($slide_index + 1)); ?>">
					<?php endif; ?>
					<div class="iddi-homepage__hero-media">
						<?php if ($image) : ?>
						<?php 
						$alt_text = '';
						if (is_numeric($image)) {
							$alt_text = get_post_meta($image, '_wp_attachment_image_alt', true);
						} elseif (is_array($image) && !empty($image['alt'])) {
							$alt_text = $image['alt'];
						}
						if (empty($alt_text)) {
							$alt_text = get_bloginfo('name') . ' - Banner slide ' . ($slide_index + 1);
						}
						?>
						<?php if ($slide_index === 0) : ?>
							<?php 
							if (is_numeric($image)) {
								echo wp_get_attachment_image($image, 'full', false, array(
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'decoding'      => 'sync',
									'class'         => 'iddi-homepage__hero-img cover-image',
									'alt'           => $alt_text,
								));
							} else {
								$img_url = is_array($image) ? $image['url'] : $image;
								?>
								<img loading="eager" 
									 fetchpriority="high"
									 decoding="sync"
									 src="<?php echo esc_url($img_url); ?>" 
									 alt="<?php echo esc_attr($alt_text); ?>" 
									 class="iddi-homepage__hero-img cover-image">
								<?php 
							}
							?>
						<?php else : ?>
							<?php 
							if (is_numeric($image)) {
								echo wp_get_attachment_image($image, 'full', false, array(
									'loading'  => 'lazy',
									'decoding' => 'async',
									'class'    => 'iddi-homepage__hero-img cover-image',
									'alt'      => $alt_text,
								));
							} else {
								$img_url = is_array($image) ? $image['url'] : $image;
								?>
								<img loading="lazy" 
									 decoding="async"
									 src="<?php echo esc_url($img_url); ?>" 
									 alt="<?php echo esc_attr($alt_text); ?>" 
									 class="iddi-homepage__hero-img cover-image">
								<?php 
							}
							?>
						<?php endif; ?>
						<?php endif; ?>
					</div>
					<?php if ($url) : ?>
				</a>
				<?php endif; ?>
			</div>
			<?php 
			$slide_index++;
			endwhile; 
			endif; 
			?>
		</div>

		<div class="swiper-pagination"></div>
		<div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001" xml:space="preserve"><path d="M388.819 239.537 156.092 6.816c-9.087-9.089-23.824-9.089-32.912.002-9.087 9.089-9.087 23.824.002 32.912l216.27 216.266-216.273 216.276c-9.087 9.089-9.087 23.824.002 32.912A23.2 23.2 0 0 0 139.636 512a23.2 23.2 0 0 0 16.457-6.817L388.819 272.45a23.27 23.27 0 0 0 0-32.913"/></svg></div>
		<div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" xml:space="preserve"><path d="M172.548 256.005 388.82 39.729c9.089-9.089 9.089-23.824 0-32.912s-23.824-9.089-32.912.002L123.18 239.551a23.26 23.26 0 0 0-6.817 16.454 23.28 23.28 0 0 0 6.817 16.457l232.727 232.721c4.543 4.544 10.499 6.816 16.455 6.816s11.913-2.271 16.457-6.817c9.089-9.089 9.089-23.824 0-32.912z"/></svg></div>
	</section>



	<section class="iddi-homepage__values">
		<div class="iddi__container hidden-element">
			<div class="iddi-homepage__values-header d-flex flex-jc-between flex-ai-center">
				<div class="iddi-homepage__number d-flex flex-ai-end "> 
					<span class="iddi-homepage__number-number d-i-block fw-400"><span>01</span></span> 
					<h2 class="iddi-homepage__number-text line-h-120 italic-font fw-300 text-color-oxford-blue "><?php echo nl2br(esc_html(get_field('core_value_subtitle'))); ?></h2>
				</div>

				<?php 
				$button_group = get_field('core_value_button');
				if( $button_group && !empty($button_group['core_value_button_text']) ): 
				$btn_url = $button_group['core_value_button_url'];
				$btn_text = $button_group['core_value_button_text'];
				?>
				<div class="iddi-homepage__values-action"> 
					<a href="<?php echo esc_url($btn_url); ?>" class="iddi-homepage__values-link fs-32 fw-300 italic-font d-flex flex-center-v text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm" aria-label="<?php echo esc_attr($btn_text . ' về giá trị cốt lõi'); ?>">
						<span><?php echo esc_html($btn_text); ?></span>
						<?php echo get_my_svg('explore'); ?>
					</a>
				</div>
				<?php endif; ?>
			</div>

			<div class="iddi-homepage__values-main">
				<div class="iddi-homepage__values-title fs-48 fw-600 italic-font text-color-oxford-blue fs-32__xl fs-24__lg fs-20__sm text-uppercase">
					<?php echo nl2br(wp_kses_post(get_field('core_value_main_title'))); ?>
				</div>

				<div class="iddi-homepage__values-grid d-grid g-column-4 g-column-2__md">
					<?php 
					for ($i = 1; $i <= 4; $i++) : 
					$group_name = 'core_value_' . $i;
					$value_data = get_field($group_name);

					if ($value_data) :
					$title   = $value_data['title'];
					$content = $value_data['content'];
					$image   = $value_data['anh_sau'];
					$image_url = '';
					if (is_array($image)) {
						$image_url = $image['url'];
					} elseif (is_numeric($image)) {
						$image_url = wp_get_attachment_image_url($image, 'full');
					} else {
						$image_url = $image;
					}
					?>
					<article class="iddi-homepage__value-card">
						<div class="iddi-homepage__value-card-inner">
							<div class="iddi-homepage__value-card-info "> 
								<h3 class="iddi-homepage__value-card-title fs-32 fw-600 text-color-flame-orange fs-20__xl fs-16__lg">
									<?php echo esc_html($title); ?>
								</h3>
								<p class="iddi-homepage__value-card-desc fs-20 fw-300 text-color-oxford-blue fs-16__xl fs-14__lg">
									<?php echo esc_html($content); ?>
								</p>
								<?php if ($image_url) : ?>
								<?php if ($i === 1) : ?>
								<img loading="eager" fetchpriority="high" decoding="sync" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="iddi-homepage__value-card-img cover-image full-width">
								<?php else : ?>
								<img loading="lazy" decoding="async" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="iddi-homepage__value-card-img cover-image full-width">
								<?php endif; ?>
								<?php endif; ?>
							</div>

						</div>
					</article>
					<?php endif; endfor; ?>
				</div>
			</div>

			<div class="iddi-homepage__values-footer">
				<div class="iddi-homepage__values-showcase d-flex flex-jc-between flex-ai-center flex-column__md">
					<div class="iddi-homepage__values-showcase-media">
						<div class="iddi-homepage__values-media-wrapper"> 
							<div class="iddi-homepage__values-implant-img-tooth-technology p-relative">
								<div class="iddi-homepage__values-implant-img-tooth-technology-group p-absolute">
									<img loading="lazy" decoding="async" class="iddi-homepage__values-implant-img-tooth-technology-img" src="/wp-content/uploads/2026/03/tooth-e1774579712536.png" width="500" height="500" alt="Mô hình răng công nghệ Implant">
								</div>
								<span class="iddi-homepage__values-label _1 fs-18 fw-300 d-i-block text-color-oxford-blue p-absolute fs-11__md fs-9__sm"><span class="fw-400">Tiêu chuẩn đào tạo </span> <br>Quốc Tế</span>
								<span class="iddi-homepage__values-label _2 fs-18 fw-300 d-i-block text-color-oxford-blue p-absolute fs-11__md fs-9__sm"><span class="fw-400">Đào tạo chuyên nghiệp từ </span><br>Giáo sư đầu ngành</span>
								<span class="iddi-homepage__values-label _3 fs-18 fw-300 d-i-block text-color-oxford-blue p-absolute fs-11__md fs-9__sm"><span class="fw-400">Dựa trên bằng chứng </span> <br>khoa học</span>
								<span class="iddi-homepage__values-label _4 fs-18 fw-300 d-i-block text-color-oxford-blue p-absolute fs-11__md fs-9__sm"><span class="fw-400">Tiên phong công nghệ trong </span><br>chẩn đoán và điều trị</span>
							</div>
						</div>
					</div>
					<div class="iddi-homepage__values-showcase-content">
						<?php 
						$core_value_content = get_field('core_value_content'); 
						if ( $core_value_content ) : ?>
						<div class="iddi-homepage__values-description line-h-120 right-text fs-40 fw-300 italic-font text-color-oxford-blue fs-20__xl fs-18__lg left-text__md fs-16__md fs-14__sm">
							<?php echo wp_kses_post($core_value_content); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="iddi-homepage__education">
		<div class="iddi__container">
			<div class="iddi-homepage__education-header">
				<div class="iddi-homepage__number center-text d-flex flex-column"> 
					<span class="iddi-homepage__number-number iddi-homepage__education-number iddi-homepage__heading-number d-i-block fw-400"><span>02</span></span>
					<h2 class="iddi-homepage__number-text iddi-homepage__education-heading fw-300 italic-font text-color-oxford-blue ">
						<?php the_field('education_&_training_subtitle'); ?>
					</h2>
				</div>
			</div>

			<div class="iddi-homepage__education-grid d-grid g-column-3 g-column-1__sm">
				<?php 
				for ($i = 1; $i <= 3; $i++) : 
				$group_name = 'education_&_training_' . $i;
				$edu_data = get_field($group_name);
				if ($edu_data) :
				$title   = $edu_data['title'];
				$content = $edu_data['content'];
				?>
				<article class="iddi-homepage__education-card padding-xl radius-xl padding-s__xl padding-1xs__lg">
					<h3 class="iddi-homepage__education-card-title fs-32 fw-600 text-color-flame-orange fs-20__xl fs-16__lg"><?php echo esc_html($title); ?></h3> 
					<p class="iddi-homepage__education-card-desc fs-24 fw-300 text-color-oxford-blue line-h-150 fs-16__xl fs-14__lg"><?php echo nl2br(esc_html($content)); ?></p> 
				</article>
				<?php endif; endfor; ?>
			</div>

			<div class="iddi-homepage__education-stats">
				<?php 
				for ($i = 1; $i <= 3; $i++) : 
				$group_name = 'the_number_achieved_' . $i;
				$stats_group = get_field($group_name);
				if ($stats_group) : 
				$count       = $stats_group['count'];
				$label       = $stats_group['label'];
				$description = $stats_group['description'];
				?>
				<div class="iddi-homepage__education-stats-row d-flex flex-jc-between flex-ai-center flex-column__sm flex-ai-start__sm"> 
					<div class="iddi-homepage__education-stats-item"> 
						<span class="iddi-homepage__education-stats-number fw-300 text-color-flame-orange"><?php echo esc_html($count); ?></span> 
						<span class="iddi-homepage__education-stats-unit fs-24 fw-300 italic-font d-i-block text-color-oxford-blue fs-16__xl fs-14__lg fs-12__md"><?php echo esc_html($label); ?></span> 
					</div>
					<p class="iddi-homepage__education-stats-text fs-32 fw-300 italic-font text-color-oxford-blue fs-20__xl fs-16__lg fs-14__md"><?php echo nl2br(esc_html($description)); ?></p> 
				</div>
				<?php endif; endfor; ?>
			</div>

			<div class="iddi-homepage__education-media">
				<?php 
				$video_group = get_field('education_&_training_video');
				$default_img = '/wp-content/uploads/2026/03/default-image.jpg';
				$video_bg    = $default_img; // Mặc định
				$video_src   = '';

				if ($video_group) {
					$raw_bg = $video_group['background_image_video'];
					if (!empty($raw_bg)) {
						$video_bg = is_array($raw_bg) ? $raw_bg['url'] : $raw_bg;
					}
					$video_src = is_array($video_group['video']) ? $video_group['video']['url'] : $video_group['video'];
				}
				?>
				<div class="video-wrapper p-relative iddi-homepage__education-video-wrapper">
					<video class="video-element cover-image" width="1330" loop poster="<?php echo esc_url($video_bg); ?>">
						<?php if($video_src): ?><source src="<?php echo esc_url($video_src); ?>" type="video/mp4"><?php endif; ?>
					</video>
					<button class="btn-play reset-button p-absolute iddi-homepage__education-video-wrapper-button" aria-label="Phát video giới thiệu đào tạo"><?php echo get_my_svg('play'); ?></button>
					<button class="btn-pause reset-button p-absolute iddi-homepage__education-video-wrapper-button" aria-label="Tạm dừng video giới thiệu đào tạo"><?php echo get_my_svg('pause'); ?></button>
				</div>

				<p class="iddi-homepage__education-media-caption fs-32 fw-300 italic-font center-text text-color-oxford-blue fs-20__xl fs-16__lg fs-14__sm"> 
					<?php echo wp_strip_all_tags(get_field('education_&_training_video__description')); ?>
				</p>

				<div class="iddi-homepage__education-action">
					<?php 
					$button_group = get_field('education_&_training_button');
					if ($button_group && !empty($button_group['education_&_training_button_text'])) : ?>
					<a href="<?php echo esc_url($button_group['education_&_training_button_url']); ?>" class="iddi-homepage__education-btn fs-32 fw-300 italic-font d-flex flex-center-v text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm" aria-label="<?php echo esc_attr($button_group['education_&_training_button_text'] . ' về đào tạo'); ?>">
						<span><?php echo esc_html($button_group['education_&_training_button_text']); ?></span>
						<?php echo get_my_svg('explore'); ?>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php
	$sub_title   = get_field('subtitle'); 
	$button_text = get_field('button_text');
	$button_link = get_field('button_link');
	$main_title  = get_field('main_title');
	$featured_img = get_field('featured_image');
	$f1 = get_field('feature_1'); $f2 = get_field('feature_2'); $f3 = get_field('feature_3'); $f4 = get_field('feature_4');
	?>
	<section class="iddi-homepage__systems">
		<div class="iddi__container">
			<header class="iddi-homepage__systems-header d-flex flex-jc-between flex-ai-end">
				<div class="iddi-homepage__number iddi-homepage__system-id-wrap d-flex flex-ai-end gap-xl"> 
					<span class="iddi-homepage__number-number iddi-homepage__system-number iddi-homepage__heading-number d-i-block fw-400"><span>03</span> </span>
					<h2 class="iddi-homepage__number-text iddi-homepage__system-heading fw-300 italic-font text-color-oxford-blue "><?php echo esc_html($sub_title); ?></h2>
				</div>
				<?php if( $button_link ): ?>
				<div class="iddi-homepage__systems-action"> 
					<a href="<?php echo esc_url($button_link); ?>" class="ddi-homepage__systems-btn fs-32 fw-300 italic-font d-flex flex-center-v text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm" aria-label="<?php echo esc_attr(($button_text ?: 'Explore More') . ' về hệ thống'); ?>">
						<span><?php echo esc_html($button_text ?: 'Explore More'); ?></span>
						<?php echo get_my_svg('explore'); ?>
					</a>
				</div>
				<?php endif; ?>
			</header>
			<div class="iddi-homepage__systems-body">
				<div class="iddi-homepage__systems-title-box"> 
					<div class="iddi-homepage__systems-main-title fs-48 fw-300 italic-font text-color-oxford-blue fs-32__xl fs-24__lg fs-20__sm"><?php echo wp_kses_post($main_title); ?></div>
				</div>
				<div class="iddi-homepage__systems-diagram d-flex__md"> 
					<div class="iddi-homepage__systems-feature-left d-grid__md g-column-2__md gap-m__md">
						<?php 
						$left_col = [ ['data' => $f1, 'class' => '_1'], ['data' => $f2, 'class' => '_2'] ];
						foreach( $left_col as $item ): 
						$f = $item['data']; if( $f ): ?>
						<article class="iddi-homepage__systems-card <?php echo $item['class']; ?>">
							<h3 class="iddi-homepage__systems-card-title fs-24 fw-400 text-color-oxford-blue fs-16__xl fs-14__lg"><?php echo esc_html($f['feature_title']); ?></h3>
							<p class="iddi-homepage__systems-card-desc fs-24 fw-300 text-color-oxford-blue fs-16__xl fs-14__lg"><?php echo esc_html($f['feature_description']); ?></p> 
						</article>
						<?php endif; endforeach; ?>
					</div>
					<div class="iddi-homepage__systems-core-visual">
						<?php 
						$featured_img_id = 0;
						if ($featured_img) {
							if (is_numeric($featured_img)) {
								$featured_img_id = $featured_img;
							} else {
								$featured_img_id = attachment_url_to_postid($featured_img);
							}
						}
						
						$systems_alt = 'Mô hình công nghệ cấy ghép răng Implant';
						if ($featured_img_id) {
							$attachment_alt = get_post_meta($featured_img_id, '_wp_attachment_image_alt', true);
							if ($attachment_alt) {
								$systems_alt = $attachment_alt;
							}
						}
						
						if ($featured_img_id) {
							echo wp_get_attachment_image($featured_img_id, 'full', false, array(
								'loading'  => 'lazy',
								'decoding' => 'async',
								'class'    => 'iddi-homepage__systems-main-img',
								'alt'      => $systems_alt,
							));
						} else {
							$img_src = $featured_img ? $featured_img : get_template_directory_uri() . '/assets/images/default-implant.jpg';
							?>
							<img loading="lazy" decoding="async" src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($systems_alt); ?>" class="iddi-homepage__systems-main-img" width="608" height="780">
							<?php
						}
						?>
					</div>
					<div class="iddi-homepage__systems-feature-right d-grid__md g-column-2__md gap-m__md">
						<?php 
						$right_col = [ ['data' => $f3, 'class' => '_3'], ['data' => $f4, 'class' => '_4'] ];
						foreach( $right_col as $item ): 
						$f = $item['data']; if( $f ): ?>
						<article class="iddi-homepage__systems-card <?php echo $item['class']; ?> right-text left-text__md">
							<h3 class="iddi-homepage__systems-card-title fs-24 fw-400 text-color-oxford-blue fs-16__xl fs-14__lg"><?php echo esc_html($f['feature_title']); ?></h3>
							<p class="iddi-homepage__systems-card-desc fs-24 fw-300 text-color-oxford-blue fs-16__xl fs-14__lg"><?php echo esc_html($f['feature_description']); ?></p> 
						</article>
						<?php endif; endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	$edu_subtitle    = get_field('education_section__subtitle');
	$edu_main_title  = get_field('education_section__main_title');
	$edu_intro       = get_field('education_section__intro_description');
	$edu_video_url   = get_field('education_section__featured_video');
	$raw_poster_url  = get_field('education_section__featured_image_video'); 
	// Gán ảnh mặc định nếu poster rỗng
	$edu_poster_url  = !empty($raw_poster_url) ? (is_array($raw_poster_url) ? $raw_poster_url['url'] : $raw_poster_url) : $default_img;

	$edu_f1 = get_field('education_section__feature_1'); $edu_f2 = get_field('education_section__feature_2');
	$edu_f3 = get_field('education_section__feature_3'); $edu_f4 = get_field('education_section__feature_4');
	$edu_f_img1 = get_field('education_section__feature_image_1'); $edu_f_img2 = get_field('education_section__feature_image_2');
	$edu_btn = get_field('education_section__button');
	?>
	<section class="iddi-homepage__evidence-based-education">
		<div class="iddi__container">
			<div class="iddi-homepage__evidence-based-education-header">
				<div class="iddi-homepage__number iddi-homepage__evidence-based-education-id-wrap d-flex flex-jc-end gap-xl flex-ai-end"> 
					<span class="iddi-homepage__number-number iddi-homepage__evidence-based-education-number iddi-homepage__heading-number d-i-block fw-400"><span>04</span></span>
					<h2 class="iddi-homepage__number-text iddi-homepage__evidence-based-education-heading fs-40 fw-300 italic-font right-text text-color-oxford-blue fs-24__xl fs-20__lg fs-18__md fs-16__sm"><?php echo nl2br(esc_html($edu_subtitle)); ?></h2>
				</div>
			</div>
			<div class="iddi-homepage__evidence-based-education-main-title fs-56 fw-300 italic-font text-color-oxford-blue fs-32__xl fs-24__lg fs-20__sm"><?php echo nl2br(wp_kses_post($edu_main_title)); ?></div>
			<div class="iddi-homepage__evidence-based-education-hero">
				<div class="video-wrapper p-relative iddi-homepage__evidence-based-education-video-wrapper">
					<video class="video-element cover-image" width="1330" loop poster="<?php echo esc_url($edu_poster_url); ?>">
						<?php if($edu_video_url): ?><source src="<?php echo esc_url($edu_video_url); ?>" type="video/mp4"><?php endif; ?>
					</video>
					<button class="btn-play reset-button p-absolute iddi-homepage__evidence-based-education-video-wrapper-button" aria-label="Phát video giáo dục thực chứng"><?php echo get_my_svg('play'); ?></button>
					<button class="btn-pause reset-button p-absolute iddi-homepage__evidence-based-education-video-wrapper-button" aria-label="Tạm dừng video giáo dục thực chứng"><?php echo get_my_svg('pause'); ?></button>
				</div>
				<div class="iddi-homepage__evidence-based-education-caption"> 
					<div class="fs-32 fw-300 italic-font center-text text-color-oxford-blue fs-20__xl fs-18__md fs-16__sm"><?php echo wp_kses_post($edu_intro); ?></div>
				</div>
			</div>
			<div class="iddi-homepage__evidence-based-education-content d-flex flex-jc-between gap-xl__xl flex-column__sm gap-l__md">
				<div class="iddi-homepage__evidence-based-education-col iddi-homepage__evidence-based-education-col--left">
					<?php $left_features = [$edu_f1, $edu_f2]; foreach($left_features as $f): if($f): ?>
					<article class="iddi-homepage__evidence-based-education-card"> 
						<div class="iddi-homepage__evidence-based-education-card-info right-text left-text__sm"> 
							<h3 class="iddi-homepage__evidence-based-education-card-tag fs-32 fw-400 text-color-flame-orange fs-20__xl fs-18__lg fs-16__sm"><?php echo esc_html($f['education_section__feature_title']); ?></h3>
							<p class="iddi-homepage__evidence-based-education-card-desc fs-32 fw-300 text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm"><?php echo esc_html($f['education_section__feature_description']); ?></p>
						</div>
					</article>
					<?php endif; endforeach; ?>
					<?php if($edu_f_img1): 
						$edu_f_img1_id = attachment_url_to_postid($edu_f_img1);
						$alt1 = $edu_f_img1_id ? get_post_meta($edu_f_img1_id, '_wp_attachment_image_alt', true) : '';
						if (empty($alt1)) {
							$alt1 = 'Đào tạo nha khoa thực chứng - IDDI Academy';
						}
					?>
					<div class="iddi-homepage__evidence-based-education-card-media right-text left-text__sm"><img loading="lazy" decoding="async" class="cover-image" src="<?php echo esc_url($edu_f_img1); ?>" alt="<?php echo esc_attr($alt1); ?>"></div>
					<?php endif; ?>
				</div>
				<div class="iddi-homepage__evidence-based-education-line-col"></div>
				<div class="iddi-homepage__evidence-based-education-col iddi-homepage__evidence-based-education-col--right d-flex__sm flex-column__sm">
					<?php if($edu_f_img2): 
						$edu_f_img2_id = attachment_url_to_postid($edu_f_img2);
						$alt2 = $edu_f_img2_id ? get_post_meta($edu_f_img2_id, '_wp_attachment_image_alt', true) : '';
						if (empty($alt2)) {
							$alt2 = 'Học viên thực hành cấy ghép Implant - IDDI Academy';
						}
					?>
					<div class="iddi-homepage__evidence-based-education-card-media"><img loading="lazy" decoding="async" class="cover-image" src="<?php echo esc_url($edu_f_img2); ?>" alt="<?php echo esc_attr($alt2); ?>"></div>
					<?php endif; ?>
					<?php $right_features = [$edu_f3, $edu_f4]; foreach($right_features as $f): if($f): ?>
					<article class="iddi-homepage__evidence-based-education-card"> 
						<div class="iddi-homepage__evidence-based-education-card-info"> 
							<h3 class="iddi-homepage__evidence-based-education-card-tag fs-32 fw-400 text-color-flame-orange fs-20__xl fs-18__lg fs-16__sm"><?php echo esc_html($f['education_section__feature_title']); ?></h3>
							<p class="iddi-homepage__evidence-based-education-card-desc fs-32 fw-300 text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm"><?php echo esc_html($f['education_section__feature_description']); ?></p>
						</div>
					</article>
					<?php endif; endforeach; ?>
				</div>
			</div>
			<?php if ( $edu_btn && !empty($edu_btn['education_section__button_text']) ): ?>
			<div class="iddi-homepage__evidence-based-education-footer right-text">
				<a href="<?php echo esc_url($edu_btn['education_section__button_url'] ?: '#'); ?>" class="iddi-homepage__evidence-based-education-btn fs-32 fw-300 italic-font d-i-flex flex-center-v text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm" aria-label="<?php echo esc_attr($edu_btn['education_section__button_text'] . ' về đào tạo thực chứng'); ?>">
					<span><?php echo esc_html($edu_btn['education_section__button_text']); ?></span>
					<?php echo get_my_svg('explore'); ?>
				</a>
			</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="iddi-homepage__education-insights">
		<div class="iddi__container">
			<?php 
			$insight_subtitle   = get_field('education_insight__subtitle');
			$insight_main_title = get_field('education_insight__main_title');
			?>
			<div class="iddi-homepage__education-insights-header">
				<div class="iddi-homepage__number iddi-homepage__education-insights-id-wrap d-flex flex-jc-between flex-ai-end"> 
					<span class="iddi-homepage__number-number iddi-homepage__education-insights-number iddi-homepage__heading-number d-i-block fw-400"><span>05</span></span>
					<?php if ($insight_subtitle) : ?>
					<h2 class="iddi-homepage__number-text iddi-homepage__education-insights-heading fw-300 italic-font right-text text-color-oxford-blue "><?php echo nl2br(esc_html($insight_subtitle)); ?></h2>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($insight_main_title) : ?>
			<div class="iddi-homepage__education-insights-main-title fs-56 fw-300 italic-font text-color-oxford-blue fs-32__xl fs-24__lg fs-20__sm"><?php echo nl2br(wp_kses_post($insight_main_title)); ?></div>
			<?php endif; ?>

			<div class="iddi-homepage__education-insights-cards"> 
				<?php 
				for ($i = 1; $i <= 3; $i++) : 
				$group_key = 'education_insight_' . $i;
				$insight = get_field($group_key);

				if ($insight) :
				// Lấy dữ liệu
				$video_data = $insight['video']; 
				$video_url  = is_array($video_data) ? $video_data['url'] : $video_data;

				$raw_insight_poster = $insight['background'];
				$poster_url = !empty($raw_insight_poster) ? (is_array($raw_insight_poster) ? $raw_insight_poster['url'] : $raw_insight_poster) : $default_img;

				$title   = $insight['title'];
				$content = $insight['content'];

				// KIỂM TRA: Nếu không có video, không có tiêu đề VÀ không có nội dung thì bỏ qua vòng lặp này
				if (empty($video_url) && empty($title) && empty($content)) {
					continue; 
				}
				?>
				<article class="iddi-homepage__education-insights-card d-flex flex-jc-between flex-ai-center gap-xl__xl flex-column__sm flex-ai-start__sm gap-m__sm"> 

					<div class="video-wrapper p-relative iddi-homepage__education-insights-card-video">
						<?php if ($video_url) : ?>
						<video class="video-element cover-image" width="924" loop poster="<?php echo esc_url($poster_url); ?>">
							<source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
						</video>
						<button class="iddi-homepage__education-insights-card-video-button btn-play reset-button p-absolute" aria-label="Phát video chia sẻ kiến thức"><?php echo get_my_svg('play'); ?></button>
						<button class="iddi-homepage__education-insights-card-video-button btn-pause reset-button p-absolute" aria-label="Tạm dừng video chia sẻ kiến thức"><?php echo get_my_svg('pause'); ?></button>
						<?php endif; ?>
					</div>

					<div class="iddi-homepage__education-insights-card-info"> 
						<?php if ($title) : ?>
						<h3 class="iddi-homepage__education-insights-card-tag fs-32 fw-400 text-color-flame-orange fs-20__xl fs-18__lg">
							<?php echo esc_html($title); ?>
						</h3>
						<?php endif; ?>

						<?php if ($content) : ?>
						<div class="iddi-homepage__education-insights-card-desc fs-32 fw-300 text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm">
							<?php echo wpautop(esc_html($content)); ?>
						</div>
						<?php endif; ?>
					</div>

				</article>
				<?php 
				endif; // End if $insight
				endfor; 
				?>
			</div>
			<div class="iddi-homepage__education-insights-footer center-text">
				<a href="/events" class="iddi-homepage__education-insights-btn fs-32 fw-300 italic-font d-i-flex flex-center-v text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm" aria-label="Xem chi tiết các khóa học và sự kiện đào tạo">
					<span>Xem chi tiết</span>
					<?php echo get_my_svg('explore'); ?>
				</a>
			</div>
		</div>
	</section>

	<section class="iddi-homepage__get-in-touch">
		<div class="iddi__container">
			<div class="iddi-homepage__get-in-touch-header">
				<div class="iddi-homepage__number iddi-homepage__get-in-touch-id-wrap d-flex gap-xl flex-ai-end"> 
					<span class="iddi-homepage__number-number iddi-homepage__get-in-touch-number iddi-homepage__heading-number d-i-block fw-400"><span>06</span></span>
					<h2 class="iddi-homepage__number-text iddi-homepage__get-in-touch-heading fw-300 italic-font text-color-oxford-blue">Liên <br>hệ</h2>
				</div>
			</div>
			<?php get_template_part('template-parts/sections/section-contact'); ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>