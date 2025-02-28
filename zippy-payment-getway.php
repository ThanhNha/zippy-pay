<?php
/* @wordpress-plugin
 * Plugin Name:       Zippy Pay
 * Plugin URI:
 * Description:       Accept adyen payments on your WooCommerce shop
 * Version:           3.0.0
 * WC requires at least: 3.0
 * WC tested up to: 6.7
 * Author:            Zippy
 * Author URI:        https://zippy.sg/
 * Text Domain:       zippy-and-woocommerce
 * license:           GPL-2.0+
 * license URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

namespace ZIPPY_Pay;

use ZIPPY_Pay\Core\Adyen\ZIPPY_Adyen_Pay_Integration;
use ZIPPY_Pay\Core\Paynow\ZIPPY_Paynow_Integration;
use ZIPPY_Pay\Core\Antom\ZIPPY_Antom_Integration;
use ZIPPY_Pay\Settings\Zippy_Pay_Ajax_Handle;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;
use ZIPPY_Pay\Src\Woocommerce\Zippy_Woo_Template;

define('ZIPPY_PAY_DIR_URL', plugin_dir_url(__FILE__));
define('ZIPPY_PAY_DIR_PATH', plugin_dir_path(__FILE__));
define('ZIPPY_PAY_ENDPOINT', plugin_dir_path(__FILE__));
define('PGAWC_VERSION', '1.0.0');
define('PAYMENT_ADYEN_NAME', 'Online Payment');
define('PAYMENT_PAYNOW_NAME', 'Paynow');
define('PAYMENT_ANTOM_NAME', 'EPOS Pay (Antom)');
define('PREFIX', 'zippy_payment_getway');
define('PAYMENT_ADYEN_ID', 'zippy_adyen_payment');
define('PAYMENT_PAYNOW_ID', 'zippy_paynow_payment');
define('PAYMENT_ANTOM_ID', 'zippy_antom_payment');

require ZIPPY_PAY_DIR_PATH . '/vendor/autoload.php';
require ZIPPY_PAY_DIR_PATH . '/includes/autoload.php';

ZIPPY_Pay_Core::global_style();

Zippy_Woo_Template::get_instance();

ZIPPY_Adyen_Pay_Integration::get_instance();

ZIPPY_Paynow_Integration::get_instance();

ZIPPY_Antom_Integration::get_instance();

Zippy_Pay_Ajax_Handle::get_instance();
