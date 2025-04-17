import {initializeAreaBricks} from "./area";
import {initializeComponents} from './components';

// tocco
import {vorstandslisteSortByFirstname} from './tocco/sortList.js';
import {eventFilter} from './tocco/eventFilter.js';

// on dom content loaded, call the function
document.addEventListener('DOMContentLoaded', () => {

    initializeComponents();
    initializeAreaBricks();

    // tocco
    vorstandslisteSortByFirstname();
    eventFilter();
    
});