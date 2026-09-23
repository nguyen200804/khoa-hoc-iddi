<?php 
/**
 * Section: Table Price
 */
enqueue_section_assets('section-table-price', true); 
?>
<section class="iddi-section-table-price">

	<div class="iddi-section-table-price__header italic-font center-text">
		<p class="fs-24 italic text-color-flame-orange">Easy as one, two, three</p>
		<h2 class="fs-48 fw-300 text-color-oxford-blue">
			Choose your IDDI Membership plan and LAUNCH your Digital Education to the next level!
		</h2>
	</div>


	<?php
	$membership_plans = [
		[
			'name'        => 'Free',
			'price'       => '£0',
			'period'      => '/mo', // Đơn vị tháng
			'badge'       => 'FREE MEMBERS',
			'sub_title'   => 'LIMITED ACCESS TO THE E-LEARNING SECTION WITH FREE COURSES',
			'description' => 'BECOME PART OF OUR INTERNATIONAL MEMBERSHIP TO DISCOVER HOW TO BECOME AND EXPERT IN DIGITAL DENTISTRY.',
			'btn_class'   => 'bg-color_color-flame-orange',
			'benefits'    => [
				'Regularly receive IDDI Newsletter',
				'Access to purchase courses and education',
				'IDDI Support Forum',
				'Access Free IDDI E-learning Tutorials',
				'Access to Free Digital Master Tutorial Videos',
				'Exclusive STL Downloads',
				'Free Tooth Libraries',
				'Guidelines and Tutorials'
			]
		],
		[
			'name'        => 'IDDI Membership',
			'price'       => '£50',
			'period'      => '/mo',
			'badge'       => 'GET ACCESS TO ALL IDDI BENEFITS.',
			'sub_title'   => 'FULL ACCESS TO THE E-LEARNING TUTORIALS SECTION & ONLINE COURSES',
			'description' => 'THE IDDEA MEMBERSHIP INCLUDES A TICKET TO OUR YEARLY CONFERENCE SO YOU CAN JOIN IN COMMUNITY EVENTS AND CATCH UP WITH OTHER DIGITAL STUDENTS.',
			'btn_class'   => 'bg-color_color-brown',
			'benefits'    => [
				'Regularly receive IDDI Newsletter',
				'Access to purchase courses and education',
				'IDDI Support Forum',
				'Access IDDI E-learning Tutorials',
				'Access to Digital Master Tutorial Videos',
				'Exclusive STL Downloads',
				'Free Tooth Libraries',
				'Guidelines and Tutorials',
				'100% Off Ticket (Worth £500) to ALL IDDI Conferences after 6 months of membership',
				'24/7 Team Support'
			]
		],
		[
			'name'        => 'MSc in Digital Dentistry',
			'price'       => 'from £185',
			'period'      => '/mo', // Có thể đổi thành /yr nếu cần
			'badge'       => '(MIN 36 MONTH TERM. COST OPTIONS DEPENDENT ON UP FRONT COST AND TERM)',
			'sub_title'   => 'GET ACCESS TO ALL IDDI BENEFITS.',
			'description' => 'ACCESS TO THE LEVEL 7 DIGITAL DENTISTRY CURRICULUM WITH A MSC SPECIALIST PRACTICE OF DIGITAL DENTISTRY TECHNOLOGY',
			'btn_class'   => 'bg-color_color-oxford-blue',
			'benefits'    => [
				'Regularly receive IDDI Newsletter',
				'Access to purchase courses and education',
				'IDDI Support Forum',
				'Access IDDI E-learning Tutorials',
				'Access to Digital Master Tutorial Videos',
				'Exclusive STL Downloads',
				'Free Tooth Libraries',
				'Guidelines and Tutorials',
				'100% Off Ticket (Worth £500) to ALL IDDI Conferences after 6 months of membership',
				'MSc in Digital Dentistry',
				'36 Months Team Support'
			]
		]
	];
	?>



	<div class="iddi-section-table-price__list d-grid g-column-3 gap-2xl">

		<?php foreach ($membership_plans as $plan) : ?>
		<article class="iddi-section-table-price__card bg-color_color-white border_color-oxford-blue border-width-1 padding-xl radius-l d-flex flex-column gap-xl">

			<h3 class="iddi-section-table-price__plan-name fs-24 fw-700 text-color-oxford-blue">
				<?php echo $plan['name']; ?>
			</h3>

			<div class="iddi-section-table-price__price-wrap fw-400 text-color-oxford-blue d-flex flex-ai-end">
				<span class="price"><?php echo $plan['price']; ?></span>
				<?php if (!empty($plan['period'])) : ?>
				<span class="period"><?php echo $plan['period']; ?></span>
				<?php endif; ?>
			</div>

			<div class="iddi-section-table-price__badge fs-20 fw-500 text-color-flame-orange">
				<?php echo $plan['badge']; ?>
			</div>

			<h4 class="iddi-section-table-price__sub-title fs-20 fw-600 text-color-oxford-blue upper-text">
				<?php echo $plan['sub_title']; ?>
			</h4>

			<div class="iddi-section-table-price__summary fs-20 fw-400 text-color-oxford-blue upper-text">
				<?php echo $plan['description']; ?>
			</div>

			<div class="iddi-section-table-price__benefit-list margin-xl__t flex-grow-1">
				<ul class="d-flex flex-column gap-s text-color-oxford-blue list-none padding-0">
					<?php foreach ($plan['benefits'] as $benefit) : ?>
					<li class="iddi-section-table-price__benefit-item d-flex flex-ai-start gap-s fs-18">
						<?php echo get_my_svg('check'); ?>
						<span class=""><?php echo $benefit; ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="iddi-section-table-price__action margin-1xs__t">
				<a href="#" class="iddi-section-table-price__btn fs-20 d-block padding-s__v text-color-white radius-m center-text fw-600 <?php echo $plan['btn_class']; ?>">
					Subscribe Now!
				</a>
			</div>

		</article>
		<?php endforeach; ?>

	</div>
</section>