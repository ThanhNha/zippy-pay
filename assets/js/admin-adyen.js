jQuery(document).ready(function () {
  var $active_plugin = jQuery("#zippy_payment_getway_test_mode");

  // if (!$active_plugin.is(":checked")) {
  //   zippy_hide_config_inputs();
  // }

  // $active_plugin.change(function () {
  //   if (!this.checked) {
  //     zippy_hide_config_inputs();
  //   } else {
  //     jQuery("#zippy_payment_getway_merchant_id").prop("disabled", false);

  //     jQuery("#zippy_payment_getway_base_url").prop("disabled", false);

  //     jQuery("#zippy_payment_getway_secret_key").prop("disabled", false);
  //   }
  // });

  // function zippy_hide_config_inputs() {
  //   jQuery("#zippy_payment_getway_merchant_id").prop("disabled", true);
  //   jQuery("#zippy_payment_getway_base_url").prop("disabled", true);
  //   jQuery("#zippy_payment_getway_secret_key").prop("disabled", true);
  // }

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
