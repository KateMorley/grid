<?php

// Updates half-hour generation data

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

class HalfHourGeneration {

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
    'other'
  ];

  /**
   * Updates the half-hour generation data
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {

    $data = Bmrs::parse(
      'FUELHH',
      [
        'startTimeOfHalfHrPeriod',
        'settlementPeriod',
        'coal',
        'ccgt',
        'ocgt',
        'nuclear',
        'oil',
        'wind',
        'npshyd',
        'ps',
        'biomass',
        'other'
      ],
      [
        'recordType',
        'intfr',
        'intirl',
        'intned',
        'intew',
        'intnem',
        'intelec',
        'intifa2',
        'intnsl',
        'activeFlag'
      ]
    );

    $database->update(self::KEYS, $data, false, true);

  }

}
