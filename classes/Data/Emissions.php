<?php

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

/** Updates emissions data. */
class Emissions {
  public const KEYS = [
    'emissions'
  ];

  /**
   * Updates the emissions data.
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

      $data[] = self::getDatum($item);
    }

    $database->update(self::KEYS, $data);
  }

  /**
   * Returns the datum for an item.
   *
   * @param array $item The item
   *
   * @throws DataException If the data was invalid
   */
  private static function getDatum(array $item): array {
    if (!isset($item['from'])) {
      throw new DataException('Missing time');
    }

    if (
      !isset($item['intensity']['actual'])
      && !isset($item['intensity']['forecast'])
    ) {
      throw new DataException('Missing emissions value');
    }

    $emissions = $item['intensity']['actual'] ?? $item['intensity']['forecast'];

    if (!is_int($emissions)) {
      throw new DataException('Invalid emissions value: ' . $emissions);
    }

    return [Time::normalise($item['from'], 30), $emissions];
  }
}
