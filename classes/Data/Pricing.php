<?php

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

/** Updates pricing data. */
class Pricing {
  public const KEYS = [
    'price'
  ];

  /**
   * Updates the pricing data.
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {
    $time = $database->getLatestHalfHourTimestamp();

    $rawData = @file_get_contents(
      sprintf(
        'https://data.elexon.co.uk/bmrs/api/v1/balancing/pricing/market-index?from=%s&to=%s&dataProviders=APXMIDP',
        gmdate('Y-m-d\\TH:i:s\\Z', $time - 24 * 60 * 60),
        gmdate('Y-m-d\\TH:i:s\\Z', $time)
      )
    );

    if ($rawData === false) {
      throw new DataException('Failed to read data');
    }

    $jsonData = json_decode($rawData, true);

    if (
      !is_array($jsonData)
      || !isset($jsonData['data'])
      || !is_array($jsonData['data'])
    ) {
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
    if (!isset($item['startTime'])) {
      throw new DataException('Missing time');
    }

    if (!isset($item['price'])) {
      throw new DataException('Missing price');
    }

    if (!is_float($item['price']) && !is_int($item['price'])) {
      throw new DataException('Invalid price: ' . $item['price']);
    }

    return [Time::normalise($item['startTime'], 30), $item['price']];
  }
}
