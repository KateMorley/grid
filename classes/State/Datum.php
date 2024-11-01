<?php

namespace KateMorley\Grid\State;

/** Represents a data point consisting of details of power sources. */
class Datum {
  public const PRICE           = 0;
  public const EMISSIONS       = 1;
  public const GENERATION      = 2;
  public const TYPES           = 3;
  public const INTERCONNECTORS = 4;
  public const STORAGE         = 5;
  public const TRANSFERS       = 6;
  public const DEMAND          = 7;
  public const VISITS          = 8;

  public readonly Price           $price;
  public readonly Emissions       $emissions;
  public readonly Generation      $generation;
  public readonly Types           $types;
  public readonly Interconnectors $interconnectors;
  public readonly Storage         $storage;
  public readonly Transfers       $transfers;
  public readonly Demand          $demand;
  public readonly Visits          $visits;

  /**
   * Constructs a new instance.
   *
   * @param array $map The map of data
   */
  public function __construct(array $map) {
    $this->price           = new Price($map);
    $this->emissions       = new Emissions($map);
    $this->generation      = new Generation($map);
    $this->types           = new Types($map);
    $this->interconnectors = new Interconnectors($map);
    $this->storage         = new Storage($map);
    $this->transfers       = new Transfers($map);
    $this->demand          = new Demand($this->types, $this->transfers);
    $this->visits          = new Visits($map);
  }

  /**
   * Returns the specified Map.
   *
   * @param int $map One of the class constants identifying a map
   */
  public function get(int $map): Map {
    switch ($map) {
      case self::PRICE           : return $this->price;
      case self::EMISSIONS       : return $this->emissions;
      case self::GENERATION      : return $this->generation;
      case self::TYPES           : return $this->types;
      case self::INTERCONNECTORS : return $this->interconnectors;
      case self::STORAGE         : return $this->storage;
      case self::TRANSFERS       : return $this->transfers;
      case self::DEMAND          : return $this->demand;
      case self::VISITS          : return $this->visits;
      default                    : throw new \Exception('Invalid map type');
    }
  }

  /**
   * Returns the total power demand. This differs from calling getTotal() on a
   * Demand instance as this value is calculated from values that have not been
   * rounded.
   */
  public function getTotal(): float {
    return ($this->generation->getTotal() + $this->transfers->getTotal());
  }
}
