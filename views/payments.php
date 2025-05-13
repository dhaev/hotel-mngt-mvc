<?php

// Retrieve client_secret and pk_key from the query parameters
$clientSecret = $_GET['client_secret'] ?? null;
$pkKey = $_GET['pk_key'] ?? '';

// Validate the presence of client_secret and pk_key
if (!$clientSecret || !$pkKey) {
    echo '<div class="alert alert-danger">Missing payment details. Please try again.</div>';
    exit();
}
?>
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  const clientSecret = '<?= $clientSecret; ?>';
  const pkKey = '<?= $pkKey; ?>';

  if (!clientSecret || !pkKey) {
    console.error('Missing client secret or public key.');
    return;
  }

  console.log('Client Secret:', clientSecret);

  const stripe = Stripe(pkKey);
  const options = {
    clientSecret: clientSecret,
    appearance: {
      theme: 'night',
      labels: 'floating',
    }
  };

  // Set up Stripe.js and Elements to use in end_date form, passing the client secret obtained
  const elements = stripe.elements(options);

  // Create and mount the Payment Element
  const paymentElementOptions = { layout: 'tabs' };
  const paymentElement = elements.create('payment', paymentElementOptions);
  paymentElement.mount('#payment-element');

  const form = document.getElementById('payment-form');

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const { error } = await stripe.confirmPayment({
      // `Elements` instance that was used to create the Payment Element
      elements,
      confirmParams: {
        return_url: '<?= BASE_URL?>index.php?controller=Booking&action=index',
      },
    });

    if (error) {
      // Show error to your customer (for example, payment details incomplete)
      const messageContainer = document.querySelector('#error-message');
      messageContainer.textContent = error.message;
    }
  });
});
</script>
<div class="container mt-5">
   <form id="payment-form">
      <h3 class="text-center">Payment</h3>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4">
            <label for="payment-element"></label>
            <div id="payment-element">
              <!-- A Stripe Element will be inserted here. -->
            </div>
            <!-- Used to display form errors. -->
            <div id="error-message" role="alert"></div>
         </div>
      </div>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-center">
            <button class="btn btn-primary" type="submit" id="submit">Submit</button>
         </div>
      </div>
   </form>
</div>