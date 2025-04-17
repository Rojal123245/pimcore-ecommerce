import {createSignal, createEffect} from "../reactivity";
import $ from 'jquery';

export const initializeAccordions = () => {

    const groups = document.querySelectorAll('.accordion-group');

    groups.forEach(group => {
        const closeOthers = group.dataset.closeOthers ?? false;
        const openFirst = group.dataset.openFirst ?? false;


        const accordions = group.querySelectorAll('.accordion');
        const setters = [];
        accordions.forEach((accordion, index) => {
            const target = document.querySelector(accordion.dataset.target);
            const [isOpen, setOpen] = createSignal(openFirst && index === 0);
            setters.push(setOpen);

            accordion.addEventListener('click', () => {
                if (closeOthers) {
                    setters.filter(s => s !== setOpen).map(setter => setter(false));
                }
                setOpen(!isOpen());
            });

            $(accordion).on('mouseenter',  function () {
                const openIcon = accordion.dataset.openIcon ?? 'icon-minus';
                const hoverIcon = accordion.dataset.hoverIcon ?? 'icon-plus-red';
                const chevron = accordion.querySelector('.collapsible-chevron');
                if (chevron){
                    if (!isOpen()) {
                        chevron.classList.add(hoverIcon);
                        chevron.classList.remove(openIcon);
                    }
                }
            });
        
            $(accordion).on('mouseleave',  function () {
                const closeIcon = accordion.dataset.closeIcon ?? 'icon-plus';
                const hoverIcon = accordion.dataset.hoverIcon ?? 'icon-plus-red';
                const chevron = accordion.querySelector('.collapsible-chevron');
                if (chevron){
                    if (!isOpen()) {
                        chevron.classList.remove(hoverIcon);
                        chevron.classList.add(closeIcon);
                    }
                }
            });

            // Toggle class on open or close
            createEffect(() => {
                if (isOpen()) {
                    target.classList.remove('h-0');
                    accordion.classList.add('text-primary-500');
                } else {
                    target.classList.add('h-0');
                    accordion.classList.remove('text-primary-500');
                }
            });

            createEffect(() => {
                const openIcon = accordion.dataset.openIcon ?? 'icon-minus';
                const closeIcon = accordion.dataset.closeIcon ?? 'icon-plus';
                const hoverIcon = accordion.dataset.hoverIcon ?? 'icon-plus-red';
                const chevron = accordion.querySelector('.collapsible-chevron');
                if (chevron) {
                    if (isOpen()) {
                        openIcon.split(' ').map(icon => {
                            chevron.classList.add(icon);
                        });
                        closeIcon.split(' ').map(icon => {
                            chevron.classList.remove(icon);
                        });
                        hoverIcon.split(' ').map(icon => {
                            chevron.classList.remove(icon);
                        });

                    } else {
                        closeIcon.split(' ').map(icon => {
                            chevron.classList.add(icon);
                        });
                        openIcon.split(' ').map(icon => {
                            chevron.classList.remove(icon);
                        });
                    }
                }
            });

        });
    });
}