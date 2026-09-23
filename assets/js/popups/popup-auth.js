/**
 * Common JS for Login & Register Popups
 */
jQuery(document).ready(function($) {
    const $loginPopup = $('#popup-login-global');
    const $registerPopup = $('#popup-register-global');

    // --- LOGIN FUNCTIONS ---
    window.openLoginPopup = function() {
        $loginPopup.css('display', 'flex').hide().fadeIn(300);
        $('body').addClass('overflow-hidden');
    };

    window.closeLoginPopup = function() {
        $loginPopup.fadeOut(300, function() {
            $(this).css('display', 'none');
            if (!$('.iddi-popup:visible').length) {
                $('body').removeClass('overflow-hidden');
            }
        });
    };

    // --- REGISTER FUNCTIONS ---
    window.openRegisterPopup = function() {
        $registerPopup.css('display', 'flex').hide().fadeIn(300);
        $('body').addClass('overflow-hidden');
    };

    window.closeRegisterPopup = function() {
        $registerPopup.fadeOut(300, function() {
            $(this).css('display', 'none');
            if (!$('.iddi-popup:visible').length) {
                $('body').removeClass('overflow-hidden');
            }
        });
    };

    // --- GLOBAL HELPERS ---
    window.togglePassword = function(id) {
        const input = document.getElementById(id);
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    };

    // --- EVENTS ---
    $('.iddi-popup__overlay').on('click', function() {
        closeLoginPopup();
        closeRegisterPopup();
    });

    $(document).on('keydown', function(e) {
        if (e.key === "Escape") {
            closeLoginPopup();
            closeRegisterPopup();
        }
    });

    $('.iddi-popup__container').on('click', function(e) {
        e.stopPropagation();
    });

    // --- AJAX LOGIN ---
    $('#iddi-login-form-popup').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $msg = $('#login-message-popup');
        const $btn = $form.find('button[type="submit"]');

        $msg.removeClass('d-none error success').addClass('d-block').text('Authenticating...');
        $btn.prop('disabled', true).css('opacity', '0.7');

        $.ajax({
            url: iddi_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'iddi_ajax_login',
                username: $('#user_login_popup').val(),
                password: $('#user_pass_popup').val(),
                remember: $form.find('input[name="remember"]').is(':checked'),
                security: iddi_vars.login_nonce
            },
            success: function(response) {
                if (response.success) {
                    $msg.addClass('success').text(response.data.message);
                    setTimeout(() => {
                        window.location.href = response.data.redirect;
                    }, 1000);
                } else {
                    $msg.addClass('error').text(response.data.message);
                    $btn.prop('disabled', false).css('opacity', '1');
                }
            },
            error: function() {
                $msg.addClass('error').text('An error occurred.');
                $btn.prop('disabled', false).css('opacity', '1');
            }
        });
    });

    // --- AJAX REGISTER ---
    $('#iddi-register-form-popup').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $msg = $('#register-message-popup');
        const $btn = $form.find('button[type="submit"]');

        $msg.removeClass('d-none error success').addClass('d-block').text('Creating account...');
        $btn.prop('disabled', true).css('opacity', '0.7');

        $.ajax({
            url: iddi_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'iddi_ajax_register',
                first_name: $('#reg_firstname_popup').val(),
                last_name: $('#reg_lastname_popup').val(),
                username: $('#reg_username_popup').val(),
                email: $('#reg_email_popup').val(),
                password: $('#reg_pass_popup').val(),
                security: iddi_vars.register_nonce
            },
            success: function(response) {
                if (response.success) {
                    $msg.addClass('success').text(response.data.message);
                    setTimeout(() => {
                        window.location.href = response.data.redirect;
                    }, 1500);
                } else {
                    $msg.addClass('error').text(response.data.message);
                    $btn.prop('disabled', false).css('opacity', '1');
                }
            },
            error: function() {
                $msg.addClass('error').text('An error occurred.');
                $btn.prop('disabled', false).css('opacity', '1');
            }
        });
    });

    // --- LINK INTERCEPTOR ---
    $(document).on('click', 'a[href*="/login/"], a[href*="/register/"]', function(e) {
        const href = $(this).attr('href');
        if (href.includes('/login')) {
            e.preventDefault();
            openLoginPopup();
        } else if (href.includes('/register')) {
            e.preventDefault();
            openRegisterPopup();
        }
    });
});
