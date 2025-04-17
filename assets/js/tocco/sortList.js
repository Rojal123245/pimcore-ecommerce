function sortList(trigger, list, text1, text2 = null, order) {

    if (order == 'ascending') {
        var ascending = true;
    } else {
        var ascending = false;
    }
  
    trigger.addEventListener('click', function() {
        [...list.children]
            .sort(function (a, b) {

                if (!text2) {
                    var x = a.querySelector(text1).innerText;
                    var y = b.querySelector(text1).innerText;
                } else {
                    var x = a.querySelector(text1).innerText + a.querySelector(text2).innerText;
                    var y = b.querySelector(text1).innerText + b.querySelector(text2).innerText;
                }
        
                if (ascending) {
                    return (x >= y ? 1 : -1);
                } else {
                    return (x >= y ? -1 : 1);
                }

            })
            .forEach(node => list.appendChild(node));
    
        if (ascending) {
            ascending = false;
            trigger.classList.add('ascending');
        } else {
            ascending = true;
            trigger.classList.remove('ascending');
        }
    });
}

export function vorstandslisteSortByFirstname() {
    const toccoVorstandsliste = document.querySelectorAll('.tocco-vorstandsliste');

    toccoVorstandsliste.forEach(vorstandsliste => {
        const triggerFirstname = vorstandsliste.querySelector('.sortByFirstname');
        const triggerLastname = vorstandsliste.querySelector('.sortByLastname');
        const triggerFunction = vorstandsliste.querySelector('.sortByFunction');
        const list = vorstandsliste.querySelector('.vorstandslisteList');
        const mobileFilter = vorstandsliste.querySelector('.mobile-sort');
        const details = vorstandsliste.querySelector('.details');

        if (triggerFirstname && list) {
            sortList(triggerFirstname, list, '.firstname', '', 'ascending');
        }

        if (triggerLastname && list) {
            sortList(triggerLastname, list, '.lastname', '', '');
        }

        if (triggerFunction && list) {
            sortList(triggerFunction, list, '.function', '', 'ascending');
        }

        // show/hide mobile sorting
        if (mobileFilter && details) {
            mobileFilter.addEventListener('click', () => {
                mobileFilter.classList.toggle('open');
                details.classList.toggle('show');
            });
        }

    });
}