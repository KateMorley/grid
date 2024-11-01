<?php

namespace KateMorley\Grid\State;

/** Represents a map from keys to values. */
abstract class Map {
  public const KEYS = [];

  protected const KEY_COMPONENTS = [];

  protected array $map = [];

  /**
   * Constructs a new instance.
   *
   * @param array $map An array mapping keys to values
   */
  public function __construct(array $map) {
    $this->map = array_map(
      fn ($components) => array_sum(
        array_map(fn ($component) => ($map[$component] ?? 0), $components)
      ),
      static::KEY_COMPONENTS
    );
  }

  /**
   * Returns the value for a key.
   *
   * @param string $key The key
   */
  public function get(string $key): float {
    return ($this->map[$key] ?? 0);
  }

  /** Returns the minimum value across all keys. */
  public function getMinimum(): float {
    return min($this->map);
  }

  /** Returns the maximum value across all keys. */
  public function getMaximum(): float {
    return max($this->map);
  }

  /** Returns the total value for all keys. */
  public function getTotal(): float {
    return array_sum($this->map);
  }
}
