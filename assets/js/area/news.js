import $ from 'jquery';
import {createSignal, createEffect} from '../reactivity';
import '../debounce';
export const initializeNews = () => {

    $('.news-overview').each((_, el) => {

        const container = $(el);
        let parent = container.parent();

        const categories = container.data('categories') ?? '';
        const [query, setQuery] = createSignal(null);
        const [category, setCategory] = createSignal(null);
        const [date, setDate] = createSignal(null);
        const [loading, setLoading] = createSignal(true);
        const [news, setNews] = createSignal([]);

        // Re-fetch from API whenever something changes
        createEffect(() => {
            let q = query();
            let cat = category();
            let d = date();

            if (!cat || cat === '') {
                cat = categories;
            }

            setLoading(true);
            getNewsByCategory(q, cat, d).then(json => {
               const {news, date} = json;

               let dateContainer = container.parent().find('.date-filter');
               if (dateContainer) {
                   dateContainer.children().each((_, option) => {
                       const val = parseInt(option.value);
                       const index = date.indexOf(val);
                       if (!date.includes(val)) {
                           dateContainer.remove(option);
                       } else if (index >= -1) {
                           date.splice(index, 1);
                       }
                   })
                   date.map(d => {
                       let option = document.createElement('option');
                       option.innerHTML = d;
                       option.value = d;
                       dateContainer.append(option);
                   })
               }
                setNews(news);
            }).finally(() => {
                setLoading(false);
            })
        });

        // Show loading icon whenever something changes
        createEffect(() => {
            let loadingContainer = container.parent().find('.loading');
            if (loading()) {
                loadingContainer.removeClass('hidden');
            } else {
                loadingContainer.addClass('hidden');
            }
        });

        // Update News
        createEffect(() => {
            let n = news();

            container.html('');
            let errorContainer = container.parent().find('.error-container');
            if (errorContainer) {
                errorContainer.html('');
                if (n.length <= 0) {
                    let template = parent.find('.empty-template');
                    errorContainer.append($(template.html()));
                    return;
                }
            }


            n.map(news => {
                let template = parent.find('.news-template');
                let newsElement = $(template.html());
                newsElement.find('.article__image').html(news.image);
                newsElement.find('.article__title').html(news.title);
                newsElement.find('.article__link').attr('href', news.link);
                newsElement.find('.article__excerpt').html(news.excerpt);
                container.append(newsElement);

            });
        })


        parent.on('change', '.date-filter', function (e) {
           let val = $(e.target).val();
           setDate(val.join(','));
        });

        parent.on('change', '.category-filter', function(e) {
            let value = $(e.target).val();
            setCategory(value.join(','));
        });

        parent.on('keydown', '.query-filter', $.debounce(function (e) {
            setQuery(e.target.value);
        }, 300));
    });
}

const getNewsByCategory = async (query, category, date) => {
    let path = `/api/${window.locale}/news`;
    let url = new URL(path, window.location.origin);

    if (category) {
       url.searchParams.append('category', category);
    }
    if (query) {
        url.searchParams.append('query', query);
    }
    if (date) {
        url.searchParams.append('date', date);
    }
    return fetch(url).then(json => json.json());
}