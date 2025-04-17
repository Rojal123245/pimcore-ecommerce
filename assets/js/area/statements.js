import {createEffect, createSignal} from "../reactivity";
import $ from "jquery";

export const initializeStatements = () => {

    const statements = $('.statement-container');
    statements.each((_, el) => {

        const statement = $(el);
        const [categories, setCategories] = createSignal([]);
        const [years, setYears] = createSignal([]);
        const [category, setCategory] = createSignal(statement.data('category-id') ?? '');
        const [year, setYear] = createSignal(null);

        const filter_category = statement.find('.filter-category');
        const filter_date = statement.find('.filter-date');

        if (filter_category) {
            const categoryId = filter_category.data('statementCategory');

            if (
                categoryId !== '' &&
                categoryId !== null &&
                categoryId !== undefined
            ) {
                setCategory(categoryId)
            }
        }

        createEffect(() => {
            const filter_container = statements.find('.filters-container');
            if (statement.data('category-id')) {
                filter_container.hide();
            } else {
                filter_container.show();
            }
        })

        createEffect(async () => {
            const url = new URL('/api/statements/categories', window.location.origin);

            url.searchParams.append('locale', window.locale);

            await fetch(url)
                .then(response => response.json())
                .then(data => {
                    setCategories(data.categories);
                    setYears(data.years);
                });
        });

        createEffect(() => {
            filter_category.html('');
            categories().map(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.innerHTML = category.name;
                filter_category.append(option);
            });
        });

        createEffect(() => {
            filter_date.html('');
            years().map(year => {
                const option = document.createElement('option');
                option.value = year.value;
                option.innerHTML = year.key;
                filter_date.append(option);
            });
        });

        createEffect(() => {
            const container = statement.find('.statement-container');
            container.html(`<div class="loading"></div>`);

            getStatements({
                category: category(),
                date: year(),
                locale: window.locale
            }).then(statements => {
                const template = statement.find('.statement-template');
                container.html('');

                if (statements.statements.length === 0) {
                    const template = statement.find('.no-statements-to-show');
                    const statementElement = $(template.html());
                    statementElement.appendTo(container);
                    return;
                }

                statements.statements.map((statement, index) => {
                    const statementElement = $(template.html());
                    if (index === 0) {
                        statementElement.find('.group').addClass('border-t');
                    }
                    statementElement.find('.statement').attr('id', `statement_${statement.id}`);
                    statementElement.find('.statement__icon').html(statement.icon);
                    statementElement.find('.statement__icon__colored').html(statement.altIcon);
                    statementElement.find('.statement__title').html(statement.title);
                    statementElement.find('.statement__content').html(statement.content);
                    statementElement.find('.statement__link').attr('href', statement.link);
                    statementElement.find('.statement__link').attr('target', statement.target);
                    statementElement.find('.statement__categories').html(statement.categories.join(', '));
                    statementElement.find('.statement__date').html(statement.date);
                    statementElement.appendTo(container);
                });
            });
        });

        filter_category.on('change', function () {
            const value = $(this).select2('data');
            let filterString = '';
            value.map(v => {
                filterString += v.id + ','
            });
            filterString = filterString.replace(/(^,)|(,$)/g, "");

            setCategory(filterString);
        });

        filter_date.on('change', function (e) {
            const value = $(this).select2('data');
            let filterString = '';
            value.map(v => {
                filterString += v.id + ','
            });
            filterString = filterString.replace(/(^,)|(,$)/g, "");

            setYear(filterString);

        });
    });

    $(document).on('mouseenter', '.statement', function () {
        $(this).find('.statement__icon').addClass('hidden');
        $(this).find('.statement__icon__colored').removeClass('hidden');
    });

    $(document).on('mouseleave', '.statement', function () {
        $(this).find('.statement__icon').removeClass('hidden');
        $(this).find('.statement__icon__colored').addClass('hidden');
    });
};

const getStatements = async ({
                                 category = null,
                                 date = null,
                                 locale = null
                             }) => {
    const url = new URL('/api/statements', window.location.origin);
    if (category) {
        url.searchParams.append('category', category);
    }
    if (date) {
        url.searchParams.append('date', date);
    }
    if (locale) {
        url.searchParams.append('locale', locale);
    }
    return await (await fetch(url)).json();
};