/**
 * JavaScript xử lý điều kiện hoàn thành bài học (Nâng cao)
 * Hỗ trợ: Chống tua, Playback Speed, và Khóa nút Hoàn thành (AJAX Compatible)
 */
(function($) {
    $(document).ready(function() {
        // Kiểm tra và log nền tảng video được dán mã nhúng
        $('iframe').each(function() {
            const src = $(this).attr('src') || '';
            let platform = '';
            
            if (src.includes('youtube.com') || src.includes('youtu.be')) {
                platform = 'YouTube';
            } else if (src.includes('vimeo.com')) {
                platform = 'Vimeo';
            } else if (src.includes('spotlightr.com') || src.includes('vooplayer.com')) {
                platform = 'Spotlightr';
            } else if (src) {
                platform = 'Khác (Không xác định)';
            }
            
            if (platform) {
                console.log(
                    '%c[Video Detector] Phát hiện mã nhúng video từ: ' + platform, 
                    'background: #1e293b; color: #38bdf8; font-weight: bold; padding: 4px 8px; border-radius: 4px;',
                    {
                        'Platform': platform,
                        'Iframe Element': this,
                        'Source URL': src
                    }
                );
            }
        });

        if (typeof iddi_lesson_config === 'undefined' || !iddi_lesson_config.enforce) {
            return;
        }

        let videoDone = false;
        let scrollDone = true; // Tạm thời đặt là true để chỉ tập trung vào Video theo yêu cầu
        let maxTimeWatched = 0;
        const requiredPercent = iddi_lesson_config.percent || 0.5;

        // Hàm khóa nút
        function disableButton($btn) {
            if (!$btn.length) return;
            $btn.prop('disabled', true).css({
                'opacity': '0.5',
                'cursor': 'not-allowed',
                'pointer-events': 'none',
                'filter': 'grayscale(1)'
            }).attr('title', 'Vui lòng xem đạt ' + (requiredPercent * 100) + '% video để hoàn thành');
        }

        // Hàm mở khóa nút
        function enableButton($btn) {
            if (!$btn.length) return;
            if (videoDone && scrollDone) {
                $btn.prop('disabled', false).css({
                    'opacity': '1',
                    'cursor': 'pointer',
                    'pointer-events': 'auto',
                    'filter': 'none'
                }).removeAttr('title');
            }
        }

        // 1. Giám sát nút Hoàn thành
        const selectors = '.lp-button-complete-item, .button-complete-item, .lp-button-complete, #learn-press-button-complete-item, .lp-btn-complete-item, .button-complete-lesson';
        
        const observer = new MutationObserver(function(mutations) {
            const $btn = $(selectors);
            if ($btn.length && (!videoDone || !scrollDone)) {
                disableButton($btn);
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });

        // Kiểm tra ngay lập tức
        disableButton($(selectors));

        let autoClicked = false;
        function checkRequirements() {
            if (videoDone && scrollDone) {
                const $btn = $(selectors);
                enableButton($btn);
                
                if (!autoClicked && $btn.length && !$btn.prop('disabled')) {
                    autoClicked = true;
                    $btn.trigger('click');
                }
            }
        }

        // 2. Kiểm tra cuộn trang
        function updateScrollStatus() {
            if (scrollDone) return;
            const scrollPercent = ($(window).scrollTop() + $(window).height()) / $(document).height();
            if (scrollPercent > 0.8) { 
                scrollDone = true;
                checkRequirements();
            }
        }

        $(window).on('scroll', updateScrollStatus);
        updateScrollStatus(); // Kiểm tra ngay khi load

        // 3. Xử lý YouTube (Hỗ trợ cả youtube-nocookie.com và youtu.be, tự động thêm enablejsapi=1)
        const $ytIframe = $('iframe[src*="youtube.com"], iframe[src*="youtube-nocookie.com"], iframe[src*="youtu.be"]');
        if ($ytIframe.length) {
            // Tự động thêm tham số enablejsapi=1 vào src của iframe nếu chưa có để kích hoạt API của YouTube
            $ytIframe.each(function() {
                let src = $(this).attr('src');
                if (src && !src.includes('enablejsapi=1')) {
                    const separator = src.includes('?') ? '&' : '?';
                    $(this).attr('src', src + separator + 'enablejsapi=1');
                }
            });

            const initYTPlayers = function() {
                $ytIframe.each(function() {
                    new YT.Player(this, {
                        events: {
                            'onStateChange': function(event) {
                                if (event.data == YT.PlayerState.PLAYING) {
                                    const player = event.target;
                                    const checkYT = setInterval(function() {
                                        const currentTime = player.getCurrentTime();
                                        const duration = player.getDuration();
                                        const rate = player.getPlaybackRate() || 1;

                                        const allowedJump = rate * 2.5; 

                                        if (currentTime > maxTimeWatched + allowedJump) {
                                            player.seekTo(maxTimeWatched);
                                        } else {
                                            maxTimeWatched = Math.max(maxTimeWatched, currentTime);
                                        }

                                        if (duration > 0 && (maxTimeWatched / duration) >= requiredPercent) {
                                            videoDone = true;
                                            clearInterval(checkYT);
                                            checkRequirements();
                                        }
                                        if (event.data != YT.PlayerState.PLAYING) clearInterval(checkYT);
                                    }, 1000);
                                }
                            }
                        }
                    });
                });
            };

            if (typeof YT !== 'undefined' && typeof YT.Player !== 'undefined') {
                // Nếu thư viện API của YouTube đã được tải trước đó
                initYTPlayers();
            } else {
                // Nếu chưa, tải và gán sự kiện onYouTubeIframeAPIReady một cách an toàn
                if (typeof YT === 'undefined') {
                    const tag = document.createElement('script');
                    tag.src = "https://www.youtube.com/iframe_api";
                    const firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                }

                const previousOnReady = window.onYouTubeIframeAPIReady;
                window.onYouTubeIframeAPIReady = function() {
                    if (typeof previousOnReady === 'function') {
                        previousOnReady();
                    }
                    initYTPlayers();
                };
            }
        }

        // 4. Xử lý Vimeo
        const $vimeoIframe = $('iframe[src*="vimeo.com"]');
        if ($vimeoIframe.length) {
            if (typeof Vimeo === 'undefined') {
                const tag = document.createElement('script');
                tag.src = "https://player.vimeo.com/api/player.js";
                document.head.appendChild(tag);
                tag.onload = () => setupVimeo($vimeoIframe);
            } else {
                setupVimeo($vimeoIframe);
            }
        }

        function setupVimeo($iframes) {
            $iframes.each(function() {
                const player = new Vimeo.Player(this);
                let vimeoMaxTime = 0;

                player.on('timeupdate', function(data) {
                    const currentTime = data.seconds;
                    const duration = data.duration;
                    
                    player.getPlaybackRate().then(function(rate) {
                        const allowedJump = rate * 2; 

                        if (currentTime > vimeoMaxTime + allowedJump) {
                            player.setCurrentTime(vimeoMaxTime);
                        } else {
                            vimeoMaxTime = Math.max(vimeoMaxTime, currentTime);
                        }

                        if (duration > 0 && (vimeoMaxTime / duration) >= requiredPercent) {
                            videoDone = true;
                            checkRequirements();
                        }
                    });
                });
            });
        }

        // 5. Xử lý Spotlightr
        const $spotlightrIframe = $('iframe[src*="spotlightr.com"], iframe[src*="vooplayer.com"]');
        if ($spotlightrIframe.length) {
            let spotlightrMaxTime = 0;
            window.addEventListener('message', function(event) {
                if (event.origin.includes('spotlightr.com') || event.origin.includes('vooplayer.com')) {
                    try {
                        let data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;
                        
                        // 5a. Xử lý sự kiện kết thúc
                        const isEnded = (data.event === 'video_end' || data.event === 'finish' || data.method === 'video_end') || 
                                        (data.data && data.data.event === 'ended');

                        if (isEnded) {
                            videoDone = true;
                            checkRequirements();
                        }

                        // 5b. Xử lý cập nhật thời gian
                        const innerData = data.data || data.params || data;
                        const currentTime = innerData.currentTime || innerData.time || data.value || 0;
                        const duration = innerData.duration || data.duration || 0;

                        if (currentTime > 0) {
                            // Chống tua
                            const allowedJump = 5; 
                            if (currentTime > spotlightrMaxTime + allowedJump) {
                                event.source.postMessage(JSON.stringify({
                                    "method": "seekTo",
                                    "params": [spotlightrMaxTime]
                                }), event.origin);
                            } else {
                                spotlightrMaxTime = Math.max(spotlightrMaxTime, currentTime);
                            }

                            // Nếu có duration, tính %
                            if (duration > 0 && (spotlightrMaxTime / duration) >= requiredPercent) {
                                if (!videoDone) {
                                    videoDone = true;
                                    checkRequirements();
                                }
                            }
                        }
                    } catch (e) {}
                }
            });
        }

        // Nếu không có video, mặc định coi như xong video
        if (!$ytIframe.length && !$vimeoIframe.length && !$spotlightrIframe.length) {
            videoDone = true;
            checkRequirements();
        }
    });
})(jQuery);
