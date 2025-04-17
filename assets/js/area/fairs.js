import $ from 'jquery';
import {createEffect} from "../reactivity";
export const initializeFairs = () => {

    const listOfFairs = $('.fairs');
    if (listOfFairs.length > 0) {

        listOfFairs.map((_, fair) => {
           const container = $(fair);
           let categories = container.data('categories');
            console.log(categories);

           createEffect(() => {
               const fairsContainer = container.find('.fairs-container');
               fairsContainer.html('');

                getListOfFairs(categories).then(({fairs}) => {
                    if (fairs.length === 0) {
                        const template = container.find('.no-fairs-to-show');
                        if (template) {
                            const element = $(template.html());

                            fairsContainer.append(element);
                        }
                        return;
                    }
                   fairs.map(fair => {
                        const template = container.find('.fair-item');
                        const element = $(template.html());
                        element.find('.fair__title').html(fair.title);
                        element.find('.fair__date').html(fair.date);
                        element.find('.fair__location').html(fair.location)
                        element.find('.fair__link').attr('href', fair.link);
                        fairsContainer.append(element);
                   });
                });
           });
        });
    }
}

const getListOfFairs = async (categories) => {

    let url = `/api/${window.locale ?? 'de'}/fairs`;
    if (categories) {
        url += '?categories=' + categories;
    }
    return fetch(url).then(json => json.json());
}