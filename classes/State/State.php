<?php

// Represents the UI state

namespace KateMorley\Grid\State;

class State {

  private int    $time;
  private Datum  $latest;
  private Datum  $pastDay;
  private Datum  $pastWeek;
  private Datum  $pastYear;
  private Datum  $allTime;
  private array  $pastDaySeries;
  private array  $pastWeekSeries;
  private array  $pastYearSeries;
  private array  $allTimeSeries;
  private Record $windRecord;
  private array  $windMilestones;

  /**
   * Constructs a new instance
   *
   * @param int    $time           The time of the latest data
   * @param Datum  $latest         The latest datum
   * @param Datum  $pastDay        The past day's datum
   * @param Datum  $pastWeek       The past week's datum
   * @param Datum  $pastYear       The past year's datum
   * @param Datum  $allTime        The all-time datum
   * @param array  $pastDaySeries  The past day series
   * @param array  $pastWeekSeries The past week series
   * @param array  $pastYearSeries The past year series
   * @param array  $allTimeSeries  The all-time series
   * @param Record $windRecord     The wind power generation record
   * @param array  $windMilestones The wind power generation milestones
   */
  public function __construct(
    int    $time,
    Datum  $latest,
    Datum  $pastDay,
    Datum  $pastWeek,
    Datum  $pastYear,
    Datum  $allTime,
    array  $pastDaySeries,
    array  $pastWeekSeries,
    array  $pastYearSeries,
    array  $allTimeSeries,
    Record $windRecord,
    array  $windMilestones
  ) {
    $this->time           = $time;
    $this->latest         = $latest;
    $this->pastDay        = $pastDay;
    $this->pastWeek       = $pastWeek;
    $this->pastYear       = $pastYear;
    $this->allTime        = $allTime;
    $this->pastDaySeries  = $pastDaySeries;
    $this->pastWeekSeries = $pastWeekSeries;
    $this->pastYearSeries = $pastYearSeries;
    $this->allTimeSeries  = $allTimeSeries;
    $this->windRecord     = $windRecord;
    $this->windMilestones = $windMilestones;
  }

  /** Returns the time of the state */
  public function getTime(): int {
    return $this->time;
  }

  /** Returns the latest datum */
  public function getLatest(): Datum {
    return $this->latest;
  }

  /** Returns the past day's datum */
  public function getPastDay(): Datum {
    return $this->pastDay;
  }

  /** Returns the past week's datum */
  public function getPastWeek(): Datum {
    return $this->pastWeek;
  }

  /** Returns the past year's datum */
  public function getPastYear(): Datum {
    return $this->pastYear;
  }

  /** Returns the all-time datum */
  public function getAllTime(): Datum {
    return $this->allTime;
  }

  /** Returns the series for the past day */
  public function getPastDaySeries(): array {
    return $this->pastDaySeries;
  }

  /** Returns the series for the past week */
  public function getPastWeekSeries(): array {
    return $this->pastWeekSeries;
  }

  /** Returns the series for the past year */
  public function getPastYearSeries(): array {
    return $this->pastYearSeries;
  }

  /** Returns the series for all time */
  public function getAllTimeSeries(): array {
    return $this->allTimeSeries;
  }

  /** Returns the wind power generation record */
  public function getWindRecord(): Record {
    return $this->windRecord;
  }

  /** Returns the wind power generation milestones */
  public function getWindMilestones(): array {
    return $this->windMilestones;
  }

}
