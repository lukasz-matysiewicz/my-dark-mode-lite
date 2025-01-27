<?php
/*
Plugin Name: My Dark Mode Lite
Plugin URI: https://github.com/lukasz-matysiewicz/my-dark-mode-lite
Description: A Super Lightweight plugin to enable dark mode on your WordPress site.
Version: 1.0.3
Author: Matysiewicz Studio
Author URI: https://matysiewicz.studio
License: GPL3
Stable Tag: 1.0.3
Tested Up To: 6.4.1

*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
function my_dark_mode_lite_deactivate_lite_plugin() {
    // Check if the Lite plugin is active
    if(is_plugin_active('my-dark-mode/my-dark-mode.php')) { 
        // Deactivate the Lite plugin
        deactivate_plugins('my-dark-mode/my-dark-mode.php');
    }
}
// Hook the function to the activation of the Pro plugin
register_activation_hook(__FILE__, 'my_dark_mode_lite_deactivate_lite_plugin');

function my_dark_mode_lite_custom_head_script() {
    wp_enqueue_script('my-dark-mode-switcher', plugins_url('assets/js/my-dark-mode-switcher.js', __FILE__), array(), '1.0.0', false);
}
add_action('wp_head', 'my_dark_mode_lite_custom_head_script', 1);

function my_dark_mode_lite_enqueue_scripts() {
    // Enqueue the dark-mode.css file
    wp_enqueue_style('my-dark-mode-css', plugin_dir_url(__FILE__) . 'assets/css/my-dark-mode.css', array(), '1.0', 'all');

    // Enqueue the switchers.css file for both admin and front-end
    wp_enqueue_style('my-dark-mode-switchers-css', plugin_dir_url(__FILE__) . 'assets/css/my-dark-mode-switchers.css', array(), '1.0', 'all');

    wp_enqueue_script('my-dark-mode-js', plugin_dir_url(__FILE__) . 'assets/js/my-dark-mode-save.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'my_dark_mode_lite_enqueue_scripts', 1);

// create setting page
function my_dark_mode_lite_admin_menu() {
    add_menu_page(
        '<h1>My Dark Mode Settings</h1>',
        'My Dark Mode',
        'manage_options',
        'my-dark-mode',
        'my_dark_mode_lite_settings_page',
        plugin_dir_url( __FILE__ ) . 'assets/img/my-dark-mode.svg',
        6
    );
}
add_action('admin_menu', 'my_dark_mode_lite_admin_menu');

function my_dark_mode_lite_section_callback() {
    ?>
    <div>Use those fields to customize My Dark Mode Styles.</div>
    <?php
}

function my_dark_mode_lite_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('my_dark_mode_messages', 'my_dark_mode_message', __('Settings Saved', 'my-dark-mode'), 'updated');
    }

    settings_errors('my_dark_mode_messages');
    ?>
    <div class="my-dark-mode-container">
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="<?php echo admin_url('options.php'); ?>" method="post">
                <input type="hidden" name="action" value="admin_post_save_my_dark_mode_settings">
                <?php wp_nonce_field('my_dark_mode_nonce'); ?>
                <?php
                settings_fields('my_dark_mode');
                do_settings_sections('my_dark_mode');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
    </div>
    <?php
}

function my_dark_mode_lite_save_settings() {
    check_admin_referer('my_dark_mode_nonce');

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Sanitize and validate the POST data
    $switcher1_width = isset($_POST['switcher1_width']) ? intval($_POST['switcher1_width']) : 0;
    $switcher1_height = isset($_POST['switcher1_height']) ? intval($_POST['switcher1_height']) : 0;
    $switcher2_width = isset($_POST['switcher2_width']) ? intval($_POST['switcher2_width']) : 0;
    $switcher2_height = isset($_POST['switcher2_height']) ? intval($_POST['switcher2_height']) : 0;

    // Update the options in the database
    update_option('mdm_switcher1_width', $switcher1_width);
    update_option('mdm_switcher1_height', $switcher1_height);
    update_option('mdm_switcher2_width', $switcher2_width);
    update_option('mdm_switcher2_height', $switcher2_height);

    // Redirect after saving settings
    wp_redirect(admin_url('admin.php?page=my-dark-mode&settings-updated=true'));
    exit;
}
add_action('admin_post_save_my_dark_mode_lite_settings', 'my_dark_mode_lite_save_settings');


//global variables
function my_dark_mode_lite_get_dark_mode_settings() {
    $switcher = get_option('my_dark_mode_switcher', 'switcher1');

    $switcher1_width = get_option('mdm_switcher1_width', 90);
    $switcher1_height = get_option('mdm_switcher1_height', 40);

    $switcher2_width = get_option('mdm_switcher2_width', 40);
    $switcher2_height = get_option('mdm_switcher2_height', 40);

    $switcher1_html = '
    <div class="mode-switch-border" style="width:'.$switcher1_width.'px; height:'.$switcher1_height.'px;">
        <input type="checkbox" id="mode-switch" data-dark-mode-toggle aria-label="Toggle Dark Mode">
        <label for="mode-switch" class="mode-label">
        <div class="toggle"></div>
        <div class="names">
            <div class="light">Light</div>
            <div class="dark">Dark</div>
        </div>
        </label>
    </div>';

    $switcher2_html = '
    <div class="circle-switch-border" style="width:'.$switcher2_width.'px; height:'.$switcher2_height.'px;">
        <input type="checkbox" id="circle-switch" data-dark-mode-toggle aria-label="Toggle Dark Mode">
        <label for="circle-switch" class="circle-label">
        <div class="circle">
            <div class="crescent"></div>
        </div>
        </label>
    </div>';

    return array(
        'switcher' => $switcher,
        'switcher1_html' => $switcher1_html,
        'switcher2_html' => $switcher2_html,
        'switcher1_width' => $switcher1_width,
        'switcher1_height' => $switcher1_height,
        'switcher2_width' => $switcher2_width,
        'switcher2_height' => $switcher2_height
    );
}

function my_dark_mode_lite_switcher_section_callback(){
    $settings = my_dark_mode_lite_get_dark_mode_settings();
    $switcher = $settings['switcher'];
    ?> 
    <div class="mdm-container">
        <p class=switcher-info>To use dark mode button on your website use <strong>widget</strong> or this shortcode: <strong>[my_dark_mode_toggle_button]</strong></p>
    <?php 
    ?>
        <div class="switcher">
            <label>
                <input type="radio" name="my_dark_mode_switcher" value="switcher1" <?php checked($switcher, 'switcher1'); ?>>
                Switcher 1
            </label>
            <div class="prev">
                Preview:
                <?php echo wp_kses_post($settings['switcher1_html']);
     ?>
                
            </div>
            <div>
                Width: <input type="number" class="switch_input" name="mdm_switcher1_width" value="<?php echo wp_kses_post($settings['switcher1_width']); ?>">
                Height: <input type="number" class="switch_input" name="mdm_switcher1_height" value="<?php echo wp_kses_post($settings['switcher1_height']); ?>">
            </div>
        </div>
        <div class="switcher">
            <label> 
                <input type="radio" name="my_dark_mode_switcher" value="switcher2" <?php checked($switcher, 'switcher2'); ?>>
                Switcher 2
            </label>
            <div class="prev">
                Preview:
                <?php echo wp_kses_post($settings['switcher2_html']); ?>
            </div>
            <div>
                Width: <input type="number" class="switch_input" name="mdm_switcher2_width" value="<?php echo wp_kses_post($settings['switcher2_width']); ?>">
                Height: <input type="number" class="switch_input" name="mdm_switcher2_height" value="<?php echo wp_kses_post($settings['switcher2_height']); ?>">
            </div>
        </div>
    </div>
<?php
}

// Register the settings field to store the custom button code
function my_dark_mode_lite_settings_init() {
    register_setting('my_dark_mode', 'my_dark_mode_switcher');
    register_setting('my_dark_mode', 'mdm_switcher1_width');
    register_setting('my_dark_mode', 'mdm_switcher1_height');
    register_setting('my_dark_mode', 'mdm_switcher2_width');
    register_setting('my_dark_mode', 'mdm_switcher2_height');
    //list of fields
    require_once plugin_dir_path(__FILE__) . 'my-dark-mode-fields.php';
    
}
add_action('admin_init', 'my_dark_mode_lite_settings_init');


// Create a shortcode for the dark mode toggle button
function my_dark_mode_lite_toggle_button_shortcode($atts) {
    $settings = my_dark_mode_lite_get_dark_mode_settings();
    $switcher = $settings['switcher'];

    if ($switcher == 'switcher1') {
        return $settings['switcher1_html'];
    }
    if ($switcher == 'switcher2') {
        return $settings['switcher2_html'];
    }
}
add_shortcode('my_dark_mode_toggle_button', 'my_dark_mode_lite_toggle_button_shortcode');

//Added button to widgets area
require_once plugin_dir_path(__FILE__) . 'my-dark-mode-widget.php';

//Add code editor instead of textarea
function my_dark_mode_lite_enqueue_admin_scripts($hook) {
    if ('toplevel_page_my-dark-mode' !== $hook) {
        return;
    }

    // Enqueue the custom admin CSS file
    wp_enqueue_style('my-dark-mode-admin-css', plugin_dir_url(__FILE__) . 'assets/css/my-dark-mode-admin.css', array(), '1.0', 'all');

    // Enqueue the custom admin JS file
    wp_enqueue_code_editor(array('type' => 'text/html'));
    wp_enqueue_script('my-dark-mode-admin-js', plugin_dir_url(__FILE__) . 'assets/js/my-dark-mode-admin.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'my_dark_mode_lite_enqueue_admin_scripts', 1);


//Add color pickers
require_once plugin_dir_path(__FILE__) . 'my-dark-mode-colors.php';

function my_dark_mode_button_lite_code_callback(){
    ?> 
    <div class="premium-label">Premium Feature</div>
    <div class="mdm-container premium">
    <p class="switcher-info"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/button-code.jpg'; ?>" alt="Custom Button"></p>
    <?php 
}
function my_dark_mode_lite_logo_callback(){
    ?> 
    <div class="premium-label">Premium Feature</div>
    <div class="mdm-container premium">
    <p class="switcher-info"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/custom-logo.jpg'; ?>" alt="Custom Logo"></p>
    <?php 
}
function my_dark_mode_lite_custom_css_callback() {
    ?> 
    <div class="premium-label">Premium Feature</div>
    <div class="mdm-container premium">
        <p class="switcher-info"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/custom-css.jpg'; ?>" alt="Custom CSS"></p>
    </div>
    <?php 
}
