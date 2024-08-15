<?php

namespace Drupal\commerce_wishlist\Mail;

use Drupal\commerce\MailHandlerInterface;
use Drupal\commerce_wishlist\Entity\WishlistInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines the wishlist share email.
 */
class WishlistShareMail implements WishlistShareMailInterface {

  use StringTranslationTrait;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The commerce mail handler.
   *
   * @var \Drupal\commerce\MailHandlerInterface
   */
  protected $mailHandler;

  /**
   * Constructs a new WishlistShareMail object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\commerce\MailHandlerInterface $mail_handler
   *   The mail handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MailHandlerInterface $mail_handler) {
    $this->configFactory = $config_factory;
    $this->mailHandler = $mail_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function send(WishlistInterface $wishlist, $to, $anonymous_sender) {
    $owner = $wishlist->getOwner();
    if ($owner->isAnonymous()) {
      // Use the site email address as the from address.
      $from = $this->configFactory->get('system.site')->get('mail');
      $duplicate = $this->configFactory->get('commerce_wishlist.settings')->get('duplicate');
      if ($duplicate) {
        // Duplicate the wishlist with wishlist items.
        $duplicate_wishlist = $wishlist->createDuplicateWishlist($wishlist);
      }
    }
    else {
      $from = $owner->getEmail();
    }

    $subject = $this->t('Check out @name @site-name wishlist', [
      '@name' => $anonymous_sender ? $anonymous_sender . '\'s' : $this->t('my'),
      '@site-name' => $this->configFactory->get('system.site')->get('name'),
    ]);
    $body = [
      '#theme' => 'commerce_wishlist_share_mail',
      '#wishlist_entity' => $duplicate_wishlist ?? $wishlist,
    ];
    $params = [
      'id' => 'wishlist_share',
      'from' => $from,
      'wishlist' => $duplicate_wishlist ?? $wishlist,
    ];

    return $this->mailHandler->sendMail($to, $subject, $body, $params);
  }

}
