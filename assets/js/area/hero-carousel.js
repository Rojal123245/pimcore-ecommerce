import jQuery from 'jquery';
import 'slick-carousel';

export function initializeHeroCarousel() {

    const $ = jQuery;

    let carousels  = jQuery('.hero-carousel');

    carousels.each((_, carousel) => {
        let slider = $(carousel);
        let parent = slider?.parent()?.parent();
        if (slider.data('enable')) {
            let speed = slider.data('slide-time') ? slider.data('slide-time') * 1000 : 7000;
            slider.slick({
                autoplaySpeed: speed,
                slidesToShow: 1,
                adaptiveHeight: true,
                accessibility: true,
                autoplay: true,
                prevArrow: '',
                nextArrow: '',
            });

            let dots = $(slider.data('carousel-control'));

            if (dots && dots.length > 0) {
                slider.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                    dots.find('.slick-active').removeClass('slick-active');
                    dots.find(`li:nth-child(${nextSlide + 1})`).addClass('slick-active');
                });

                dots.slick({
                    dots: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    asNavFor: slider,
                });

            }


            if (parent) {
                let player = parent.find('.carousel-player');
                let pauser = parent.find('.carousel-pauser');


                $(parent).on('click', '.carousel-pauser', function (event) {
                    event.preventDefault();
                    slider.slick('slickPause');
                    $(this).hide();
                    player.show();
                });
                $(parent).on('click', '.carousel-player', function (event) {
                    event.preventDefault();
                    slider.slick('slickPlay');
                    $(this).hide();
                    pauser.show();
                });
                $(parent).on('click', '.carousel-control-prev', function () {
                    slider.slick('slickPrev');
                });

                $(parent).on('click', '.carousel-control-next', function () {
                    slider.slick('slickNext');
                });
            }

        }
    });

}
