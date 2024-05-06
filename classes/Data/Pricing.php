<?php

// Updates pricing data

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

class Pricing {

  public const KEYS = [
    'price'
  ];

  /**
   * Updates the pricing data
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {

    $data = Bmrs::parse(
      'DERSYSDATA',
      [
        'settlementDate',
        'settlementPeriod',
        'systemBuyPrice',
        'systemSellPrice'
      ],
      [
        'recordType',
        'bSADDefault',
        'priceDerivationCode',
        'reserveScarcityPrice',
        'indicativeNetImbalanceVolume',
        'sellPriceAdjustment',
        'buyPriceAdjustment',
        'replacementPrice',
        'replacementPriceCalculationVolume',
        'totalSystemAcceptedOfferVolume',
        'totalSystemAcceptedBidVolume',
        'totalSystemTaggedAcceptedOfferVolume',
        'totalSystemTaggedAcceptedBidVolume',
        'totalSystemAdjustmentSellVolume',
        'totalSystemAdjustmentBuyVolume',
        'totalSystemTaggedAdjustmentSellVolume',
        'totalSystemTaggedAdjustmentBuyVolume',
        'activeFlag'
      ],
      false
    );

    foreach ($data as $index => $datum) {
      $data[$index] = [
        $datum[0],
        (($datum[1] + $datum[2]) / 2)
      ];
    }

    $database->update(self::KEYS, $data);

  }

}
