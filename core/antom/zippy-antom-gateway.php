<?php

namespace ZIPPY_Pay\Core\Antom;

use WC_Payment_Gateway;
use WC_Order;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;
use ZIPPY_Pay\Src\Antom\ZIPPY_Antom_Api;

defined('ABSPATH') || exit;

class ZIPPY_Antom_Gateway extends WC_Payment_Gateway
{

	/**
	 * ZIPPY_Antom_Gateway constructor.
	 */
	public function __construct()
	{
		$this->id           =  PAYMENT_ANTOM_ID;
		$this->method_title = __(PAYMENT_ANTOM_NAME, PREFIX . '_zippy_payment');
		$this->icon  =  ZIPPY_PAY_DIR_URL . 'includes/assets/icons/paynow.svg';
		$this->has_fields   = true;
		$this->init_form_fields();
		$this->init_settings();
		$this->title = PAYMENT_ANTOM_NAME;
		$this->method_description = __('', PREFIX . '_zippy_payment');
		$this->enabled         = $this->get_option('enabled');

		// Register the action hook when the plugin loads

		add_action('woocommerce_receipt_' . $this->id, [$this, 'receipt_page']);
		// add_action('woocommerce_thankyou_' . $this->id, [$this, 'handle_send_message_whatsapp']);
		// add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
		add_action('woocommerce_api_wc_zippy_antom_redirect', [$this, 'handle_redirect']);
	}

	/**
	 * Setup key form fields
	 *
	 */
	public function init_form_fields()
	{

		$this->form_fields = [
			'enabled'         => [
				'title'   => __('Enable ' . PAYMENT_ANTOM_NAME, PREFIX . '_zippy_payment'),
				'type'    => 'checkbox',
				'label'   => __('Enable ' . PAYMENT_ANTOM_NAME, PREFIX . '_zippy_payment'),
				'default' => 'no'
			],
		];
	}

	/**
	 * Inlude payment data form UI
	 *
	 */
	public function payment_fields()
	{
		$is_active = $this->is_gateway_configured();

		echo ZIPPY_Pay_Core::get_template('payment-section.php', [
			'is_active' => 	$is_active,
		], dirname(__FILE__), '/templates');
	}

	/**
	 * Woocomerce process payment
	 *
	 */
	public function process_payment($order_id)
	{

		$order              = new WC_Order($order_id);

		// // Failed Payment
		if (empty($order)) {
			$this->handle_payment_failed();
		}

		return $this->handle_do_payment($order);
	}


	/**
	 *
	 * Handle payment after receive response from Zippy
	 *
	 * @param WC_Order $order
	 *
	 * @return mixed
	 */

	private function handle_do_payment($order)
	{

		// if ($is_active = $this->is_gateway_configured()) {
		// 	return $this->handle_payment_failed();
		// }

		// always redirect to Zippy
		return $this->handle_payment_redirect($order);
	}

	/**
	 * This function will be run after user enter the place order button
	 *
	 */
	private function handle_payment_redirect($order)
	{

		return  [
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url(true)
		];
	}

	/**
	 * Woocomerce Custom receipt page support redirect checkout.
	 *
	 */

	public function receipt_page($order_id)
	{
		$order = new WC_Order($order_id);
		// var_dump($alipayResponse);

		echo ZIPPY_Pay_Core::get_template('payment-page.php', [
			'order_id' => 	$order_id,
		], dirname(__FILE__), '/templates');

		if (!empty($_REQUEST['antom_process']) &&  $_REQUEST['antom_process'] == 'checking') {

			$api = new ZIPPY_Antom_Api($order_id);

			$response =  $api->checkPaymentTransactionCallback($order_id);

			if ($response['data']->data == 'SUCCESS') {

				return $this->check_order_status($order_id);
			} else {

				wp_safe_redirect($order->get_checkout_payment_url());
			}
		}
	}

	/**
	 * This is callback func called after reponse from Zippy 
	 *
	 */
	public function handle_redirect()
	{
		// Check status order 
		$order_id = intval($_REQUEST['order_id']);

		var_dump($order_id);
		// 	$merchant_id = get_option(PREFIX . '_merchant_id');

		// 	$order = new WC_Order($order_id);

		// 	$amount = $order->get_total();

		// 	if (!isset($_REQUEST['order_id']) || empty($_REQUEST['order_id'])) {
		// 		wp_redirect($order->get_checkout_payment_url());
		// 		exit;
		// 	}

		// 	$api = new ZIPPY_Paynow_Api();

		// 	$status = $api->checkStatusOrder($merchant_id, $order_id, $amount);

		// 	return $this->check_order_status($status, $order);
	}

	private function check_order_status($order_id)
	{

		$order          = new WC_Order($order_id);

		$status = $this->handle_get_transaction_status($order_id);

		if (isset($status) && $status == "SUCCESS") {

			$order->add_order_note(sprintf(__('Payment was complete via ' . PAYMENT_ANTOM_NAME, PREFIX . '_zippy_payment')));

			$order->payment_complete();

			// should get payment details to log in the order.

			wp_redirect($this->get_return_url($order));
		} else {
			wp_safe_redirect($order->get_checkout_payment_url()); // Redirect to page pay-order to payment again.
		}
	}


	/**
	 * Handle get payment-status for payment case: pending,received and redirect
	 *
	 * @param $result
	 *
	 * @param WC_Order $order
	 *
	 * @return mixed
	 */
	private function handle_get_transaction_status($order_id)
	{


		for ($i = 0; $i < 2; $i++) {

			if ($i > 0) sleep(5);

			$transaction =	get_post_meta($order_id, 'zippy_antom_transaction');

			$status = $transaction[0]->transactionStatus;

			if (isset($status) && $status == "SUCCESS") break;
		}

		return $status;
	}
	/**
	 * Handle do payment failed
	 *
	 * @return mixed
	 */

	private function handle_payment_failed()
	{

		$this->add_notice();
		return false;
	}


	// public function is_available()
	// {
	// 	return $this->is_gateway_configured();
	// }

	private function is_gateway_configured()
	{
		$is_active = true;
		// $api = new ZIPPY_Paynow_Api();

		// // $merchant_id = get_option(PREFIX . '_merchant_id');

		// // $checkPaynowStatus = $api->checkPaynowIsActive($merchant_id);

		// if (empty($checkPaynowStatus['data']) || !$checkPaynowStatus['status']) return false;

		// $is_active = $checkPaynowStatus['data']->result->paynowConfig;

		return 	$is_active;
	}

	/**
	 * Add notice when payment failed
	 *
	 * @return mixed
	 */

	private function add_notice()
	{
		return	wc_add_notice(__('Something went wrong with the payment. Please try again using another Credit / Debit Card.', PREFIX . '_zippy_payment'), 'error');
	}
}
