<article class="iddi-events__item d-flex flex-column">
	<div class="iddi-events__item-thumbnail">
		<a class="d-flex" href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail('large', array('class' => 'iddi-events__img')); ?>
			<?php else : ?>
				<img src="/wp-content/uploads/2026/03/default-image.jpg" alt="<?php the_title(); ?>" class="iddi-events__img square">
			<?php endif; ?>
		</a>
	</div>
	<h3 class="iddi-events__item-title fw-500">
		<a class="text-color-oxford-blue" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>
	<div class="iddi-events__item-meta">
		<p class="iddi-events__item-info text-color-oxford-blue">
			<span class="iddi-events__label text-color-flame-orange">Thời gian:</span> 
			<span class="iddi-events__value"><?php echo esc_html(get_post_meta(get_the_ID(), 'date_and_time_event', true)); ?></span>
		</p>
		<?php if ( get_post_meta(get_the_ID(), 'address_event', true) ) : ?>
		<p class="iddi-events__item-info text-color-oxford-blue">
			<span class="iddi-events__label text-color-flame-orange">Địa điểm:</span> 
			<span class="iddi-events__value"><?php echo esc_html(get_post_meta(get_the_ID(), 'address_event', true)); ?></span>
		</p>
		<?php endif; ?>
	</div>
</article>
