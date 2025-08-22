/**
 * @file
 * Contains the definition of the behaviour Global Pages Page.
 */

(function ($, Drupal, drupalSettings) {
  "use strict";
  /**
   * Attaches the Global Pages behaviour.
   */
  Drupal.behaviors.globalPages = {
    attach: function (context, settings) {
      var alias = jQuery("input[name='path[0][alias]']").val();
      if(typeof alias !== "undefined") {
        jQuery("#edit-path-settings").attr("open", true);
        jQuery("input[name='path[0][alias]']").attr("required","required");
        jQuery(".form-item-path-0-alias label").addClass('js-form-required form-required');
      }
      // Entity Browser Selection Screen
      if(jQuery("#field-component-list-widget-entity-form .entities-list .item-container").length < 1 ) {
        jQuery("#field-component-list-widget-entity-form .entity-browser-processed").trigger("click");
      }
    }
  };
})(jQuery, Drupal, drupalSettings);
