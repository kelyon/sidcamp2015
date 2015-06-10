<?php

namespace Drupal\speakers\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\speakers\SpeakersService;
use Drupal\speakers\SpeakersServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SpeakersBlock
 *
 * @Block(
 *   id = "speakers_block",
 *   admin_label = @Translation("Speakers bio Block"),
 *   category = @Translation("Other")
 * )
 */
class SpeakersBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\speakers\SpeakersServiceInterface
   */
  private $speakersService;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * {@inheritdoc}
   *
   * Uses late static binding to create an instance of this class with
   * injected dependencies.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('SpeakersService'),
      $container->get('config.factory')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\speakers\SpeakersServiceInterface $speakersService
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SpeakersServiceInterface $speakersService, ConfigFactoryInterface $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->speakersService = $speakersService;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Retrieves the configured surname with the Configuration management API
    $surname = $this->configFactory->get('speakers.config')->get('surname');

    // Obtains the BIO from the speakers service
    $bio = $this->speakersService->getBio($surname);

    return array(
      '#theme' => 'speakers', // theme function
      '#data' => $bio,
      '#cache' => array(
        'keys' => array("speakers_html"),
        'tags' => array("surname:$surname"),
      ),
    );
  }
}
