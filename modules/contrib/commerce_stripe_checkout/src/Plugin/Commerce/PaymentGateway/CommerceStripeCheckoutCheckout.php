<?php

namespace Drupal\commerce_stripe_checkout\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\PaymentMethodTypeManager;
use Drupal\commerce_payment\PaymentTypeManager;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\commerce_price\Calculator;
use Drupal\commerce_stripe_checkout\Event\CommerceStripeCheckoutEvents;
use Drupal\commerce_stripe_checkout\Event\CommerceStripeCheckoutPaymentEvent;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides the Stripe Checkout payment gateway plugin.
 *
 * @CommercePaymentGateway(
 *   id = "commerce_stripe_checkout_checkout",
 *   label = @Translation("Stripe Checkout"),
 *   display_label = @Translation("Stripe Checkout"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_stripe_checkout\PluginForm\CommerceStripeCheckoutCheckoutForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   credit_card_types = {
 *     "mastercard", "visa", "maestro",
 *   },
 * )
 */
class CommerceStripeCheckoutCheckout extends OffsitePaymentGatewayBase implements CommerceStripeCheckoutCheckoutInterface {

  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */

  protected $eventDispatcher;
  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */

  protected $state;

  /**
   * Constructs a new PaymentGatewayBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\commerce_payment\PaymentTypeManager $payment_type_manager
   *   The payment type manager.
   * @param \Drupal\commerce_payment\PaymentMethodTypeManager $payment_method_type_manager
   *   The payment method type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The event dispatcher service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, PaymentTypeManager $payment_type_manager, PaymentMethodTypeManager $payment_method_type_manager, TimeInterface $time, EventDispatcherInterface $eventDispatcher, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $payment_type_manager, $payment_method_type_manager, $time);
    $this->eventDispatcher = $eventDispatcher;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.commerce_payment_type'),
      $container->get('plugin.manager.commerce_payment_method_type'),
      $container->get('datetime.time'),
      $container->get('event_dispatcher'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'redirect_method' => 'post',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['live_secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live Test Secret key'),
      '#description' => $this->t('The merchant id from the stirpe provider.'),
      '#default_value' => $this->state->get('stripe_checkout.live_secret_key'),
    ];

    $form['secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Test Secret key'),
      '#description' => $this->t('The secret key id from the stirpe provider.'),
      '#default_value' => $this->state->get('stripe_checkout.secret_key'),
    ];

    $form['pass_customer_email'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Pass customer email to stripe'),
      '#description' => $this->t('Pass customer email to stripe during checkout.'),
      '#default_value' => $this->state->get('stripe_checkout.pass_customer_email'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $this->state->set('stripe_checkout.live_secret_key', $values['live_secret_key']);
      $this->state->set('stripe_checkout.secret_key', $values['secret_key']);
      $this->state->set('stripe_checkout.pass_customer_email', $values['pass_customer_email']);
      $this->configuration['live_secret_key'] = $values['live_secret_key'];
      $this->configuration['secret_key'] = $values['secret_key'];
      $this->configuration['pass_customer_email'] = $values['pass_customer_email'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onReturn(OrderInterface $order, Request $request) {
    $data = $this->getRequestData($request);

    $configuration = $this->getConfiguration();
    $data['fp_hash'] = strtoupper($this->hashData($data, $configuration['secret_key']));
    $fp_hash = addslashes(trim($request->request->get('fp_hash')));

    if ($data['fp_hash'] !== $fp_hash) {
      throw new PaymentGatewayException('Invalid signature');
    }

    $this->createPaymentStorage($order, $request);

    if ($request->request->get('action') == "0") {
      $order->setData('state', 'completed');

      $event = new CommerceStripeCheckoutPaymentEvent($order);
      $this->eventDispatcher->dispatch(CommerceStripeCheckoutEvents::PAYMENT_SUCCESS, $event);

      $this->messenger->addMessage($this->t('The payment was made successfully.'));
    }
    else {
      $event = new CommerceStripeCheckoutPaymentEvent($order);
      $this->eventDispatcher->dispatch(CommerceStripeCheckoutEvents::PAYMENT_FAILURE, $event);

      $this->messenger->addWarning($this->t('Transaction failed: @message'), [
        '@message' => $request->request->get('message'),
      ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setCommerceStripeCheckoutCheckoutData(PaymentInterface $payment) {
    $order = $payment->getOrder();

    $amount = $payment->getAmount();
    $configuration = $this->getConfiguration();

    // Order description.
    $order_desc = 'Order #' . $order->id() . ': ';

    foreach ($order->getItems() as $item) {
      $product_sku = $item->getPurchasedEntity()->getSku();
      $order_desc .= $item->getTitle() . ' [' . $product_sku . ']';
      $order_desc .= ', ';
    }

    // Remove the last comma.
    $order_desc = rtrim($order_desc, ', ');

    // Curent timestamp.
    $timestamp = gmdate('YmdHis');
    $nonce = md5(microtime() . mt_rand());

    // Build a name-value pair array for this transaction.
    // The data which should be signed to be transported to
    // CommerceStripeCheckout.ro.
    $data = [
      'amount' => Calculator::round($amount->getNumber(), 2),
      'currency' => $amount->getCurrencyCode(),
      'invoice_id' => $order->id(),
      'order_desc' => $order_desc,
      'merch_id' => $configuration['live_secret_key'] ?? '',
      'timestamp' => $timestamp,
      'nonce' => $nonce,
    ];

    $address = $order->getBillingProfile()->get('address')->first();

    // The hidden data wich should be transported to CommerceStripeCheckout.ro.
    $nvp_data = [
      'fname' => $address->getGivenName(),
      'lname' => $address->getFamilyName(),
      'country' => $address->getCountryCode(),
      'city' => $address->getLocality(),
      'email' => $order->getEmail(),
      'amount' => Calculator::round($amount->getNumber(), 2),
      'currency' => $amount->getCurrencyCode(),
      'invoice_id' => $order->id(),
      'order_desc' => $order_desc,
      'merch_id' => $configuration['live_secret_key'] ?? '',
      'timestamp' => $timestamp,
      'nonce' => $nonce,
      'order' => $order,
      'fp_hash' => strtoupper($this->hashData($data, $configuration['secret_key'] ?? '')),
    ];

    return $nvp_data;
  }

  /**
   * Get data from Request object.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return array
   *   The built request array.
   */
  public function getRequestData(Request $request) {
    return [
      'amount' => addslashes(trim($request->request->get('amount'))),
      'curr' => addslashes(trim($request->request->get('curr'))),
      'invoice_id' => addslashes(trim($request->request->get('invoice_id'))),
       // A unique id provided by CommerceStripeCheckout.ro.
      'ep_id' => addslashes(trim($request->request->get('ep_id'))),
      'merch_id' => addslashes(trim($request->request->get('merch_id'))),
       // For the transaction to be ok, the action should be 0.
      'action' => addslashes(trim($request->request->get('action'))),
       // The transaction response message.
      'message' => addslashes(trim($request->request->get('message'))),
       // If the transaction action is different 0, the approval value is empty.
      'approval' => addslashes(trim($request->request->get('approval'))),
      'timestamp' => addslashes(trim($request->request->get('timestamp'))),
      'nonce' => addslashes(trim($request->request->get('nonce'))),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function onNotify(Request $request) {
    $data = $request->request->all();

    $configuration = $this->getConfiguration();
    if (isset($data['fp_hash']) && isset($data['lang'])) {
      unset($data['fp_hash']);
      unset($data['lang']);
    }
    $data['fp_hash'] = strtoupper($this->hashData($data, $configuration['secret_key']));
    $fp_hash = $request->request->get('fp_hash');

    if ($data['fp_hash'] !== $fp_hash) {
      throw new PaymentGatewayException('Invalid signature');
    }

    $order = Order::load($data['invoice_id']);

    if ($request->request->get('action') == "0") {
      $order->set('state', 'completed');
      $this->messenger->addMessage($this->t('The payment was made successfully.'));
      $url = Url::fromUri('internal:/checkout/' . $order->id() . '/complete');
    }
    else {
      $this->messenger->addWarning($this->t('Transaction failed: @message.', [
        '@message' => $request->request->get('message'),
      ]));
      $url = Url::fromUri('internal:/checkout/' . $order->id() . '/order_information');
    }

    $order->save();

    return new RedirectResponse($url->toString());
  }

  /**
   * Creates a PaymentStorage object.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $order
   *   The commerce_order object.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The Request object.
   * @param string|null $payment_state
   *   The payment state.
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   *   The PaymentStorage object.
   */
  public function createPaymentStorage(OrderInterface $order, Request $request, ?string $payment_state) {
    $paymentStorage = $this->entityTypeManager->getStorage('commerce_payment');
    $requestTime = $this->time->getRequestTime();

    $paymentStorage->create([
      'amount' => $order->getTotalPrice(),
      'payment_gateway' => $this->entityId,
      'order_id' => $order->id(),
      'test' => $this->getMode() == 'test',
      'remote_id' => $request->request->get('ep_id'),
      'remote_state' => $request->request->get('message'),
      'authorized' => $requestTime,
    ]);

    if ($request->request->get('action') == '0') {
      $paymentStorage->state = isset($payment_state) ? 'completed' : 'authorization';
      $this->messenger->addMessage($this->t('The payment was made successfully.'));
    }
    else {
      $paymentStorage->state = 'authorization_voided';
      $this->messenger->addWarning($this->t('Transaction failed: @message'), [
        '@message' => $request->request->get('message'),
      ]);
    }

    $paymentStorage->save();
    return $paymentStorage;
  }

  /**
   * Custom function from CommerceStripeCheckout documentation.
   *
   * For more details, please read the documentation from the module.
   *
   * @param array $data
   *   Data that is passed through SHA1 function.
   * @param string $key
   *   Test Secret key.
   *
   * @return string
   *   Hash code that is sent to CommerceStripeCheckout.
   */
  public static function hashData($data, $key) {
    $str = NULL;

    foreach ($data as $d) {
      if ($d === NULL || strlen($d) == 0) {
        // The NULL values will be replaced with - .
        $str .= '-';
      }
      else {
        $str .= strlen($d) . $d;
      }
    }
    // We convert the secret code into a binary string.
    // $key = pack('H*', $key);.
    return self::hashSha1($str, $key);
  }

  /**
   * Custom function from CommerceStripeCheckout documentation.
   *
   * For more details, please read the documentation from the module.
   *
   * @param string $data
   *   Data regarding the order.
   * @param string $key
   *   Test Secret key.
   *
   * @return string
   *   The digest of the function.
   */
  private static function hashSha1($data, $key) {
    $blocksize = 64;
    $hashfunc = 'md5';

    if (strlen($key) > $blocksize) {
      $key = pack('H*', $hashfunc($key));
    }

    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);

    $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
    return bin2hex($hmac);
  }

}
