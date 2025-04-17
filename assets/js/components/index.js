import {initializeCollapsibles} from "./collapsible";
import {initializeAccordions} from "./accordion";
import {initializeQuickLinks} from "./quicklinks";
import {initializeSelect2} from "./select2";
import {initializeSlider} from "./slider";
import {initializeMegaMenu, initializeMobileMenu} from "./megamenu";
import {initializeLanguageSwitcher} from "./language-switcher";
import {initializeModal} from "./modal";
import { initializeCarousel } from "./carousel";


export const initializeComponents = () => {
    initializeCarousel();
    initializeCollapsibles();
    initializeAccordions();
    initializeQuickLinks();
    initializeSelect2();
    initializeSlider();
    initializeMegaMenu();
    initializeMobileMenu();
    initializeLanguageSwitcher();
    initializeModal();
}