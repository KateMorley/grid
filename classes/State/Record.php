<?php

// Represents a power generation record

namespace KateMorley\Grid\State;

class Record {

  private int   $time;
  private float $power;

  /**
   * Constructs a new instance
   *
   * @param int   $time  The time when the record was set
   * @param float $power The power
   */
  public function __construct(int $time, float $power) {
    $this->time  = $time;
    $this->power = $power;
  }

  /** Returns the time when the record was set */
  public function getTime(): int {
    return $this->time;
  }

  /** Returns the power */
  public function getPower(): float {
    return $this->power;
  }

}
