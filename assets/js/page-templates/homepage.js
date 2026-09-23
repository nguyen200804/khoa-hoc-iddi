document.addEventListener('DOMContentLoaded', function() {
	const swiper = new Swiper('.iddiHeroSwiper', {
		loop: true,
		autoplay: { delay: 2000 },
		pagination: { el: '.swiper-pagination', clickable: true },
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	});
});