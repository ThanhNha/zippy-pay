import { AMSCheckoutPage } from "@alipay/ams-checkout";
import { webApi } from "./api";

class Antom {
  constructor() {
    this.checkoutApp = new AMSCheckoutPage({
      environment: "sandbox",
      locale: "en_US",
      onLog: ({ code, message }) => {
        console.log("Log:", code, message);
      },
      onError: ({ code, result }) => {
        console.log("Error:", code, result);
      },
      onEventCallback: this.onEventCallback,
    });
  }

  onEventCallback({ code, result }) {
    switch (code) {
      case "SDK_PAYMENT_SUCCESSFUL":
        let currentUrl = new URL(window.location.href);

        if (!currentUrl.searchParams.has("antom_process")) {
          currentUrl.searchParams.set("antom_process", "checking");
          window.location.href = currentUrl.toString();
        }

        break;
      case "SDK_PAYMENT_PROCESSING":
        console.log("Check the payment result data", result);
        // Payment is being processed. Query the payment status through the server or wait for the payment result notification. At the same time, you can check whether the user has completed the payment. If the payment is not completed, guide the user to pay again.
        break;
      case "SDK_PAYMENT_FAIL":
        console.log("Check the payment result data", result);
        // Payment failed. Please refer to the processing suggestions in the Event codes and guide the user to pay again.
        break;
      case "SDK_PAYMENT_CANCEL":
        // The user exits the payment page without submitting the order. You can re-invoke the SDK with paymentSessionData that is still valid. If it has expired, you need to re-request paymentSessionData.
        break;
      case "SDK_PAYMENT_ERROR":
        console.log("Check the payment result data", result);
        // The payment status is abnormal. Query the payment status through the server or wait for the payment result notification, or guide the user to pay again.
        break;
      case "SDK_END_OF_LOADING":
        // End the custom loading animation.
        break;
      default:
        break;
    }
  }

  async getPaymentSessionData(orderId) {
    try {
      const response = await webApi.createPaymentSession({ order_id: orderId });

      const data = await response.data;
      return data;
    } catch (error) {
      console.error("Error fetching payment session data:", error);
      return null;
    }
  }

  async checkPaymentTransaction() {
    try {
      const response = await webApi.checkPaymentTransaction({
        order_id: orderId,
      });

      const data = await response.data;
      return data;
    } catch (error) {
      console.error("Error fetching payment session data:", error);
      return null;
    }
  }

  async create(orderId) {
    const paymentSessionData = await this.getPaymentSessionData(orderId);
    // console.log(paymentSessionData);
    await this.checkoutApp.mountComponent(
      {
        sessionData: paymentSessionData?.data?.data?.paymentSessionData,
      },
      "#zippy_antom"
    );
  }

  async remove() {
    this.checkoutApp.unmount();
  }
}

export default Antom;
