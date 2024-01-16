<?php

namespace ZIPPY_Pay\Core\Adyen;

use ZIPPY_Pay\Core\Adyen\ZIPPY_Adyen_Pay_Gateway;

class ZIPPY_Adyen_Pay_Integration
{

    /**
     * The single instance of the class.
     *
     * @var   ZIPPY_Adyen_Pay_Integration
     */
    protected static $_instance = null;

    /**
     * @return ZIPPY_Adyen_Pay_Integration
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * ZIPPY_Adyen_Pay_Integration constructor.
     */
    public function __construct()
    {

        if (!$this->is_woocommerce_active()) {
            return;
        }
        if (is_admin()) {
            add_filter('woocommerce_get_settings_pages', [$this, 'setting_page']);
        }

        add_filter('woocommerce_payment_gateways', [$this, 'add_zippy_to_woocommerce']);
        // add_action('plugins_loaded', [$this, 'init_zippy_payment_gateway']);
        add_action('plugins_loaded', [$this, 'zippy_payment_load_plugin_textdomain']);
        add_action('wp_enqueue_scripts', [$this, 'scripts_and_styles']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts_and_styles']);

    }
    
    public function setting_page($settings){
            $settings[] = include ZIPPY_PAY_DIR_PATH . '/settings/zippy-pay-settings.php';
            return $settings;
    }

    public function admin_scripts_and_styles()
    {
        wp_enqueue_script('adyen-admin', ZIPPY_PAY_DIR_URL . 'includes/assets/js/admin-adyen.js');
    }

    public function scripts_and_styles()
    {

        if (!is_checkout()) {
            return;
        }

        wp_enqueue_script('adyen-sdk', ZIPPY_PAY_DIR_URL . 'includes/assets/js/adyen-live.min.js', [], '5.49.0', true);
        wp_enqueue_style('adyen-css', ZIPPY_PAY_DIR_URL . 'includes/assets/css/adyen.min.css', [], '5.49.0');
        wp_enqueue_style('adyen-css-checkout', ZIPPY_PAY_DIR_URL . 'includes/assets/css/checkout.css', [], '5.49.0');
    }


    public function add_zippy_to_woocommerce($gateways)
    {

        $gateways[] = ZIPPY_Adyen_Pay_Gateway::class;
        return $gateways;
    }

    public function init_zippy_payment_gateway()
    {
        include ZIPPY_PAY_DIR_PATH . '/zippy-payment-getway.php';
    }

    public function zippy_payment_load_plugin_textdomain()
    {
        load_plugin_textdomain('payment-gateway-for-adyen-and-woocommerce', false, basename(dirname(__FILE__)) . '/languages/');
    }


    private function is_woocommerce_active()
    {
        $active_plugins = (array) get_option('active_plugins', array());

        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }

        return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }
}
