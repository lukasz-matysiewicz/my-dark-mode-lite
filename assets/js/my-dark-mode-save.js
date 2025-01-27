jQuery(document).ready(function($) {
    // Set switch state based on HTML attribute
    if ($('html').attr('my-dark-mode') === 'dark') {
        $('[data-dark-mode-toggle]').prop('checked', true);
    } else {
        $('[data-dark-mode-toggle]').prop('checked', false);
    }

    // Toggle dark mode on checkbox change
    $('[data-dark-mode-toggle]').on('change', function() {
        if ($(this).is(':checked')) {
            $('html').attr('my-dark-mode', 'dark');
            localStorage.setItem('dark-mode', 'enabled');
        } else {
            $('html').attr('my-dark-mode', 'light');
            localStorage.removeItem('dark-mode');
        }
    });
});
