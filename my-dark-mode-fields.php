<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

add_settings_section(
    'my_dark_mode_section',
    __('', 'my-dark-mode'),
    'my_dark_mode_lite_section_callback',
    'my_dark_mode'
);

add_settings_field(
    'my_dark_mode_switcher',
    __('<h1>Choose a switcher:</h1>', 'my-dark-mode'),
    'my_dark_mode_lite_switcher_section_callback',
    'my_dark_mode',
    'my_dark_mode_section'
);

add_settings_field(
    'my_dark_mode_colors',
    __('<h1>Define Colors:<h1>', 'my-dark-mode'),
    'my_dark_mode_lite_colors_callback',
    'my_dark_mode',
    'my_dark_mode_section'
);

add_settings_field(
    'my_dark_mode_button_code',
    __('<h1>Button Code:</h1>', 'my-dark-mode'),
    'my_dark_mode_button_lite_code_callback',
    'my_dark_mode',
    'my_dark_mode_section',
    array('class' => 'premium-wrapper')
);

add_settings_field(
    'my_dark_mode_logo',
    __('<h1>Custom Logo:<h1>', 'my-dark-mode'),
    'my_dark_mode_lite_logo_callback',
    'my_dark_mode',
    'my_dark_mode_section',
    array('class' => 'premium-wrapper')
);

add_settings_field(
    'my_dark_mode_custom_css',
    __('<h1>Custom CSS:</h1>', 'my-dark-mode'),
    'my_dark_mode_lite_custom_css_callback',
    'my_dark_mode',
    'my_dark_mode_section',
    array('class' => 'premium-wrapper')
);