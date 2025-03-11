<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/wp-content/plugins/zippy-pay/includes/assets/dist/css/web.min.css?ver=<?php echo time(); ?>">
  <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">

  <title>Pending</title>
</head>

<body>
  <header id="antom_header_payment_page">
    <span>Epos Pay</span>
  </header>
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
  <?php
  if (function_exists('WC')) {
    $customer_orders = wc_get_orders([
      'limit'   => 1,
      'orderby' => 'date',
      'order'   => 'DESC',
      'customer_id' => get_current_user_id()
    ]);

    if (!empty($customer_orders)) {
      $order_id = $customer_orders[0]->get_id();
    }
  }
  ?>

  <div>
    <input id="antom_order_id_pending" hidden type="text" value="1710">
  </div>
  <script src="/wp-content/plugins/zippy-pay/includes/assets/dist/js/web.min.js?ver=<?php echo time(); ?>"></script>
</body>

</html>
