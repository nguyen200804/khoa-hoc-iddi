<?php
/**
 * Template Name: Register Template
 */

if (is_user_logged_in()) {
    wp_redirect(home_url('/membership/'));
    exit;
}

get_header(); ?>

<main class="iddi-auth-page iddi-register-page bg-img-fixed">
    <div class="container padding-y-2xl">
        <div class="iddi-auth-card radius-l bg-color_color-white padding-2xl shadow-l">
            <div class="iddi-auth-header center-text margin-bottom-xl">
                <h1 class="iddi-auth-title fs-48 fw-700 txt-color_color-oxford-blue">Join IDDI Academy</h1>
                <p class="iddi-auth-subtitle fs-18 fw-400 txt-color_color-oxford-blue">Create an account to start your learning journey.</p>
            </div>

            <form id="iddi-register-form" class="iddi-auth-form d-flex flex-column gap-l">
                <div class="form-group d-flex flex-column gap-s">
                    <label for="reg_lastname" class="fs-16 fw-600">Họ</label>
                    <input type="text" name="last_name" id="reg_lastname" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue" placeholder="Nhập họ" required>
                </div>

                <div class="form-group d-flex flex-column gap-s">
                    <label for="reg_firstname" class="fs-16 fw-600">Tên</label>
                    <input type="text" name="first_name" id="reg_firstname" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue" placeholder="Nhập tên" required>
                </div>

                <div class="form-group d-flex flex-column gap-s">
                    <label for="reg_username" class="fs-16 fw-600">Username</label>
                    <input type="text" name="username" id="reg_username" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue" placeholder="Choose a username" required>
                </div>

                <div class="form-group d-flex flex-column gap-s">
                    <label for="reg_email" class="fs-16 fw-600">Email Address</label>
                    <input type="email" name="email" id="reg_email" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue" placeholder="your@email.com" required>
                </div>

                <div class="form-group d-flex flex-column gap-s">
                    <label for="reg_pass" class="fs-16 fw-600">Mật khẩu</label>
                    <div class="password-wrapper p-relative">
                        <input type="password" name="password" id="reg_pass" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue full-width" placeholder="••••••••" required>
                        <button type="button" class="password-toggle p-absolute reset-button" onclick="togglePassword('reg_pass')">
                            <?php echo get_my_svg('eye'); ?>
                        </button>
                    </div>
                </div>

                <div class="form-group d-flex flex-column gap-s">
                    <label for="reg_pass_confirm" class="fs-16 fw-600">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirm" id="reg_pass_confirm" class="iddi-auth-input padding-m radius-s border-width-1 border_color-alice-blue" placeholder="••••••••" required>
                </div>

                <div id="register-message" class="auth-message d-none padding-s radius-s center-text fs-14"></div>

                <button type="submit" class="iddi-auth-submit btn-flame bg-color_color-flame-orange txt-color_color-white padding-m radius-s fs-18 fw-600 cursor-pointer border-none transition-03">
                    Register Now
                </button>
            </form>

            <div class="iddi-auth-footer center-text margin-top-xl">
                <p class="fs-16">Already have an account? 
                    <?php 
                    $login_page = get_pages(array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'page-templates/login.php'
                    ));
                    $login_url = ($login_page) ? get_permalink($login_page[0]->ID) : wp_login_url();
                    ?>
                    <a href="<?php echo esc_url($login_url); ?>" class="txt-color_color-flame-orange fw-600">Login Here</a>
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
    $('#iddi-register-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $msg = $('#register-message');
        const $btn = $form.find('button[type="submit"]');

        if ($('#reg_pass').val() !== $('#reg_pass_confirm').val()) {
            $msg.removeClass('d-none success').addClass('d-block error').text('Passwords do not match.');
            return;
        }

        $msg.removeClass('d-none error success').addClass('d-block').text('Creating account...');
        $btn.prop('disabled', true).css('opacity', '0.7');

        $.ajax({
            url: iddi_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'iddi_ajax_register',
                first_name: $('#reg_firstname').val(),
                last_name: $('#reg_lastname').val(),
                username: $('#reg_username').val(),
                email: $('#reg_email').val(),
                password: $('#reg_pass').val(),
                security: '<?php echo wp_create_nonce("iddi-register-nonce"); ?>'
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
                $msg.addClass('error').text('An error occurred. Please try again.');
                $btn.prop('disabled', false).css('opacity', '1');
            }
        });
    });
});
</script>

<style>
.iddi-auth-card {
    max-width: 500px;
    margin: 0 auto;
}
.password-toggle {
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}
.auth-message.error {
    background-color: #fee2e2;
    color: #dc2626;
}
.auth-message.success {
    background-color: #dcfce7;
    color: #16a34a;
}
.transition-03 {
    transition: all 0.3s ease;
}
.iddi-auth-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(228, 94, 37, 0.3);
}
</style>

<?php get_footer(); ?>
