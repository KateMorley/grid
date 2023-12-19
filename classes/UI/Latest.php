<?php

// Outputs the latest data

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\Datum;
use KateMorley\Grid\State\Generation;
use KateMorley\Grid\State\Interconnectors;
use KateMorley\Grid\State\Map;
use KateMorley\Grid\State\Storage;
use KateMorley\Grid\State\Types;
use KateMorley\Grid\UI\PieChart;

class Latest {

  /**
   * Outputs the latest data
   *
   * @param Datum $datum The datum
   */
  public static function output(Datum $datum): void {

?>
      <div id="latest">
        <section id="generation">
          <h2>Generation</h2>
          <div class="pie-chart-container">
            <?php PieChart::output($datum); ?>
          </div>
          <div>
            Note: percentages are relative to demand, so will exceed 100% if power is being exported
          </div>
        </section>
<?php

    $generation      = $datum->getGeneration();
    $types           = $datum->getTypes();
    $interconnectors = $datum->getInterconnectors();
    $storage         = $datum->getStorage();
    $demand          = $datum->getTotal();

?>
        <section id="fossils">
          <h2><?= Value::formatPercentage($types->get(Types::FOSSILS) / $demand) ?>% fossil fuels</h2>
<?php

    self::outputTable($generation, [
      Generation::COAL => '<p>Coal-fired power stations burn coal to produce steam to drive a turbine. The world’s first coal-fired power station, at 57 Holborn Viaduct in London, started producing power on 12th January 1882, lighting the surrounding streets and local buildings through 968 incandescent lamps. Coal fuelled the industrial revolution, and by 1900 Great Britain was mining 250,000,000 tonnes of coal every year. </p><p>Burning coal causes carbon dioxide and other pollutants to be emitted, worsening the climate crisis and damaging human health. In 2001 the European Union issued the Large Combustion Plant Directive, obliging power stations to limit their emissions or close by 2015. Most coal-fired power stations in Great Britain closed in response, and those remaining operate only in times of high demand.</p><p>Great Britain first went a full day without any power generation from coal on 21st April 2015, followed by a full week between 1st May and 8th May 2019.</p>',
      Generation::GAS  => '<p>Gas-fired power stations burn natural gas to drive a turbine. Most gas-fired power stations use the excess heat from burning the gas to produce steam to drive a second turbine. Burning natural gas causes carbon dioxide and other pollutants to be emitted, worsening the climate crisis and damaging human health.</p><p>In 2001 the European Union issued the Large Combustion Plant Directive, obliging power stations to limit their emissions or close by 2015. Most coal-fired power stations in Great Britain closed in response, with gas-fired power stations taking over as the largest source of Great Britain’s power.</p>'
    ], $demand);

?>
        </section>
        <section id="renewables">
          <h2><?= Value::formatPercentage($types->get(Types::RENEWABLES) / $demand) ?>% renewables</h2>
<?php

    self::outputTable($generation, [
      Generation::SOLAR         => '<p>Solar panels generate power from the photovoltaic effect, where light falling on a material produces an electric current.</p><p>Despite Great Britain’s northerly latitude and frequently cloudy conditions, solar panels are still able to generate a useful amount of power. Rooftop solar panels on residential buildings have become increasingly popular as the price of solar panels has fallen.</p><p>Solar panels are connected to the local distribution network rather than the national transmission network, so their reported power generation is an estimate from National Grid ESO, based on weather conditions and observed transmission network demand.</p>',
      Generation::WIND          => '<p>Wind turbines generate power from the movement of air. Turbines can be located on land (onshore) or at sea (offshore). Offshore wind turbines benefit from higher and more consistent wind speeds.</p><p>Great Britain’s exposed position in the north-east Atlantic makes it one of the best locations in the world for wind power generation, and the shallow waters of the North Sea host several of the world’s largest offshore wind farms.</p><p>Onshore wind turbines in England and Wales (and some in Scotland) are connected to the local distribution network rather than the national transmission network, so their reported power generation is an estimate from National Grid ESO, based on weather conditions and observed transmission network demand. Offshore wind turbines (and many onshore wind turbines in Scotland) are connected to the transmission network and their power generation is measured directly.</p>',
      Generation::HYDROELECTRIC => '<p>Hydroelectric turbines generate power from the movement of water. Large hydroelectric systems use a reservoir held back by a dam to provide water at a controlled rate. Smaller hydroelectric systems located on rivers rely on the variable flow of the river.</p><p>Large hydroelectric systems make use of mountainous topography to contain their reservoirs, so most of Great Britain’s hydroelectric systems are located in Scotland, with a smaller number in Wales and a few in England.</p>'
    ], $demand);

?>
        </section>
        <section id="others">
          <h2><?= Value::formatPercentage($types->get(Types::OTHERS) / $demand) ?>% other sources</h2>
<?php

    self::outputTable($generation, [
      Generation::NUCLEAR => '<p>Nuclear power stations use the heat produced from the radioactive decay of uranium to produce steam to drive a turbine. The world’s first commercial nuclear power station, Calder Hall in Cumbria, started producing power on 27th August 1956.</p><p>The risk of accidents releasing radioactive material makes nuclear power controversial. Great Britain’s worst nuclear accident happened on 10th October 1957 when a reactor at Windscale (now known as Sellafield) in Cumbria caught fire. The accident is believed to have caused around 240 cases of cancer, about half of which were fatal. Decommissioning of the site is ongoing.</p><p>Great Britain’s nuclear programme has produced around 150,000 cubic metres of radioactive waste to date, most of which is stored in temporary facilities at Sellafield in Cumbria and Dounreay in Scotland. There are plans for a permanent disposal site deep underground, but it has been difficult to find a location suitable for storing radioactive waste for 100,000 years.</p>',
      Generation::BIOMASS => '<p>Biomass power stations burn plant material to produce steam to drive a turbine. Great Britain’s largest power station, Drax, is a former coal-fired power station converted to burn wood pellets.</p><p>Biomass power stations qualify for renewable energy subsidies (over £6bn so far in the case of Drax) because newly planted trees can absorb the carbon dioxide produced by burning wood from mature trees. However, this process can take decades, during which time the effects on atmospheric carbon dioxide levels are worse than those from burning fossil fuels.</p><p>Furthermore, Drax imports most of its wood pellets, and <a href=\'https://www.bbc.co.uk/news/science-environment-63089348\'>a BBC investigation</a> found that Drax was clearfelling irreplaceable old-growth forests in Canada.</p>'
    ], $demand);

?>
        </section>
        <section id="transfers">
          <h2><?= Value::formatPercentage($interconnectors->getTotal() / $demand) ?>% interconnectors</h2>
<?php

    self::outputTable($interconnectors, [
      Interconnectors::BELGIUM     => '<p>There is one link between Great Britain and Belgium:</p><p>Nemo Link is a 1<abbr>GW</abbr> link between Richborough in England and Zeebrugge in Belgium. It entered service in 2019.</p>',
      Interconnectors::FRANCE      => '<p>There are three links between Great Britain and France:</p><p>IFA (Interconnexion France–Angleterre) is a 2<abbr>GW</abbr> link between Sellindge in England and Bonningues-lès-Calais in France. It entered service in 1986.</p><p>IFA-2 (Interconnexion France–Angleterre 2) is a 1<abbr>GW</abbr> link between Warsash in England and Tourbe in France. It entered service in 2021.</p><p>ElecLink is a 1<abbr>GW</abbr> link between Folkestone in England and Peuplingues in France, running through the Channel Tunnel. It entered service in 2022.</p>',
      Interconnectors::IRELAND     => '<p>Since 2007 the Republic of Ireland and Northern Ireland have formed a single electricity market. There are two links between Great Britain and the island of Ireland:</p><p>Moyle is a 0.5<abbr>GW</abbr> link between Auchencrosh in Scotland and Ballycronan More in Northern Ireland. It entered service in 2001.</p><p>EWIC (the East–West Interconnector) is a 0.5<abbr>GW</abbr> link between Shotton in Wales and Rush North Beach in the Republic of Ireland. It entered service in 2012.</p>',
      Interconnectors::NETHERLANDS => '<p>There is one link between Great Britain and the Netherlands:</p><p>BritNed is a 1<abbr>GW</abbr> link between the Isle of Grain in England and Maasvlakte in the Netherlands. It entered service in 2011.</p>',
      Interconnectors::NORWAY      => '<p>There is one link between Great Britain and Norway:</p><p>NSL (the North Sea Link) is a 1.4<abbr>GW</abbr> link between Blyth in England and Kvilldal in Norway. It entered service in 2021.</p>'
    ], $demand, true);

?>
        </section>
        <section id="storage">
          <h2><?= Value::formatPercentage($storage->getTotal() / $demand) ?>% storage</h2>
<?php

    self::outputTable($storage, [
      Storage::PUMPED_STORAGE => '<p>Pumped storage systems use electricity when it is comparatively cheap to pump water from a lower reservoir into a higher reservoir. When electricity is comparatively expensive the water is released, driving turbines to produce power.</p><p>Negative values mean water is being pumped, while positive values mean power is being generated.</p>',
      'battery' => '<p>Battery storage systems use electricity when it is comparatively cheap to charge a group of batteries. When electricity is comparatively expensive the batteries are discharged.</p><p>Several battery storage systems are in operation in Great Britain, but full reporting is not yet available: reports include discharging but not charging. As this would lead to double counting, with power being reported both when originally generated and when discharged from battery storage systems, battery storage data is not yet shown on this site.</p>'
    ], $demand, true);

?>
        </section>
      </div>
<?php

  }

  /**
   * Outputs a table
   *
   * @param Map   $map         The map
   * @param array $keys        An array mapping keys to help
   * @param float $demand      The total demand
   * @param bool  $isTransfers Whether the table shows transfers
   */
  private static function outputTable(
    Map   $map,
    array $keys,
    float $demand,
    bool  $isTransfers = false
  ): void {

?>
          <table class="sources<?= ($isTransfers ? ' transfers' : '') ?>">
<?php

    foreach ($keys as $key => $help) {

      echo '            <tr><td class="';
      echo $key;
      echo '"><td>';

      if ($key === 'battery') {
        echo 'Battery storage';
      } else {
        echo $map::KEYS[$key];
      }

      echo ' <span data-help="';
      echo $help;
      echo '"></span></td><td>';

      if ($key === 'battery') {
        echo '—';
      } else {
        echo Value::formatPower($map->get($key));
      }

      echo '</td><td>';

      if ($key === 'battery') {
        echo '—';
      } else {
        echo Value::formatPercentage($map->get($key) / $demand);
      }

      echo "</td></tr>\n";

    }

?>
          </table>
<?php

  }

}
