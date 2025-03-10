<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/wp-content/plugins/zippy-pay/includes/assets/dist/css/web.min.css?ver=<?php echo time();?>">
  <title>Document</title>
</head>

<body>
  <div id="zippy_antom_loader">
    <div class="spinner"></div>
  </div>
  <div id="antom_error">
    <div class="message_wrapper">
      <span class="message">We cannot process the payment at the moment.</span>

      <span class="message"> Please try again later.</span>
    </div>

    <button class="back_to_checkout">
      <a href="/checkout">Back to checkout</a>
    </button>
  </div>
  <div id="zippy_antom"></div>
  <div>
    <input id="antom_order_id" hidden type="text" value="<?php echo $order_id; ?>">
  </div>

  <div id="zippy_antom_notification">
    <div class="notification_wrapper">
      <div class="notification_text">
        <span class="">Do not exit this screen till the payment is confirmed</span>
      </div>
      <div class="notification_text">
        <span class="return"><a href="/checkout">Return</a></span>
        <span class="" style="line-height: 1;">&nbsp;to change payment method</span>
      </div>
    </div>
  </div>
<script src="/wp-content/plugins/zippy-pay/includes/assets/dist/js/web.min.js?ver=<?php echo time();?>"></script>
</body>

</html>
