<?php

namespace ZIPPY_Pay\Core\Antom;

use ZIPPY_Pay\Core\Antom\ZIPPY_Antom_Gateway;

use ZIPPY_Pay\Core\ZIPPY_Pay_Core;

class ZIPPY_Antom_Integration
{

    /**
     * The single instance of the class.
     *
     * @var   ZIPPY_Antom_Integration
     */
    protected static $_instance = null;

    /**
     * @return ZIPPY_Antom_Integration
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * ZIPPY_Antom_Integration constructor.
     */
    public function __construct()
    {

        if (!ZIPPY_Pay_Core::is_woocommerce_active()) {
            return;
        }
        add_filter('woocommerce_payment_gateways',  array($this, 'add_zippy_antom_to_woocommerce'));
        add_action('plugins_loaded',  array($this, 'zippy_antom_load_plugin_textdomain'));
    }

    public function add_zippy_antom_to_woocommerce($gateways)
    {

        $gateways[] = ZIPPY_Antom_Gateway::class;
        return $gateways;
    }

    public function init_zippy_payment_gateway()
    {
        include ZIPPY_PAY_DIR_PATH . '/zippy-payment-getway.php';
    }

    public function zippy_antom_load_plugin_textdomain()
    {
        load_plugin_textdomain('payment-gateway-for-zippy-and-woocommerce', false, basename(dirname(__FILE__)) . '/languages/');
    }


}
