import $ from 'jquery';
export const initializeModal = () => {

    $('.modal-open').map((_, modal) => {
        $(modal).on('click', function () {
            const target = $(this).data('target');
            $(target).removeClass('hidden');
        });
    });

    $('.modal-close').map((_, modal) => {

        $(modal).on('click', function (e) {
            if (!e.target.classList.contains('modal-close')) {
                return;
            }
            const target = $(this).data('target');
            $(target).addClass('hidden');
            const videos = $(target).find('video');

            // pause html5 videos when closing modal
            videos.map((_, video) => {
                video.pause();
            });


            const youtubeIframes = $(target).find('.youtube_iframe');

            // pause youtube videos when closing modal
            youtubeIframes.each(function () {
                this.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*')
            });

        });
    });
}