// ===========================================
// START - HEADER & DROPDOWN MENU NAVIGATION
// ===========================================
(function($) {
    "use strict";
    $(function() {
        const $nav = $('.iddi-header__popup-menu-nav');
        const $menuItems = $nav.find('li > a');
        const $menuWithChildren = $nav.find('li.menu-item-has-children');
        const $btnMenu = $('.iddi-header__btn-menu > button');
        const $btnClose = $('.iddi-header__popup-menu-button-close > button'); // Nút đóng mới
        const $popupMenu = $('.iddi-header__popup-menu');
        const $body = $('body');

        // 1. Thêm class 'current-menu' và tự động mở menu con đang active
        $nav.find('li').each(function() {
            const $this = $(this);
            if ($this.attr('class') && $this.attr('class').indexOf('current') !== -1) {
                $this.addClass('current-menu');
                if ($this.hasClass('menu-item-has-children')) {
                    $this.addClass('is-open');
                    $this.children('.sub-menu').show(); 
                }
            }
        });

        // 2. Hàm cập nhật height của menu item vào CSS Variable
        const updateMenuHeight = () => {
            if ($menuItems.length) {
                const menuItemHeight = $menuItems.first().outerHeight();
                $nav.css('--height-menu-item', menuItemHeight + 'px');
            }
        };
        updateMenuHeight();

        // 3. Thêm biểu tượng mũi tên SVG (Giữ nguyên từ code của bạn)
        if ($menuWithChildren.length) {
            $menuWithChildren.append('<span class="dropdown-toggle-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M505.183 123.179c-9.087-9.087-23.824-9.089-32.912.002l-216.266 216.27L39.729 123.179c-9.087-9.087-23.824-9.089-32.912.002-9.089 9.089-9.089 23.824 0 32.912L239.55 388.82a23.27 23.27 0 0 0 32.91-.001l232.721-232.727c9.091-9.088 9.091-23.824.002-32.913"/></svg></span>');
        }

        // 4. Xử lý Mở Menu (Nút hamburger)
        $btnMenu.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $popupMenu.addClass('is-active');
            $(this).addClass('is-clicked');
            $body.addClass('no-scroll');
        });

        // 5. Xử lý Đóng Menu (Nút X trong popup)
        $btnClose.on('click', function(e) {
            e.preventDefault();
            $popupMenu.removeClass('is-active');
            $btnMenu.removeClass('is-clicked');
            $body.removeClass('no-scroll');
        });

        // 6. Xử lý sự kiện Click MŨI TÊN để xổ Sub-menu
        $nav.on('click', '.dropdown-toggle-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $parentLi = $(this).closest('li');
            const $subMenu = $parentLi.children('.sub-menu');

            $parentLi.siblings().removeClass('is-open').find('.sub-menu').slideUp(300);
            $parentLi.toggleClass('is-open');
            $subMenu.stop().slideToggle(300);
        });

        // 7. Đóng popup khi click ra ngoài vùng menu
        $(document).on('click', function(e) {
            if ($popupMenu.hasClass('is-active') && 
                !$popupMenu.is(e.target) && 
                $popupMenu.has(e.target).length === 0 && 
                !$(e.target).closest('.iddi-header__btn-menu').length) {
                
                $popupMenu.removeClass('is-active');
                $btnMenu.removeClass('is-clicked');
                $body.removeClass('no-scroll');
            }
        });

        // 8. Xử lý Toggle Dropdown Ngôn ngữ (Language Switcher)
        $(document).on('click', '.gt_switcher .gt_selected a, .iddi-header__lang-toggle', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $switcher = $(this).closest('.gt_switcher, .iddi-header__lang-dropdown');
            $switcher.toggleClass('is-open');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.gt_switcher, .iddi-header__lang-dropdown').length) {
                $('.gt_switcher, .iddi-header__lang-dropdown').removeClass('is-open');
            }
        });

        $(window).on('resize', updateMenuHeight);
    });
})(jQuery);
// ===========================================
// END - HEADER & DROPDOWN MENU NAVIGATION
// ===========================================





document.addEventListener('DOMContentLoaded', function() {
    const btnSearchOpen = document.querySelector('.button-open-search');
    const btnSearchClose = document.querySelector('.close-popup-search');
    const searchContainer = document.querySelector('.iddi-header-search');
    const searchInput = document.querySelector('.iddi-header-search__input');

    if (btnSearchOpen && searchContainer) {
        // Mở popup
        btnSearchOpen.addEventListener('click', function(e) {
            e.preventDefault();
            searchContainer.classList.add('open-popup');
            
            // Tự động focus vào ô input sau khi mở (delay nhẹ để chờ hiệu ứng CSS nếu có)
            setTimeout(() => {
                if(searchInput) searchInput.focus();
            }, 300);
        });
    }

    if (btnSearchClose && searchContainer) {
        // Đóng popup
        btnSearchClose.addEventListener('click', function(e) {
            e.preventDefault();
            searchContainer.classList.remove('open-popup');
        });
    }

    // Đóng popup khi click ra ngoài vùng tìm kiếm
    document.addEventListener('click', function(e) {
        if (searchContainer && searchContainer.classList.contains('open-popup') && 
            btnSearchOpen && !btnSearchOpen.contains(e.target) && 
            !searchContainer.contains(e.target)) {
            searchContainer.classList.remove('open-popup');
        }
    });

    // Tùy chọn: Đóng popup khi nhấn phím ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && searchContainer && searchContainer.classList.contains('open-popup')) {
            searchContainer.classList.remove('open-popup');
        }
    });
});
