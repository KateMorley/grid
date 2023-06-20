<?php

// Represents the wholesale price of energy

namespace KateRoseMorley\Grid\State;

class Price extends Map {

  public const PRICE = 'price';

  public const KEYS = [
    self::PRICE => 'Price'
  ];

  protected const KEY_COMPONENTS = [
    self::PRICE => ['price']
  ];

}
