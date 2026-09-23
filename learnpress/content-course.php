<?php
/**
 * Template part for displaying a LearnPress course card (unified layout).
 */
$course = learn_press_get_course(get_the_ID());

if ($course) :
    // Lấy thời lượng và việt hóa
    $duration = get_post_meta(get_the_ID(), '_lp_duration', true);
    if ($duration) {
        $search_vals  = array( 'minutes', 'minute', 'hours', 'hour', 'days', 'day', 'weeks', 'week', 'months', 'month' );
        $replace_vals = array( 'phút', 'phút', 'giờ', 'giờ', 'ngày', 'ngày', 'tuần', 'tuần', 'tháng', 'tháng' );
        $duration     = str_ireplace( $search_vals, $replace_vals, $duration );
    } else {
        $duration = 'Khóa học Online';
    }
?>
<article class="iddi-taxonomy-course-catetory-courses__card iddi-courses__online-tutorial-courses-card padding-xl d-flex flex-column gap-l radius-l bg-color_color-white">
    
    <div class="iddi-taxonomy-course-catetory-courses__thumb iddi-courses__online-tutorial-courses-thumb">
        <a href="<?php the_permalink(); ?>" class="d-flex">
            <?php if (has_post_thumbnail()) : ?>
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>" class="radius-s cover-image full-width">
            <?php endif; ?>
        </a>
    </div>

    <h3 class="iddi-taxonomy-course-catetory-courses__name iddi-courses__online-tutorial-courses-name fs-32 fw-400 text-color-oxford-blue">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <div class="iddi-taxonomy-course-catetory-courses__excerpt iddi-courses__online-tutorial-courses-excerpt fs-24 fw-300 text-color-oxford-blue">
        <?php 
        $excerpt = get_the_excerpt();
        $excerpt = preg_replace( '/\[\s*(\.|\x{2026}|&hellip;)*\s*\]/u', '...', $excerpt );
        ?>
        <p><?php echo esc_html( trim( $excerpt ) ); ?></p>
    </div>

    <div class="d-flex flex-jc-between flex-ai-end">
        <div class="iddi-taxonomy-course-catetory-courses__meta iddi-courses__online-tutorial-courses-meta d-flex flex-column gap-s">
            <span class="iddi-taxonomy-course-catetory-courses__price price d-block fs-32 fw-700 text-color-flame-orange">
                <?php echo $course->get_price_html(); ?>
            </span>
            <span class="iddi-taxonomy-course-catetory-courses__date date fs-20 fw-300 text-color-oxford-blue">
                <?php echo esc_html($duration); ?>
            </span>
        </div>
        <div class="iddi-course-details-info__card-btn-wrapper">
            <a href="<?php the_permalink(); ?>" class="iddi-course-details-info__card-btn iddi-courses__online-tutorial-courses-enrol fs-24 fw-600 padding-s__v center-text text-color-white radius-s bg-color_color-flame-orange d-block" style="min-width: 100px;">
                Xem chi tiết
            </a>
        </div>
    </div>

</article>
<?php endif; ?>
