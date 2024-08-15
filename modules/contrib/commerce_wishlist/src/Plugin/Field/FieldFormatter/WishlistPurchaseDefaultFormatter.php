<?php

namespace Drupal\commerce_wishlist\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'commerce_wishlist_purchase_default' formatter.
 *
 * @FieldFormatter(
 *   id = "commerce_wishlist_purchase_default",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "commerce_wishlist_purchase"
 *   }
 * )
 */
class WishlistPurchaseDefaultFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->dateFormatter = $container->get('date.formatter');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [
      '#type' => 'table',
      '#caption' => $this->t('Purchases'),
      '#header' => [
        $this->t('Order ID'),
        $this->t('Quantity'),
        $this->t('Purchased'),
      ],
      '#cache' => [
        'contexts' => [
          'languages:' . LanguageInterface::TYPE_INTERFACE,
        ],
      ],
    ];
    /** @var \Drupal\commerce_wishlist\Plugin\Field\FieldType\WishlistPurchaseItem $item */
    foreach ($items as $item) {
      $purchase = $item->toPurchase();
      $elements['#rows'][] = [
        $purchase->getOrderId(),
        $purchase->getQuantity(),
        $this->dateFormatter->format($purchase->getPurchasedTime()),
      ];
    }

    return $elements;
  }

}
