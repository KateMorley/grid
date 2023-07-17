<?php

// Represents a data point consisting of a price and details of power sources

namespace KateMorley\Grid\State;

class Datum {

  public const PRICE           = 0;
  public const EMISSIONS       = 1;
  public const GENERATION      = 2;
  public const TYPES           = 3;
  public const INTERCONNECTORS = 4;
  public const STORAGE         = 5;
  public const TRANSFERS       = 6;
  public const DEMAND          = 7;

  private Price           $price;
  private Emissions       $emissions;
  private Generation      $generation;
  private Types           $types;
  private Interconnectors $interconnectors;
  private Storage         $storage;
  private Transfers       $transfers;
  private Demand          $demand;

  /**
   * Constructs a new instance
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
  }

  /**
   * Returns the specified Map
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
      default                    : throw new \Exception('Invalid map type');
    }
  }

  /** Returns the wholesale price of energy */
  public function getPrice(): Price {
    return $this->price;
  }

  /** Returns the emissions from power production */
  public function getEmissions(): Emissions {
    return $this->emissions;
  }

  /** Returns the details of generation by type */
  public function getTypes(): Types {
    return $this->types;
  }

  /** Returns the details of generation */
  public function getGeneration(): Generation {
    return $this->generation;
  }

  /** Returns the details of interconnectors */
  public function getInterconnectors(): Interconnectors {
    return $this->interconnectors;
  }

  /** Returns the details of storage */
  public function getStorage(): Storage {
    return $this->storage;
  }

  /** Returns the details of transfers */
  public function getTransfers(): Transfers {
    return $this->transfers;
  }

  /** Returns the details of demand */
  public function getDemand(): Demand {
    return $this->demand;
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
