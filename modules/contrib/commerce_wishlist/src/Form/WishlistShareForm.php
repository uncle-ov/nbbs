<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxFormHelperTrait;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseDialogCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the wishlist share form.
 */
class WishlistShareForm extends EntityForm {

  use AjaxFormHelperTrait;

  /**
   * The wishlist share mail.
   *
   * @var \Drupal\commerce_wishlist\Mail\WishlistShareMailInterface
   */
  protected $wishlistShareMail;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->wishlistShareMail = $container->get('commerce_wishlist.wishlist_share_mail');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
    $wishlist = $this->entity;
    $form['#tree'] = TRUE;
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    // Workaround for core bug #2897377.
    $form['#id'] = Html::getId($form_state->getBuildInfo()['form_id']);

    $owner = $wishlist->getOwner();
    if ($owner->isAnonymous()) {
      $form['from'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Your name'),
        '#required' => TRUE,
      ];
    }
    $form['to'] = [
      '#type' => 'email',
      '#title' => $this->t('Recipient'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send email'),
      '#submit' => ['::submitForm'],
    ];
    if ($this->isAjax()) {
      $actions['submit']['#ajax']['callback'] = '::ajaxSubmit';
    }

    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
    $wishlist = $this->entity;
    $to = $form_state->getValue('to');
    $owner = $wishlist->getOwner();
    if ($owner->isAnonymous()) {
      $anonymous_sender = $form_state->getValue('from');
    }
    $this->wishlistShareMail->send($wishlist, $to, $anonymous_sender ?? '');

    $this->messenger()->addStatus($this->t('Shared the wishlist to @recipient.', [
      '@recipient' => $to,
    ]));
    $form_state->setRedirectUrl($wishlist->toUrl('user-form'));
  }

  /**
   * {@inheritdoc}
   */
  protected function successfulAjaxSubmit(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new PrependCommand('.commerce-wishlist-form', ['#type' => 'status_messages']));
    $response->addCommand(new CloseDialogCommand());

    return $response;
  }

}
