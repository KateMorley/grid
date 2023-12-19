<?php

// Updates five-minute generation data

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

class FiveMinuteGeneration {

  /**
   * Updates the five-minute generation data
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {

    $data = Bmrs::parse(
      'FUELINST',
      [
        'publishingPeriodCommencingTime',
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
        'startTimeOfHalfHrPeriod',
        'settlementPeriod',
        'intfr',
        'intirl',
        'intned',
        'intew',
        'intnem',
        'intelec',
        'intifa2',
        'intnsl',
        'intvkl',
        'activeFlag'
      ],
      true,
      true
    );

    $database->update(HalfHourGeneration::KEYS, $data, true, false);

  }

}
