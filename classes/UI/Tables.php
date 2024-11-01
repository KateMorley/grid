<?php

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\Datum;

/** Outputs tables of sources. */
class Tables {
  /**
   * Outputs tables of sources.
   *
   * @param Datum $datum The datum
   */
  public static function output(Datum $datum): void {
    $demand = $datum->getTotal();

?>
            <h3>Generation by type</h3>
            <table class="sources">
<?php

    $map = $datum->types;
    foreach ($map::KEYS as $key => $description) {
      self::outputTableRow($key, $description, $map->get($key), $demand, true);
    }

?>
            </table>
            <h3>Generation by source</h3>
            <table class="sources">
<?php

    $map = $datum->generation;
    foreach ($map::KEYS as $key => $description) {
      self::outputTableRow($key, $description, $map->get($key), $demand);
    }

?>
            </table>
            <h3>Interconnectors</h3>
            <table class="sources transfers">
<?php

    $map = $datum->interconnectors;
    foreach ($map::KEYS as $key => $description) {
      self::outputTableRow($key, $description, $map->get($key), $demand);
    }

?>
            </table>
            <h3>Storage</h3>
            <table class="sources transfers">
<?php

    $map = $datum->storage;
    foreach ($map::KEYS as $key => $description) {
      self::outputTableRow($key, $description, $map->get($key), $demand);
    }

?>
            </table>
<?php
 }

  /**
   * Outputs a table row.
   *
   * @param string $source      The source
   * @param string $description The description
   * @param float  $power       The power
   * @param float  $demand      The total demand
   * @param bool   $isTotal     Whether this is a power total
   */
  private static function outputTableRow(
    string $source,
    string $description,
    float  $power,
    float  $demand,
    bool   $isTotal = false
  ): void {
?>
              <tr><td class="<?= $source ?>"></td><td><?= $description ?></td><td><?= ($isTotal ? Value::formatTotalPower($power) : Value::formatPower($power)) ?></td><td><?= Value::formatPercentage($power / $demand) ?></td></tr>
<?php
  }
}
