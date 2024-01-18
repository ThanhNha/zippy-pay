<?php

namespace ZIPPY_Pay\Core\Paynow;

use stdClass;

defined('ABSPATH') || exit;


defined('ABSPATH') || exit;

class ZIPPY_Adyen_Pay_Api
{
  public function GetConfig()
  {
    $response = '{
      "status": 1,
      "statusName": "Success",
      "message": null,
      "result": {
          "adyenConfig": {
              "countryCode": "SG",
              "currency": "SGD",
              "environment": "Test",
              "clientKey": "test_J7E3RWXOBRENNMZYT3X6IWXNP4HC32UK",
              "apiKey": "AQErhmfxKo3JbxxEw0m/n3Q5qf3VbbtiPpRYV3BG8BN4ZAL4b8SYnO1/LfRIWBDBXVsNvuR83LVYjEgiTGAH-ZKEP08zlgM8l1iBTp+2IG4c3rTn419PlGB2fuaouUtc=-3S~@Tzm;nTb[jkUP",
              "merchantAccount": "EPOS-SGPOS",
              "prefixUrl": "",
              "adyenCreditType": true,
              "paymentMethods": {
                  "paymentMethods": [
                      {
                          "brands": [
                              "amex"
                          ],
                          "name": "American Express",
                          "type": "scheme"
                      },
                      {
                          "name": "AliPay",
                          "type": "alipay"
                      },
                      {
                          "configuration": {
                              "merchantId": "50",
                              "gatewayMerchantId": "EPOS-SGPOS"
                          },
                          "name": "Google Pay",
                          "type": "paywithgoogle"
                      }
                  ]
              }
          },
          "paynowConfig": {
              "paynowIntegrationType": true,
              "paynowPaymentType": "uob",
              "paynowMerchantUEN": "UENABC",
              "paynowMCC": "MCCS",
              "paynowMerchantName": "NAME",
              "paynowMerchantContact": "869996961"
          }
      }
  }';
    return  $response;
  }
}
