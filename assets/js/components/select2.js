import jQuery from "jquery";
require('select2/dist/js/select2.min');

export const initializeSelect2 = () => {
    const select2 = jQuery('.select2');

    if (select2) {
        select2.map((_, element) => {
            const select = jQuery(element);

            select.select2({
                placeholder: select2.data('placeholder') ?? '',
            });



            setTimeout(() => {
                const container = select.siblings('.select2').find('.select2-search--inline');
                const span = document.createElement('span');
                let url = '/public/build/images/icons/chevron-down.svg';
                if (select.data('icon-open')) {
                    url = select.data('icon-open');
                }
                span.classList.add('icon');
                span.style.backgroundImage = `url(${url}) repeat-none center center`;
                span.style.backgroundColor = 'transparent';

                container.append(span);

            }, 100);


            select.on('select2:open', function (e) {
                const container = select.siblings('.select2').find('.select2-search--inline');
                const icon = container.find('.icon').first();
                let url = '/public/build/images/icons/chevron-up.svg';
                if (select.data('icon-close')) {
                    url = select.data('icon-close');
                }
                icon.css('background-color', 'black');
                icon.css('background-image', `url(${url}) repeat-none center center`);
                // icon.style.backgroundColor = 'black';
            });
            select.on('select2:close', function (e) {
                const container = select.siblings('.select2').find('.select2-search--inline');
                const icon = container.find('.icon').first();
                let url = '/public/build/images/icons/chevron-down.svg';
                if (select.data('icon-open')) {
                    url = select.data('icon-open');
                }
                icon.css('background-color', 'transparent');
                icon.css('background-image', `url(${url}) repeat-none center center`);
                // icon.style.backgroundColor = 'transparent';
            });
        });
    }
}
