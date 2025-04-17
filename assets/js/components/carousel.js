import $ from 'jquery';
import 'slick-carousel';
import {createSignal, createEffect} from '../reactivity';

const initializeCarousel = () => {
    const carousels = $('.carousel-with-controls');

    carousels.each((_, el) => {
        const element = $(el);
        const carousel = element.find('.carousel-slider').first();
        const [isPaused, setPaused] = createSignal(false);
        
        if (!carousel) {
            return;
        }

        const config = carousel.data('slider') ?? {
            dots: false
        };

        config.prevArrow = '';
        config.nextArrow = '';
        const slideTime = carousel.data('slide-time') ?? 7;

        config.autoplaySpeed = slideTime * 1000;

        const prevButton = element.find('.carousel-control-prev');
        const nextButton = element.find('.carousel-control-next');

        if (prevButton) {
            prevButton.on('click', function () {
                slider.slick('slickPrev');
            })
        }

        if (nextButton) {
            nextButton.on('click', function () {
                slider.slick('slickNext');
            });
        }

        const slider = carousel.slick(config);


        const pauser = element.find('.carousel-pauser');

        if (pauser) {
            pauser.on('click', function () {
                setPaused(!isPaused());
            });
        }

        const dots = element.find('.carousel-dots');
        slider.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            dots.find('.slick-active').removeClass('slick-active');
            dots.find(`li:nth-child(${nextSlide + 1})`).addClass('slick-active');
        });
        
        if (dots) {
            dots.slick({
                dots: true,
                prevArrow: '',
                nextArrow: '',
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: slider,
            });

        }

        createEffect(() => {
            const playing = !isPaused();
            if (slider && pauser) {
                const method = playing ? 'slickPlay' : 'slickPause';
                slider.slick(method);

                let icon = pauser.find('.fa');
                
                if (icon) {
                    icon.removeClass(playing ? 'fa-play' : 'fa-pause');
                    icon.addClass(playing ? 'fa-pause' : 'fa-play');
                }
            }
        });
    });

}

export {initializeCarousel}