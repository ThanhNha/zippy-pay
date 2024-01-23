"use strict";
$ = jQuery;
$(document).ready(function () {
  var $active_credit_card = $("#woocommerce_zippy_adyen_payment_settings");
  var $active_paynow = $("#woocommerce_zippy_paynow_payment_settings");
  var $zippy_setting_wrapper = $("#zippy_setting_wrapper");

  function toggleCreditCardSection() {
    if ($active_credit_card.is(":checked") || $active_paynow.is(":checked")) {
      $zippy_setting_wrapper.fadeIn();
    } else {
      $zippy_setting_wrapper.fadeOut();
    }
  }

  toggleCreditCardSection(); // Initial state

  $active_credit_card.change(function () {
    toggleCreditCardSection();
  });
  $active_paynow.change(function () {
    toggleCreditCardSection();
  });

  // const btn_authorization = $("#zippy_authorization_button");
  // if (btn_authorization.length) {
  // }

  // function zippy_authorization() {
  //   $.ajax({
  //     url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
  //     type: "POST",
  //     data: {
  //       action: "zippy_authorization", // this is the function in your functions.php that will be triggered
  //       name: "John",
  //       age: "38",
  //     },
  //     success: function (data) {
  //       //Do something with the result from server
  //       console.log(data);
  //     },
  //   });
  // }
});
