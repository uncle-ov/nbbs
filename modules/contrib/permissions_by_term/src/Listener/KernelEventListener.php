<?php

namespace Drupal\permissions_by_term\Listener;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\permissions_by_term\Event\PermissionsByTermDeniedEvent;
use Drupal\permissions_by_term\Service\AccessCheck;
use Drupal\permissions_by_term\Service\AccessStorage;
use Drupal\permissions_by_term\Service\TermHandler;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * KernelEventListener to handle access checking.
 *
 * @package Drupal\permissions_by_term
 */
class KernelEventListener implements EventSubscriberInterface {

  /**
   * The access check service.
   *
   * @var \Drupal\permissions_by_term\Service\AccessCheck
   */
  private $accessCheckService;

  /**
   * The term handler.
   *
   * @var \Drupal\permissions_by_term\Service\TermHandler
   */
  private $term;

  /**
   * The event dispatcher.
   *
   * @var \Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher
   */
  private $eventDispatcher;

  /**
   * The access storage.
   *
   * @var \Drupal\permissions_by_term\Service\AccessStorage
   */
  private $accessStorageService;

  /**
   * The cache kill switch.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  private $pageCacheKillSwitch;

  /**
   * The language manager interface.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  private LanguageManagerInterface $languageManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private AccountProxyInterface $currentUser;

  /**
   * The path validator.
   *
   * @var \Drupal\Core\Path\PathValidatorInterface
   */
  private PathValidatorInterface $pathValidator;

  /**
   * The current path stack.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  private CurrentPathStack $currentPath;

  /**
   * Whether or not node access records are to be used.
   *
   * @var bool
   */
  private $disabledNodeAccessRecords;

  /**
   * Constructs a new KernelEventListener.
   */
  public function __construct(
    AccessCheck $accessCheck,
    AccessStorage $accessStorage,
    TermHandler $termHandler,
    ContainerAwareEventDispatcher $eventDispatcher,
    KillSwitch $pageCacheKillSwitch,
    ConfigFactoryInterface $configFactory,
    LanguageManagerInterface $languageManager,
    AccountProxyInterface $currentUser,
    PathValidatorInterface $pathValidator,
    CurrentPathStack $currentPath
  ) {
    $this->accessCheckService = $accessCheck;
    $this->accessStorageService = $accessStorage;
    $this->term = $termHandler;
    $this->eventDispatcher = $eventDispatcher;
    $this->pageCacheKillSwitch = $pageCacheKillSwitch;
    $this->disabledNodeAccessRecords = $configFactory->get('permissions_by_term.settings')->get('disable_node_access_records');
    $this->languageManager = $languageManager;
    $this->currentUser = $currentUser;
    $this->pathValidator = $pathValidator;
    $this->currentPath = $currentPath;
  }

  /**
   * Access restriction on kernel request.
   */
  public function onKernelRequest(RequestEvent $event): void {
    if ($event->isMainRequest()) {

      $this->handleAccessToNodePages($event);

      $this->handleAccessToTermAutocompleteLists($event);

      $this->handleAccessToTaxonomyTermViewsPages();

    }
  }

  /**
   * Restricts access on kernel response.
   */
  public function onKernelResponse(ResponseEvent $event) {
    $this->restrictTermAccessAtAutoCompletion($event);
  }

  /**
   * Restricts access to terms on AJAX auto completion.
   */
  private function restrictTermAccessAtAutoCompletion(ResponseEvent $event) {
    if ($event->getRequest()->attributes->get('target_type') === 'taxonomy_term' &&
      $event->getRequest()->attributes->get('_route') === 'system.entity_autocomplete'
    ) {
      $json_suggested_terms = $event->getResponse()->getContent();
      $suggested_terms = json_decode($json_suggested_terms);
      $allowed_terms = [];
      foreach ($suggested_terms as $term) {
        $tid = $this->term->getTermIdByName($term->label);
        $termLangcode = $this->languageManager->getCurrentLanguage()->getId();
        if ($this->term->getTerm() instanceof Term) {
          $termLangcode = $this->term->getTerm()->language()->getId();
        }

        if ($this->accessCheckService->isAccessAllowedByDatabase($tid, $this->currentUser->id(), $termLangcode)) {
          $allowed_terms[] = [
            'value' => $term->value,
            'label' => $term->label,
          ];
        }
      }

      $json_response = new JsonResponse($allowed_terms);
      $event->setResponse($json_response);
    }
  }

  /**
   * The subscribed events.
   */
  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::REQUEST => 'onKernelRequest',
      KernelEvents::RESPONSE => 'onKernelResponse',
    ];
  }

  private function canRequestGetNode(Request $request): bool {
    if (method_exists($request->attributes, 'get') && !empty($request->attributes->get('node'))) {
      if (method_exists($request->attributes->get('node'), 'get')) {
        return TRUE;
      }
    }

    return FALSE;
  }

  private function handleAccessToTaxonomyTermViewsPages(): void {
    $url_object = $this->pathValidator->getUrlIfValid($this->currentPath->getPath());
    if ($url_object instanceof Url && $url_object->isRouted() && $url_object->getRouteName() === 'entity.taxonomy_term.canonical') {
      $route_parameters = $url_object->getrouteParameters();
      $termLangcode = $this->languageManager->getCurrentLanguage()->getId();
      if (!$this->accessCheckService->isAccessAllowedByDatabase($route_parameters['taxonomy_term'], $this->currentUser->id(), $termLangcode)) {
        $this->pageCacheKillSwitch->trigger();
        throw new AccessDeniedHttpException();
      }
    }
  }

  private function handleAccessToNodePages(RequestEvent $event): void {
    // Restricts access to nodes (views/edit).
    if ($this->canRequestGetNode($event->getRequest())) {
      $node = $event->getRequest()->attributes->get('node');
      if (!$this->accessCheckService->canUserAccessByNode($node, false, $this->accessStorageService->getLangCode($node->id()))) {
        $accessDeniedEvent = new PermissionsByTermDeniedEvent($node->id());
        $this->eventDispatcher->dispatch($accessDeniedEvent, PermissionsByTermDeniedEvent::NAME);

        if ($this->disabledNodeAccessRecords) {
          $this->pageCacheKillSwitch->trigger();
        }

        throw new AccessDeniedHttpException();
      }
    }
  }

  private function handleAccessToTermAutocompleteLists(RequestEvent $event): void {
    // Restrict access to taxonomy terms by autocomplete list.
    if ($event->getRequest()->attributes->get('target_type') === 'taxonomy_term' &&
      $event->getRequest()->attributes->get('_route') === 'system.entity_autocomplete') {
      $query_string = $event->getRequest()->get('q');
      $query_string = trim($query_string);

      $tid = $this->term->getTermIdByName($query_string);

      $term = $this->term->getTerm();
      $termLangcode = $this->languageManager->getCurrentLanguage()->getId();
      if ($term instanceof Term) {
        $termLangcode = $term->language()->getId();
      }

      if (!$this->accessCheckService->isAccessAllowedByDatabase($tid, $this->currentUser->id(), $termLangcode)) {
        throw new AccessDeniedHttpException();
      }
    }

  }

}
