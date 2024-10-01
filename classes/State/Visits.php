<?php

namespace KateMorley\Grid\State;

/** Represents the number of visits to the site. */
class Visits extends Map {
  public const VISITS = 'visits';

  public const KEYS = [
    self::VISITS => 'Visits'
  ];

  protected const KEY_COMPONENTS = [
    self::VISITS => ['visits']
  ];
}
