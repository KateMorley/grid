<?php

// Generates the favicon

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\Types;

class Favicon {
  private const RADIUS       = 7.5;
  private const STROKE_WIDTH = 1;

  private const FOSSILS_COLOUR    = '#c45';
  private const RENEWABLES_COLOUR = '#5b5';
  private const OTHERS_COLOUR     = '#27c';

  /**
   * Creates and returns the SVG for the favicon
   *
   * @param Types $types The details of power generation by type
   */
  public static function create(Types $types): string {
    $svg = (
      '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 16 16" width="16" height="16"><g stroke="#fff" stroke-width="'
      . self::STROKE_WIDTH
      . '">'
    );

    $total      = $types->getTotal();
    $fossils    = $types->get(Types::FOSSILS)    / $total;
    $renewables = $types->get(Types::RENEWABLES) / $total;
    $others     = $types->get(Types::OTHERS)     / $total;

    $svg .= self::createArc(
      self::FOSSILS_COLOUR,
      0,
      $fossils
    );

    $svg .= self::createArc(
      self::RENEWABLES_COLOUR,
      $fossils,
      $renewables
    );

    $svg .= self::createArc(
      self::OTHERS_COLOUR,
      $fossils + $renewables,
      $others
    );

    $svg .= '</g></svg>';

    return $svg;
  }

  /**
   * Creates and returns the SVG for an arc
   *
   * @param string $colour      The colour
   * @param float  $angleOffset The angle offset
   * @param float  $angle       The angle
   */
  private static function createArc(
    string $colour,
    float  $angleOffset,
    float  $angle
  ): string {
    if ($angle === 0) {
      return '';
    }

    return (
      '<path fill="'
      . $colour
      . '" d="M'
      . self::getArcPoint($angleOffset)
      . 'A'
      . self::RADIUS
      . ','
      . self::RADIUS
      . ' 0 '
      . ($angle < 0.5 ? 0 : 1)
      . ' 1 '
      . self::getArcPoint($angleOffset + $angle)
      . 'L0,0z"/>'
    );
  }

  /**
   * Returns the co-ordinates of a point on an arc
   *
   * @param float $angle The angle
   */
  private static function getArcPoint(float $angle): string {
    return (
      sprintf('%0.1f', self::RADIUS * sin($angle * 2 * M_PI))
      . ','
      . sprintf('%0.1f', self::RADIUS * -cos($angle * 2 * M_PI))
    );
  }
}
