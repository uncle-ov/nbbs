<?php

namespace Drupal\commerce_stripe_checkout\PluginForm;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Component\Utility\Random;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Defines the CommerceStripeCheckoutCheckoutForm class.
 *
 * Extends the BasePaymentOffsiteForm class for handling checkout forms.
 */
class CommerceStripeCheckoutCheckoutForm extends BasePaymentOffsiteForm {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;

    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
    $configuration = $payment_gateway_plugin->getConfiguration();
    $mode = $configuration['mode'];
    $pass_customer_email = $configuration['pass_customer_email'] ?? FALSE;

    if ($mode == 'live') {
      $secret_key = $configuration['live_secret_key'] ?? '';
    }
    else {
      $secret_key = $configuration['secret_key'] ?? '';
    }

    $order = $payment->getOrder();

    $random = new Random();
    $length = 30;

    $stsess = $random->string($length);

    /** @var \Drupal\commerce_stripe_checkout\Plugin\Commerce\PaymentGateway $payment_gateway_plugin */
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();

    $session = \Drupal::service('session');
    $session->set('stripe_payment', $payment);
    $session->set('stripe_order', $order);
    $session->set('stsess', $stsess);

    $commerce_stripe_checkout_data = $payment_gateway_plugin->setCommerceStripeCheckoutCheckoutData($payment);

    $order = $commerce_stripe_checkout_data['order'];
    $currency = $commerce_stripe_checkout_data['currency'];

    $line_items_data = [];

    if ($order instanceof OrderInterface) {
      // Get the order's line items.
      $line_items = $order->getItems();

      // Loop through the line items.
      foreach ($line_items as $line_item) {
        $line_item_data = [];
        // Perform operations with each line item.
        // For example, you can access the title and quantity.
        $title = $line_item->getTitle();
        $quantity = $line_item->getQuantity();
        $quantity = round($quantity);
        $unit_price = $line_item->getUnitPrice();

        $unit_price_number = $unit_price->getNumber();

        $unit_price_value = $unit_price_number * 100;
        // Amount in cents.
        $line_item_data = [
          'price_data' => [
            'currency' => $currency,
            'unit_amount' => $unit_price_value,
            'product_data' => [
              'name' => $title,
            ],
          ],
          'quantity' => $quantity,
        ];

        $line_items_data[] = $line_item_data;
      }
    }

    try {
      if (class_exists('\Stripe\Stripe')) {
        // Set your Stripe API keys.
        Stripe::setApiKey($secret_key);

        $success_redirect_url = Url::fromRoute('commerce_stripe_checkout.paymentSuccess', ['stsess' => $stsess], ['absolute' => TRUE])->toString();
        $cancel_redirect_url = Url::fromRoute('commerce_stripe_checkout.paymentCancel', ['stsess' => $stsess], ['absolute' => TRUE])->toString();

        $user_data = [
          'payment_method_types' => ['card'],
          'line_items' => $line_items_data,
          'mode' => 'payment',
          'success_url' => $success_redirect_url,
          'cancel_url' => $cancel_redirect_url,
        ];

        if ($pass_customer_email && isset($order->mail->value) && $order->mail->value != '') {
          $user_data['customer_email'] = $order->mail->value;
        }

        // Create a new checkout session.
        $session = Session::create($user_data);

        // Get the checkout session ID.
        $redirect_url = $session->url;

        $commerce_stripe_checkout_data = $payment_gateway_plugin->setCommerceStripeCheckoutCheckoutData($payment);
        foreach ($commerce_stripe_checkout_data as $name => $value) {
          if (!empty($value)) {
            $data[$name] = $value;
          }
        }

        $response = new RedirectResponse($redirect_url);
        $response->send();
      }
      else {
        $message = $this->t('Something went wrong. Please contact the site administrator.');
        \Drupal::messenger()->addError($message);

        $message = $this->t('Stripe is missing on the system. Please install the dependency with following command "composer require stripe/stripe-php ^10.0"');
        commerce_stripe_checkout_log_messages('alert', $message);
      }
    }
    catch (\Exception $e) {
      $message = $e->getMessage();
      \Drupal::messenger()->addError($message);
      commerce_stripe_checkout_log_messages('alert', $message);

    }
  }

}
