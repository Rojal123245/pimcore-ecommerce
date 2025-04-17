import {initializeHeroCarousel} from "./hero-carousel";
import {initializeStatements} from "./statements";
import {initializeVacancies} from "./vacancies";
import {initializeFairs} from "./fairs";
import {initializeNews} from './news';
export const initializeAreaBricks = () => {

    initializeHeroCarousel();
    initializeVacancies();
    initializeFairs();
    initializeNews();
    if (window.statementsPage) {
        initializeStatements();
    }
}