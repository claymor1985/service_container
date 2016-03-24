<?php
namespace Drupal\services_list\Controller;


use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Ajax\OpenModalDialogCommand;

class ServicesListController extends \Drupal\Core\Controller\ControllerBase
{

  public function content() {
    $ids = \Drupal::getContainer()->getServiceIds();
    $links = [];
    foreach ($ids as $service_id) {
      $link = Link::fromTextAndUrl($service_id,
        Url::fromRoute('services_list.service_info', ['service_id' => $service_id]));
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

  public function ajaxCallback($service_id) {
    $response = new AjaxResponse();

    $response->addCommand( new OpenModalDialogCommand(t('Modal'),
      kdevel_print_object(\Drupal::service($service_id)),
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