import Antom from "./Antom";
("use strict");

$(document).ready(function () {
  // Ensure the payment method is unchecked by default
  resetAntomSelection();

  function resetAntomSelection() {
    $("#payment_method_zippy_antom_payment").prop("checked", false);
  }

  let currentUrl = new URL(window.location.href);

  if (!currentUrl.searchParams.has("antom_process")) {
    initializeAntomCheckout();
  }

  function initializeAntomCheckout() {
    const $checkoutContainer = $("#zippy_antom");
    const orderId = $("#antom_order_id").val()?.trim();
    const $loadingIndicator = $("#zippy_antom_loader"); // Add a loading element

    if ($checkoutContainer.length === 0 || !orderId) {
      console.warn("Antom Checkout: Missing container or order ID.");
      return;
    }

    // Show loading indicator before initializing
    $loadingIndicator.addClass("show-loading");

    try {
      const antomInstance = new Antom();
      antomInstance
        .create(orderId)
        .then(() => {
          $loadingIndicator.removeClass("show-loading"); // Hide loading after successful creation
        })
        .catch((error) => {
          console.error("Antom Checkout: Initialization failed.", error);
          $loadingIndicator.removeClass("show-loading"); // Hide loading on failure
        });
    } catch (error) {
      console.error("Antom Checkout: Unexpected error.", error);
      $loadingIndicator.removeClass("show-loading");
    }
  }
});
