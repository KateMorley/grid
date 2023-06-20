<?php

// Outputs the wind power section

namespace KateRoseMorley\Grid\UI;

use KateRoseMorley\Grid\State\State;

class Wind {

  /**
   * Outputs the wind power section
   *
   * @param State $state The state
   */
  public static function output(State $state): void {

    $record = $state->getWindRecord();

?>
        <section>
          <h2>
            The rise of wind
          </h2>
          <p>
            Great Britain’s exposed position in the north-east Atlantic makes it one of the best locations in the world for wind power, and the shallow waters of the North Sea host several of the world’s largest offshore wind farms.
          </p>
          <p>
            New wind power records are set regularly, and between <?= date('g:ia', $record->getTime() - 1800) ?> and <?= date('g:ia', $record->getTime()) ?> on <?= date('jS F Y', $record->getTime()) ?> British wind farms averaged a record <?= Value::formatPower($record->getPower()) ?>GW of generation.
          </p>
          <table class="wind-milestones">
            <tr><th>Power</th><th>Date first achieved</th></tr>
<?php

    foreach ($state->getWindMilestones() as $power => $time) {
?>
            <tr><td><?= $power ?><abbr>GW</abbr></td><td><?= date('jS F Y', $time) ?></td></tr>
<?php
    }

?>
          </table>
        </section>
<?php

  }

}
