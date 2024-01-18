<?php

namespace ZIPPY_Pay\Core\Paynow;

use WC_Payment_Gateway;


defined('ABSPATH') || exit;

class ZIPPY_Paynow_Gateway extends WC_Payment_Gateway
{

  /**
   * ZIPPY_Adyen_Pay_Gateway constructor.
   */
  public function __construct()
  {

    $this->id           =  PAYMENT_PAYNOW_ID;
    $this->method_title = __(PAYMENT_PAYNOW_NAME, PREFIX . '_woocommerce');
    $this->icon  =  ZIPPY_PAY_DIR_URL . 'includes/assets/icons/paynow.svg';
    $this->has_fields   = true;
    $this->title = 'Paynow';
		$this->method_description = __('', PREFIX . '_woocommerce');

  }
}
