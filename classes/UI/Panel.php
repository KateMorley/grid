<?php

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\Datum;

/** Outputs a panel. */
class Panel {
  /**
   * Outputs a panel.
   *
   * @param string $time       The time
   * @param Datum  $average    The average
   * @param array  $series     The series
   * @param Axes   $axes       The axes information
   * @param int    $timeStep   The time step
   * @param string $timeFormat The time format
   */
  public static function output(
    string $time,
    Datum  $average,
    array  $series,
    Axes   $axes,
    int    $timeStep,
    string $timeFormat
  ): void {
?>
          <div>
<?php Status::output($average, $time); ?>
          </div>
          <div>
<?php Equation::output($average); ?>
          </div>
          <div>
            <?php PieChart::output($average); ?>
          </div>
          <div>
<?php Tables::output($average); ?>
          </div>
          <div>
            <h3>Price per MWh</h3>
            <?php Graph::output($series, $axes, Datum::PRICE, '£', '', $timeStep, $timeFormat, 2); ?>
          </div>
          <div>
            <h3>Emissions per kWh</h3>
            <?php Graph::output($series, $axes, Datum::EMISSIONS, '', 'g', $timeStep, $timeFormat, 0); ?>
          </div>
          <div>
            <h3>Demand</h3>
            <?php Graph::output($series, $axes, Datum::DEMAND, '', 'GW', $timeStep, $timeFormat, 1); ?>
          </div>
          <div>
            <h3>Generation</h3>
            <?php Graph::output($series, $axes, Datum::GENERATION, '', 'GW', $timeStep, $timeFormat, 2); ?>
          </div>
          <div>
            <h3>Transfers</h3>
            <?php Graph::output($series, $axes, Datum::TRANSFERS, '', 'GW', $timeStep, $timeFormat, 2); ?>
          </div>
<?php
  }
}
