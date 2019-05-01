<?php if ($_SERVER['REQUEST_METHOD'] == 'POST'){ require_once('./update_card.php');} 

if (isset($error)) {
  echo $error;
} elseif (isset($success)) {
  echo $success;
}
?>
<form action="" method="POST">
E-mail: <input type="text" name="email">
  <script
  src="https://checkout.stripe.com/checkout.js" class="stripe-button"
  data-key="pk_test_psEcqt6lvkbyMKJW8DtbXKTw"
  data-image="https://d3uwvxbaltcpss.cloudfront.net/wp-content/uploads/2018/09/Algorank-Logo-NewFont-1.png"
  data-name="Algorank | Simple Site Co"
  data-panel-label="Update Card Details"
  data-label="Update Card Details"
  data-allow-remember-me=false
  data-locale="auto">
  </script>
</form>