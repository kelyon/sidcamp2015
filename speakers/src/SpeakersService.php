<?php

namespace Drupal\speakers;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Http\Client;

/**
 * Class SpeakersService
 *
 * Uses the sidcamp.it Drupal 7 Services API to retrieve the bio of the speaker.
 */
class SpeakersService implements SpeakersServiceInterface {
  /**
   * @var \Drupal\Core\Http\Client
   */
  private $client;

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * @param \Drupal\Core\Http\Client $client
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   */
  public function __construct(Client $client, CacheBackendInterface $cache) {
    $this->client = $client;
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public function getBio($surname) {
    if ($data = $this->cache->get("speakers_data")) {
      return $data->data;
    }
    else {
      $response = $this->client->get('http://sidcamp.it/relatori/relatori?field_cognome_value=' . $surname);
      $data = $response->json();
      $this->cache->set("speakers_data", $data, Cache::PERMANENT, array("surname:$surname"));
      return $data;
    }
  }

}
