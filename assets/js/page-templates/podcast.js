/**
 * Podcast Page Scripts
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Featured Episodes Swiper
    if (document.querySelector('.podcastFeaturedSwiper')) {
        const podcastSwiper = new Swiper('.podcastFeaturedSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            centeredSlides: true,
            loop: true,
            grabCursor: true,
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
            breakpoints: {
                640: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                }
            }
        });
    }

    // Filter functionality (Placeholder for UI interaction)
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');
            
            // In a real scenario, you would trigger an AJAX call or filter the DOM elements here
            console.log('Filtering by:', this.textContent);
        });
    });
});
