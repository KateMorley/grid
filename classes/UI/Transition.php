<?php

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\State;

/** Outputs the energy transition section. */
class Transition {
  /**
   * Outputs the energy transition section.
   *
   * @param State $state The state
   */
  public static function output(State $state): void {
?>
        <section id="transition">
          <h2>
            The energy transition
          </h2>
          <p>
            Between 12th January 1882, when the world’s first coal-fired power station opened at 57 Holborn Viaduct in London, and 30th September 2024, when Great Britain’s last coal-fired power station closed, the country burnt 4.6 billion tonnes of coal, <a href="https://interactive.carbonbrief.org/coal-phaseout-UK/">emitting 10.6 billion tonnes of carbon dioxide</a>.
          </p>
          <p>
            In 2001 the European Union updated the Large Combustion Plant Directive, obliging power stations to limit their emissions or close by 2015. Most older coal-fired power stations in Great Britain closed in response. The government’s introduction of a carbon price floor in 2013, and its subsequent increase in 2015, made coal uncompetitive with gas, which rapidly replaced coal in the country’s energy mix.
          </p>
          <p>
            At the same time, renewable power generation was steadily rising. Great Britain’s exposed position in the north-east Atlantic makes it one of the best locations in the world for wind power, and the shallow waters of the North Sea host several of the world’s largest offshore wind farms.
          </p>
          <p>
            New wind power records are set regularly, and between <?= date('g:ia', $state->windRecord->time - 1800) ?> and <?= date('g:ia', $state->windRecord->time) ?> on <?= date('jS F Y', $state->windRecord->time) ?> British wind farms averaged a record <?= Value::formatPower($state->windRecord->power) ?>GW of generation.
          </p>
          <table class="wind-milestones">
            <tr><th>Power</th><th>Date first achieved</th></tr>
<?php

    foreach ($state->windMilestones as $power => $time) {
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
