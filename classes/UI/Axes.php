<?php

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\Datum;
use KateMorley\Grid\State\State;

/** Represents graph axes. */
class Axes {
  private const VISITS_STEP = 100000;

  private array $minimums = [];
  private array $maximums = [];
  private array $steps    = [];

  /**
   * Constructs a new instance.
   *
   * @param State $state The state
   */
  public function __construct(State $state) {
    foreach ([
      Datum::PRICE,
      Datum::EMISSIONS,
      Datum::GENERATION,
      Datum::TRANSFERS,
      Datum::DEMAND
    ] as $graph) {
      $minimums = [0];
      $maximums = [];

      foreach ([
        $state->getPastDaySeries(),
        $state->getPastWeekSeries(),
        $state->getPastYearSeries(),
        $state->getAllTimeSeries()
      ] as $series) {
        foreach ($series as $datum) {
          $minimums[] = $datum->get($graph)->getMinimum();
          $maximums[] = $datum->get($graph)->getMaximum();
        }
      }

      $minimum = min($minimums);
      $maximum = max($maximums);
      $range   = $maximum - $minimum;

      if ($range > 2000) {
        $step = 500;
      } elseif ($range > 1000) {
        $step = 200;
      } elseif ($range > 500) {
        $step = 100;
      } elseif ($range > 200) {
        $step = 50;
      } elseif ($range > 100) {
        $step = 20;
      } elseif ($range > 50) {
        $step = 10;
      } elseif ($range > 20) {
        $step = 5;
      } elseif ($range > 10) {
        $step = 2;
      } else {
        $step = 1;
      }

      $this->setGraphAxis($graph, $minimum, $maximum, $step);
    }

    $this->setGraphAxis(
      Datum::VISITS,
      0,
      max(...array_map(
        fn ($datum) => $datum->get(Datum::VISITS)->getMaximum(),
        $state->getPastYearSeries()
      )),
      self::VISITS_STEP
    );
  }

  /**
   * Sets the axis details for a graph.
   *
   * @param int   $graph   One of the Datum class constants identifying a graph
   * @param float $minimum The minimum
   * @param float $maximum The maximum
   * @param int   $step    The step size
   */
  private function setGraphAxis(
    int   $graph,
    float $minimum,
    float $maximum,
    int   $step
  ): void {
    $this->minimums[$graph] = $step * floor($minimum / $step);
    $this->maximums[$graph] = $step * ceil($maximum  / $step);
    $this->steps[$graph]    = $step;
  }

  /**
   * Returns the minimum for the specified graph.
   *
   * @param int $graph One of the Datum class constants identifying a graph
   */
  public function getMinimum(int $graph): int {
    return $this->minimums[$graph];
  }

  /**
   * Returns the maximum for the specified graph.
   *
   * @param int $graph One of the Datum class constants identifying a graph
   */
  public function getMaximum(int $graph): int {
    return $this->maximums[$graph];
  }

  /**
   * Returns the step size for the specified graph.
   *
   * @param int $graph One of the Datum class constants identifying a graph
   */
  public function getStep(int $graph): int {
    return $this->steps[$graph];
  }
}
