// This is your test secret API key.
const stripe = Stripe(
  "pk_test_51IVzJTL8mOGPmzyG4HdFy3evJqfryRFQIME4ku2W6DrPvoE7NUJGgRNKMB1Wu8DB6TQkO7w9CU2GACHFwMLT8i0E00GGC7I0W1"
);

initialize();

// Create a Checkout Session
async function initialize() {
  const fetchClientSecret = async () => {
    const response = await fetch("/checkout.php", {
      method: "POST",
    });
    const { clientSecret } = await response.json();
    return clientSecret;
  };

  const checkout = await stripe.initEmbeddedCheckout({
    fetchClientSecret,
  });

  // Mount Checkout
  checkout.mount("#checkout");
}
