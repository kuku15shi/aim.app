
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const body = document.body;

    function setTheme(theme) {
        body.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        // Update Icon if it exists
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }
    }

    // Check for saved user preference, if any, on load of the website
    const savedTheme = localStorage.getItem('theme');
    const systemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Apply initial theme
    if (savedTheme) {
        setTheme(savedTheme);
    } else if (systemDark) {
        setTheme('dark');
    } else {
        setTheme('light');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = (currentTheme === 'dark') ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }
});
