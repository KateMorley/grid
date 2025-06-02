<?php

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\State;
use KateMorley\Grid\State\Datum;

/** Outputs the about section. */
class About {
  /**
   * Outputs the about section.
   *
   * @param State $state The state
   */
  public static function output(State $state): void {
?>
        <section>
          <h2>
            About this site
          </h2>
          <p>
            This site is an open source project by <a href="https://iamkate.com/">Kate Morley</a>. I’ve published <a href="https://github.com/KateMorley/grid">the code on GitHub</a> under the terms of the <a href="https://creativecommons.org/publicdomain/zero/1.0/legalcode">Creative Commons CC0 1.0 Universal Legal Code</a>. This means I’ve waived all copyright and related rights to the extent possible under law, with the intention of dedicating the code to the public domain. You can use and adapt it without attribution.
          </p>
          <p>
            If you’d like to thank me for the time I’ve spent working on this project, or help me cover the costs of hosting a site that received <?= number_format($state->yearlyVisits) ?> visits over the past year, <a href="https://ko-fi.com/katemorley">I do accept donations</a>.
          </p>
          <div class="visits-graph">
            <h3>Weekly visits</h3>
            <?php Graph::output($state->pastYearSeries, new Axes($state), Datum::VISITS, '', '', 13, 'd/m/Y', 0); ?>
          </div>
          <p>
            The data comes from the <a href="https://bmrs.elexon.co.uk/">Elexon Insights Solution</a>, the <a href="https://www.neso.energy/data-portal">National Energy System Operator Data Portal</a>, and the <a href="https://carbonintensity.org.uk/">Carbon Intensity API</a> (a project by the National Energy System Operator and the University Of Oxford Department Of Computer Science). <a href="https://www.elexon.co.uk/data/balancing-mechanism-reporting-agent/copyright-licence-bmrs-data/">Elexon’s licence</a> requires the following statement: Contains BMRS data © Elexon Limited copyright and database right <?= date('Y') ?>.
          </p>
        </section>
<?php
  }
}
