<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TZPVQM72');</script>
<!-- End Google Tag Manager -->
		<?php wp_head(); ?>

		<?php 
		// Lấy giá trị từ ACF (Giả sử bạn đặt trong Options Page, nếu không hãy bỏ ', "option"')
		$logo_desktop = get_field('desktop-width-logo', 'option');
		$logo_tablet  = get_field('tablet-width-logo', 'option');
		$logo_mobile  = get_field('mobile-width-logo', 'option');
		?>

		<style type="text/css">
			/* Desktop: Mặc định */
			.iddi-header__logo img, 
			.custom-logo-link img,
			.iddi-header__user,
			.iddi-header__popup-menu-logo img {
				width: auto !important;
				/*             width: <?php echo $logo_desktop ? $logo_desktop . 'px' : 'auto'; ?> !important; */
				height: auto !important;
			}

			/* Tablet: < 1280px */
			@media (max-width: 1200px) {
				.iddi-header__logo img, 
				.custom-logo-link img,
				.iddi-header__popup-menu-logo img {
					/*                 width: <?php echo $logo_tablet ? $logo_tablet . 'px' : 'auto'; ?> !important; */
				}

				.iddi-header__user {
					width: unset !important;
				}
			}

			/* Mobile: < 768px */
			@media (max-width: 767px) {
				.iddi-header__logo img, 
				.custom-logo-link img,
				.iddi-header__popup-menu-logo img {
					/*                 width: <?php echo $logo_mobile ? $logo_mobile . 'px' : 'auto'; ?> !important; */
				}
			}
		</style>
	</head>

	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
        <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TZPVQM72"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<header class="iddi-header">
			<div class="container d-flex flex-ai-center flex-jc-between">


				<!-- Start - MENU MOBILE -->
				<div class="iddi-header__popup-menu">
					<div class="iddi-header__popup-menu-container">
						<div class="iddi-header__popup-menu-button-close">
							<button class="close-btn reset-button"><?php echo get_my_svg('times'); ?></button>
						</div>
						<div class="iddi-header__popup-menu-wrapper">
							<div class="iddi-header__popup-menu-logo center-text">
								<?php 
								if ( has_custom_logo() ) :
									$custom_logo_id = get_theme_mod( 'custom_logo' );
									echo sprintf( 
										'<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>', 
										esc_url( home_url( '/' ) ), 
										wp_get_attachment_image( $custom_logo_id, 'full', false, array(
											'loading'  => 'lazy',
											'decoding' => 'async',
											'class'    => 'custom-logo',
										) ) 
									);
								else : 
								?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<img loading="lazy" decoding="async" src="<?php echo get_template_directory_uri(); ?>/logo-iddi.png" alt="<?php bloginfo( 'name' ); ?>">
								</a>
								<?php endif; ?>
							</div>
							<nav class="iddi-header__popup-menu-nav fs-18__xl fs-16__md">
								<?php
								wp_nav_menu( array(
									'menu'           => 3,
									'container'      => false,
									'items_wrap'     => '<ul class="">%3$s</ul>',
									'fallback_cb'    => false,
								) );
								?>
							</nav>
							<div class="iddi-header__popup-menu-actions d-flex">
								<button class="button-header contact-now" 
        onclick="jQuery('#popup-contact-global').css('display', 'flex').hide().fadeIn()">
    Đăng ký ngay
</button>

								<a class="button-header call-now" href="tel:0369750194"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" viewBox="0 0 52 52" xml:space="preserve"><path d="M48.5 37.9 42.4 33c-1.4-1.1-3.4-1.2-4.8-.1l-5.2 3.8c-.6.5-1.5.4-2.1-.2l-7.8-7-7-7.8c-.6-.6-.6-1.4-.2-2.1l3.8-5.2c1.1-1.4 1-3.4-.1-4.8l-4.9-6.1c-1.5-1.8-4.2-2-5.9-.3L3 8.4c-.8.8-1.2 1.9-1.2 3 .5 10.2 5.1 19.9 11.9 26.7S30.2 49.5 40.4 50c1.1.1 2.2-.4 3-1.2l5.2-5.2c1.9-1.5 1.8-4.3-.1-5.7"/></svg><span>0369 750 194</span></a>
							</div>
						</div>
					</div>
				</div>
				<!-- END - MENU MOBILE -->

				
				<div class="iddi-header__btn-menu d-none">
					<button class="reset-button"><?php echo get_my_svg('bar-menu'); ?></button>
				</div>

				<div class="iddi-header__logo">
					<?php 
					if ( has_custom_logo() ) :
						$custom_logo_id = get_theme_mod( 'custom_logo' );
						echo sprintf( 
							'<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>', 
							esc_url( home_url( '/' ) ), 
							wp_get_attachment_image( $custom_logo_id, 'full', false, array(
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'decoding'      => 'sync',
								'class'         => 'custom-logo',
							) ) 
						);
					else : 
					?>
					<a class="d-i-block" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img loading="eager" fetchpriority="high" decoding="sync" src="<?php echo get_template_directory_uri(); ?>/logo-iddi.png" alt="<?php bloginfo( 'name' ); ?>">
					</a>
					<?php endif; ?>
				</div>

				<nav class="iddi-header__nav ">
					<?php
					wp_nav_menu( array(
						'menu'           => 3,
						'container'      => false,
						'items_wrap'     => '<ul class="iddi-header__nav-list d-flex flex-jc-center">%3$s</ul>',
						'fallback_cb'    => false,
					) );
					?>
				</nav>

				


				<button class="button-header contact-now d-none__xl" onclick="jQuery('#popup-contact-global').css('display', 'flex').hide().fadeIn()"><span>Đăng ký ngay</span></button>

				<a class="button-header call-now d-none__xl" href="tel:0369750194"><span><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" viewBox="0 0 52 52" xml:space="preserve"><path d="M48.5 37.9 42.4 33c-1.4-1.1-3.4-1.2-4.8-.1l-5.2 3.8c-.6.5-1.5.4-2.1-.2l-7.8-7-7-7.8c-.6-.6-.6-1.4-.2-2.1l3.8-5.2c1.1-1.4 1-3.4-.1-4.8l-4.9-6.1c-1.5-1.8-4.2-2-5.9-.3L3 8.4c-.8.8-1.2 1.9-1.2 3 .5 10.2 5.1 19.9 11.9 26.7S30.2 49.5 40.4 50c1.1.1 2.2-.4 3-1.2l5.2-5.2c1.9-1.5 1.8-4.3-.1-5.7"/></svg><span>0369 750 194</span></span></a>


				<div class="iddi-header__btn-search">
					<button class="reset-button button-open-search" aria-label="Tìm kiếm">
						<svg viewBox="0 0 512 512" data-name="11 Search" xmlns="http://www.w3.org/2000/svg">
							<path data-name="Path 16" d="M497.914 497.913a48.085 48.085 0 0 1-68.008 0l-84.863-84.863a222.6 222.6 0 0 1-120.659 35.717C100.469 448.767 0 348.313 0 224.383S100.469 0 224.384 0c123.931 0 224.384 100.452 224.384 224.383a222.87 222.87 0 0 1-35.718 120.676l84.864 84.863a48.066 48.066 0 0 1 0 67.991m-273.53-433.8a160.274 160.274 0 1 0 160.274 160.269A160.27 160.27 0 0 0 224.384 64.109Z" fill-rule="evenodd" fill="currentColor"/>
						</svg>
					</button>
				</div>



				<div class="iddi-header__user d-flex flex-ai-center gap-m">
					<?php if ( is_user_logged_in() ) : 
						$current_user = wp_get_current_user();
						$avatar_url = get_avatar_url( $current_user->ID, array('size' => 100) ); 
					?>
						<div class="iddi-header__avatar-wrap right-text">
							<a href="<?php echo home_url('/membership/'); ?>">
								<img loading="eager" 
									 fetchpriority="high" 
									 decoding="sync" 
									 src="<?php echo esc_url( $avatar_url ); ?>" 
									 alt="<?php echo esc_attr( $current_user->display_name ); ?>" 
									 class="iddi-header__avatar circle-image border-width-2 border_color-flame-orange"
									 >
							</a>
						</div>
					<?php else : ?>
						<div class="iddi-header__login-btn">
							<button onclick="window.openLoginPopup()" class="reset-button d-flex flex-ai-center gap-s txt-color_color-oxford-blue fw-600 fs-16">
								
								<svg style="width: var(--w-logo); height: var(--w-logo);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" xml:space="preserve"><path fill="currentColor" d="m48.35 50.783.254.305c-4.997 4.488-11.608 7.222-18.842 7.222s-13.833-2.721-18.83-7.196l.28-.331s3.293-2.619 7.171-3.585 5.632-3.687 5.632-3.687v-4.755s-2.823-3.776-2.428-6.395c0 0-3.496-2.327-1.068-5.721 0 0-5.62-16.134 8.633-16.299 3.611-.038 5.403 2.708 5.403 2.708 9.65-.966 4.488 13.591 4.488 13.591 2.428 3.395-1.068 5.721-1.068 5.721.394 2.619-2.428 6.395-2.428 6.395v4.755s1.755 2.721 5.632 3.687c3.878.966 7.171 3.585 7.171 3.585"/><path fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M48.35 50.783s-3.293-2.619-7.171-3.585-5.632-3.687-5.632-3.687v-4.755s2.823-3.776 2.428-6.395c0 0 3.496-2.327 1.068-5.721 0 0 5.162-14.558-4.488-13.591 0 0-1.793-2.746-5.403-2.708-14.253.165-8.633 16.299-8.633 16.299-2.428 3.395 1.068 5.721 1.068 5.721-.394 2.619 2.428 6.395 2.428 6.395v4.755s-1.755 2.721-5.632 3.687c-3.878.966-7.171 3.585-7.171 3.585"/><path fill="none" stroke="currentColor" stroke-width="3" stroke-miterlimit="10" d="M10.932 51.113C5.16 45.939 1.524 38.425 1.524 30.071c0-15.6 12.638-28.238 28.238-28.238C45.349 1.833 58 14.471 58 30.071c0 8.353-3.624 15.854-9.396 21.016-4.997 4.488-11.608 7.222-18.842 7.222s-13.833-2.72-18.83-7.196z"/></svg>
<!-- 								<span class="d-none__xl">Login</span> -->
							</button>
						</div>
					<?php endif; ?>
				</div>
				
			</div>

			<!-- Popup Tìm kiếm (Overlay) -->
			<div class="iddi-header-search d-flex align-items-center fs-18 fs-16__xl fs-14__md p-relative">
				<?php 
				$events_page_url = get_template_directory_uri(); // Mặc định
				$pages = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'events.php' // Tên file template của bạn
				));
				$target_url = ($pages) ? get_permalink($pages[0]->ID) : home_url('/events/');
				?>
				<button class="close-popup-search reset-button"><?php echo get_my_svg('times'); ?></button>
				<form method="get" action="<?php echo esc_url( home_url('/') ); ?>" class="full-width d-flex">
					<input type="text" 
						   name="s" 
						   placeholder="Tìm kiếm..." 
						   value="<?php echo get_search_query(); ?>" 
						   class="iddi-header-search__input no-bg-color full-width">

					<button type="submit" class="iddi-header-search__submit btn-flame no-bg-color p-absolute">
						<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path d="M21.71 20.29 18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.39M11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7" fill="currentColor"/>
						</svg>
					</button>
				</form>
			</div>
		</header>