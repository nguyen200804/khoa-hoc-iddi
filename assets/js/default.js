document.addEventListener('DOMContentLoaded', function() {
    // Tìm tất cả các khung chứa video
    const videoWrappers = document.querySelectorAll('.video-wrapper');

    videoWrappers.forEach(wrapper => {
        const video = wrapper.querySelector('video');
        const btnPlay = wrapper.querySelector('.btn-play'); // Giả sử bạn thêm class .btn-play
        const btnPause = wrapper.querySelector('.btn-pause'); // Giả sử bạn thêm class .btn-pause

        // Hàm xử lý Play
        const playVideo = () => {
            video.play();
            if(btnPlay) btnPlay.style.display = 'none';
            if(btnPause) btnPause.style.display = 'flex';
        };

        // Hàm xử lý Pause
        const pauseVideo = () => {
            video.pause();
            if(btnPause) btnPause.style.display = 'none';
            if(btnPlay) btnPlay.style.display = 'flex';
        };

        // Sự kiện click nút Play
        if(btnPlay) {
            btnPlay.addEventListener('click', (e) => {
                e.stopPropagation();
                playVideo();
            });
        }

        // Sự kiện click nút Pause
        if(btnPause) {
            btnPause.addEventListener('click', (e) => {
                e.stopPropagation();
                pauseVideo();
            });
        }

        // Click trực tiếp vào video để Toggle Play/Pause
        video.addEventListener('click', () => {
            if (video.paused) {
                playVideo();
            } else {
                pauseVideo();
            }
        });
    });

    // Tự động điều chỉnh độ rộng của thẻ select theo nội dung bên trong
    const autoResizeSelects = document.querySelectorAll('.iddi__page-header__filter-select, .auto-width-select');
    function fitSelectWidth(el) {
        if (!el.options[el.selectedIndex]) return;
        const tempSpan = document.createElement('span');
        tempSpan.style.visibility = 'hidden';
        tempSpan.style.position = 'absolute';
        tempSpan.style.whiteSpace = 'nowrap';
        const computedStyle = window.getComputedStyle(el);
        tempSpan.style.fontFamily = computedStyle.fontFamily;
        tempSpan.style.fontSize = computedStyle.fontSize;
        tempSpan.style.fontWeight = computedStyle.fontWeight;
        tempSpan.style.textTransform = computedStyle.textTransform;
        tempSpan.innerText = el.options[el.selectedIndex].text;
        document.body.appendChild(tempSpan);
        el.style.width = `calc(${tempSpan.offsetWidth}px + 1.5rem)`;
        document.body.removeChild(tempSpan);
    }
    autoResizeSelects.forEach(select => {
        fitSelectWidth(select);
        select.addEventListener('change', function() { fitSelectWidth(this); });
    });
});





jQuery(document).ready(function($) {
    // Mở popup theo ID được định nghĩa trong data-popup
    $('.open-popup-btn').on('click', function() {
        var targetId = $(this).data('popup');
        $('#' + targetId).fadeIn();
    });

    // Đóng popup - CHỈ KHI BẤM NÚT X
    $('.close-popup').on('click', function() {
        var targetId = $(this).data('target');
        $('#' + targetId).fadeOut();
    });

    /* ĐÃ LOẠI BỎ: Phần đóng khi nhấn ra ngoài vùng .custom-popup-overlay 
       để bắt buộc người dùng tương tác với nút X.
    */
});