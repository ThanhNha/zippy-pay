<?php

/** @var $order_id */
?>

<?php if (isset($order_id) && !empty($order_id) && empty($_REQUEST['antom_process'])): ?>
  <div id="zippy_antom_loader">
    <div class="spinner"></div>

  </div>
  <div id="zippy_antom"></div>
  <div>
    <input id="antom_order_id" hidden type="text" value="<?php echo $order_id; ?>">
  </div>
<?php endif; ?>
