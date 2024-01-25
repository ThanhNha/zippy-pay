<?php

namespace ZIPPY_Pay\Core\Paynow;

use WC_Payment_Gateway;
use ZIPPY_Pay\Src\Paynow\ZIPPY_Paynow_Api;


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
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
		add_action('woocommerce_api_zippy_paynow_callback', [$this, 'handle_payment_redirect']);
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
		//Check paynow is avaliable from zippy
		if ($this->is_gateway_configured()) {
			$message = '<p>Paynow is ready for payment.</p>';
		} else {
			$message = '<span class="zippy-has-error">We can not process the payment at the moment. Please, try again later.</span>';
		}
		echo wp_kses_post($message);
	}


	/**
	 * Woocomerce process payment
	 *
	 */
	public function process_payment($order_id)
	{

		$order              = new WC_Order($order_id);
		// $adyen_payment_data = $this->get_adyen_payment_data();
		// $zippy              = new ZIPPY_Pay_Adyen($this->zippyConfigs);
		// $result = $zippy->pay($order, $adyen_payment_data);

		// Failed Payment
		if (empty($result)) {
			$this->handle_payment_failed();
		}

		return $this->handle_do_payment($order);
	}

	// public function is_available()
	// {
	// 	return $this->is_gateway_configured();
	// }


	private function is_gateway_configured()
	{
		$paynow_response = ZIPPY_Paynow_Api::CheckPaynowIsActive();

		if (empty($paynow_response) || !$paynow_response['status']) return false;

		$is_active = $paynow_response['data']->result->paynowConfig;

		return 	$is_active;
	}
}
