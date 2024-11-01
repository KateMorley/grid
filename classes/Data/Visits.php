<?php

namespace KateMorley\Grid\Data;

use KateMorley\Grid\Database;

/** Updates visit data. */
class Visits {
  public const KEYS = [
    'visits'
  ];

  /**
   * Updates the visit data.
   *
   * @param Database $database The database instance
   *
   * @throws DataException If the data was invalid
   */
  public static function update(Database $database): void {
    if (
      getenv('CLOUDFLARE_API_TOKEN') === ''
      || getenv('CLOUDFLARE_ZONE_ID') === ''
    ) {
      return;
    }

    $curl = curl_init();

    curl_setopt(
      $curl,
      CURLOPT_URL,
      'https://api.cloudflare.com/client/v4/graphql'
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . getenv('CLOUDFLARE_API_TOKEN'),
      'Content-Type: application/json'
    ]);

    $zoneId    = getenv('CLOUDFLARE_ZONE_ID');
    $time      = $database->getLatestHalfHourTimestamp();
    $startTime = gmdate('Y-m-d\\TH:i:s\\Z', $time - 12 * 60 * 60);
    $endTime   = gmdate('Y-m-d\\TH:i:s\\Z', $time);

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
      'query' => <<<QUERY
        query {
          viewer {
            zones(
              filter: {
                zoneTag: "{$zoneId}"
              }
            ) {
              httpRequests1mGroups(
                filter: {
                  datetime_geq: "{$startTime}",
                  datetime_lt: "{$endTime}"
                },
                orderBy: [datetimeHalfOfHour_ASC],
                limit: 100
              ) {
                dimensions {
                  datetimeHalfOfHour
                }
                sum {
                  pageViews
                }
              }
            }
          }
        }
      QUERY
    ]));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $rawData = curl_exec($curl);

    if ($rawData === false) {
      throw new DataException('Failed to read data');
    }

    $jsonData = json_decode($rawData, true);

    if (
      !isset($jsonData['data']['viewer']['zones'][0]['httpRequests1mGroups'])
      || !is_array($jsonData['data']['viewer']['zones'][0]['httpRequests1mGroups'])
    ) {
      throw new DataException('Missing data');
    }

    $data = [];

    foreach (
      $jsonData['data']['viewer']['zones'][0]['httpRequests1mGroups'] as $item
    ) {
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
    if (!isset($item['dimensions']['datetimeHalfOfHour'])) {
      throw new DataException('Missing time');
    }

    if (!isset($item['sum']['pageViews'])) {
      throw new DataException('Missing visits');
    }

    if (!is_int($item['sum']['pageViews'])) {
      throw new DataException('Invalid visits: ' . $item['sum']['pageViews']);
    }

    return [
      Time::normalise($item['dimensions']['datetimeHalfOfHour'], 30),
      $item['sum']['pageViews']
    ];
  }
}
