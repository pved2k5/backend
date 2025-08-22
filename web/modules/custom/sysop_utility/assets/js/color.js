/**
 * @file
 * Javascript for Background coloe field widget.
 */

/**
 * Provides a farbtastic colorpicker for the fancier widget.
 */
(function ($) {

  'use strict';

  Drupal.behaviors.background_color_colorpicker = {
    attach: function () { console.log("Kukka");
      $('.edit-field-colorpicker').on('focus', function (event) {
        var edit_field = this;
        var picker = $(this).closest('div').parent().find('.field-colorpicker');
        // Hide all color pickers except this one.
        $('.field-colorpicker').hide();
        $(picker).show();
        $.farbtastic(picker, function (color) {
          edit_field.value = color;
        }).setColor(edit_field.value);
      });
    }
  };
})(jQuery);
