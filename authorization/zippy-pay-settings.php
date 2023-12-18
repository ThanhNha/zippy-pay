<?php

namespace ZIPPY_Pay\Authorization;

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
    $this->label = __('Zippy Payment',  PREFIX . 'woocommerce-settings-tab');
    add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
    add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
    add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
    add_action('woocommerce_admin_field_zippy_authorization', array($this, 'output_additional_settings'));
    add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
  }

  /**
   * Add plugin options tab
   *
   * @return array
   */
  public function add_settings_tab($settings_tabs)
  {
    $settings_tabs[$this->id] = __('Zippy Pay', PREFIX . 'woocommerce-settings-tab');
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
      ''                      => __('Config', PREFIX . 'woocommerce-settings-tab'),
      // 'zippy_authorization'   => __('Authorization', PREFIX . 'woocommerce-settings-tab'),
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

      case 'zippy_authorization':

        $settings = array(
          'section_title' => array(
            'name'     => __('Authorization', PREFIX . 'woocommerce-settings-tab'),
            'type'     => 'title',
            'desc'     => __('Authorization to call api Zippy platform', PREFIX . 'woocommerce-settings-tab'),
            'id'       => 'wc_settings_tab_authorization'
          ),
          'divider' => ZIPPY_Pay_Core::divider(),
          'zippy_authorization' => array(
            'id'        => 'zippy_authorization',
            'type'      => 'zippy_authorization',
            'default' => '',
          ),
          'section_end' => array(
            'type' => 'sectionend',
            'id' => 'wc_settings_tab_end_authorization'
          )
        );

        break;

      default:
        $settings = array(
          'section_title' => array(
            'name'     => __('Config', PREFIX . 'woocommerce-settings-tab'),
            'type'     => 'title',
            'desc'     => __('Config to integration with Zippy Pay' . $this->show_warning_message(), PREFIX . 'woocommerce-settings-tab'),
            'id'       => 'wc_settings_tab_congfig'
          ),
          'test_mode'         => array(
            'title'   => __('Test Mode', PREFIX . 'woocommerce-settings-tab'),
            'type'    => 'checkbox',
            'label'   => __('Test Mode', PREFIX . 'woocommerce-settings-tab'),
            'default' => 'no',
            'id'       => PREFIX . '_test_mode'
          ),
          'sep'   => ZIPPY_Pay_Core::divider(),
          'merchant_id'       => array(
            'title'   => __('Merchant id', PREFIX . 'woocommerce-settings-tab'),
            'type'    => 'text',
            'desc' => __('Your ID store on Zippy platform', PREFIX . 'woocommerce-settings-tab'),
            'default' => '',
            'id'       => PREFIX . '_merchant_id'
          ),
          'base_url'       => array(
            'title'   => __('Base Url', PREFIX . 'woocommerce-settings-tab'),
            'type'    => 'text',
            'desc' => __('Endpoint get api from Zippy platform', PREFIX . 'woocommerce-settings-tab'),
            'default' => '',
            'id'       => PREFIX . '_base_url'
          ),
          'secret_key'       => array(
            'title'   => __('Secrect Key', PREFIX . 'woocommerce-settings-tab'),
            'type'    => 'text',
            'desc' => __('Your key on Zippy platform', PREFIX . 'woocommerce-settings-tab'),
            'default' => '',
            'id'       => PREFIX . '_secret_key'
          ),
          'section_end' => array(
            'type' => 'sectionend',
            'id' => 'wc_settings_tab_end_authorization'
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

    // $this->do_update_options_action();
  }

  /**
   * Print Out Additional Settings
   *
   * @return array
   */
  function output_additional_settings($current_section = '')
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

    echo ZIPPY_Pay_Core::get_template('adyen/section-fields.php', [
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
    $message = '<span style="color: #cc0000;display: block;"><br> **NOTE** We only support order total with format 2 number of decimals places</span>';
    return $message;
  }
}

$wc_gateways      = new WC_Payment_Gateways();
$payment_gateways = $wc_gateways->payment_gateways();

if (!isset($payment_gateways[PAYMENT_ID]) || $payment_gateways[PAYMENT_ID]->enabled == 'no') {
  return '';
} else {
  return new ZIPPY_Pay_Settings();

}
