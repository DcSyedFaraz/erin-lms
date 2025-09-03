const themeSwitcher = document.getElementById('theme-switcher');
const body = document.body;
const navbar = document.querySelector('.main-header');

// Function to toggle between dark and light mode
const toggleTheme = () => {
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');
    navbar.classList.toggle('navbar-dark');
    navbar.classList.toggle('navbar-light');

    // Update the icon based on the current theme
    const isDarkMode = body.classList.contains('dark-mode');
    themeSwitcher.innerHTML = isDarkMode ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';

    // Store the user's preference in local storage
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
};

// Event listener for the theme switcher button
if (themeSwitcher) {
    themeSwitcher.addEventListener('click', (e) => {
        e.preventDefault();
        toggleTheme();
    });
}

// Check for the user's preferred theme from local storage
const preferredTheme = localStorage.getItem('theme');

if (preferredTheme === 'light') {
    body.classList.remove('dark-mode');
    body.classList.add('light-mode');
    navbar.classList.add('navbar-light');
    navbar.classList.remove('navbar-dark');
    if (themeSwitcher) themeSwitcher.innerHTML = '<i class="fas fa-moon"></i>';
} else {
    body.classList.remove('light-mode');
    body.classList.add('dark-mode');
    navbar.classList.add('navbar-dark');
    navbar.classList.remove('navbar-light');
    if (themeSwitcher) themeSwitcher.innerHTML = '<i class="fas fa-sun"></i>';
}
