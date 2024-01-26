<?php

namespace ZIPPY_Pay\Src\Paynow;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class ZIPPY_Paynow_Api
{
  private $client;
  private $errorMessage;

  /**
   * ZIPPY_Paynow_Api constructor.
   */
  public function __construct()
  {
    $this->client = new Client([
      'base_uri' => 'http://192.168.1.35:4466/',
      'timeout'  => 5.0,
    ]);

    $this->errorMessage = 'We cannot process the payment at the moment. Please try again later.';
  }

  public function checkPaynowIsActive()
  {
    try {
      $response = $this->client->get(
        "v1/payment/ecommerce/paymentoptions",
        ['query' => ['merchantId' => '445adf53-3a26-4296-8723-b6a28299f712', 'paymentOption' => 'paynow']]
      );

      $statusCode = $response->getStatusCode();
      if ($statusCode == 200) {
        $responseData = json_decode($response->getBody());
        $response = array(
          'status' => true,
          'message' => 'Paynow is ready for payment.',
          'data' => $responseData
        );
      } else {
        $response = array(
          'status' => $statusCode,
          'message' => $this->errorMessage,
        );
      }
    } catch (ConnectException $e) {
      $response = array(
        'status' => false,
        'message' => 'Connection timed out. Please try again later.',
      );
    } catch (RequestException $e) {
      $response = array(
        'status' => $e->getResponse()->getStatusCode(),
        'message' => $this->errorMessage,
      );
    }

    return $response;
  }

  public function paynowPayment($payload)
  {

    try {
      $response = $this->client->post("v1/payment/paynow/qr", ['form_params' => $payload]);
      $statusCode = $response->getStatusCode();
      if ($statusCode === 200) {
        $response = json_decode($response->getBody());
      } else {
        $response = array(
          'status' => $statusCode,
          'message' => $this->errorMessage,
        );
      }
    } catch (ConnectException $e) {
      $response = array(
        'status' => false,
        'message' => 'Connection timed out. Please try again later.',
      );
    } catch (RequestException $e) {
      $response = array(
        'status' => $e->getResponse()->getStatusCode(),
        'message' => $this->errorMessage,
      );
    }

    return $response;
  }
}
