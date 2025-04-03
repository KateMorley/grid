<?php

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

/** Updates generation data. */
class Generation {
  public const KEYS = [
    'coal',
    'ccgt',
    'ocgt',
    'nuclear',
    'oil',
    'wind',
    'hydro',
    'pumped',
    'biomass',
    'battery',
    'other',
    'ifa',
    'moyle',
    'britned',
    'ewic',
    'nemo',
    'ifa2',
    'nsl',
    'eleclink',
    'viking',
    'greenlink'
  ];

  private const COLUMNS = [
    'COAL'    => 1,
    'CCGT'    => 2,
    'OCGT'    => 3,
    'NUCLEAR' => 4,
    'OIL'     => 5,
    'WIND'    => 6,
    'NPSHYD'  => 7,
    'PS'      => 8,
    'BIOMASS' => 9,
    'BESS'    => 10,
    'OTHER'   => 11,
    'INTFR'   => 12,
    'INTIRL'  => 13,
    'INTNED'  => 14,
    'INTEW'   => 15,
    'INTNEM'  => 16,
    'INTIFA2' => 17,
    'INTNSL'  => 18,
    'INTELEC' => 19,
    'INTVKL'  => 20,
    'INTGRNL' => 21
  ];

  /**
   * Updates the generation data.
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {
    $rawData = @file_get_contents(
      sprintf(
        'https://data.elexon.co.uk/bmrs/api/v1/datasets/FUELINST/stream?publishDateTimeFrom=%s&publishDateTimeTo=%s',
        gmdate('Y-m-d\\TH:i:s\\Z', time() - 24 * 60 * 60),
        gmdate('Y-m-d\\TH:i:s\\Z')
      )
    );

    if ($rawData === false) {
      throw new DataException('Failed to read data');
    }

    $jsonData = json_decode($rawData, true);

    if (!is_array($jsonData)) {
      throw new DataException('Missing data');
    }

    $data = [];

    foreach ($jsonData as $item) {
      if (!is_array($item)) {
        throw new DataException('Invalid item');
      }

      $time = self::getTime($item);

      if (!isset($data[$time])) {
        $data[$time] = array_fill(0, count(self::COLUMNS) + 1, 0);
        $data[$time][0] = $time;
      }

      $data[$time][self::getColumn($item)] = self::getGeneration($item);
    }

    $database->updateGeneration($data);
  }

  /**
   * Returns the time for an item.
   *
   * @param array $item The item
   *
   * @throws DataException If the time was invalid
   */
  private static function getTime(array $item): string {
    if (!isset($item['startTime'])) {
      throw new DataException('Missing start time');
    }

    $time = $item['startTime'];

    if (!is_string($time)) {
      throw new DataException('Invalid start time: ' . $time);
    }

    return Time::normalise($item['startTime'], 5);
  }

  /**
   * Returns the column for an item.
   *
   * @param array $item The item
   *
   * @throws DataException If the fuel type was invalid
   */
  private static function getColumn(array $item): int {
    if (!isset($item['fuelType'])) {
      throw new DataException('Missing fuel type');
    }

    $fuelType = $item['fuelType'];

    if (!is_string($fuelType) || !isset(self::COLUMNS[$fuelType])) {
      throw new DataException('Invalid fuel type: ' . $fuelType);
    }

    return self::COLUMNS[$fuelType];
  }

  /**
   * Returns the generation for an item.
   *
   * @param array $item The item
   *
   * @throws DataException If the generation was invalid
   */
  private static function getGeneration(array $item): float {
    if (!isset($item['generation'])) {
      throw new DataException('Missing generation');
    }

    $generation = $item['generation'];

    if (!is_int($generation)) {
      throw new DataException('Invalid generation value: ' . $generation);
    }

    return round($generation / 1000, 2);
  }
}
