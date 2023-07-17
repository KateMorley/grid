<?php

// Represents the emissions from power production

namespace KateMorley\Grid\State;

class Emissions extends Map {

  public const EMISSIONS = 'emissions';

  public const KEYS = [
    self::EMISSIONS => 'Emissions'
  ];

  protected const KEY_COMPONENTS = [
    self::EMISSIONS => ['emissions']
  ];

}
