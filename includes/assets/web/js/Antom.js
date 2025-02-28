import { AMSCheckoutPage } from "@alipay/ams-checkout";

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
      onEventCallback: ({ code, message }) => {
        console.log("Event:", code, message);
      },
    });
  }

  async create(paymentSessionData) {
    await this.checkoutApp.mountComponent(
      {
        sessionData:
          "fgvDCbylmwqYTxztMce0EZk64QnfrlJOHSGCN4sNoLcYCzroH2pl8I9xErwOb/YerW96P3JLUG9VQOyTKQBrmg==&&SG&&111&&eyJleHRlbmRJbmZvIjoie1wiT1BFTl9NVUxUSV9QQVlNRU5UX0FCSUxJVFlcIjpcInRydWVcIixcImRpc3BsYXlBbnRvbUxvZ29cIjpcImZhbHNlXCJ9IiwicGF5bWVudFNlc3Npb25Db25maWciOnsicGF5bWVudE1ldGhvZENhdGVnb3J5VHlwZSI6IkFMTCIsInByb2R1Y3RTY2VuZSI6IkNIRUNLT1VUX1BBWU1FTlQiLCJwcm9kdWN0U2NlbmVWZXJzaW9uIjoiMS4wIn0sInBheW1lbnRTZXNzaW9uRmFjdG9yIjp7ImV4dGVuZEluZm8iOnsibWVyY2hhbnRDYXBhYmlsaXRpZXMiOlsic3VwcG9ydHMzRFMiXSwic3VwcG9ydGVkTmV0d29ya3MiOlsiTUFTVEVSQ0FSRCIsIlZJU0EiXX0sImV4dGVybmFsUmlza1RpbWVvdXQiOjAsIm1lcmNoYW50SW5mbyI6eyJpbnN0TWlkIjoibWVyY2hhbnQuY29tLmFudG9tLmNoZWNrb3V0LnByb2QiLCJtZXJjaGFudE5hbWUiOiJNZXJjaGFudCIsInBhcnRuZXJJZCI6IjIxMTExMjAwMDE3MTY1RDYiLCJyZWdpc3RlcmVkQ291bnRyeSI6IlNHIn0sIm9yZGVyIjp7Im9yZGVyRGVzY3JpcHRpb24iOiJQVV8yODAyMjAyNV9RUi01MyJ9LCJwYXltZW50QW1vdW50Ijp7ImN1cnJlbmN5IjoiU0dEIiwiY3VycmVuY3lEaXZpZGVyIjoiICIsImN1cnJlbmN5TGFiZWwiOiIkIiwiY3VycmVuY3lTeW1ib2xQb3NpdGlvbiI6IkwiLCJmb3JtYXR0ZWRWYWx1ZSI6IjUuMjYiLCJ2YWx1ZSI6IjUuMjYifSwicGF5bWVudE1ldGhvZEluZm8iOnsicGF5bWVudE1ldGhvZFR5cGUiOiJBUFBMRVBBWSJ9fSwic2VjdXJpdHlDb25maWciOnsiYXBwSWQiOiIiLCJhcHBOYW1lIjoiT25lQWNjb3VudCIsImJpelRva2VuIjoiNlRjZGJyMnJGM3JQWXg0aGtWckhxYnZqIiwiZ2F0ZXdheSI6Imh0dHBzOi8vaW1ncy1zZWEuYWxpcGF5LmNvbS9tZ3cuaHRtIiwiaDVnYXRld2F5IjoiaHR0cHM6Ly9vcGVuLXNlYS1nbG9iYWwuYWxpcGF5LmNvbS9hcGkvb3Blbi9yaXNrX2NsaWVudCIsIndvcmtTcGFjZUlkIjoiIn0sInNraXBSZW5kZXJQYXltZW50TWV0aG9kIjpmYWxzZX0=",
      },
      "#zippy_antom"
    );
  }

  async remove() {
    this.checkoutApp.unmount();
  }
}

export default Antom;
