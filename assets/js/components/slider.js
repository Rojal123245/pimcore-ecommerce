import $ from 'jquery';
import 'slick-carousel';

export const initializeSlider = () => {

    const sliders = $('.slider');

    sliders.map((_, slider) => {
        const sliderElement = $(slider);
        let config = {};

        if (sliderElement.data('slider')) {
            try {
                config = sliderElement.data('slider');
            } catch (e) {

            }
        }
        config.speed = 300;
        sliderElement.slick(config);
        const prevButton = $(slider).parent().find('.slider-previous');
        const nextButton = $(slider).parent().find('.slider-next');
        if (prevButton) {
            prevButton.on('click', function () {
                sliderElement.slick('slickPrev');
            });
        }

        if (nextButton) {
            nextButton.on('click', function () {
                sliderElement.slick('slickNext');
            });
        }
    });
}

