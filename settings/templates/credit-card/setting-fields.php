<?php

namespace ZIPPY_Pay\Settings;

defined('ABSPATH') || exit;

?>

<table class="form-table">
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Payment Methods', PREFIX . 'zippy-settings-field'); ?>
      <?php echo wc_help_tip(__('This is your key on Zippy', 'zippy_payment_getway')); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="adyen-checkout__card__brands">
        <span class="adyen-checkout__card__brands__brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/visa.svg" alt="" class="adyen-checkout__image adyen-checkout__image--loaded">
        </span>
        <span class="adyen-checkout__card__brands__brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/mc.svg" alt="" class="adyen-checkout__image adyen-checkout__image--loaded">
        </span>
        <!-- <span class="adyen-checkout__card__brands__brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/diners.svg" alt="" class="adyen-checkout__image adyen-checkout__image--loaded">
        </span> -->
        <span class="adyen-checkout__card__brands__brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/discover.svg" alt="" class="adyen-checkout__image adyen-checkout__image--loaded">
        </span>
        <span class="adyen-checkout__card__brands__brand-wrapper">
          <img src="https://checkoutshopper-live.adyen.com/checkoutshopper/images/logos/jcb.svg" alt="" class="adyen-checkout__image adyen-checkout__image--loaded">
        </span>
      </span>
    </td>
  </tr>
</table>
<div class="pt-15">
  <button id="zippy_authorization_button" type="button" class="button button-primary">Save changes</button>
</div>
