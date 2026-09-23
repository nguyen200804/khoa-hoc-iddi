<div class="iddi-membership-sidebar">
    <h4 class="iddi-membership-sidebar__title">PORTAL MENU</h4>
    <nav class="iddi-membership-sidebar__nav">
<?php $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'personal-info'; ?>
        <ul class="iddi-membership-sidebar__list">
            <li class="iddi-membership-sidebar__item <?php echo $current_tab === 'personal-info' ? 'is-active' : ''; ?>">
                <a href="?tab=personal-info" class="iddi-membership-sidebar__link">
                    <span class="iddi-membership-sidebar__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <span class="iddi-membership-sidebar__text">Personal Info</span>
                </a>
            </li>
            <li class="iddi-membership-sidebar__item <?php echo $current_tab === 'my-courses' ? 'is-active' : ''; ?>">
                <a href="?tab=my-courses" class="iddi-membership-sidebar__link">
                    <span class="iddi-membership-sidebar__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </span>
                    <span class="iddi-membership-sidebar__text">My Courses</span>
                </a>
            </li>
            <li class="iddi-membership-sidebar__item <?php echo $current_tab === 'certificates' ? 'is-active' : ''; ?>">
                <a href="?tab=certificates" class="iddi-membership-sidebar__link">
                    <span class="iddi-membership-sidebar__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                    </span>
                    <span class="iddi-membership-sidebar__text">Certificates</span>
                </a>
            </li>
            <li class="iddi-membership-sidebar__item <?php echo $current_tab === 'order-history' ? 'is-active' : ''; ?>">
                <a href="?tab=order-history" class="iddi-membership-sidebar__link">
                    <span class="iddi-membership-sidebar__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </span>
                    <span class="iddi-membership-sidebar__text">Order History</span>
                </a>
            </li>
        </ul>

        <ul class="iddi-membership-sidebar__list iddi-membership-sidebar__list--bottom">
            <li class="iddi-membership-sidebar__item <?php echo $current_tab === 'support' ? 'is-active' : ''; ?>">
                <a href="?tab=support" class="iddi-membership-sidebar__link">
                    <span class="iddi-membership-sidebar__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </span>
                    <span class="iddi-membership-sidebar__text">Support</span>
                </a>
            </li>
            <li class="iddi-membership-sidebar__item">
                <a href="<?php echo wp_logout_url(home_url()); ?>" class="iddi-membership-sidebar__link iddi-membership-sidebar__link--logout">
                    <span class="iddi-membership-sidebar__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </span>
                    <span class="iddi-membership-sidebar__text">Sign Out</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
