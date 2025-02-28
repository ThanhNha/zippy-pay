import Antom from "./Antom";
("use strict");

$(document).ready(function () {
  // default is unclick Payment Antom
  unClickAntom();
  function unClickAntom() {
    var antom = $("#payment_method_zippy_antom_payment");
    antom.prop("checked", false);
  }
  handleChooseAntom();

  function handleChooseAntom() {
    var antomCheckoutPage = $("#zippy_antom");
    if (antomCheckoutPage.length == 0) return;
    const antomInstance = new Antom();
    antomInstance.create();

    // $("#order_review").on(
    //   "click",
    //   "#payment_method_zippy_antom_payment",
    //   function () {
    //     if ($(this).is(":checked")) {
    //       antomInstance.create(); // Mount the checkout component
    //     } else {
    //       antomInstance.remove(); // Unmount the checkout component
    //     }
    //   }
    // );
  }
});
