<?php

namespace ZIPPY_Pay\Src\Antom;

use ZIPPY_Pay\Src\Antom\ZIPPY_Antom_Api;
use ZIPPY_Pay\Src\Logs\ZIPPY_Pay_Logger;
use WP_REST_Response;

defined('ABSPATH') || exit;

class ZIPPY_Antom_Payment
{
  /**
   * Build Payment Session
   *
   * @param ZIPPY_Antom_Payment $order_id
   */

  const MAX_ATTEMPTS = 5;
  const CHECK_ANTOM_TRANSACTION_JOB_NAME = 'zippy_check_antom_payment_task';

  public static function registerHooks()
  {
    add_action(self::CHECK_ANTOM_TRANSACTION_JOB_NAME, [self::class, 'checkPaymentTransactionJob'], 10, 1);
  }

  public static function createPaymentSession($request)
  {
    $order_id = $request->get_param('order_id');

    if (!is_numeric($order_id) || empty($order_id)) {
      return new WP_Error('invalid_order_id', 'Order ID must be a number', array('status' => 400));
    }

    $api = new ZIPPY_Antom_Api($order_id);

    $response = $api->createPaymentSessionApi();
    //stored paymentRequestId
    if (!empty($response['data']->paymentRequestId)) {
      update_post_meta($order_id, 'paymentRequestId', $response['data']->paymentRequestId);
    }

    return new WP_REST_Response(['data' => $response], 200);
  }

  public static function checkPaymentTransaction($request)
  {
    $order_id = $request->get_param('order_id');

    if (!wp_next_scheduled(self::CHECK_ANTOM_TRANSACTION_JOB_NAME, array($order_id, 1))) {

      do_action(self::CHECK_ANTOM_TRANSACTION_JOB_NAME, array($order_id, 1));

      wp_schedule_single_event(time() + 5, self::CHECK_ANTOM_TRANSACTION_JOB_NAME, array($order_id, 1));
    }

    return new WP_REST_Response(['data' => "SUCCESS"], 200);
  }

  public static function checkPaymentTransactionJob($args)
  {
    $order_id = $args[0];

    $attempt = $args[1];

    $api = new ZIPPY_Antom_Api($order_id);

    $response = $api->checkPaymentTransactionApi();

    // var_dump($response['data']->paymentStatus);

    // Validate response format
    if (!isset($response['data'])) {
      ZIPPY_Pay_Logger::log_checkout("Invalid response for order_id: $order_id", $order_id);
      return;
    }

    $transaction_status = $response['data']->paymentStatus;

    if ($transaction_status === 'SUCCESS') {

      update_post_meta($order_id, 'zippy_antom_transaction', $response['data']);

      // Stop further scheduling since payment is complete
      wp_clear_scheduled_hook(self::CHECK_ANTOM_TRANSACTION_JOB_NAME, array($order_id));
      ZIPPY_Pay_Logger::log_checkout("Payment SUCCESS for order_id: $order_id. Stopping background job.", $order_id);
      return;
    }

    if ($attempt >= self::MAX_ATTEMPTS) {
      ZIPPY_Pay_Logger::log_checkout("Max attempts reached for order_id: $order_id. Stopping retries.", $order_id);
      return;
    }

    $next_attempt = $attempt + 1;

    ZIPPY_Pay_Logger::log_checkout("Retrying payment check for order_id: $order_id. Attempt $next_attempt in 5 minutes", $order_id);

    // Schedule next attempt in 2 minutes
    wp_schedule_single_event(time() + 30, self::CHECK_ANTOM_TRANSACTION_JOB_NAME, array($order_id, $next_attempt));
  }
}

// Register the action hook when the plugin loads
ZIPPY_Antom_Payment::registerHooks();
