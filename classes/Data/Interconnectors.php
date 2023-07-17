<?php

// Updates interconnector data

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

class Interconnectors {

  public const KEYS = [
    'ifa',
    'moyle',
    'britned',
    'ewic',
    'nemo',
    'ifa2',
    'nsl',
    'eleclink'
  ];

  /**
   * Updates the interconnector data
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {

    $data = Bmrs::parse(
      'INTERFUELHH',
      [
        'settlementDate',
        'startTimeOfHalfHrPeriod',
        'intfrGeneration',
        'intirlGeneration',
        'intnedGeneration',
        'intewGeneration',
        'intnemGeneration',
        'intifa2Generation',
        'intnslGeneration',
        'intelecGeneration'
      ],
      [
        'recordType',
        'activeFlag'
      ]
    );

    $database->update(self::KEYS, $data, true, true);

  }

}
