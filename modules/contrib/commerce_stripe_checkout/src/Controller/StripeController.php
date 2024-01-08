<?php

namespace Drupal\commerce_stripe_checkout\Controller;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Controller class for handling Stripe payments via Stripe Checkout.
 *
 * @package Drupal\commerce_stripe_checkout\Controller
 */
class StripeController extends ControllerBase {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new StripeController object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The current request stack service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler service.
   */
  public function __construct(MessengerInterface $messenger, RequestStack $requestStack, ModuleHandlerInterface $moduleHandler) {
    $this->messenger = $messenger;
    $this->requestStack = $requestStack;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('request_stack'),
      $container->get('module_handler')
    );
  }

  /**
   * Handles the callback URL for Stripe Checkout payment success.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to the checkout form or the homepage.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown when the transaction session is invalid.
   */
  public function paymentSuccess() {
    $request = $this->requestStack->getCurrentRequest();
    $stsess = $request->query->get('stsess');

    $getStripeStsess = $this->getStripeStsess();

    if ($stsess != $getStripeStsess) {
      return $this->redirectHomepage();
    }

    $getStripePayment = $this->getStripePayment();
    $getStripeOrder = $this->getStripeOrder();

    if ($getStripeOrder instanceof OrderInterface && $getStripePayment instanceof PaymentInterface) {

      // Assuming commerce_stripe_checkout_change_payment_status_completed
      // function exists.
      commerce_stripe_checkout_change_payment_status_completed($getStripePayment, $getStripeOrder);

      $message = '';
      // Invoke the custom hook for success message.
      $this->moduleHandler->invokeAll('commerce_stripe_checkout_success_message', [&$message]);
      if ($message == '') {
        $message = $this->t('<strong>Success!</strong> Your payment has been successfully processed.');
      }

      $this->messenger->addMessage($message);

      $this->unsetStripeSession();

      $url = Url::fromRoute('commerce_checkout.form', ['commerce_order' => $getStripeOrder->id()]);

      $response = new RedirectResponse($url->toString());
      return $response;
    }
    else {
      return $this->redirectHomepage();
    }
  }

  /**
   * Handles the cancellation of a Stripe Checkout payment.
   */
  public function paymentCancel() {
    $request = $this->requestStack->getCurrentRequest();
    $stsess = $request->query->get('stsess');
    $getStripeStsess = $this->getStripeStsess();
    if ($stsess != $getStripeStsess) {
      return $this->redirectHomepage();
    }

    $getStripeOrder = $this->getStripeOrder();
    if ($getStripeOrder instanceof OrderInterface) {
      // Assuming commerce_stripe_checkout_change_payment_status_cancelled
      // function exists.
      commerce_stripe_checkout_change_payment_status_cancelled($getStripeOrder);
      $this->unsetStripeSession();

      $message = '';

      // Invoke the custom hook for failure message.
      $this->moduleHandler->invokeAll('commerce_stripe_checkout_failure_message', [&$message]);

      if ($message == '') {
        $message = $this->t('<strong>Payment Canceled!</strong> Your transaction has been canceled.');
      }

      $this->messenger->addMessage($message);
    }

    return $this->redirectHomepage();
  }

  /**
   * Get stripe payment.
   */
  public function getStripePayment() {
    $session = \Drupal::service('session');
    $stripe_payment = $session->get('stripe_payment') ?? 0;
    return $stripe_payment;
  }

  /**
   * Get stripe order.
   */
  public function getStripeOrder() {
    $session = \Drupal::service('session');
    $stripe_order = $session->get('stripe_order') ?? 0;
    return $stripe_order;
  }

  /**
   * Get stripe session key.
   */
  public function getStripeStsess() {
    $session = \Drupal::service('session');
    $stsess = $session->get('stsess');
    return $stsess;
  }

  /**
   * Unset stripe session.
   */
  public function unsetStripeSession() {
    $session = \Drupal::service('session');
    $session->set('stripe_payment', []);
    $session->set('stripe_order', []);
    $session->set('stsess', '');
  }

  /**
   * Redirect to homepage.
   */
  public function redirectHomepage() {
    $redirect_url = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
    $response = new RedirectResponse($redirect_url);
    return $response;
  }

}
