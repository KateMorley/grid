<?php

// Formats values

namespace KateRoseMorley\Grid\UI;

class Value {

  /**
   * Formats a power value
   *
   * @param float $value The value
   */
  public static function formatPower(float $value): string {
    return self::format($value, 2);
  }

  /**
   * Formats a total power value
   *
   * @param float $value The value
   */
  public static function formatTotalPower(float $value): string {
    return self::format($value, 1);
  }

  /**
   * Formats a percentage
   *
   * @param float $value The value, as a fraction
   */
  public static function formatPercentage(float $value): string {
    return self::format(100 * $value, 1);
  }

  /**
   * Formats a price
   *
   * @param float $value The value
   */
  public static function formatPrice(float $value): string {
    return self::format($value, 2, '£');
  }

  /**
   * Formats a value
   *
   * @param float  $value,        The value
   * @param int    $decimalPlaces The number of decimal places to show
   * @param string $prefix        An option prefix
   */
  private static function format(
    float  $value,
    int    $decimalPlaces,
    string $prefix = ''
  ): string {
    return (
      ($value < 0 ? '−' : '')
      . $prefix
      . sprintf('%0.' . $decimalPlaces . 'f', abs($value))
    );
  }

}
