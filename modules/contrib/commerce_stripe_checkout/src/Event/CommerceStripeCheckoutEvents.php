<?php

namespace Drupal\commerce_stripe_checkout\Event;

/**
 * Defines events for the Commerce CommerceStripeCheckout module.
 */
final class CommerceStripeCheckoutEvents {

  /*
   * Name of the event fired when Checkout authorizes a transaction.
   *
   * @Event
   *
   */
  const PAYMENT_SUCCESS = 'commerce_stripe_checkout.paymentSuccess';
  /**
   * Name of the event fired when CommerceStripeCheckout voids a transaction.
   *
   * @Event
   *
   * @see \Drupal\commerce_stripe_checkout\Event\CommerceStripeCheckoutPaymentEvent
   */
  const PAYMENT_FAILURE = 'commerce_stripe_checkout.payment_failure';

}
