<?php
/**
 * @file
 * Contains Drupal\services_list\Controller\ServicesListController.
 */
namespace Drupal\services_list\Controller;

use Doctrine\Common\Annotations\AnnotationReader;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Link;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class ServicesListController
 * @package Drupal\services_list\Controller
 */
class ServicesListController extends ControllerBase
{

  /**
   * Render service list page content.
   *
   * @return array
   */
  public function content() {

    /**
     * @var \Drupal\Core\DependencyInjection\Container $container.
     */
    $container = \Drupal::getContainer();

    $service_ids = $container->getServiceIds();
    $links = [];
    foreach ($service_ids as $service_id) {

      // Get service info.
      $serviceInfo = \Drupal::service($service_id);
      $comment = '';
      if (is_object($serviceInfo)) {
        $class = get_class($serviceInfo);
        $reflectionClass = new \ReflectionClass($class);
        $comment = $reflectionClass->getDocComment();
      }

      // Set link attributes.
      $attributes = new Attribute(['data-service-name' => $service_id, 'title' => $comment]);
      $attributes['class'] = ['use-ajax'];
      $link = [
        '#theme' => 'services_link',
        '#text' => $service_id,
        '#url' => Url::fromRoute('services_list.service_info', ['service_id' => $service_id]),
        '#attributes' => $attributes,
      ];

      $links[] = $link;
    }

    return [
      '#theme' => 'services_list',
      '#links' => $links,
      '#attached' => [
        'library' => ['core/drupal.dialog.ajax', 'services_list/services_list.services_filter'],
      ]
    ];

  }

  /**
   * Render service debugging info in popup.
   *
   * @param $serviceId
   * @return AjaxResponse
   */
  public function ajaxCallback($service_id) {
    $response = new AjaxResponse();

    $service_info = \Drupal::service($service_id);

    // Get class comment.
    $comment = '';
    if (is_object($service_info)) {
      $class = get_class($service_info);
      $reflectionClass = new \ReflectionClass($class);
      $comment = $reflectionClass->getDocComment();
    }

    // Create info block content.
    $content = '<pre>' . $comment . '</pre>';

    // Check for function from devel module.
    if (function_exists('kdevel_print_object')) {

      // Kint styled output.
      $content .= kdevel_print_object($service_info);
    }
    else {

      // Plain output.
      $content .= print_r($service_info, TRUE);
    }
    $response->addCommand( new OpenModalDialogCommand($this->t('Information about service: @service', ['@service' => $service_id]),
      $content,
      [
        'width' => '80%',
        'position' => [
          'at' => 'top - 20%',
        ],
        'draggable' => TRUE,
        'dialogClass' => 'popup-dialog-class',
      ])
    );

    return $response;
  }

}
