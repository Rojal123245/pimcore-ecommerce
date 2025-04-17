import {createSignal, createEffect} from '../reactivity'

const MAX_SCROLL = 100;
const MIN_SCROLL = 0;
const MAX_WIDTH = 213;
const MIN_WIDTH = 130;

const isTransparencyEnabled = window.enableTransparentNavigation ?? false;
export const initializeMegaMenu = () => {

    // make megamenu stick on top and have a white background if the user scrolls down, use tailwind classes

    const navigation = document.querySelector('#mainNavigation');
    const navigationMenuLinks = document.querySelectorAll('#mainNavigation a');
    const flyouts = document.querySelectorAll('.navigation-flyout');
    const logo = document.querySelector('#navigationLogo');
    const languageSwitcher = document.querySelector('#localeSwitcher');

    const interpolateScrollData = (scrollPosition, maxScroll, minScroll, maxWidth, minWidth) => {
        scrollPosition = Math.min(maxScroll, Math.max(minScroll, scrollPosition));

        return minWidth + ((maxWidth - minWidth) / (minScroll - maxScroll)) * (scrollPosition - maxScroll);
    }

    window.addEventListener('scroll', (event) => {

        if (!isTransparencyEnabled) {
            return;
        }
        const logoWidth = interpolateScrollData(window.scrollY, MAX_SCROLL, MIN_SCROLL, MAX_WIDTH, MIN_WIDTH);



        if (window.scrollY > 0) {
            logo.src = logo.dataset.dark;
            logo.style.width = `${logoWidth}px`;
            navigation.classList.add('bg-white');
            languageSwitcher.classList.add('lg:text-black');
            languageSwitcher.classList.remove('lg:text-white');
            navigation.classList.remove('bg-gradient-to-b');
            navigationMenuLinks.forEach((link) => {
                link.classList.add('text-black');
                link.classList.remove('text-white');
            });
            flyouts.forEach((flyout) => {
                flyout.classList.add('bg-white');
            });
        } else {
            logo.src = logo.dataset.light;
            let flyouts = [];
            document.querySelectorAll('.navigation-flyout').forEach((el) => {
                const styles = window.getComputedStyle(el);
                if (styles.display !== 'none') {
                    flyouts.push(el);
                }
            });

            if (flyouts.length <= 0) {
                logo.style.width = `${logoWidth}px`;
                navigation.classList.add('bg-gradient-to-b');
                languageSwitcher.classList.add('lg:text-white');
                languageSwitcher.classList.remove('lg:text-black');
                navigation.classList.remove('bg-white');
                navigationMenuLinks.forEach((link) => {
                    link.classList.add('text-white');
                    link.classList.remove('text-black');
                });
                flyouts.forEach((flyout) => {
                    flyout.classList.remove('bg-white');
                });
            }
        }

    });

    navigation.addEventListener('mouseenter', (event) => {
        if (!isTransparencyEnabled || window.scrollY > 0) {
            return;
        }
        logo.src = logo.dataset.dark;
        navigation.classList.add('bg-white');
        navigation.classList.remove('bg-transparent');
        navigationMenuLinks.forEach((link) => {
            link.classList.add('text-black');
            link.classList.remove('text-white');
        });
        flyouts.forEach((flyout) => {
            flyout.classList.add('bg-white');
            flyout.classList.remove('bg-black');
        });

        languageSwitcher.classList.remove('lg:text-white');
        languageSwitcher.classList.add('lg:text-black');
    });

    navigation.addEventListener('mouseleave', (event) => {
        if (!isTransparencyEnabled || window.scrollY > 0) {
            return;
        }
        logo.src = logo.dataset.light;

        languageSwitcher.classList.remove('lg:text-black');
        languageSwitcher.classList.add('lg:text-white');
        navigation.classList.add('bg-transparent');
        navigation.classList.remove('bg-white');
        navigationMenuLinks.forEach((link) => {
            link.classList.add('text-white');
            link.classList.remove('text-black');
        });
        flyouts.forEach((flyout) => {
            flyout.classList.remove('bg-white');
        });
    });
}

export const initializeMobileMenu = () => {
    const [isOpen, setOpen] = createSignal(false);

    const toggle = document.querySelector('.toggle-menu');

    const first = toggle.querySelector('.first');
    const second = toggle.querySelector('.second');
    const third = toggle.querySelector('.third');

    // hide the third line when the menu is open
    createEffect(() => {
        if (isOpen()) {
            third.classList.add('hidden');
        } else {
            third.classList.remove('hidden');
        }
    });

    // rotate the first and second line when the menu is open
    createEffect(() => {
        if (isOpen()) {
            first.classList.add('rotate-45');
            first.classList.remove('-translate-y-2');

            second.classList.add('-rotate-45');
            second.classList.remove('translate-y-2');
        } else {
            first.classList.add('-translate-y-2');
            first.classList.remove('rotate-45');

            second.classList.remove('-rotate-45');
            second.classList.add('translate-y-2');
        }
    });

    createEffect(() => {
        if (isOpen()) {
            third.classList.remove('bg-black');
            third.classList.add('bg-primary-500');
            second.classList.remove('bg-primary-500');
            second.classList.add('bg-black');
        } else {
            third.classList.add('bg-black');
            third.classList.remove('bg-primary-500');
            second.classList.add('bg-primary-500');
            second.classList.remove('bg-black');
        }
    });

    createEffect(() => {
        const menu = document.querySelector('#mobileMenu');

        if (isOpen()) {
            menu.classList.remove('hidden');
            if (isTransparencyEnabled) {
                menu.parentElement.classList.add('bg-white');
                menu.parentElement.classList.remove('bg-transparent');
            }
        } else {
            menu.classList.add('hidden');
            if (isTransparencyEnabled) {
                menu.parentElement.classList.remove('bg-white');
                menu.parentElement.classList.add('bg-transparent');
            }
        }
    });

    toggle.addEventListener('click', () => {
        setOpen(!isOpen());
    });
}