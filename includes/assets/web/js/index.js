import Antom from "./Antom";
("use strict");

$(document).ready(function () {
  // handleChooseAntom();

  function handleChooseAntom() {
    var payment_methods = $('#payment input[type="radio"]');
    var antom = $("#payment_method_zippy_antom_payment");
    const antomInstance = new Antom();

    if (payment_methods.length === 1) {
      antomInstance.create();
      return;
    }

    antom.prop("checked", false);

    $("#order_review").on(
      "click",
      "#payment_method_zippy_antom_payment",
      function () {
        if ($(this).is(":checked")) {
          antomInstance.create(); // Mount the checkout component
        } else {
          antomInstance.remove(); // Unmount the checkout component
        }
      }
    );
  }
});
