<?php

// Updates data from the National Grid ESO Demand Data Update

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

class Demand {

  public const KEYS = [
    'embedded_wind',
    'embedded_solar',
    'pumped_storage_pumping'
  ];

  /**
   * Updates the demand data
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {

    $rows = Csv::parse(
      'https://data.nationalgrideso.com/backend/dataset/7a12172a-939c-404c-b581-a6128b74f588/resource/177f6fa4-ae49-4182-81ea-0c6b35f26ca6/download/demanddataupdate.csv',
      [
        'SETTLEMENT_DATE',
        'SETTLEMENT_PERIOD',
        'EMBEDDED_WIND_GENERATION',
        'EMBEDDED_SOLAR_GENERATION',
        'PUMP_STORAGE_PUMPING'
      ],
      [
        'ND',
        'FORECAST_ACTUAL_INDICATOR',
        'TSD',
        'ENGLAND_WALES_DEMAND',
        'EMBEDDED_WIND_CAPACITY',
        'EMBEDDED_SOLAR_CAPACITY',
        'NON_BM_STOR',
        'SCOTTISH_TRANSFER',
        'IFA_FLOW',
        'IFA2_FLOW',
        'BRITNED_FLOW',
        'MOYLE_FLOW',
        'EAST_WEST_FLOW',
        'NEMO_FLOW',
        'NSL_FLOW',
        'ELECLINK_FLOW',
        'VIKING_FLOW'
      ]
    );

    $data = [];

    // we import 28 days of data as the estimates can be retrospectively updated
    $earliestTime = gmdate(
      'Y-m-d H:i:s',
      gmmktime(0, 0, 0, gmdate('n'), gmdate('j') - 28)
    );

    // we ignore future predictions
    $latestTime = $database->getLatestHalfHour();

    foreach ($rows as $item) {

      $datum = self::getDatum($item, $earliestTime, $latestTime);

      if ($datum !== null) {
        $data[] = $datum;
      }

    }

    $database->update(self::KEYS, $data);

  }

  /**
   * Returns the datum for an item, or null if the item is not from a relevant
   * time period
   *
   * @param array  $item         The item
   * @param string $earliestTime The earliest relevant time
   * @param string $latestTime   The latest relevant time
   *
   * @throws DataException If the data was invalid
   */
  private static function getDatum(
    array  $item,
    string $earliestTime,
    string $latestTime
  ): ?array {

    $time = SettlementPeriod::getTime($item[0], $item[1]);

    if ($time < $earliestTime || $time > $latestTime) {
      return null;
    }

    for ($i = 2; $i <= 4; $i ++) {
      if (!ctype_digit($item[$i])) {
        throw new DataException('Non-integer value: ' . $item[$i]);
      }
    }

    return [
      '"' . $time . '"',
      (int)$item[2] / 1000,
      (int)$item[3] / 1000,
      -(int)$item[4] / 1000 // pumped storage pumping is negative generation
    ];

  }

}
