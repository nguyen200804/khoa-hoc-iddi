<?php 
    // Lấy dữ liệu từ ACF với cơ chế an toàn đa ngôn ngữ
    $col1 = function_exists('iddi_get_field_option') ? iddi_get_field_option('footer__column_1') : get_field('footer__column_1', 'option');
    $col2 = function_exists('iddi_get_field_option') ? iddi_get_field_option('footer__column_2') : get_field('footer__column_2', 'option');
    $col3 = function_exists('iddi_get_field_option') ? iddi_get_field_option('footer__column_3') : get_field('footer__column_3', 'option');
    $copyright = function_exists('iddi_get_field_option') ? iddi_get_field_option('footer__copy_right') : get_field('footer__copy_right', 'option');
?>

<footer class="iddi-footer p-relative">
    <svg class="iddi-footer-wave p-absolute" viewBox="0 0 1920 103" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 36.425s310.486 127.241 984.749 17.357C1659.01-56.1 1920 36.425 1920 36.425V103H0z" fill="#fff"/>
    </svg>
    
    <div class="container d-flex flex-column gap-l gap-m__xl">
        <div class="iddi-footer__main d-flex flex-jc-between flex-wrap__md">

            <div class="iddi-footer__column iddi-footer__column--about d-flex flex-column gap-l gap-s__lg">
                <?php if($col1): ?>
                    <div class="iddi-footer__widget"> 
                        <p class="iddi-footer__widget-title fs-20 fw-500 text-color-oxford-blue fs-18__md fs-16__xl">
                            <?php echo esc_html($col1['title']); ?>
                        </p>
                    </div>
                    <div class="iddi-footer__widget"> 
                        <div class="iddi-footer__about-text fs-18 fw-400 text-color-oxford-blue line-h-130 fs-14__xl fs-14__lg">
                            <?php echo $col1['content']; // WYSIWYG editor ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="iddi-footer__column iddi-footer__column--links d-flex flex-column gap-l gap-s__lg">
                <?php if($col2): ?>
                    <div class="iddi-footer__widget"> 
                        <p class="iddi-footer__widget-title fs-20 fw-500 text-color-oxford-blue fs-18__md fs-16__xl">
                            <?php echo esc_html($col2['title']); ?>
                        </p>
                    </div>
                    <div class="iddi-footer__widget"> 
                        <?php
                            if(!empty($col2['select_menu'])) {
                                $footer_menu_id = $col2['select_menu'];
                                // Tự động lấy ID menu dịch tương ứng với ngôn ngữ hiện tại nếu có
                                if ( function_exists('pll_get_term') && is_numeric($footer_menu_id) ) {
                                    $trans_menu_id = pll_get_term( $footer_menu_id );
                                    if ( $trans_menu_id ) {
                                        $footer_menu_id = $trans_menu_id;
                                    }
                                }
                                wp_nav_menu(array(
                                    'menu'            => $footer_menu_id,
                                    'container'       => false,
                                    'menu_class'      => 'iddi-footer__link-list fs-18 fw-400 upper-text text-color-oxford-blue fs-14__xl fs-14__lg',
                                    'fallback_cb'     => false,
                                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                    'link_before'     => get_my_svg('chevron-right') . '<span>',
                                    'link_after'      => '</span>',
                                ));
                            }
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="iddi-footer__column iddi-footer__column--contact d-flex flex-column gap-l gap-s__lg">
                <?php if($col3): ?>
                    <div class="iddi-footer__widget"> 
                        <p class="iddi-footer__widget-title fs-20 fw-500 text-color-oxford-blue fs-18__md fs-16__xl">
                            <?php echo esc_html($col3['title']); ?>
                        </p>
                    </div>
                    <div class="iddi-footer__widget-content d-flex flex-column gap-m"> 
                        <?php if($col3['email']): ?>
                        <div class="iddi-footer__contact-item"> 
                            <a href="mailto:<?php echo antispambot($col3['email']); ?>" class="fs-18 d-flex flex-ai-center gap-s fs-14__xl fs-14__lg">
                                <?php echo get_my_svg('mail'); ?>
                                <span class="fw-400 line-h-130 text-color-oxford-blue"><?php echo esc_html($col3['email']); ?></span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if($col3['phone_number']): ?>
                        <div class="iddi-footer__contact-item"> 
                            <a href="tel:<?php echo preg_replace('/\D+/', '', $col3['phone_number']); ?>" class="fs-18 d-flex flex-ai-center gap-s fs-14__xl fs-14__lg">
                                <?php echo get_my_svg('phone'); ?>
                                <span class="fw-400 line-h-130 text-color-oxford-blue"><?php echo esc_html($col3['phone_number']); ?></span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if($col3['address']): ?>
                        <div class="iddi-footer__contact-item"> 
                            <p class="fs-18 fw-400 line-h-130 text-color-oxford-blue fs-14__xl fs-14__lg">
                                <?php echo esc_html($col3['address']); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="iddi-footer__social-list"> 
    <?php 
    if ( !empty($col3['social']) && is_array($col3['social']) ) : 
        foreach ( $col3['social'] as $item ) : 
            $social_url  = $item['url']; 
            $social_icon = $item['icon']; 
            
            if ( $social_icon ) : 
    ?>
                <a href="<?php echo esc_url($social_url ? $social_url : '#'); ?>" 
                   class="iddi-footer__social-link d-i-block circle-image padding-2xs"
                   target="_blank" 
                   rel="noopener noreferrer">
                    <?php 
                        // Kiểm tra nếu là URL (chọn từ Media Library)
                        if ( filter_var($social_icon, FILTER_VALIDATE_URL) ) {
                            // Chuyển URL thành đường dẫn server để đọc file nhanh và an toàn hơn
                            $path = str_replace(site_url('/'), ABSPATH, $social_icon);
                            
                            if ( file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) === 'svg' ) {
                                // Đọc và xuất trực tiếp code SVG
                                echo file_get_contents($path);
                            } else {
                                // Nếu không phải file vật lý, cố gắng đọc qua URL
                                echo wp_remote_retrieve_body(wp_remote_get($social_icon));
                            }
                        } else {
                            // Nếu là String bình thường (facebook, linkedin...), dùng hàm của bạn
                            echo get_my_svg($social_icon); 
                        }
                    ?>
                </a>
    <?php 
            endif;
        endforeach; 
    endif; 
    ?>
</div>
            </div>

        </div> 
        
        <div class="iddi-footer__bottom">
            <div class="iddi-footer__copyright fs-14 center-text text-color-oxford-blue fs-14__xl">
                <?php echo $copyright ? $copyright : (function_exists('iddi_tr') ? iddi_tr('Copyright © 2026 IDDI Academy. All rights reserved.') : 'Copyright © 2026 IDDI Academy'); ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>