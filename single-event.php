<?php
/**
 * The template for displaying all single event posts
 */

get_header(); ?>

<main id="primary" class="site-main page-single-event">
    <?php while ( have_posts() ) : the_post(); ?>

    <section class="iddi-event-detail">
        <div class="iddi__container">
            <div class="iddi-event-detail__header d-flex flex-column flex-ai-center gap-l gap-s__xl">
                <div class="iddi-event-detail__time fs-28 fw-500 italic-font text-color-flame-orange fs-20__xl fs-18__lg fs-16__md"> 
                    <?php 
                    $date_time = get_field('date_and_time_event');
                    echo $date_time ? esc_html($date_time) : 'TBA'; 
                    ?>
                </div>
                <h1 class="iddi-event-detail__title fs-40 fw-600 italic-font text-color-oxford-blue fs-28__xl fs-24__lg  fs-20__sm center-text"> 
                    <?php the_title(); ?>
                </h1>
                <div class="iddi-event-detail__location d-flex fs-24 fw-400 text-color-oxford-blue flex-jc-center gap-m fs-16__xl fs-14__lg fs-12__md"> 
                    <?php echo get_my_svg('address'); ?>
                    <span><?php the_field('address_event'); ?></span>
                </div>
            </div>
        </div>
    </section>
	
	 <?php $content = get_the_content(); if ( ! empty( trim( $content ) ) ) : ?>
    <section class="iddi-event-detail__content">
        <div class="iddi__container fs-20 fs-16__xl fs-15__lg">
            <?php the_content(); ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="iddi-event-detail__speakers">
        <div class="iddi__container">
            <div class="iddi-event-detail__speakers-container radius-xl padding-2xl padding-xl__xl padding-l__md padding-xs__sm">
                <div class="iddi-event-detail__speakers-header">
                    <h2 class="iddi-event-detail__speakers-title fs-32 fw-500 fs-24__xl fs-20__md upper-text"><?php iddi_tr_e('Báo cáo viên'); ?></h2>
                </div>
                <div class="iddi-event-detail__speakers-list d-grid g-column-1">
                    <?php if (have_rows('event_speakers_list')) : ?>
                        <?php while (have_rows('event_speakers_list')) : the_row(); ?>
                        <article class="iddi-event-detail__speakers-item">
                            <div class="iddi-event-detail__speakers-info d-flex flex-ai-center">
                                <div class="iddi-event-detail__speakers-meta center-text">
                                    <div class="iddi-event-detail__speakers-avatar-wrap"> 
                                        <?php $avatar = get_sub_field('speaker_avatar'); if ($avatar) : ?>
                                            <img src="<?php echo esc_url($avatar); ?>" alt="<?php the_sub_field('speaker_name'); ?>" class="iddi-event-detail__speakers-avatar circle-image cover-image">
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="iddi-event-detail__speakers-name fs-20 fw-700 fs-16__xl fs-14__lg fs-12__md fs-11__sm">
                                        <?php the_sub_field('speaker_name'); ?> 
                                    </h4> 
                                    <div class="iddi-event-detail__speakers-country fs-16 fs-14__xl fs-13__lg">
                                        <span><?php the_sub_field('speaker_country'); ?></span> 
                                        <?php $flag = get_sub_field('speaker_country_flag'); if ($flag) : ?>
                                            <img src="<?php echo esc_url($flag); ?>" alt="Flag" class="iddi-event-detail__speakers-flag">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="iddi-event-detail__speakers-bio">
                                    <ul class="iddi-event-detail__speakers-bio-list noreset-ul">
                                        <?php if (have_rows('speaker_bio-info_list')) : ?>
                                            <?php while (have_rows('speaker_bio-info_list')) : the_row(); ?>
                                            <li class="iddi-event-detail__speakers-bio-item fs-18 fw-300 fs-14__xl fs-13__lg fs-12__md">
                                                <?php the_sub_field('speaker_bio-info_item'); ?> 
                                            </li> 
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div> 
        </div>
    </section>

   

    <?php if (have_rows('day_sessions')): ?>
    <section class="iddi-event-detail__schedule">
        <div class="iddi__container">
            <?php $day_count = 1; while (have_rows('day_sessions')): the_row(); 
                $m_data = get_sub_field('morning_session');
                $a_data = get_sub_field('afternoon_session');
            ?>
            <h2 class="iddi-event-detail__schedule-heading fs-32 fw-600 text-color-flame-orange center-text fs-24__xl">
                <?php iddi_tr_e('Ngày'); ?> <?php echo $day_count; ?> <?php $dt = get_sub_field('day_title'); if ($dt) echo ': ' . esc_html($dt); ?>
            </h2>
            <div class="iddi-event-detail__schedule-grid d-flex flex-jc-between gap-2xl__xl gap-xl__lg gap-l__md flex-column__sm">
                <?php if (!empty($m_data['morning_session_list'])) : ?>
                <div class="iddi-event-detail__schedule-col iddi-event-detail__schedule-col--morning d-flex flex-column">
                    <div class="iddi-event-detail__schedule-label fs-28 fw-600 text-color-flame-orange fs-20__xl"><?php iddi_tr_e('Sáng'); ?></div>
                    <ul class="iddi-event-detail__timeline d-flex flex-column">
                        <?php foreach ($m_data['morning_session_list'] as $m_item): 
                            $st = $m_item['start_time'] ?? ''; $et = $m_item['end_time'] ?? '';
                            $show_t = (!($m_item['khong-hien-thoi-gian'] ?? false) && $st && $et);
                        ?>
                        <li class="d-flex flex-wrap fs-20 fw-300 text-color-oxford-blue fs-18__xl fs-16__lg ">
                            <?php if ($show_t) : ?><span class="time fw-600 text-color-flame-orange"><?php echo esc_html($st); ?> - <?php echo esc_html($et); ?></span><?php endif; ?>
                            <div class="iddi-event-detail__content-wrapper full-width">
                                <?php if (!empty($m_item['content'])): foreach ($m_item['content'] as $md): ?>
                                    <div class="iddi-event-detail__timeline-content">
                                        <?php if (!empty($md['title'])): ?><h4 class="fw-600"><?php echo esc_html($md['title']); ?></h4><?php endif; ?>
                                        <?php if (!empty($md['description'])): ?><div><?php echo nl2br(wp_kses_post($md['description'])); ?></div><?php endif; ?>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="iddi-event-detail__schedule-divider"></div>

                <?php if (!empty($a_data['afternoon_session_list'])) : ?>
                <div class="iddi-event-detail__schedule-col iddi-event-detail__schedule-col--afternoon d-flex flex-column">
                    <div class="iddi-event-detail__schedule-label fs-28 fw-600 text-color-flame-orange fs-20__xl"><?php iddi_tr_e('Chiều'); ?></div>
                    <ul class="iddi-event-detail__timeline d-flex flex-column">
                        <?php foreach ($a_data['afternoon_session_list'] as $a_item): 
                            $st = $a_item['start_time'] ?? ''; $et = $a_item['end_time'] ?? '';
                            $show_t = (!($a_item['khong-hien-thoi-gian'] ?? false) && $st && $et);
                        ?>
                        <li class="d-flex flex-wrap fs-20 fw-300 text-color-oxford-blue fs-18__xl fs-16__lg">
                            <?php if ($show_t) : ?><span class="time fw-600 text-color-flame-orange"><?php echo esc_html($st); ?> - <?php echo esc_html($et); ?></span><?php endif; ?>
                            <div class="iddi-event-detail__content-wrapper full-width">
                                <?php if (!empty($a_item['content'])): foreach ($a_item['content'] as $ad): ?>
                                    <div class="iddi-event-detail__timeline-content">
                                        <?php if (!empty($ad['title'])): ?><h4 class="fw-600"><?php echo esc_html($ad['title']); ?></h4><?php endif; ?>
                                        <?php if (!empty($ad['description'])): ?><div><?php echo nl2br(wp_kses_post($ad['description'])); ?></div><?php endif; ?>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <?php $day_count++; endwhile; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="iddi-event-detail__tickets">
        <div class="iddi__container">
            <div class="iddi-event-detail__tickets-container center-text">
<!--                 <?php 
                $t_title = get_field('event_ticket_title'); $t_desc = get_field('event_ticket_description');
                $price = get_field('price_event'); $t_url = get_field('url_get_your_ticket'); 
                if ($t_title) : ?><div class="iddi-event-detail__tickets-highlight italic fs-32 fw-300 italic-font text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md"><?php echo esc_html($t_title); ?></div><?php endif; ?>
                <?php if ($t_desc) : ?><div class="iddi-event-detail__tickets-info fs-32 fw-300 italic-font text-color-oxford-blue fs-20__xl fs-18__lg fs-16__md fs-14__sm"><?php echo wp_kses_post($t_desc); ?></div><?php endif; ?>
                <div class="iddi-event-detail__tickets-action">
                    <a href="<?php echo $t_url ? esc_url($t_url) : '#'; ?>" class="iddi-event-detail__tickets-btn fw-600 bg-color_color-flame-orange d-i-block text-color-white radius-m padding-xl__v padding-1xl__h">
                        <?php if ($price) echo esc_html($price) . ' - '; ?>Đăng ký ngay!
                    </a>
                </div> -->
				
				
				<div class="iddi-event-detail__tickets-action">
                    <button 
    data-event-title="<?php echo esc_attr(get_the_title()); ?>"
    onclick="jQuery('#popup-contact-global').css('display', 'flex').hide().fadeIn()" 
    class="iddi-event-detail__tickets-btn fw-600 bg-color_color-flame-orange d-i-block text-color-white radius-m padding-xl__v padding-1xl__h"
>
    <?php if ($price) echo esc_html($price) . ' - '; ?><?php iddi_tr_e('Đăng ký ngay'); ?>!
</button>
                </div>
				
				
<!-- 				<button class="button-header contact-now d-none__xl" onclick="jQuery('#popup-contact-global').css('display', 'flex').hide().fadeIn()"><span>Đăng ký ngay</span></button> -->
            </div> 
        </div>
    </section>
    
    <?php get_template_part('template-parts/sections/section-testimonials'); ?>

    <section class="iddi-event-detail__related-events">
        <div class="iddi__container">
            <div class="iddi-event-detail__related-events-header">
                <h2 class="iddi-event-detail__related-events-title fs-48 fw-300 italic-font fs-32__xl fs-24__lg fs-20__sm">
                    <span class="fw-400 text-color-flame-orange">2026</span> - CẬP NHẬT KIẾN THỨC CHỈNH NHA
                </h2>
            </div>

            <?php
            $curr_id = get_the_ID();
            $final_list = array();

            // Bước A: Lấy bài từ ACF Related Event
            $acf_rel = get_field('related_event');
            if ($acf_rel) {
                $final_list = is_array($acf_rel) ? $acf_rel : array($acf_rel);
            } else {
                // Bước B: Fallback lấy theo taxonomy
                $terms = get_the_terms($curr_id, 'linh-vuc');
                $t_ids = ($terms && !is_wp_error($terms)) ? wp_list_pluck($terms, 'term_id') : array();

                $auto_args = array(
                    'post_type' => 'event', 'posts_per_page' => 6, // Lấy nhiều hơn một chút để sắp xếp rồi lọc lại
                    'post_status' => 'publish', 'post__not_in' => array($curr_id),
                );
                if (!empty($t_ids)) { $auto_args['tax_query'] = array(array('taxonomy' => 'linh-vuc', 'field' => 'term_id', 'terms' => $t_ids)); }
                
                $auto_q = new WP_Query($auto_args);
                if ($auto_q->have_posts()) { $final_list = $auto_q->posts; }
                wp_reset_postdata();
            }

            // Bước C: Sắp xếp theo ACF Group date_range -> start-date (Tăng dần)
            if (!empty($final_list)) {
                usort($final_list, function($a, $b) {
                    $id_a = is_object($a) ? $a->ID : $a;
                    $id_b = is_object($b) ? $b->ID : $b;
                    
                    $gr_a = get_field('date_range', $id_a); $gr_b = get_field('date_range', $id_b);
                    // Giả định Return Format của Date Picker là Ymd (VD: 20260326)
                    $da = $gr_a['start-date'] ?? '99999999'; $db = $gr_b['start-date'] ?? '99999999';
                    return strcmp($da, $db);
                });
                $final_list = array_slice($final_list, 0, 2); // Chỉ lấy 2 bài sau khi sắp xếp
            }

            if (!empty($final_list)) : ?>
            <div class="iddi-event-detail__related-events-list d-grid g-column-2 g-column-1__sm">
                <?php foreach ($final_list as $rel_p) : 
                    $p_id = is_object($rel_p) ? $rel_p->ID : $rel_p;
                    $p_price = get_field('price_event', $p_id);
                    $p_date = get_field('date_and_time_event', $p_id);
                ?>
                <article class="iddi-event-detail__related-events-card padding-xl d-flex flex-column gap-l radius-l bg-color_color-white gap-m__xl padding-s__xl padding-xs__lg gap-s__lg padding-1xs__md">
                    <div class="iddi-event-detail__related-events-thumb">
                        <?php if (has_post_thumbnail($p_id)) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url($p_id, 'large'); ?>" class="radius-s cover-image" alt="<?php echo get_the_title($p_id); ?>">
                        <?php endif; ?>
                    </div>
                    <h3 class="iddi-event-detail__related-events-name fs-28 fw-400 text-color-oxford-blue fs-20__xl fs-20__lg fs-16__md">
                        <a href="<?php echo get_permalink($p_id); ?>"><?php echo get_the_title($p_id); ?></a>
                    </h3>
                    <div class="iddi-event-detail__related-events-excerpt fs-24 fw-300 text-color-oxford-blue fs-16__xl fs-14__lg fs-12__md">
                        <?php echo wp_trim_words(get_post_field('post_content', $p_id), 25, '...'); ?>
                    </div>
                    <div class="d-flex flex-jc-between flex-ai-end">
                        <div class="iddi-event-detail__related-events-meta d-flex flex-column gap-m">
                            <span class="price d-block fs-32 fw-700 text-color-flame-orange fs-20__xl fs-18__lg fs-16__md">
                                <?php echo $p_price ? esc_html($p_price) : esc_html( function_exists('iddi_tr') ? iddi_tr('Liên hệ') : 'Liên hệ' ); ?>
                            </span>
                            <?php if ($p_date) : ?><span class="date fs-20 fw-300 text-color-flame-orange fs-16__xl fs-14__lg fs-12__md"><?php echo esc_html($p_date); ?></span><?php endif; ?>
                        </div>
						<button 
								data-event-title="<?php echo esc_attr(get_the_title($p_id)); ?>" 
								class="iddi-event-detail__related-events-enrol open-event-popup fs-24 fw-600 d-i-block center-text bg-color_color-flame-orange padding-s__v radius-s text-color-white fs-16__xl fs-14__md"
								>
							<?php iddi_tr_e('Đăng ký ngay'); ?>
						</button>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else : ?><p style="text-align: center;"><?php iddi_tr_e('Chưa có sự kiện liên quan.'); ?></p><?php endif; ?>
        </div>
    </section>

    <section class="iddi-event-detail__contact">
        <div class="iddi__container"><?php get_template_part('template-parts/sections/section-contact'); ?></div>
    </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>