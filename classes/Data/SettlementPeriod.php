<?php

// Converts settlement periods into times

namespace KateMorley\Grid\Data;

class SettlementPeriod {

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
  public static function getTime(string $date, int $period): string {

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
      'Y-m-d H:i:s',
      (
        mktime(0, 0, 0, (int)$matches[2], (int)$matches[3], (int)$matches[1])
        + ($period - 1) * 1800
      )
    );

  }

  /**
   * Validates a time
   *
   * @throws DataException If the time is invalid
   */
  public static function validateTime(string $time): void {

    if (!preg_match(
      '/^(\d\d\d\d)-(\d\d)-(\d\d)(T| )(2[0-3]|[01][0-9]):[0-5][0-9](:00)?Z?$/',
      $time,
      $matches
    )) {
      throw new DataException('Invalid settlement time format: ' . $time);
    }

    if (!checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1])) {
      throw new DataException('Invalid settlement date: ' . $time);
    }

  }

}
