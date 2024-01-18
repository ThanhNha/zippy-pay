<?php

namespace ZIPPY_Pay\Settings;

defined('ABSPATH') || exit;

?>

<table class="form-table">
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Payment Methods', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-card-brands">
        <span class="epos-card-brands_brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/visa.svg" alt="" class="epos-brands-image">
        </span>
        <span class="epos-card-brands_brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/mc.svg" alt="" class="epos-brands-image">
        </span>
        <!-- <span class="epos-card-brands_brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/diners.svg" alt="" class="epos-brands-image">
        </span> -->
        <span class="epos-card-brands_brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/discover.svg" alt="" class="epos-brands-image">
        </span>
        <span class="epos-card-brands_brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/jcb.svg" alt="" class="epos-brands-image">
        </span>
      </span>
    </td>
  </tr>
</table>
<!-- <div class="pt-15">
  <button id="zippy_authorization_button" type="submit" class="button button-primary">Save changes</button>
</div> -->
