<?php

namespace Drupal\speakers;

/**
 * Interface SpeakerServiceInterface
 */
interface SpeakersServiceInterface {

  /**
   * Retrieves the bio of the speaker using speaker's surname
   *
   * @param string $surname
   *
   * @return array
   */
  public function getBio($surname);

}
