import $ from 'jquery';
import {createSignal, createEffect} from '../reactivity'

export const initializeVacancies = () => {

    const bricks = $('.vacancies');

    bricks.map((_, brick) => {
        const [query, setQuery] = createSignal(null);
        const [location, setLocation] = createSignal(null);
        const [sortBy, setSortBy] = createSignal('published_at');
        const [sortOrder, setSortOrder] = createSignal('desc');
        const [loading, setLoading] = createSignal(true);
        const toggleSortOrder = () => {
            if (sortOrder() === 'asc') {
                setSortOrder('desc');
            } else {
                setSortOrder('asc');
            }
        }

        const container = $(brick);

        createEffect(() => {
            const loadingContainer = container.find('.loading');
            if (loadingContainer) {
                if (loading()) {
                    loadingContainer.removeClass('hidden')
                } else {
                    loadingContainer.addClass('hidden')
                }
            }
        });

        fetch('/api/' + (window.locale ?? 'de') + '/job-locations')
            .then(json => json.json())
            .then(({locations}) => {
                // Update filter locations
                const filters = container.find('.filter-location');
                filters.html('');
                locations.map(location => {
                    const option = document.createElement('option');
                    option.innerHTML = location.name;
                    option.value = location.id;
                    filters.append(option);
                });
            })
            .catch(err => {
                console.log(err)
            });

        createEffect(() => {
            const icon = container.find('.sort-order-icon');

            if (icon) {
                if (sortOrder() === 'asc') {
                    icon.addClass('fa-arrow-up');
                    icon.removeClass('fa-arrow-down');
                } else {
                    icon.removeClass('fa-arrow-up');
                    icon.addClass('fa-arrow-down');
                }
            }
        });

        createEffect(() => {

            setLoading(true);
            getVacancies(
                location(),
                sortOrder(),
                sortBy(),
                query()
            ).then(json => {
                const {jobs, countText} = json;


                // Update count text
                const countTextContainer = container.find('.search-results-count');
                if (countTextContainer) {
                    countTextContainer.html(countText);
                }

                // Update listing

                const listingContainer = container.find('.vacancy-container');
                const template = container.find('.vacancy-item');
                const notFoundTemplate = container.find('.no-jobs-to-show');

                if (listingContainer) {
                    listingContainer.html('');

                    if (jobs.length === 0) {
                        listingContainer.append($(notFoundTemplate.html()));
                        return;
                    }

                    jobs.map(job => {
                        const element = $(template.html());
                        element.find('.vacancy__title').html(job.title);
                        element.find('.vacancy__published_at').html(job.published_at);
                        element.find('.vacancy__location').html(job.location.map(l => l.name).join(','))
                        element.find('.vacancy__link').attr('href', job.link);
                        listingContainer.append(element);
                    });
                }
            }).catch(err => {
                console.error(err);
            }).finally(() => {
                setLoading(false);
            });
        });


        container.on('keyup', '.search', function () {
            const text = $(this).val();
            if (text.length > 0) {
                setQuery(text)
            } else {
                setQuery(null);
            }
        })

        container.on('click', '.toggle-sort', function () {
            toggleSortOrder();
        });

        container.on('change', '.sort-order', function () {
            const data = $(this).select2('data');
            if (data.length > 0) {
                setSortBy(data[0].id);
            } else {
                setSortBy(null);
            }
        });

        container.on('change', '.filter-location', function () {
            const data = $(this).select2('data');
            if (data.length > 0) {
                setLocation(data[0].id ?? null);
            } else {
                setLocation(null);
            }
        });
    });
}

const getVacancies = async (filters, sortOrder, sortBy, query) => {
    const locale = window.locale ?? 'de';
    const url = '/api/' + locale + '/jobs';

    return await fetch(url, {
        method: 'POST',
        body: JSON.stringify({
            locations: filters,
            sort_order: sortOrder,
            sort_by: sortBy,
            query: query
        })
    }).then(response => {
        return response.json();
    });
}