export function eventFilter() {
    const sections = document.querySelectorAll('.tocco-veranstaltungen');

    sections.forEach(section => {
        const entries = section.querySelectorAll('.alignment');
        const themeOptions = section.querySelectorAll('.theme-option');
        const typeOptions = section.querySelectorAll('.type-option');

        const filters = [
            section.querySelector('.filter-type'),
            section.querySelector('.filter-theme')
        ];

        // toggle dropdowns
        filters.forEach(filter => {
            if (filter) {
                filter.addEventListener('click', () => {
                    const dropdown = filter.querySelector('.filter-dropdown');

                    if (dropdown) {
                        dropdown.classList.toggle('active');
                    }
                });
            }
        });

        // dropdown entries
        themeOptions.forEach(option => {
            option.addEventListener('click', e => {
                e.stopPropagation();

                // current state of option
                var isActive = option.classList.contains('active');

                // remove all active options
                themeOptions.forEach(option => {
                    option.classList.remove('active');
                });

                typeOptions.forEach(option => {
                    option.classList.remove('active');
                });

                // set active option
                if (!isActive) {
                    option.classList.add('active');
                }

                entries.forEach(entry => {

                    // show all
                    if (isActive) {
                        entry.classList.remove('hidden');
                    } else {
                        if (entry.dataset.theme && entry.dataset.theme.includes(option.innerHTML)) {
                            entry.classList.remove('hidden');
                        } else {
                            entry.classList.add('hidden');
                        }
                    }
                });

            });
        })

        typeOptions.forEach(option => {
            option.addEventListener('click', e => {
                e.stopPropagation();

                // current state of option
                var isActive = option.classList.contains('active');

                // remove all active options
                themeOptions.forEach(option => {
                    option.classList.remove('active');
                });
                
                typeOptions.forEach(option => {
                    option.classList.remove('active');
                });

                // set active option
                if (!isActive) {
                    option.classList.add('active');
                }

                entries.forEach(entry => {

                    // show all
                    if (isActive) {
                        entry.classList.remove('hidden');
                    } else {
                        if (entry.dataset.type && entry.dataset.type.includes(option.innerHTML)) {
                            entry.classList.remove('hidden');
                        } else {
                            entry.classList.add('hidden');
                        }
                    }
                });

            });
        })

    });

}