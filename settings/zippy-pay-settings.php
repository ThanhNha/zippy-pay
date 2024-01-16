<?php

namespace ZIPPY_Pay\Settings;

use WC_Settings_Page;
use WC_Admin_Settings;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;
use WC_Payment_Gateways;


defined('ABSPATH') || exit;

class ZIPPY_Pay_Settings extends WC_Settings_Page
{

  /**
   * @var string
   */
  private $client_key;
  /**
   * @var string
   */
  private $secret_key;

  /**
   * Constructor
   */
  public function __construct()
  {

    $this->id    = 'zippy_payment_getway';
    $this->label = __('Zippy Payment',  PREFIX . 'zippy-settings-tab');
    add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
    add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
    add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
    add_action('woocommerce_admin_field_zippy_credit_card_field', array($this, 'zippy_credit_card_settings'));
    add_action('woocommerce_admin_field_zippy_paynow_field', array($this, 'zippy_paynow_settings'));
    add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
  }

  /**
   * Add plugin options tab
   *
   * @return array
   */
  public function add_settings_tab($settings_tabs)
  {
    $settings_tabs[$this->id] = __('EPOSPay', PREFIX . 'zippy-settings-tab');
    return $settings_tabs;
  }

  /**
   * Get sections
   *
   * @return array
   */
  public function get_sections()
  {

    $sections = array(
      ''                      => __('General', PREFIX . 'zippy-settings-tab'),
      'zippy_credit_card'   => __('EPOSPay Credit Card', PREFIX . 'zippy-settings-tab'),
      'zippy_paynow'   => __('EPOSPay Paynow', PREFIX . 'zippy-settings-tab'),
    );

    return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
  }

  /**
   * Get sections
   *
   * @return array
   */
  public function get_settings($section = null)
  {

    switch ($section) {

      case 'zippy_credit_card':

        $settings = array(
          'section_title' => $this->show_warning_message(),
          'divider' => ZIPPY_Pay_Core::divider(),
          'enable_credit_card'         => array(
            'title'   => __('Enable EPOSPay Credit Card', PREFIX . 'zippy-settings-tab'),
            'type'    => 'checkbox',
            'label'   => __('Enable EPOSPay Credit Card', PREFIX . 'zippy-settings-tab'),
            'default' => 'no',
            'id'       => PREFIX . 'enable_credit_card_section'
          ),
          'zippy_credit_card_field'         => array(
            'id'       => 'zippy_credit_card_field',
            'title'   => __('Payment methods', PREFIX . 'zippy-settings-tab'),
            'type'      => 'zippy_credit_card_field',
          ),
          'divider' => ZIPPY_Pay_Core::divider(),
          'section_end' => array(
            'type' => 'sectionend',
            'id' => 'zippy_settings_tab_end_credit_card'
          )
        );

        break;

      case 'zippy_paynow':

        $settings = array(
          'section_title' => $this->show_warning_message(),
          'divider' => ZIPPY_Pay_Core::divider(),
          'enable_zippy_paynow'         => array(
            'title'   => __('Enable EPOSPay Paynow', PREFIX . 'zippy-settings-tab'),
            'type'    => 'checkbox',
            'label'   => __('Enable EPOSPay Paynow', PREFIX . 'zippy-settings-tab'),
            'default' => 'no',
            'id'       => PREFIX . 'enable_credit_card_section'
          ),
          'zippy_paynow_field' => array(
            'id'        => 'zippy_paynow_field',
            'type'      => 'zippy_paynow_field',
            'default' => '',
          ),
          'section_end' => array(
            'type' => 'sectionend',
            'id' => 'zippy_settings_tab_end_paynow'
          )
        );

        break;

      default:
        $settings = array(
          'section_title' => $this->show_warning_message(),
          'divider' => ZIPPY_Pay_Core::divider(),
          'merchant_id'       => array(
            'title'   => __('Merchant ID', PREFIX . 'zippy-settings-tab'),
            'type'    => 'text',
            'desc' => __('Your Store ID in the Zippy platform', PREFIX . 'zippy-settings-tab'),
            'default' => '',
            'id'       => PREFIX . '_merchant_id'
          ),
          'secret_key'       => array(
            'title'   => __('Secret Key', PREFIX . 'zippy-settings-tab'),
            'type'    => 'text',
            'desc' => __('Your Secret Key in the Zippy platform', PREFIX . 'zippy-settings-tab'),
            'default' => '',
            'id'       => PREFIX . '_secret_key'
          ),
          'section_end' => array(
            'type' => 'sectionend',
            'id' => 'zippy_settings_tab_end_general'
          )
        );
        break;
    }

    return apply_filters('wc_settings_tab_settings', $settings, $section);
  }

  /**
   * Output the settings
   */
  public function output()
  {
    global $current_section, $hide_save_button;

    if (!empty($current_section)) $hide_save_button = true;
    $settings = $this->get_settings($current_section);
    WC_Admin_Settings::output_fields($settings);
  }


  /**
   * Save settings
   */
  public function save()
  {
    global $current_section;
    $settings = $this->get_settings($current_section);
    WC_Admin_Settings::save_fields($settings);
    $this->save_settings_for_current_section();

    $this->do_update_options_action();
  }

  /**
   * Print Out Additional Settings
   *
   * @return array
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
   * Print Out Additional Settings
   *
   * @return array
   */
  function zippy_paynow_settings($current_section = '')
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

  protected function do_update_options_action($section_id = null)
  {
    global $current_section;

    if (is_null($section_id)) {
      $section_id = $current_section;
    }

    if ($section_id) {
      do_action('woocommerce_update_options_' . $section_id);
    }
  }
  protected function show_warning_message()
  {
    $settings_title = array(
      'name'     => __('EPOSPay', PREFIX . 'zippy-settings-tab'),
      'type'     => 'title',
      'desc'     => __('This configuration Integrates with EPOSPay Credit Card & EPOSPay Paynow <span style="color: #cc0000;display: block;">
      ** We only support order totals up to 2 decimals places</span>', PREFIX . 'zippy-settings-tab'),
      'id'       => 'zippy_settings_tab_title_section'
    );
    return $settings_title;
  }
}

$wc_gateways      = new WC_Payment_Gateways();
$payment_gateways = $wc_gateways->payment_gateways();

if (!isset($payment_gateways[PAYMENT_ID]) || $payment_gateways[PAYMENT_ID]->enabled == 'no') {
  return '';
} else {
  return new ZIPPY_Pay_Settings();
}
