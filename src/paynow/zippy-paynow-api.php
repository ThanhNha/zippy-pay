<?php

namespace ZIPPY_Pay\Src\Paynow;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class ZIPPY_Paynow_Api
{

  /**
   * @var Client
   */
  private static $client;

  /**
   * ZIPPY_Settings_Api constructor.
   */

  public function __construct()
  {
    self::$client = new Client([
      'base_uri' => 'http://192.168.1.28:446/',
      'timeout'  => 5.0,
    ]);
  }

  public static function CheckPaynowIsActive()
  {

    if (self::$client === null) {
      new self();
    }
    try {


      $configs = self::$client->request(
        "GET",
        "v1/payment/ecommerce/paymentoptions",
        ['query' => ['merchantId' => '445adf53-3a26-4296-8723-b6a28299f712', 'paymentOption' => 'paynow']]
      );

      if ($configs->getStatusCode() == 200) {
        $response = array(
          'status' => true,
          'message' => 'Paynow is ready for payment.',
          'data' => json_decode($configs->getBody())
        );
      }
    } catch (ClientException $e) {
      $response = array(
        'status' => $e->getResponse()->getStatusCode(),
        'message' => 'We can not process the payment at the moment. Please, try again later.',
      );
    } catch (ConnectException $e) {
      $response = array(
        'status' => false,
        'message' => 'We can not process the payment at the moment. Please, try again later.',
      );
    }

    return $response;
  }
}
