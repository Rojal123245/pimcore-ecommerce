import {createSignal, createEffect} from "../reactivity";

export const initializeCollapsibles = () => {
    const collapsibles = document.querySelectorAll('.collapsible');
    collapsibles.forEach(collapsible => {
        const [isOpen, setOpen] = createSignal(collapsible.dataset.open ?? false);
        const target = collapsible.dataset.target;
        const chevron = collapsible.querySelector('.collapsible-chevron');

        const targetElement = document.querySelector(target);


        createEffect(() => {
            if (isOpen()) {
                targetElement.classList.remove('hidden');
            } else {
                targetElement.classList.add('hidden');
            }
        });

        // Toggle chevron classes
        createEffect(() => {
            if (chevron) {
                if (isOpen()) {
                    chevron.classList.remove('fa-chevron-down');
                    chevron.classList.add('fa-chevron-up');
                } else {
                    chevron.classList.remove('fa-chevron-up');
                    chevron.classList.add('fa-chevron-down');
                }
            }
        });

        // Toggle collapsible when clicking the header
        collapsible.addEventListener('click', (event) => {
            setOpen(!isOpen());
        });
    });
}