<?php

namespace ZIPPY_Pay\Settings;

defined('ABSPATH') || exit;

?>

<table class="form-table">
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Paynow Integration Type', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-paynow-details">OUB Integrated</span>
    </td>
  </tr>
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Merchant UEN', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-paynow-details">Merchant UEN</span>
    </td>
  </tr>
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('MCC', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-paynow-details">MCC</span>
    </td>
  </tr>
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Merchant Name', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-paynow-details">Merchant Name</span>
    </td>
  </tr>
  <tr>
    <th scope="row" class="titledesc">
      <?php _e('Merchant Contact', PREFIX . 'zippy-settings-field'); ?>
    </th>
    <td class="forminp forminp-text">
      <span class="epos-paynow-details"><strong>Merchant Contact</strong></span>
    </td>
  </tr>
</table>
<!-- <div class="pt-15">
  <button id="zippy_setting_button" type="submit" class="button button-primary">Save changes</button>
</div> -->
