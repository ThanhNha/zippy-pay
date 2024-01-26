<?php

namespace ZIPPY_Pay\Core\Paynow;

use WC_Payment_Gateway;
use WC_Order;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;
use ZIPPY_Pay\Src\Paynow\ZIPPY_Paynow_Api;
use ZIPPY_Pay\Src\Paynow\ZIPPY_Paynow_Payment;


defined('ABSPATH') || exit;

class ZIPPY_Paynow_Gateway extends WC_Payment_Gateway
{

	/**
	 * ZIPPY_Paynow_Gateway constructor.
	 */
	public function __construct()
	{

		$this->id           =  PAYMENT_PAYNOW_ID;
		$this->method_title = __(PAYMENT_PAYNOW_NAME, PREFIX . '_zippy_payment');
		$this->icon  =  ZIPPY_PAY_DIR_URL . 'includes/assets/icons/paynow.svg';
		$this->has_fields   = true;
		$this->init_form_fields();
		$this->init_settings();
		$this->title = 'Paynow';
		$this->method_description = __('', PREFIX . '_zippy_payment');
		$this->enabled         = $this->get_option('enabled');
		add_action('woocommerce_receipt_' . $this->id, [$this, 'receipt_page']);
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
		add_action('woocommerce_api_zippy_paynow_callback', [$this, 'handle_redirect']);
	}

	/**
	 * Setup key form fields
	 *
	 */
	public function init_form_fields()
	{

		$this->form_fields = [
			'enabled'         => [
				'title'   => __('Enable ' . PAYMENT_PAYNOW_NAME, PREFIX . '_zippy_payment'),
				'type'    => 'checkbox',
				'label'   => __('Enable ' . PAYMENT_PAYNOW_NAME, PREFIX . '_zippy_payment'),
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
		include_once ZIPPY_PAY_DIR_PATH . 'core/templates/paynow/message-fields.php';
	}

	/**
	 * Woocomerce process payment
	 *
	 */
	public function process_payment($order_id)
	{

		$order              = new WC_Order($order_id);

		// Failed Payment
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
		// always redirect to Zippy 
		return $this->handle_payment_redirect($order);
	}

	/**
	 * This function will be run after user enter the place order button
	 *
	 */
	private function handle_payment_redirect($order)
	{

		$order_id = $order->get_id();

		$paynow = new ZIPPY_Paynow_Payment($order);

		$api = new ZIPPY_Paynow_Api();

		$paynow_payload = $paynow->build_payment_payload();

		$paynow_response = $api->paynowPayment($paynow_payload);

		update_option('zippy_paynow_redirect_object_' . $order_id, $paynow_response);

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
		$redirectData = get_option('zippy_paynow_redirect_object_' . $order_id);

		if (!isset($redirectData) || empty($redirectData)) {
			wp_safe_redirect(wc_get_checkout_url(), '301');
			$this->add_notice();
		}

		wp_redirect($redirectData->Result->redirectUrl);
	}

	/**
	 * This is callback func called after reponse from Zippy 
	 *
	 */
	public function handle_redirect()
	{
		echo 'shin';
		die();
		
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
		$api = new ZIPPY_Paynow_Api();

		$checkPaynowStatus = $api->checkPaynowIsActive();

		if (empty($checkPaynowStatus['data']) || !$checkPaynowStatus['status']) return false;

		$is_active = $checkPaynowStatus['data']->result->paynowConfig;

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
