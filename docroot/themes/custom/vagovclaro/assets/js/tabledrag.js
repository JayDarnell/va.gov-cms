/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal) {
  $.extend(Drupal.theme, {
    tableDragChangedWarning: function tableDragChangedWarning() {
      return '<div class="tabledrag-changed-warning va-alert messages messages--warning" role="alert">' + Drupal.theme('tableDragChangedMarker') + ' ' + Drupal.t('You have unsaved changes.') + '</div>';
    }
  });
})(jQuery, Drupal);