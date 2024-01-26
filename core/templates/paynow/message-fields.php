<?php

/** @var $is_active */
?>

<div class="zippy-paynow-payment-mess">
  <?php if (isset($is_active) && $is_active) : ?>
    <p>Paynow is ready for payment.</p>
  <?php else : ?>
    <span class="zippy-has-error">We can not process the payment at the moment. Please, try again later.</span>
  <?php endif; ?>
</div>
