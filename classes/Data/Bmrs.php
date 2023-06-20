<?php

// Parses BMRS data from a URL

namespace KateRoseMorley\Grid\Data;

class Bmrs {

  /**
   * Reads BMRS data from a URL and returns an array with an item for each XML
   * item, with each item being an array of values for the required fields
   *
   * @param string $report         The BMRS report
   * @param array  $requiredFields The required fields
   * @param array  $ignoredFields  The ignored fields
   * @param bool   $isPower        Whether the values are power values; if true,
   *                               values are converted from MW to GW
   * @param bool   $hasTime        Whether the first field is a time; if false,
   *                               the first two fields are assumed to refer to
   *                               a settlement period and are converted to a
   *                               time
   *
   * @throws DataException If the data was invalid
   */
  public static function parse(
    string $report,
    array  $requiredFields,
    array  $ignoredFields,
    bool   $isPower = true,
    bool   $hasTime = false
  ): array {

    $rawData = @file_get_contents(
      sprintf(
        'https://www.bmreports.com/bmrs/?q=ajax/xml_download/%s/xml/',
        $report
      )
    );

    if ($rawData === false) {
      throw new DataException('Failed to read data');
    }

    try {
      $xmlData = @new \SimpleXMLElement($rawData);
    } catch (\Exception $e) {
      throw new DataException('Invalid XML');
    }

    if (!isset($xmlData->responseBody->responseList->item)) {
      throw new DataException('Missing data');
    }

    $data = [];

    foreach ($xmlData->responseBody->responseList->item as $item) {
      $data[] = self::getDatum(
        $item,
        $requiredFields,
        $ignoredFields,
        $isPower,
        $hasTime
      );
    }

    return $data;

  }

  /**
   * Returns the datum for an item
   *
   * @param \SimpleXMLElement $item           The item
   * @param array             $requiredFields The required fields
   * @param array             $ignoredFields  The ignored fields
   * @param bool              $isPower        Whether the values are power
   *                                          values; if true, values are
   *                                          converted from MW to GW
   * @param bool              $hasTime        Whether the first field is a time;
   *                                          if false, the first two fields are
   *                                          assumed to refer to a settlement
   *                                          period and are converted to a time
   *
   * @throws DataException If the data was invalid
   */
  private static function getDatum(
    \SimpleXMLElement $item,
    array             $requiredFields,
    array             $ignoredFields,
    bool              $isPower,
    bool              $hasTime
  ): array {

    foreach ($item as $field => $value) {
      if (
        !in_array($field, $requiredFields, true)
        && !in_array($field, $ignoredFields, true)
      ) {
        throw new DataException('Unrecognised field: ' . $field);
      }
    }

    $datum = [];

    foreach ($requiredFields as $field) {
      if (isset($item->$field)) {
        $datum[] = (string)$item->$field;
      } else {
        throw new DataException('Missing field: ' . $field);
      }
    }

    if ($hasTime) {
      $time = array_shift($datum);
      SettlementPeriod::validateTime($time);
    } else {
      $time = SettlementPeriod::getTime(
        array_shift($datum),
        array_shift($datum)
      );
    }

    foreach ($datum as $index => $value) {
      if (!is_numeric($value)) {
        throw new DataException('Non-numeric value: ' . $value);
      } elseif ($isPower) {
        $datum[$index] = round($value / 1000, 2);
      } else {
        $datum[$index] = (float)$value;
      }
    }

    array_unshift($datum, '"' . $time . '"');

    return $datum;

  }

}
