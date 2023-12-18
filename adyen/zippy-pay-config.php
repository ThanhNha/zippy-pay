<?php

namespace ZIPPY_Pay\Adyen;

defined('ABSPATH') || exit;


class ZIPPY_Pay_Config
{

	/** @var string */
	private $environment;
	/** @var array */
	private $payment_config;
	/** @var string */
	private $merchant_id;
	/** @var string */
	private $base_url;
	/** @var string */

	/**
	 * Adyen_Config constructor.
	 *
	 * @param array $settings
	 */
	public function __construct($settings)
	{
		$this->setEnvironment($settings['test_mode']);
		$this->setMerchantId($settings['merchant_id']);
		$this->setBaseUrl($settings['base_url']);
	}

	/**
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->base_url;
	}

	/**
	 * @param string $base_url
	 *
	 * @return ZIPPY_Pay_Config
	 */
	public function setBaseUrl($base_url)
	{

		$this->base_url = trim($base_url);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	/**
	 * @param string $test_mode
	 *
	 * @return ZIPPY_Pay_Config
	 */
	public function setEnvironment($test_mode)
	{
		$this->environment = ($test_mode === 'yes') ? \Adyen\Environment::TEST : \Adyen\Environment::LIVE;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMerchantId()
	{
		return $this->merchant_id;
	}

	/**
	 * @param string $merchant_id
	 *
	 * @return ZIPPY_Pay_Config
	 */
	public function setMerchantId($merchant_id)
	{

		$this->merchant_id = trim($merchant_id);

		return $this;
	}
}
