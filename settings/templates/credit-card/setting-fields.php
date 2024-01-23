<?php

namespace ZIPPY_Pay\Settings;

defined('ABSPATH') || exit;
// var_dump($test);

?>

<table class="form-table" id="zippy_setting_wrapper" style="display: none;">

  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Payment Methods', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-card-brands">
        <span class="epos-card-brands_brand-wrapper">
          <img src="<?php echo ZIPPY_PAY_DIR_URL . 'includes/assets/icons/visa.svg' ?>" alt="" class="epos-brands-image">
        </span>
        <span class="epos-card-brands_brand-wrapper">
          <img src="<?php echo ZIPPY_PAY_DIR_URL . 'includes/assets/icons/mc.svg' ?>" alt="" class="epos-brands-image">
        </span>
        <span class="epos-card-brands_brand-wrapper">
          <img src="<?php echo ZIPPY_PAY_DIR_URL . 'includes/assets/icons/amex.svg' ?>" alt="" class="epos-brands-image">
        </span>
        <span class="epos-card-brands_brand-wrapper">
          <img src="<?php echo ZIPPY_PAY_DIR_URL . 'includes/assets/icons/jcb.svg' ?>" alt="" class="epos-brands-image">
        </span>
        <span class="epos-card-brands_brand-wrapper">
          <img src="<?php echo ZIPPY_PAY_DIR_URL . 'includes/assets/icons/discover.svg' ?>" alt="" class="epos-brands-image">
        </span>
      </span>
    </td>
  </tr>
  <tr>
    <th>
      <button id="zippy-sync-config" type="button" class="button button-secondary">
        <div class="update" aria-hidden="true">
          <p class="sync-btn dashicons-before dashicons-update"> Sync Configs</p>
        </div>
      </button>
    </th>
  </tr>
</table>
<div class="pt-15">

</div>
