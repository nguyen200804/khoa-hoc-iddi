document.addEventListener( 'wpcf7beforesubmit', function( event ) {
    var btn = event.target.querySelector('.iddi-section-contact__submit-btn');
    if (btn) {
        btn.querySelector('span').innerText = 'ĐANG GỬI...';
        btn.classList.add('is-loading');
    }
}, false );

document.addEventListener( 'wpcf7submit', function( event ) {
    var btn = event.target.querySelector('.iddi-section-contact__submit-btn');
    if (btn) {
        btn.querySelector('span').innerText = 'GỬI NGAY';
        btn.classList.remove('is-loading');
    }
}, false );