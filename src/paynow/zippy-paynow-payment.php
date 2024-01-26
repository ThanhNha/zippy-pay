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
      'wc-api'      => 'zippy_paynow_callback',
      'order_id'     => $order_id
    ), home_url('/')));

    $payload = array(

      "StoreId" => $merchant_id,
      "OrderId" => $order_id,
      "Amount" => $total,
      "CallbackUrl" => $callback_url,
      "ReturnUrl" => wc_get_checkout_url()

    );

    return $payload;
  }
}
