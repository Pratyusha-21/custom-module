<?php

namespace Drupal\menu_api\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\menu_api\EventSubscriber
 */
class MovieBudgetEvent implements EventSubscriberInterface {

  protected $config;
  protected $route_match;

  /**
   *
   */
  public function __construct(ConfigFactoryInterface $config, RouteMatchInterface $route) {
    $this->config = $config;
    $this->route_match = $route;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('current_route_match'),
    );
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::VIEW][] = ['CheckBudget', 100];
    return $events;
  }

  /**
   *
   */
  public function checkBudget(ViewEvent $event) {
    $request = $event->getRequest();
    $node = $this->route_match->getParameter('node');
    if ($node instanceof NodeInterface) {
      $bundle = $node->getType();
      $config = $this->config->get('menu_api.settings');
      $budget = $config->get('movie_budget');
      $price = $node->get('field_movie_price')->value;
      if($bundle == 'movie') {

        if ($price > $budget) {
          $message = t('The movie is over budget');
        }
        elseif ($price < $budget) {
          $message = t('The movie is under budget');
        }
        else {
          $message = t('The movie is within budget');
        }

        $build['price'] = [
          '#markup' => $message,
        ];
      }
    }
  }

}
