<?php

// Outputs a pie chart

namespace KateRoseMorley\Grid\UI;

use KateRoseMorley\Grid\State\Datum;
use KateRoseMorley\Grid\State\Map;

class PieChart {

  private const OUTER_RADIUS = 0.75;
  private const INNER_RADIUS = 0.50;

  /**
   * Outputs a pie chart
   *
   * @param Datum $datum The datum
   */
  public static function output(Datum $datum): void {

    $generation = $datum->getGeneration()->getTotal();
    $demand     = $datum->getTotal();

    $generationPower      = Value::formatTotalPower($datum->getDemand()->getGeneration());
    $generationPercentage = Value::formatPercentage($generation / $demand);

    echo '<div class="pie-chart"><div><div>Generation</div><div class="generation"></div><div><span>';
    echo $generationPower;
    echo '</span>GW</div><div><span>';
    echo $generationPercentage;
    echo '</span>%</div></div><svg viewBox="-1 -1 2 2" data-power="';
    echo $generationPower;
    echo '" data-percentage="';
    echo $generationPercentage;
    echo '">';

    self::outputRing(
      $datum->getGeneration(),
      $generation,
      $demand,
      self::OUTER_RADIUS,
      1
    );

    self::outputRing(
      $datum->getTypes(),
      $generation,
      $demand,
      self::INNER_RADIUS,
      self::OUTER_RADIUS,
      true
    );

    echo "</svg></div>\n";

  }

  /**
   * Outputs a ring
   *
   * @param Map   $sources     The sources
   * @param float $generation  The total generation
   * @param float $demand      The total demand
   * @param float $innerRadius The inner radius
   * @param float $outerRadius The outer radius
   * @param bool  $isTotal     Whether this ring shows power totals
   */
  private static function outputRing(
    Map   $sources,
    float $generation,
    float $demand,
    float $innerRadius,
    float $outerRadius,
    bool  $isTotal = false
  ): void {

    $offset = 0;

    foreach ($sources::KEYS as $key => $description) {

      $power = $sources->get($key);
      if ($power > 0) {

        $fraction = $power / $generation;

        self::outputArc(
          $key,
          ($isTotal ? Value::formatTotalPower($power) : Value::formatPower($power)),
          Value::formatPercentage($power / $demand),
          $fraction,
          $offset,
          $innerRadius,
          $outerRadius
        );

        $offset += $fraction;

      }

    }

  }

  /**
   * Outputs an arc
   *
   * @param string $source      The source
   * @param string $power       The power
   * @param string $percentage  The percentage
   * @param float  $faction     The fraction of the circle
   * @param float  $offset      The faction offset
   * @param float  $innerRadius The inner radius
   * @param float  $outerRadius The outer radius
   */
  private static function outputArc(
    string $source,
    string $power,
    string $percentage,
    float  $faction,
    float  $offset,
    float  $innerRadius,
    float  $outerRadius
  ): void {
    echo '<path class="';
    echo $source;
    echo '" d="M';
    self::outputArcPoint($offset, $outerRadius);
    echo 'A';
    echo $outerRadius;
    echo ',';
    echo $outerRadius;
    echo ' 0 ';
    echo ($faction < 0.5 ? 0 : 1);
    echo ' 1 ';
    self::outputArcPoint($offset + $faction, $outerRadius);
    echo 'L';
    self::outputArcPoint($offset + $faction, $innerRadius);
    echo 'A';
    echo $innerRadius;
    echo ',';
    echo $innerRadius;
    echo ' 0 ';
    echo ($faction < 0.5 ? 0 : 1);
    echo ' 0 ';
    self::outputArcPoint($offset, $innerRadius);
    echo 'z" data-power="';
    echo $power;
    echo '" data-percentage="';
    echo $percentage;
    echo '"/>';
  }

  /**
   * Outputs the co-ordinates of a point on an arc
   *
   * @param float $faction The fraction of the circle
   * @param float $radius  The radius
   */
  private static function outputArcPoint(float $faction, float $radius): void {
    printf('%0.4f', $radius * sin($faction * 2 * M_PI));
    echo ',';
    printf('%0.4f', $radius * -cos($faction * 2 * M_PI));
  }

}
