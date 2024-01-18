<?php

namespace ZIPPY_Pay\Settings;

use WC_Admin_Settings;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;


defined('ABSPATH') || exit;

class ZIPPY_Fields_Setting
{

  /**
     * The single instance of the class.
     *
     * @var   ZIPPY_Field_Settings
     */
    protected static $_instance = null;

    /**
     * @return ZIPPY_Field_Settings
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

  /**
   * Constructor
   */
  public function __construct()
  {

    add_action('woocommerce_admin_field_zippy_credit_card_field', array($this, 'zippy_credit_card_settings'));
    add_action('woocommerce_admin_field_zippy_paynow_field', array($this, 'zippy_paynow_settings'));
    add_action('woocommerce_admin_field_zippy_general_field', array($this, 'zippy_general_settings'));
  }

  /**
   * Add Additional Settings Of Zippy General Setting
   *
   *
   */
  function zippy_general_settings($current_section = '')
  {
    
  }

  /**
   * Add Additional Settings Of Zippy credit card
   *
   *
   */
  function zippy_credit_card_settings($current_section = '')
  {
    $client_id =  WC_Admin_Settings::get_option(PREFIX . '_client_id_key');

    $secret_key =  WC_Admin_Settings::get_option(PREFIX . '_secret_key');

    $token =  null;

    $params = array(
      'client_key' => array(
        'id' => PREFIX . '_client_id_key',
        'value' => $client_id
      ),
      'secret_key' => array(
        'id' => PREFIX . '_secret_key',
        'value' => $secret_key
      ),
      'token' => array(
        'id' => PREFIX . '_token',
        'value' => $token
      ),

    );

    echo ZIPPY_Pay_Core::get_template('credit-card/setting-fields.php', [
      'params' => $params,
    ], dirname(__FILE__), '/templates');
  }

  /**
   * Add Additional Settings Of Zippy Paynow
   *
   *
   */
  function zippy_paynow_settings($current_section = '')
  {


    echo ZIPPY_Pay_Core::get_template('paynow/setting-fields.php', [
      'params' => null,
    ], dirname(__FILE__), '/templates');
  }
}
