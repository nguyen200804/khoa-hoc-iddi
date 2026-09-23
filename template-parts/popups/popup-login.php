<?php
/**
 * Template Part: Popup Login
 * ID: #popup-login-global
 */
?>

<div id="popup-login-global" class="iddi-popup iddi-popup--auth p-fixed inset-0 z-50 d-none flex-center-h flex-ai-center">
    <div class="iddi-popup__overlay p-absolute inset-0 bg-color_color-oxford-blue opacity-8"></div>
    <div class="iddi-popup__container p-relative bg-color_color-white shadow-xl">
        <button class="iddi-popup__close reset-button p-absolute" onclick="window.closeLoginPopup()">
            <?php echo get_my_svg('times'); ?>
        </button>

        <div class="iddi-auth-header center-text margin-bottom-l">
            <h2 class="iddi-auth-header__title fw-700 txt-color_color-oxford-blue">Welcome Back</h2>
            <p class="iddi-auth-header__subtitle txt-color_color-oxford-blue opacity-7">Sign in to your account</p>
        </div>

        <form id="iddi-login-form-popup" class="iddi-auth-form d-flex flex-column gap-l">
            <div class="form-group d-flex flex-column gap-s">
                <label for="user_login_popup" class="iddi-auth-form__label fw-600 upper-text">Username or Email</label>
                <input type="text" name="username" id="user_login_popup" class="iddi-auth-input border-width-1 border_color-alice-blue" placeholder="Enter your username" required>
            </div>
            <div class="form-group d-flex flex-column gap-s">
                <label for="user_pass_popup" class="iddi-auth-form__label fw-600 upper-text">Password</label>
                <div class="password-wrapper p-relative">
                    <input type="password" name="password" id="user_pass_popup" class="iddi-auth-input border-width-1 border_color-alice-blue full-width" placeholder="********" required>
                    <button type="button" class="password-toggle p-absolute reset-button" onclick="togglePassword('user_pass_popup')">
                        <?php echo get_my_svg('eye'); ?>
                    </button>
                </div>
            </div>
            <div class="iddi-auth-form__actions form-actions d-flex flex-jc-between flex-ai-center">
                <label class="remember-me d-flex flex-ai-center gap-s cursor-pointer">
                    <input type="checkbox" name="remember" value="forever"> Remember me
                </label>
                <a href="<?php echo wp_lostpassword_url(); ?>" class="lost-password txt-color_color-flame-orange fw-500">Forgot your password?</a>
            </div>
            <div id="login-message-popup" class="auth-message d-none padding-s radius-s center-text"></div>
            <button type="submit" class="iddi-auth-submit btn-flame bg-color_color-flame-orange txt-color_color-white fw-600 cursor-pointer border-none transition-03">
                Log in
            </button>
        </form>

        <div class="iddi-auth-footer center-text margin-top-xl">
            <p class="iddi-auth-footer__text">Don't have an account? <a href="javascript:void(0)" onclick="window.closeLoginPopup(); window.openRegisterPopup();" class="txt-color_color-flame-orange fw-600">Register now</a></p>
        </div>
    </div>
</div>
