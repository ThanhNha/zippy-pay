<?php

namespace ZIPPY_Pay\Adyen;

use Throwable;
use ZIPPY_Pay\Core\Adyen\ZIPPY_Adyen_Pay_Api;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;
use WC_Order;
use Exception;
use ZIPPY_Pay\Adyen\ZIPPY_Pay_Adyen_Logger;
use WC_Admin_Settings;

class ZIPPY_Pay_Adyen
{
	/**
	 * @var ZIPPY_Pay_Adyen_Config
	 */
	private $adyenConfig;

	/**
	 * Adyen constructor.
	 *
	 * @param ZIPPY_Pay_Adyen_Config $adyenConfig
	 */
	public function __construct($adyenConfig)
	{
		$this->adyenConfig = $adyenConfig;
	}

	/**
	 * @param $url
	 *
	 * @param $adyen_payment_data
	 *
	 * @return mixed
	 * @throws Throwable
	 */

	public function get_token($url)
	{

		$token = ZIPPY_Adyen_Pay_Api::get_token_from_zippy($url);

		return $token;
	}

	/**
	 * @param WC_Order $order
	 *
	 * @param $adyen_payment_data
	 *
	 * @return mixed
	 * @throws Throwable
	 */
	public function pay($order, $adyen_payment_data)
	{
		$url              = WC_Admin_Settings::get_option(PREFIX .  '_base_url');

		try {
			$payload = $this->build_payment_payload($order, $adyen_payment_data);

			$result = ZIPPY_Adyen_Pay_Api::checkout($url, $payload);

			if (is_numeric($result)) {
				$token = $this->get_token($url, false);

				$result = ZIPPY_Adyen_Pay_Api::checkout($url, $payload, $token);
			}
			
			if (!$result || !isset($result->Result)) {
				throw new Exception("Checkout Order Failed.");
			};
		} catch (Throwable $exception) {
			ZIPPY_Pay_Adyen_Logger::log_checkout($exception->getMessage(), $payload);
			return [];
		}
		return $result;
	}

	/**
	 * @param WC_Order $order_key
	 *
	 * @return mixed
	 *
	 * @throws Throwable
	 */
	public function get_transaction_status($order_key)
	{

		$url              = WC_Admin_Settings::get_option(PREFIX .  '_base_url');
		$current_time = date('Y-m-d H:i:s.v');
		$params = array(
			"orderNumber" => ZIPPY_Pay_Core::get_merchant_reference($order_key),
			"updatedFrom" => $current_time
		);
		try {
			$status = ZIPPY_Adyen_Pay_Api::transactionStatus($url, $params);

			if (is_numeric($status)) {
				$token = $this->get_token($url, false);
				$status = ZIPPY_Adyen_Pay_Api::transactionStatus($url, $params, $token);
			}
			if (!$status && !isset($status->result)) {
				throw new Exception("Missing Transaction Status.");
			}
		} catch (Throwable $exception) {
			ZIPPY_Pay_Adyen_Logger::log($exception->getMessage());
			return [];
		}
		return $status;
	}

	/**
	 * @param $amount
	 *
	 * @return mixed
	 */
	public function get_payment_config($url)
	{
		$url = WC_Admin_Settings::get_option(PREFIX .  '_base_url');
		try {
			$paymentConfig              = ZIPPY_Adyen_Pay_Api::getConfigs($url);
			if (is_numeric($paymentConfig)) {
				$token = $this->get_token($url, false);
				$paymentConfig              = ZIPPY_Adyen_Pay_Api::getConfigs($url, $token);
			}
			if (!$paymentConfig || !isset($paymentConfig->result)) {
				throw new Exception("Missing Payment Configs.");
			}
		} catch (Throwable $exception) {
			ZIPPY_Pay_Adyen_Logger::log($exception->getMessage());
			return [];
		}

		return $paymentConfig->result;
	}

	/**
	 * Builds the required payment payload
	 *
	 * @param WC_Order $order
	 * @param $adyen_payment_data
	 * @return array
	 */
	protected function build_payment_payload(WC_Order $order, $adyen_payment_data)
	{

		$payload = [
			'store' 								 	 => WC_Admin_Settings::get_option(PREFIX . '_merchant_id'), //store_id get from config
			'merchantOrderReference'   => ZIPPY_Pay_Core::get_merchant_reference($order->get_order_key()),
			'reference' 						 	 => $order->get_id(),
			'metadata' 			=> [
				'cardBrand' 					 	 => $adyen_payment_data['paymentMethod']['brand'],
				'orgName' 					 	 	 => ZIPPY_Pay_Core::get_domain_name(),
				'merchantOrderReference' => ZIPPY_Pay_Core::get_merchant_reference($order->get_order_key()),

			],
			'returnUrl'                => str_replace('https:', 'http:', add_query_arg(array(
				'wc-api'      => 'WC_Zippy_Redirect',
				'order_id'   	=> $order->get_id()
			), home_url('/'))),
			'channel'                  => 'Web',
			'amount'        => [
				'currency'               => $order->get_currency(),
				'value'                  => $order->get_total() * 100,
			],
			'paymentMethod'            => $adyen_payment_data['paymentMethod'],
			'browserInfo' 	=> [
				'userAgent'      				 => ZIPPY_Pay_Core::get_user_agent(),
				'acceptHeader'   				 => ZIPPY_Pay_Core::get_http_accept(),
				'language'       				 => ZIPPY_Pay_Core::get_locale(),
				'javaEnabled'    				 => true,
				'colorDepth'     				 => 24,
				'timeZoneOffset' 				 => 0,
				'screenHeight'   				 => 723,
				'screenWidth'    				 => 1536
			],
		];

		return $payload;
	}
}
