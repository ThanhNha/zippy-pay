<?php

namespace ZIPPY_Pay\Settings;

defined('ABSPATH') || exit;

?>

<table class="form-table">
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Client id', PREFIX . 'woocommerce-settings-tab'); ?>
      <?php echo wc_help_tip(__('This is your key on Zippy', 'zippy_payment_getway')); ?>
    </th>
    <td class="forminp forminp-text">
      <input id="<?php echo esc_attr($params['client_key']['id']); ?>" name="<?php echo esc_attr($params['client_key']['id']); ?>" type="text" value="<?php echo esc_attr($params['client_key']['value']) ?>">
    </td>
  </tr>
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Secret key', PREFIX . 'woocommerce-settings-tab'); ?>
      <?php echo wc_help_tip(__('This is secret key on Zippy.', 'zippy_payment_getway')); ?>
    </th>
    <td class="forminp forminp-text">
      <input id="<?php echo esc_attr($params['secret_key']['id']); ?>" name="<?php echo esc_attr($params['secret_key']['id']); ?>" type="password" value="<?php echo esc_attr($params['secret_key']['value']) ?>">
    </td>
  </tr>
  <tr>
    <td class="forminp forminp-text">
      <input hidden id="<?php echo esc_attr($params['token']['id']); ?>" name="<?php echo esc_attr($params['token']['id']); ?>" type="password" value="<?php echo esc_attr($params['token']['value']) ?>">
    </td>
  </tr>
</table>
<div class="pt-15">
  <button id="zippy_authorization_button" type="button" class="button button-primary">Click to authorize</button>
  <p>Status: <span style="color: #cc0000;">Unauthorized</span></p>
</div>
