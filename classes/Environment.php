<?php

namespace KateMorley\Grid;

/** Loads the environment. */
class Environment {
  /**
   * Loads the environment from a file.
   *
   * @param string $path The path to the environment file.
   */
  public static function load(string $path): void {
    $file = fopen($path, 'r');

    while ($line = fgets($file)) {
      $line = trim($line);

      if ($line !== '' && $line[0] !== '#') {
        putenv($line);
      }
    }
  }
}
