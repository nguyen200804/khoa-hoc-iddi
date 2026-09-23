document.addEventListener('DOMContentLoaded', function() {
    const contentBody = document.querySelector('.single-post__body');
    const tocList = document.getElementById('single-post-toc-list');
    const tocToggle = document.getElementById('single-post-toc-toggle');
    const tocDropdown = document.getElementById('single-post-toc-dropdown');
    
    if (contentBody && tocList) {
        const headings = contentBody.querySelectorAll('h2, h3');
        
        if (headings.length > 0) {
            headings.forEach((heading, index) => {
                // Assign ID to heading if not present
                if (!heading.id) {
                    heading.id = 'heading-' + index;
                }
                
                // Create TOC item
                const a = document.createElement('a');
                a.href = '#' + heading.id;
                a.textContent = heading.textContent;
                a.className = 'single-post__toc-link';
                if (heading.tagName.toLowerCase() === 'h3') {
                    a.style.paddingLeft = '15px';
                    a.style.fontSize = '13px';
                }
                
                // Add smooth scrolling effect
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        // Giữ lại khoảng trống cho header phía trên (khoảng 100px)
                        const offset = 100;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Cập nhật URL mà không gây nhảy trang
                        history.pushState(null, null, '#' + targetId);
                        
                        // Đóng popup trên mobile nếu đang mở
                        const tocWrapper = document.querySelector('.single-post__toc-wrapper');
                        if (tocWrapper && tocWrapper.classList.contains('is-open')) {
                            tocWrapper.classList.remove('is-open');
                            if (tocToggle) tocToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                tocList.appendChild(a);
            });
            
            // Add scroll spy
            const tocLinks = tocList.querySelectorAll('.single-post__toc-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                headings.forEach(heading => {
                    const headingTop = heading.getBoundingClientRect().top;
                    // Offset for sticky header if any, usually 100-150px
                    if (headingTop < 150) {
                        current = heading.getAttribute('id');
                    }
                });
                
                tocLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
                
                // Highlight first element if at the top
                if (!current && tocLinks.length > 0) {
                     tocLinks[0].classList.add('active');
                }
            });
            
            // Initial trigger
            window.dispatchEvent(new Event('scroll'));
            
            // Toggle Logic for Mobile
            const tocWrapper = document.querySelector('.single-post__toc-wrapper');
            if (tocToggle && tocDropdown && tocWrapper) {
                tocToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = tocWrapper.classList.contains('is-open');
                    if (isOpen) {
                        tocWrapper.classList.remove('is-open');
                        tocToggle.setAttribute('aria-expanded', 'false');
                    } else {
                        tocWrapper.classList.add('is-open');
                        tocToggle.setAttribute('aria-expanded', 'true');
                    }
                });
                
                // Đóng khi click ra ngoài
                document.addEventListener('click', function(e) {
                    if (tocWrapper.classList.contains('is-open') && !tocWrapper.contains(e.target)) {
                        tocWrapper.classList.remove('is-open');
                        tocToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        } else {
            // Hide sidebar if no headings
            const sidebar = document.querySelector('.single-post__sidebar');
            if (sidebar) sidebar.style.display = 'none';
        }
    }
});
