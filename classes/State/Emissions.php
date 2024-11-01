<?php

namespace KateMorley\Grid\State;

/** Represents the emissions from power production. */
class Emissions extends Map {
  public const EMISSIONS = 'emissions';

  public const KEYS = [
    self::EMISSIONS => 'Emissions'
  ];

  protected const KEY_COMPONENTS = [
    self::EMISSIONS => ['emissions']
  ];
}
