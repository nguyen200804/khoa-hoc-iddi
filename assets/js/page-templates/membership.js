/**
 * JS for Membership Page
 */
jQuery(document).ready(function($) {
    // Example logic: Change Avatar Click
    $('.iddi-membership-profile__avatar-edit, .iddi-membership-profile__btn').on('click', function(e) {
        e.preventDefault();
        alert('Tính năng tải lên Avatar sẽ được tích hợp tại đây!');
    });

    // Form submission UI logic
    $('.iddi-membership-form').on('submit', function() {
        var $btn = $(this).find('.iddi-membership-form__btn-submit');
        $btn.html('Saving...').prop('disabled', true);
        // We let it submit normally for PHP processing
    });

    // Accordion Logic for Support Tab
    $('.iddi-membership-faq__header').on('click', function() {
        var $faq = $(this).parent('.iddi-membership-faq');
        var $content = $(this).next('.iddi-membership-faq__content');
        
        if ( $faq.hasClass('iddi-membership-faq--active') ) {
            $faq.removeClass('iddi-membership-faq--active');
            $content.slideUp(300);
        } else {
            // Close others to create a true accordion effect
            $('.iddi-membership-faq').removeClass('iddi-membership-faq--active');
            $('.iddi-membership-faq__content').slideUp(300);
            
            $faq.addClass('iddi-membership-faq--active');
            $content.slideDown(300);
        }
    });
});
