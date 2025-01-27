if (localStorage.getItem('dark-mode') === 'enabled') {
    document.documentElement.setAttribute('my-dark-mode', 'dark');
} else {
    document.documentElement.setAttribute('my-dark-mode', 'light');
}
