<?php

namespace Drupal\commerce_stripe_checkout\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Entity\PaymentInterface;

/**
 * Provides the interface for the Checkout payment gateway.
 */
interface CommerceStripeCheckoutCheckoutInterface {

  /**
   * SetCommerceStripeCheckoutCheckout request.
   *
   * Builds the data for the request.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentInterface $payment
   *   The payment.
   *
   * @return array
   *   CommerceStripeCheckout data.
   */
  public function setCommerceStripeCheckoutCheckoutData(PaymentInterface $payment);

}
