<?php
/**
 * Template Name: Login Template
 */

if (is_user_logged_in()) {
    wp_redirect(home_url('/membership/'));
    exit;
}

get_header(); ?>

<main class="iddi-auth-page iddi-login-page bg-img-fixed">
    <div class="container padding-y-2xl">
        <div class="iddi-auth-card radius-l bg-color_color-white padding-2xl shadow-l">
            <div class="iddi-auth-header center-text margin-bottom-xl">
                <h1 class="iddi-auth-title fs-48 fw-700 txt-color_color-oxford-blue">Welcome Back</h1>
                <p class="iddi-auth-subtitle fs-18 fw-400 txt-color_color-oxford-blue">Sign in to your academic dashboard</p>
            </div>

            <form id="iddi-login-form" class="iddi-auth-form d-flex flex-column gap-l">
                <div class="form-group d-flex flex-column gap-s">
                    <label for="user_login" class="fs-16 fw-600">Email Address</label>
                    <input type="text" name="username" id="user_login" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue" placeholder="Enter your username" required>
                </div>

                <div class="form-group d-flex flex-column gap-s">
                    <label for="user_pass" class="fs-16 fw-600">Password</label>
                    <div class="password-wrapper p-relative">
                        <input type="password" name="password" id="user_pass" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue full-width" placeholder="********" required>
                        <button type="button" class="password-toggle p-absolute reset-button" onclick="togglePassword('user_pass')">
                            <?php echo get_my_svg('eye'); ?>
                        </button>
                    </div>
                </div>

                <div class="form-actions d-flex flex-jc-between flex-ai-center">
                    <label class="remember-me d-flex flex-ai-center gap-s cursor-pointer">
                        <input type="checkbox" name="remember" value="forever"> Remember me
                    </label>
                    <a href="<?php echo wp_lostpassword_url(); ?>" class="lost-password fs-14 txt-color_color-flame-orange fw-500">Forgot Password?</a>
                </div>

                <div id="login-message" class="auth-message d-none padding-s radius-s center-text "></div>

                <button type="submit" class="iddi-auth-submit btn-flame bg-color_color-flame-orange txt-color_color-white padding-m radius-s fw-600 cursor-pointer border-none transition-03">
                    Log in
                </button>
            </form>

            <div class="iddi-auth-footer center-text margin-top-xl">
                <p>Don't have an account? 
                    <?php 
                    $register_page = get_pages(array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'page-templates/register.php'
                    ));
                    $register_url = ($register_page) ? get_permalink($register_page[0]->ID) : wp_registration_url();
                    ?>
                    <a href="<?php echo esc_url($register_url); ?>" class="txt-color_color-flame-orange fw-600">Create Account</a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

jQuery(document).ready(function($) {
    $('#iddi-login-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $msg = $('#login-message');
        const $btn = $form.find('button[type="submit"]');

        $msg.removeClass('d-none error success').addClass('d-block').text('Authenticating...');
        $btn.prop('disabled', true).css('opacity', '0.7');

        $.ajax({
            url: iddi_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'iddi_ajax_login',
                username: $('#user_login').val(),
                password: $('#user_pass').val(),
                remember: $('input[name="remember"]').is(':checked'),
                security: '<?php echo wp_create_nonce("iddi-login-nonce"); ?>'
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
                $msg.addClass('error').text('An error occurred. Please try again.');
                $btn.prop('disabled', false).css('opacity', '1');
            }
        });
    });
});
</script>

<?php get_footer(); ?>
