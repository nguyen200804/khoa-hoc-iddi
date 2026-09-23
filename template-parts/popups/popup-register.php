<?php
/**
 * Template Part: Popup Register
 * ID: #popup-register-global
 */
?>

<div id="popup-register-global" class="iddi-popup iddi-popup--auth p-fixed inset-0 z-50 d-none flex-center-h flex-ai-center">
    <div class="iddi-popup__overlay p-absolute inset-0 bg-color_color-oxford-blue opacity-8"></div>
    <div class="iddi-popup__container p-relative bg-color_color-white shadow-xl">
        <button class="iddi-popup__close reset-button p-absolute" onclick="window.closeRegisterPopup()">
            <?php echo get_my_svg('times'); ?>
        </button>

        <div class="iddi-auth-header center-text margin-bottom-l">
            <h2 class="iddi-auth-header__title fw-700 txt-color_color-oxford-blue">Join Academy</h2>
            <p class="iddi-auth-header__subtitle txt-color_color-oxford-blue opacity-7">Start your learning journey today</p>
        </div>

        <form id="iddi-register-form-popup" class="iddi-auth-form d-flex flex-column gap-l">
            <div class="form-group d-flex flex-column">
                <label for="reg_lastname_popup" class="fw-600 iddi-auth-form__label upper-text">Họ</label>
                <input type="text" name="last_name" id="reg_lastname_popup" class="iddi-auth-input border-width-1 border_color-alice-blue" placeholder="Nhập họ" required>
            </div>
            <div class="form-group d-flex flex-column">
                <label for="reg_firstname_popup" class="fw-600 iddi-auth-form__label upper-text">Tên</label>
                <input type="text" name="first_name" id="reg_firstname_popup" class="iddi-auth-input border-width-1 border_color-alice-blue" placeholder="Nhập tên" required>
            </div>
            <div class="form-group d-flex flex-column">
                <label for="reg_username_popup" class="fw-600 iddi-auth-form__label upper-text">Username</label>
                <input type="text" name="username" id="reg_username_popup" class="iddi-auth-input border-width-1 border_color-alice-blue" placeholder="Choose a username" required>
            </div>
            <div class="form-group d-flex flex-column">
                <label for="reg_email_popup" class="fw-600 iddi-auth-form__label upper-text">Email</label>
                <input type="email" name="email" id="reg_email_popup" class="iddi-auth-input border-width-1 border_color-alice-blue" placeholder="your@email.com" required>
            </div>
            <div class="form-group d-flex flex-column">
                <label for="reg_pass_popup" class="fw-600 iddi-auth-form__label upper-text">Mật khẩu</label>
                <div class="password-wrapper p-relative">
                    <input type="password" name="password" id="reg_pass_popup" class="iddi-auth-input border-width-1 border_color-alice-blue full-width" placeholder="••••••••" required>
                    <button type="button" class="password-toggle p-absolute reset-button" onclick="togglePassword('reg_pass_popup')">
                        <?php echo get_my_svg('eye'); ?>
                    </button>
                </div>
            </div>
            <div id="register-message-popup" class="auth-message d-none padding-s radius-s center-text fs-14"></div>
            <button type="submit" class="iddi-auth-submit btn-flame bg-color_color-flame-orange txt-color_color-white padding-m radius-s fw-600 cursor-pointer border-none transition-03">
                Create Account
            </button>
        </form>

        <div class="iddi-auth-footer center-text margin-top-xl">
            <p class="iddi-auth-footer__text">Already have an account? <a href="javascript:void(0)" onclick="window.closeRegisterPopup(); window.openLoginPopup();" class="iddi-auth-footer__text txt-color_color-flame-orange fw-600">Login Here</a></p>
        </div>
    </div>
</div>
