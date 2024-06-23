<?php

// Functions for handling times

namespace KateMorley\Grid\Data;

class Time {

  /**
   * Normalises a time and returns it as a "YYYY-MM-DD HH:MM:SS" string
   *
   * @param string $time     The time
   * @param int    $interval The time interval, in minutes
   *
   * @throws DataException If the time is invalid
   */
  public static function normalise(string $time, int $interval): string {

    if (!preg_match(
      '/^(\d\d\d\d)-(\d\d)-(\d\d)(T| )(2[0-3]|[01]\d):([0-5]\d)(:00)?Z?$/',
      $time,
      $matches
    )) {
      throw new DataException('Invalid time format: ' . $time);
    }

    if (!checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1])) {
      throw new DataException('Invalid date: ' . $time);
    }

    if ((int)$matches[6] % $interval !== 0) {
      throw new DataException(
        'Not a multiple of ' . $interval . ' minutes: ' . $time
      );
    }

    return '"' . str_replace(['T', 'Z'], [' ', ''], $time) . '"';

  }

  /**
   * Returns the time corresponding to a settlement period. Settlement periods
   * usually range from 1 (corresponding to 00:05-00:30) to 48 (corresponding to
   * 23:35-00:00 the next day).
   *
   * The settlement date respects BST. As a result, there are 46 settlement
   * periods when BST begins and 50 settlement periods when BST ends, so the
   * times corresponding to settlement periods differ from those on other days.
   *
   * @param string $date   The settlement date
   * @param int    $period The half hour settlement period
   *
   * @throws DataException If the date or period is invalid
   */
  public static function getSettlementTime(string $date, int $period): string {

    if (!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $date, $matches)) {
      throw new DataException('Invalid settlement date format: ' . $date);
    }

    if (!checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1])) {
      throw new DataException('Invalid settlement date: ' . $date);
    }

    if ($period < 1 || $period > 50) {
      throw new DataException('Invalid settlement period: ' . $period);
    }

    return gmdate(
      '"Y-m-d H:i:s"',
      (
        mktime(0, 0, 0, (int)$matches[2], (int)$matches[3], (int)$matches[1])
        + ($period - 1) * 1800
      )
    );

  }

}
