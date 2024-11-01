<?php

namespace KateMorley\Grid\Data;

/** Parses CSV data from a URL. */
class Csv {
  /**
   * Reads CSV data from a URL and returns an array with an item for each line,
   * with each item being an array of values for the required headers.
   *
   * @param string $url             The URL of the CSV data
   * @param array  $requiredHeaders The required headers
   * @param array  $ignoredHeaders  The ignored headers
   *
   * @throws DataException If the data was invalid
   */
  public static function parse(
    string $url,
    array  $requiredHeaders,
    array  $ignoredHeaders
  ): array {
    $file = @fopen($url, 'r');
    if (!$file) {
      throw new DataException('Failed to read data');
    }

    $row = fgetcsv($file);
    if (!$row) {
      throw new DataException('Missing CSV headers');
    }

    foreach ($row as $header) {
      if (
        !in_array($header, $requiredHeaders, true)
        && !in_array($header, $ignoredHeaders, true)
      ) {
        throw new DataException('Unrecognised header: ' . $header);
      }
    }

    $columnCount = count($row);

    $columns = array_map(
      fn ($header) => array_search($header, $row, true),
      $requiredHeaders
    );

    if (in_array(false, $columns, true)) {
      throw new DataException('Missing required header');
    }

    $data = [];

    while ($row = fgetcsv($file)) {
      if (count($row) !== $columnCount) {
        throw new DataException('Column count does not match header count');
      }

      $data[] = array_map(fn ($column) => $row[$column], $columns);
    }

    return $data;
  }
}
