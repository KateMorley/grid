<?php

namespace KateMorley\Grid\State;

/** Represents the UI state. */
class State {
  /**
   * Constructs a new instance.
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
    public readonly int    $time,
    public readonly Datum  $latest,
    public readonly Datum  $pastDay,
    public readonly Datum  $pastWeek,
    public readonly Datum  $pastYear,
    public readonly Datum  $allTime,
    public readonly array  $pastDaySeries,
    public readonly array  $pastWeekSeries,
    public readonly array  $pastYearSeries,
    public readonly array  $allTimeSeries,
    public readonly Record $windRecord,
    public readonly array  $windMilestones
  ) {
  }
}
