<?php

// Outputs a graph

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\Datum;

class Graph {

  /**
   * Outputs a graph
   *
   * @param array  $series        The series
   * @param Axes   $axes          The axes information
   * @param int    $graph         One of the Datum class constants identifying a
   *                              graph
   * @param string $prefix        The value prefix
   * @param string $suffix        The value suffix
   * @param int    $timeStep      The time step
   * @param string $timeFormat    The time format
   * @param int    $decimalPlaces The number of decinmal places for values
   */
  public static function output(
    array  $series,
    Axes   $axes,
    int    $graph,
    string $prefix,
    string $suffix,
    int    $timeStep,
    string $timeFormat,
    int    $decimalPlaces
  ): void {

    $lines   = self::getLines($series, $graph);
    $minimum = $axes->getMinimum($graph);
    $maximum = $axes->getMaximum($graph);
    $step    = $axes->getStep($graph);

    echo '<div class="graph" data-prefix="';
    echo $prefix;
    echo '" data-suffix="';
    echo $suffix;
    echo '"';
    if ($graph === Datum::TRANSFERS) {
      echo ' data-transfers="true"';
    }
    echo '>';

    self::outputValueAxis($minimum, $maximum, $step, $prefix, $suffix);
    self::outputTimeAxis(array_keys($series), $timeStep, $timeFormat);

    echo '<svg viewBox="0 0 1000 1000" width="1000" height="1000" preserveAspectRatio="none">';
    self::outputLines($lines, $minimum, $maximum - $minimum);
    self::outputOverlay($series, $graph, $timeFormat, $decimalPlaces);
    echo '</svg>';

    echo "</div>\n";

  }

  /**
   * Returns the lines to show on the graph, as an array mapping map keys to
   * arrays of values
   *
   * @param array $series The series
   * @param int   $graph  One of the Datum class constants identifying a graph
   */
  private static function getLines(array $series, int $graph): array {

    $lines = [];

    foreach ($series as $datum) {

      $map = $datum->get($graph);

      foreach (array_keys($map::KEYS) as $key) {
        @$lines[$key][] = $map->get($key);
      }

    }

    return $lines;

  }

  /**
   * Outputs the value axis
   *
   * @param int    $minimum The minimum value
   * @param int    $maximum The maximum value
   * @param int    $step    The value step
   * @param string $prefix  The value prefix
   * @param string $suffix  The value suffix
   */
  private static function outputValueAxis(
    int    $minimum,
    int    $maximum,
    int    $step,
    string $prefix,
    string $suffix
  ): void {

    echo '<div>';

    for ($label = $maximum; $label >= $minimum; $label -= $step) {

      echo '<div>';

      if ($label < 0) {
        echo '−';
      }

      echo $prefix;
      echo abs($label);
      echo $suffix;
      echo '</div><div></div>';

    }

    echo '</div>';

  }

  /**
   * Outputs the time axis
   *
   * @param array  $times  The times
   * @param string $step   The time step
   * @param string $format The time format
   */
  private static function outputTimeAxis(
    array  $times,
    string $step,
    string $format
  ): void {

    echo '<div>';

    $index = ceil($step / 2);

    foreach ($times as $time) {

      if ($index % $step === 0) {
        echo '<div>';
        echo date($format, $time);
        echo '</div>';
      }

      $index ++;

    }

    echo '</div>';

  }

  /**
   * Outputs the lines
   *
   * @param array $lines   An array mapping map keys to arrays of values
   * @param int   $minimum The minimum value
   * @param int   $range   The value range
   */
  private static function outputLines(
    array $lines,
    int   $minimum,
    int   $range
  ): void {
    foreach ($lines as $key => $values) {
      echo '<polyline class="';
      echo $key;
      echo '" points="';
      echo implode(' ', self::getPoints($values, $minimum, $range));
      echo '"/>';
    }
  }

  /**
   * Returns the point co-ordinates for a line
   *
   * @param array $values  The values
   * @param int   $minimum The minimum value
   * @param int   $range   The range
   */
  private static function getPoints(
    array $values,
    int   $minimum,
    int   $range
  ): array {

    $width = count($values);

    $points = [];

    foreach ($values as $index => $value) {
      $points[] = (
        round(1000 * ($index + 0.5) / $width)
        . ' '
        . round(1000 * (1 - ($value - $minimum) / $range))
      );
    }

    return $points;

  }

  /**
   * Outputs the overlay
   *
   * @param array  $series        The series
   * @param int    $graph         One of the Datum class constants identifying a
   *                              graph
   * @param string $timeFormat    The time format
   * @param int    $decimalPlaces The number of decinmal places for values
   */
  private static function outputOverlay(
    array  $series,
    int    $graph,
    string $timeFormat,
    int    $decimalPlaces

  ): void {

    $width = count($series);

    $index = 0;

    foreach ($series as $time => $datum) {

      $map = $datum->get($graph);

      echo '<rect x="';
      echo round(1000 * $index / $width);
      echo '" y="0" width="';
      echo round(1000 / $width);
      echo '" height="1000" data-time="';
      echo date($timeFormat, $time);
      echo '" data-values="';

      echo implode(
        ' ',
        array_map(
          fn ($key) => sprintf('%0.' . $decimalPlaces . 'f', $map->get($key)),
          array_keys($map::KEYS)
          )
      );

      echo '"/>';

      $index ++;

    }

  }

}
