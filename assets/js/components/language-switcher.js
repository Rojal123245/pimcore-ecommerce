const initializeLanguageSwitcher = () => {
    const switchers = document.querySelectorAll('.localeSwitcher');

    switchers.forEach(switcher => {
        switcher.addEventListener('change', function (event) {
            const targetLanguage = event.target.value;
            const currentUrl = window.location.origin;

            window.location.href = `${currentUrl}/${targetLanguage}`;
        });
    });
}

export {initializeLanguageSwitcher}