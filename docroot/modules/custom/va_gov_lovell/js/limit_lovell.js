/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function (Drupal) {
  var myFacility = "";

  var adminField = document.getElementById("edit-field-administration");
  var adminFieldOptions = document.querySelectorAll("#edit-field-administration option");
  var facilityField = document.getElementById("edit-field-facility-location");
  var facilityFieldOptions = document.querySelectorAll("#edit-field-facility-location option");
  var systemField = document.getElementById("edit-field-regional-health-service");
  var systemFieldOptions = document.querySelectorAll("#edit-field-regional-health-service option");
  var regionPageOptions = document.querySelectorAll("#edit-field-region-page option");

  var lovellFederalText = "Lovell Federal health care";
  var lovellVaPattern = /Lovell.*VA/i;
  var lovellTricarePattern = /Lovell.*TRICARE/i;
  var lovellWinnower = function lovellWinnower() {
    var pathType = drupalSettings.path.currentPath.split("/")[1];

    if (typeof facilityField !== "undefined" && facilityField !== null && pathType === "add") {
      facilityField.selectedIndex = "_none";
    }
    if (typeof systemField !== "undefined" && systemField !== null && pathType === "add") {
      systemField.selectedIndex = "_none";
    }

    var adminFieldText = adminField.options[adminField.selectedIndex].text;

    var adminMatcher = void 0;
    if (adminFieldText.search(lovellTricarePattern) > -1) {
      adminMatcher = "Lovell Federal TRICARE health care";
    }
    if (adminFieldText.search(lovellVaPattern) > -1) {
      adminMatcher = "Lovell Federal VA health care";
    }

    function hideSeekShowLovell(domElement, textMatch) {
      domElement.forEach(function (i) {
        if (i.text.includes("Lovell")) {
          i.classList.add("hidden-option");
          if (i.text.includes(textMatch)) {
            i.classList.remove("hidden-option");
          }
        }
      });
    }
    if (facilityFieldOptions) {
      hideSeekShowLovell(facilityFieldOptions, adminMatcher);
    }
    if (systemFieldOptions) {
      hideSeekShowLovell(systemFieldOptions, adminMatcher);
    }

    function seekHide(domElement, textMatch) {
      domElement.forEach(function (i) {
        if (i.text.includes(textMatch)) {
          i.classList.add("hidden-option");
        }
      });
    }

    if (adminFieldOptions) {
      seekHide(adminFieldOptions, lovellFederalText);
    }
    if (regionPageOptions) {
      seekHide(regionPageOptions, lovellFederalText);
    }
  };

  Drupal.behaviors.vaGovLimitLovell = {
    attach: function attach() {
      if (myFacility === "" || window.onload) {
        lovellWinnower();
      }
      adminField.addEventListener("change", lovellWinnower);
      if (facilityField !== null) {
        facilityField.addEventListener("change", function setText() {
          myFacility = facilityField.options[facilityField.selectedIndex].text;
        });
      }
    }
  };
})(Drupal);