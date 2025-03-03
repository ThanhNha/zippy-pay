<?php

namespace ZIPPY_Pay\Src\Antom;

use ZIPPY_Pay\Src\Antom\ZIPPY_Antom_Api;
use WP_REST_Response;

defined('ABSPATH') || exit;

class ZIPPY_Antom_Payment
{
  /**
   * Build Payment Session
   *
   * @param ZIPPY_Antom_Payment $order_id
   */

  public static function buildPaymentSession($request)
  {
    $order_id = $request->get_param('order_id');

    if (!is_numeric($order_id) || empty($order_id)) {
      return new WP_Error('invalid_order_id', 'Order ID must be a number', array('status' => 400));
    }
    $api = new ZIPPY_Antom_Api($order_id);

    $response =  json_encode($api->createPaymentSession());

    return new WP_REST_Response(['message' => 'Payment session created successfully', 'data' => $response], 200);
  }
}
