<?php

// Database functions

namespace KateMorley\Grid;

use KateMorley\Grid\Data\Demand;
use KateMorley\Grid\Data\Emissions;
use KateMorley\Grid\Data\HalfHourGeneration;
use KateMorley\Grid\Data\Interconnectors;
use KateMorley\Grid\Data\Pricing;
use KateMorley\Grid\State\Datum;
use KateMorley\Grid\State\Record;
use KateMorley\Grid\State\State;

class Database {

  private const PAST_DAY  = '(SELECT * FROM past_half_hours ORDER BY time DESC LIMIT 48)';
  private const PAST_WEEK = '(SELECT * FROM past_days ORDER BY time DESC LIMIT 1,7)';
  private const PAST_YEAR = '(SELECT * FROM past_weeks ORDER BY time DESC LIMIT 1,52)';

  private \mysqli $connection;

  /** Constructs a new instance */
  public function __construct() {

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $this->connection = new \mysqli(
      DATABASE_HOSTNAME,
      DATABASE_USERNAME,
      DATABASE_PASSWORD,
      DATABASE_DATABASE
    );

    $this->connection->set_charset('utf8mb4');

  }

  /** Returns the latest state */
  public function getState(): State {
    return new State(
      strtotime($this->getLatestTime('latest') . ' UTC'),
      $this->getLatest(),
      $this->getPastPeriod(self::PAST_DAY),
      $this->getPastPeriod(self::PAST_WEEK),
      $this->getPastPeriod(self::PAST_YEAR),
      $this->getPastPeriod('past_days'),
      $this->getSeries(self::PAST_DAY),
      $this->getSeries(self::PAST_WEEK),
      $this->getSeries(self::PAST_YEAR),
      $this->getSeries('past_years'),
      $this->getWindRecord(),
      $this->getWindMilestones()
    );
  }

  /** Returns the latest half hour */
  public function getLatestHalfHour(): string {
    return $this->getLatestTime('past_half_hours');
  }

  /**
   * Returns the latest time from a table
   *
   * @param string $table The table
   */
  private function getLatestTime(string $table): string {
    return $this->connection->query(
      'SELECT MAX(time) FROM ' . $table
    )->fetch_row()[0];
  }

  /** Returns the latest datum */
  private function getLatest(): Datum {

    $map = [];

    $rows = $this->connection->query('SELECT source,value FROM latest');
    while ($source = $rows->fetch_row()) {
      $map[$source[0]] = $source[1];
    }

    return new Datum($map);

  }

  /**
   * Returns a past period's datum
   *
   * @param string $table The table
   */
  private function getPastPeriod(string $table): Datum {

    $row = $this->connection->query(
      'SELECT '
      . implode(
        ',',
        array_map(
          fn ($source) => 'AVG(' . $source . ') AS ' . $source,
          self::getColumns()
        )
      )
      . ' FROM '
      . $table
      . ' AS t'
    )->fetch_assoc();

    return new Datum($row);

  }

  /**
   * Returns a past period's series
   *
   * @param string $table The table
   */
  private function getSeries(string $table): array {

    $series = [];

    $rows = $this->connection->query(
      'SELECT time,'
      . implode(',', self::getColumns())
      . ' FROM '
      . $table
      . ' AS t ORDER BY time ASC'
    );

    while ($row = $rows->fetch_assoc()) {
      $series[strtotime($row['time'] . ' UTC')] = new Datum($row);
    }

    return $series;

  }

  /** Returns the wind power generation records */
  private function getWindRecord(): Record {

    $record = $this->connection->query(
      'SELECT value,time FROM wind_records ORDER BY value DESC'
    )->fetch_assoc();

    return new Record(
      strtotime($record['time'] . ' UTC'),
      $record['value']
    );

  }

  /** Returns the wind power generation milestones */
  private function getWindMilestones(): array {

    $milestones = [];

    $rows = $this->connection->query(
      'SELECT * FROM wind_records ORDER BY value DESC'
    );

    while ($row = $rows->fetch_assoc()) {
      $milestones[floor($row['value'])] = strtotime($row['time'] . ' UTC');
    }

    return $milestones;

  }

  /**
   * Updates data
   *
   * @param array $columns      The columns to update
   * @param array $data         The data
   * @param bool  $isLatest     Whether the data is the latest available
   * @param bool  $isHalfHourly Whether the data is half-hourly data
   */
  public function update(
    array $columns,
    array $data,
    bool  $isLatest,
    bool  $isHalfHourly
  ): void {

    if (count($data) === 0) {
      return;
    }

    // updateLatest requires the most recent data at the start of the array
    usort($data, fn ($a, $b) => $b[0] <=> $a[0]);

    if ($isLatest) {
      $this->updateLatest('latest', $columns, $data);
    }

    if ($isHalfHourly) {
      $this->updateLatest('latest_half_hours', $columns, $data);
      $this->updatePastHalfHours($columns, $data);
    }

  }

  /**
   * Updates the latest data
   *
   * @param string $table   The table
   * @param array  $sources The sources to update
   * @param array  $data    The data
   */
  private function updateLatest(
    string $table,
    array  $sources,
    array  $data
  ): void {

    if (count($data) === 0) {
      return;
    }

    foreach ($sources as $index => $source) {
      $this->connection->query(
        'INSERT INTO '
        . $table
        . ' (source,value,time) VALUES ("'
        . $source
        . '",'
        . $data[0][$index + 1]
        . ','
        . $data[0][0]
        . ')'
        . self::getOnDuplicateKeyUpdateClause(['value', 'time'])
      );
    }

  }

  /**
   * Updates past half-hours
   *
   * @param array $columns The columns to update
   * @param array $data    The data
   */
  private function updatePastHalfHours(array $columns, array $data): void {

    $rows = array_map(
      fn ($datum) => '(' . implode(',', $datum) . ')',
      $data
    );

    $this->connection->query(
      'INSERT INTO past_half_hours (`time`,'
      . implode(',', $columns)
      . ') VALUES '
      . implode(',', $rows)
      . self::getOnDuplicateKeyUpdateClause($columns)
    );

  }

  /** Finishes a database update */
  public function finishUpdate(): void {

    $this->deleteOldHalfHours();
    $this->updateLatestHalfHour();
    $this->updateWindRecords();

    $this->aggregateTimeSeries(
      'past_half_hours',
      'past_days',
      'DATE_SUB(DATE_SUB(time,INTERVAL MINUTE(time) MINUTE),INTERVAL HOUR(time) HOUR)'
    );

    $this->aggregateTimeSeries(
      'past_days',
      'past_weeks',
      'DATE_SUB(time,INTERVAL WEEKDAY(time) DAY)'
    );

    $this->aggregateTimeSeries(
      'past_days',
      'past_years',
      'DATE_SUB(DATE_SUB(time,INTERVAL (DAYOFMONTH(time) - 1) DAY),INTERVAL (MONTH(time) - 1) MONTH)'
    );

  }

  /**
   * Deletes old half-hourly data to reduce the size of the database. Data older
   * than the latest midnight more than four weeks ago is deleted; this ensures
   * that the remaining data represents complete days for aggregation.
   */
  private function deleteOldHalfHours(): void {
    $this->connection->query(
      'DELETE FROM past_half_hours WHERE time<"'
      . gmdate('Y-m-d H:i:s', gmmktime(0, 0, 0, gmdate('n'), gmdate('j') - 28))
      . '"'
    );
  }

  /**
   * Updates the latest half hour. Because the different data sources may update
   * at different times, the row for the latest half hour may be incomplete. To
   * work around this, we fill it with the most recent values; these will be
   * overwritten in a subsequent update when the final values are available.
   */
  private function updateLatestHalfHour(): void {

    $columns = [];
    $data    = ['"' . $this->getLatestHalfHour(). '"'];

    $latest = $this->connection->query(
      'SELECT source,value FROM latest_half_hours'
    );

    while ($row = $latest->fetch_assoc()) {
      $columns[] = $row['source'];
      $data[]    = $row['value'];
    }

    $this->updatePastHalfHours($columns, [$data]);

  }

  /** Updates the wind records */
  private function updateWindRecords(): void {

    $time = '"' . $this->getLatestHalfHour() . '"';

    $record = (float)$this->connection->query(
      'SELECT MAX(value) FROM wind_records'
    )->fetch_row()[0];

    $current = (float)$this->connection->query(
      'SELECT embedded_wind+wind FROM past_half_hours WHERE time=' . $time
    )->fetch_row()[0];

    if ($current > $record) {
      $this->connection->query(
        'INSERT INTO wind_records (value,time) VALUES ('
        . $current
        . ','
        . $time
        . ')'
      );
    }

  }

  /**
   * Aggregates a time series
   *
   * @param string $sourceTable      The source table
   * @param string $destinationTable The destination table
   * @param string $timeExpression   The expression to group times
   */
  private function aggregateTimeSeries(
    string $sourceTable,
    string $destinationTable,
    string $timeExpression
  ): void {

    $columns = self::getColumns();

    $this->connection->query(
      'INSERT INTO '
      . $destinationTable
      . ' (`time`,'
      . implode(',', $columns)
      . ') SELECT '
      . $timeExpression
      . ' AS aggregated_time,'
      . implode(
        ',',
        array_map(fn ($column) => 'AVG(' . $column . ')', $columns)
      )
      . ' FROM '
      . $sourceTable
      . ' GROUP BY aggregated_time'
      . self::getOnDuplicateKeyUpdateClause($columns)
    );

  }

  /** Returns the list of database columns */
  private static function getColumns(): array {
    return array_merge(
      Demand::KEYS,
      HalfHourGeneration::KEYS,
      Interconnectors::KEYS,
      Pricing::KEYS,
      Emissions::KEYS
    );
  }

  /**
   * Returns an ON DUPLICATE KEY UPDATE clause
   *
   * @param array $columns The columns
   */
  private static function getOnDuplicateKeyUpdateClause(
    array $columns
  ): string {
    return (
      ' ON DUPLICATE KEY UPDATE '
      . implode(
        ',',
        array_map(
          fn ($column) => $column . '=VALUES(' . $column . ')',
          $columns
        )
      )
    );
  }

  /**
   * Clears recorded errors for an action that completed successfully
   *
   * @param string $action The action
   */
  public function clearErrors(string $action): void {
    $this->connection->query(
      'DELETE FROM errors WHERE action="'
      . $this->connection->real_escape_string($action)
      . '"'
    );
  }

  /**
   * Returns the count of occurrences of an error
   *
   * @param string $action The action
   * @param string $error  The error
   */
  public function getErrorCount(string $action, string $error): int {

    $this->connection->query(
      'INSERT INTO errors (action,error,count) VALUES ("'
      . $this->connection->real_escape_string($action)
      . '","'
      . $this->connection->real_escape_string($error)
      . '",1) ON DUPLICATE KEY UPDATE count=count+1'
    );

    return (int)$this->connection->query(
      'SELECT count FROM errors WHERE action="'
      . $this->connection->real_escape_string($action)
      . '" AND error="'
      . $this->connection->real_escape_string($error)
      . '"'
    )->fetch_row()[0];

  }

}
