<?php

namespace ZIPPY_Pay\Src\Antom;

use WC_Order;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class ZIPPY_Antom_Api
{
  private WC_Order $order;
  private string $base_uri = "https://52a2-115-79-143-251.ngrok-free.app/";
  private string $errorMessage = 'We cannot process the payment at the moment. Please try again later.';

  public function __construct(int $order_id)
  {
    $this->order = new WC_Order($order_id);
  }


  public function createPaymentSessionApi()
  {
    $order_id = $this->order->get_id();
    $path = "/v1/payment/antom/ecommerce/session";

    $client = new Client([
      'base_uri' => $this->base_uri,
      'timeout'  => 30,
      'headers'  => ['Content-Type' => 'application/json'],
    ]);

    $data = $this->buildSessionPayload($order_id);

    try {
      $response = $client->post($path, ['json' => $data]);
      return $this->formatResponse(true, 'Get Payment Session Successfully', json_decode($response->getBody()));
    } catch (ConnectException $e) {
      return $this->formatResponse(false, 'Connection timed out. Please try again later.');
    } catch (RequestException $e) {
      $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
      $errorMessage = $e->getResponse() ? (string) $e->getResponse()->getBody() : 'Unknown error occurred.';
      return $this->formatResponse(false, "Request failed: $errorMessage", [], $statusCode);
    }
  }

  public function checkPaymentTransactionApi()
  {
    $paymentId =  $this->order->get_order_key();

    $order_id = $this->order->get_id();
    $path = "/v1/payment/antom/ecommerce/validate ";

    $client = new Client([
      'base_uri' => $this->base_uri,
      'timeout'  => 30,
      'headers'  => ['Content-Type' => 'application/json'],
    ]);

    $data = $this->buildValidateTransactionPayload($order_id);

    try {
      $response = $client->post($path, ['json' => $data]);
      return $this->formatResponse(true, 'Get Payment Transaction Successfully', json_decode($response->getBody()));
    } catch (ConnectException $e) {
      return $this->formatResponse(false, 'Connection timed out. Please try again later.');
    } catch (RequestException $e) {
      $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
      $errorMessage = $e->getResponse() ? (string) $e->getResponse()->getBody() : 'Unknown error occurred.';
      return $this->formatResponse(false, "Request failed: $errorMessage", [], $statusCode);
    }
  }

  public function checkPaymentTransactionCallback($order_id)
  {

    $client = new Client([
      'base_uri' => home_url(),
      'headers' => [
        'Content-Type' => 'application/json',
      ],
      'timeout'  => 15,
    ]);

    try {
      $response = $client->post(
        "/wp-json/zippy-pay/v1/antom/checkPaymentTransaction",
        ['query' => ['order_id' => $order_id]]
      );

      $statusCode = $response->getStatusCode();
      if ($statusCode == 200) {
        $responseData = json_decode($response->getBody());
        $response = array(
          'status' => true,
          'message' => 'Trigger Background Job',
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


  private function buildSessionPayload($order_id)
  {
    $data = array(
      'OrderId' => $order_id,
      'CustomerId' => ZIPPY_Pay_Core::get_domain_name(),
      'OrderAmount' => $this->order->get_total(),
      'RedirectUrl' => ZIPPY_Pay_Core::get_origin_domain(),
      'Currency' => "SGD"
    );
    return $data;
  }

  private function buildValidateTransactionPayload($order_id)
  {
    $data = array(
      'OrderId' => 1746,
      'PaymentRequestId' => 'ZIPPY#TQHNLM1TINZYKUPLOSFY#test',
    );
    return $data;
  }
  /**
   * Helper function to format API responses
   */
  private function formatResponse($status, $message, $data = [], $statusCode = 200)
  {
    return [
      'status' => $status,
      'message' => $message,
      'data' => $data,
      'code' => $statusCode
    ];
  }
}
