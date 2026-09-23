<?php 
/**
 * Section: Contact
 */
enqueue_section_assets('section-contact', true); 
?>
<section class="iddi-section-contact" id="contact">
	<div class="iddi-section-contact__container d-flex flex-jc-between flex-column__md">

		<div class="iddi-section-contact__form-wrapper d-flex flex-column flex-jc-between">

    <div class="iddi-section-contact__header">
        <?php 
        $contact_main_title = function_exists('iddi_get_field_option') ? iddi_get_field_option('contact_section__main_title') : get_field('contact_section__main_title', 'option'); 
        if ( $contact_main_title ) : ?>
            <div class="iddi-section-contact__title fs-40 fw-300 italic-font text-color-oxford-blue fs-24__xl fs-20__lg">
                <?php echo wp_kses_post($contact_main_title); ?>
            </div>
        <?php endif; ?>
    </div>

    
			
			
			<?php 
			$contact_form_code = function_exists('iddi_tr') ? iddi_tr('[contact-form-7 id="7d92c07" title="Liên hệ ngay"]') : '[contact-form-7 id="7d92c07" title="Liên hệ ngay"]';
			echo do_shortcode( $contact_form_code ); 
			?>

</div>

		<div class="iddi-section-contact__map-wrapper">
			<div class="iddi-section-contact__map-frame">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1814.6769615025921!2d106.69692989253!3d10.740736089378272!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fa18c0db363%3A0x82951cbc0ad6f7a3!2zNjUgxJAuIE5ndXnhu4VuIFRo4buLIFRo4bqtcCwgS2h1IGTDom4gY8awIEhpbSBMYW0sIFF14bqtbiA3LCBUaMOgbmggcGjhu5EgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1773648227532!5m2!1svi!2s"  width="677" height="639"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>

	</div>
</section>