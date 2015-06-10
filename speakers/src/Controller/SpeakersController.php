<?php

/**
 * @file
 * Contains \Drupal\speakers\Controller\SpeakersController.
 */

namespace Drupal\speakers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\speakers\SpeakersService;
use Drupal\speakers\SpeakersServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SpeakersController
 */
class SpeakersController extends ControllerBase {

  /**
   * @var \Drupal\speakers\SpeakersServiceInterface
   */
  private $speakersService;

  /**
   * {@inheritdoc}
   *
   * Uses late static binding to create an instance of this class with
   * injected dependencies.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('speakersService')
    );
  }

  /**
   * @param \Drupal\speakers\SpeakersServiceInterface $speakersService
   */
  public function __construct(SpeakersServiceInterface $speakersService) {
    $this->speakersService = $speakersService;
  }

  /**
   * @return array
   */
  public function view() {
    $surname = $this->config('speakers.config')->get('surname');
    $bio = $this->speakersService->getBio($surname);

    return array(
      '#theme' => 'speakers',
      '#data' => $bio,
    );
  }

}
