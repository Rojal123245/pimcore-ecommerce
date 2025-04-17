import $ from 'jquery';
import {createEffect, createSignal} from "../reactivity";

const initializeQuickLinks = () => {

    const [isOpen, setOpen] = createSignal(false);


    createEffect(() => {
        if (isOpen()) {
            $('#quickLinks').removeClass('hidden');
        } else {
            $('#quickLinks').addClass('hidden');
        }
    })


    $(document).on('click', '.quick-links-trigger', function () {
        setOpen(true);
    });

    $(document).on('click', '.quick-links-backdrop', function (event) {
        if (event.target.classList.contains('quick-links-backdrop') || event.target.parentNode.classList.contains('quick-links-backdrop')) {
            setOpen(false);
        }
    });

}

export {initializeQuickLinks};