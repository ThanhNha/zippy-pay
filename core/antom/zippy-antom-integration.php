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
        // add_filter('template_include', array($this, 'zippy_override_page_template'), 1, 3);
        add_filter('woocommerce_payment_gateways',  array($this, 'add_zippy_antom_to_woocommerce'));

        add_action('plugins_loaded',  array($this, 'zippy_antom_load_plugin_textdomain'));

        add_action('wp_enqueue_scripts', [$this, 'scripts_and_styles']);
    }

    public function zippy_override_page_template($template)
    {
        if (isset($_GET['amtom_payment']) && $_GET['amtom_payment'] === 'true') {
            $template_directory = untrailingslashit(plugin_dir_path(__FILE__)) . "/templates/page-payment-antom.php";

            if (file_exists($template_directory)) {
                return $template_directory;
            }
        }
        return $template;
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

    public function scripts_and_styles()
    {

        if (!is_checkout()) {
            return;
        }
        $version = time();
        // wp_enqueue_script('antom-sdk','https://sdk.marmot-cloud.com/package/ams-checkout/1.27.0/dist/umd/ams-checkout.min.js', [], '', true);

        wp_enqueue_script('antom-custom', ZIPPY_PAY_DIR_URL . 'includes/assets/dist/js/web.min.js', [], $version, ['strategy'  => 'async',]);
    }

    public function zippy_antom_load_plugin_textdomain()
    {
        load_plugin_textdomain('payment-gateway-for-zippy-and-woocommerce', false, basename(dirname(__FILE__)) . '/languages/');
    }
}
