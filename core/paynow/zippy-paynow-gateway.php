<?php

namespace ZIPPY_Pay\Core\Paynow;

use ZIPPY_Pay\Core\ZIPPY_Pay_Core;
use WC_Payment_Gateway;



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
			'sep'   => ZIPPY_Pay_Core::separator(),
		];
	}
}
