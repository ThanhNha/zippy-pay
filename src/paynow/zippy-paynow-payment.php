<?php

namespace ZIPPY_Pay\Src\Paynow;

defined('ABSPATH') || exit;

class ZIPPY_Paynow_Payment
{

  /**
   * Paynow constructor.
   *
   * @param ZIPPY_Paynow_Payment $order_id
   */
  public function __construct($order)
  {
    $this->order = $order;
  }

  /**
   * Build Payment Payload
   *
   * @param ZIPPY_Paynow_Payment $order_id
   */
  public function build_payment_payload()
  {
    $order_id = $this->order->get_id();

    if (empty($order_id)) return;

    $total = $this->order->get_total();

    $merchant_id = get_option(PREFIX . '_merchant_id');

    $callback_url = str_replace('https:', 'http:', add_query_arg(array(
      'wc-api'      => 'zippy_paynow_transaction',
      'order_id'     => $order_id
    ), home_url('/')));

    $payload = array(
      "OrderId" => $order_id,
      "Amount" => $total,
      "CallbackUrl" => $callback_url,
    );

    $key = get_option(PREFIX . '_secret_key');

    $payload_2 = array(
      "data" => $this->encryptString($payload, $key)
    );

    return $payload_2;
  }

  public function encryptString($string, $secretKey)
  {
    $jsonString = json_encode($string);
    $method = 'aes-256-cbc';
    $key = substr(hash('sha256', $secretKey), 0, 32);
    $iv = random_bytes(16);

    $encrypted = openssl_encrypt($jsonString, $method, $key, 0, $iv);
    $encoded = base64_encode($jsonString);

    return $encoded;
  }
}
