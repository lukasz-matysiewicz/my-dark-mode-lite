(function($) {
    $(document).ready(function() {
        var rowCount = $('.colors-group table.colors tbody tr.colors').length;

        // Helper function to initialize the color picker for specific elements
        function initColorPicker(selector) {
            $(selector).wpColorPicker({
                palettes: true
            });
        }

        function checkRowCount() {
            if (rowCount >= 10) {
                $('#addRow').hide();
            } else {
                $('#addRow').show();
            }
        }

        // Check the initial row count to decide if the "Add Row" button should be shown or hidden
        checkRowCount();

        // Initialize the color picker for existing color input fields
        initColorPicker('#mdm_dark_bg_color_picker, .colors-group table.colors tbody tr.colors input[type="text"][id^="mdm_dark_new_color_picker_"]');
        
        // Add a new row
        $('#addRow').on('click', function() {
            var newRow = `
                <tr class="colors">
                    <td id="col1"><input type="text" id="mdm_new_color_class_${rowCount}" name="mdm_new_color_class_${rowCount}" value=""></td>
                    <td id="col2"><input type="text" id="mdm_dark_new_color_att_${rowCount}" name="mdm_dark_new_color_att_${rowCount}" value=""></td>
                    <td id="col3"><input type="text" id="mdm_dark_new_color_picker_${rowCount}" name="mdm_dark_new_color_picker_${rowCount}" value=""></td>
                </tr>
            `;

            $('.colors-group table.colors tbody').append(newRow);

            // Initialize the color picker for the newly added row's color input
            initColorPicker(`#mdm_dark_new_color_picker_${rowCount}`);

            rowCount++;
            checkRowCount();
        });

        $('#removeRow').on('click', function() {
            if ($('.colors-group table.colors tbody tr.colors').length > 1) { 
                $('.colors-group table.colors tbody tr.colors:last').remove();
                rowCount--;
                checkRowCount();
            }
        });
    });
})(jQuery);
