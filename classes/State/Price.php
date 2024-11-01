<?php

namespace KateMorley\Grid\State;

/** Represents the wholesale price of energy. */
class Price extends Map {
  public const PRICE = 'price';

  public const KEYS = [
    self::PRICE => 'Price'
  ];

  protected const KEY_COMPONENTS = [
    self::PRICE => ['price']
  ];
}
