<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

function my_dark_mode_lite_colors_callback() {
    $bg_color_class = get_option('mdm_bg_color_class', 'body');
    $dark_bg_att = get_option('mdm_dark_bg_color_att', 'background-color');
    $dark_bg_color = get_option('mdm_dark_bg_color_picker', '#222');
    ?>
    <div class="mdm-container colors-group">
        <table class="colors">
            <thead>
                <tr>
                    <th><h2>Element</h2></th>
                    <th><h2>Attribute</h2></th>
                    <th><h2>Color in Dark Mode</h2></th>
                </tr>
            </thead>
            <tbody>
                <tr class="colors">
                    <td id="col1"><input type="text" id="mdm_bg_color_class" name="mdm_bg_color_class" value="<?php echo esc_attr($bg_color_class); ?>"></td>
                    <td id="col2"><input type="text" id="mdm_dark_bg_color_att" name="mdm_dark_bg_color_att" value="<?php echo esc_attr($dark_bg_att); ?>"></td>
                    <td id="col3"><input type="text" id="mdm_dark_bg_color_picker" name="mdm_dark_bg_color_picker" value="<?php echo esc_attr($dark_bg_color); ?>"></td>
                </tr>
                <?php 
                for ($i = 0; $i < 15; $i++) {
                    $color_class = get_option('mdm_new_color_class_' . $i, '');
                    $color_att = get_option('mdm_dark_new_color_att_' . $i, '');
                    $color_value = get_option('mdm_dark_new_color_picker_' . $i, '');
                
                    if ($color_class) {
                        echo '<tr class="colors">
                              <td><input type="text" id="' . esc_attr('mdm_new_color_class_' . $i) . '" name="' . esc_attr('mdm_new_color_class_' . $i) . '" value="' . esc_attr($color_class) . '"></td>
                              <td><input type="text" id="' . esc_attr('mdm_dark_new_color_att_' . $i) . '" name="' . esc_attr('mdm_dark_new_color_att_' . $i) . '" value="' . esc_attr($color_att) . '"></td>
                              <td><input type="text" id="' . esc_attr('mdm_dark_new_color_picker_' . $i) . '" name="' . esc_attr('mdm_dark_new_color_picker_' . $i) . '" value="' . esc_attr($color_value) . '"></td>
                             </tr>';                   
                    }                 
                }
                ?>
            </tbody>
        </table>
        <button type="button" id="addRow" class="button">Add Row</button>
        <button type="button" id="removeRow" class="button">Remove Row</button>
    </div>
    <?php
}

function my_dark_mode_lite_register_settings() {
    register_setting('my_dark_mode', 'mdm_bg_color_class', 'sanitize_text_field');
    register_setting('my_dark_mode', 'mdm_dark_bg_color_att', 'sanitize_text_field');
    register_setting('my_dark_mode', 'mdm_dark_bg_color_picker', 'sanitize_hex_color');
    
    for ($i = 0; $i < 10; $i++) {
        register_setting('my_dark_mode', 'mdm_new_color_class_' . $i, 'sanitize_text_field');
        register_setting('my_dark_mode', 'mdm_dark_new_color_att_' . $i, 'sanitize_text_field');
        register_setting('my_dark_mode', 'mdm_dark_new_color_picker_' . $i, 'sanitize_hex_color');
    }
      
}

add_action('admin_init', 'my_dark_mode_lite_register_settings');


function my_dark_mode_lite_generate_css() {
    $bg_color_class = get_option('mdm_bg_color_class', 'body');
    $dark_bg_att = get_option('mdm_dark_bg_color_att', 'background-color');
    $dark_bg_color = get_option('mdm_dark_bg_color_picker', '#222');

    $css = "
    html[my-dark-mode='dark'] {$bg_color_class} {
            {$dark_bg_att}: {$dark_bg_color}!important;
        }
    ";
    for ($i = 0; $i < 10; $i++) {
        $new_color_class = get_option('mdm_new_color_class_' . $i, '');
        $new_dark_att = get_option('mdm_dark_new_color_att_' . $i, '');
        $new_dark_color = get_option('mdm_dark_new_color_picker_' . $i, '');

        if ($new_color_class && $new_dark_att && $new_dark_color) {
            $css .= "
            html[my-dark-mode='dark'] {$new_color_class} {
                    {$new_dark_att}: {$new_dark_color}!important;
            }";
        }
    }
    

    wp_add_inline_style('my-dark-mode-css', $css);
}
add_action('wp_enqueue_scripts', 'my_dark_mode_lite_generate_css', 20);
