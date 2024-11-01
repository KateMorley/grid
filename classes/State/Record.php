<?php

namespace KateMorley\Grid\State;

/** Represents a power generation record. */
class Record {
  /**
   * Constructs a new instance.
   *
   * @param int   $time  The time when the record was set
   * @param float $power The power
   */
  public function __construct(
    public readonly int $time,
    public readonly float $power
  ) {
  }
}
