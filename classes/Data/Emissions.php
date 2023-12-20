<?php

// Updates emissions data

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

class Emissions {

  public const KEYS = [
    'emissions'
  ];

  /**
   * Updates the emissions data
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {

    $rawData = @file_get_contents(
      sprintf(
        'https://api.carbonintensity.org.uk/intensity/%s/pt24h',
        gmdate('Y-m-d\\TH:i:s\\Z')
      )
    );

    if ($rawData === false) {
      throw new DataException('Failed to read data');
    }

    $jsonData = json_decode($rawData, true);

    if (!isset($jsonData['data']) || !is_array($jsonData['data'])) {
      throw new DataException('Missing data');
    }

    $data = [];

    foreach ($jsonData['data'] as $item) {

      if (!is_array($item)) {
        throw new DataException('Invalid item');
      }

      $datum = self::getDatum($item);

      if ($datum !== null) {
        $data[] = $datum;
      }

    }

    $database->update(self::KEYS, $data, true, true);

  }

  /**
   * Returns the datum for an item, or null if emissions are not yet available
   *
   * @param array $item The item
   *
   * @throws DataException If the data was invalid
   */
  private static function getDatum(array $item): ?array {

    if (!isset($item['to'])) {
      throw new DataException('Missing time');
    }

    SettlementPeriod::validateTime($item['to']);

    if (!isset($item['intensity']['actual'])) {
      return null;
    }

    if (!is_int($item['intensity']['actual'])) {
      throw new DataException(
        'Invalid emissions value: ' . $item['intensity']['actual']
      );
    }

    return [
      '"' . str_replace(['T', 'Z'], [' ', ''], $item['to']) . '"',
      $item['intensity']['actual']
    ];

  }

}
