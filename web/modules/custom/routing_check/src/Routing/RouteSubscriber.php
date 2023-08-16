<?php

namespace Drupal\routing_check\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 *
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $route = $collection->get('routing_check.settings');

    if ($route) {
      $route->setPath('/check');
      $route->setRequirement('_role', 'administrator');
    }
  }

}
