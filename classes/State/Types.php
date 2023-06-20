<?php

// Represents details of power generation by type

namespace KateRoseMorley\Grid\State;

class Types extends Map {

  public const FOSSILS    = 'fossils';
  public const RENEWABLES = 'renewables';
  public const OTHERS     = 'others';

  public const KEYS = [
    self::FOSSILS    => 'Fossil fuels',
    self::RENEWABLES => 'Renewables',
    self::OTHERS     => 'Other sources'
  ];

  protected const KEY_COMPONENTS = [
    self::FOSSILS    => ['coal', 'ocgt', 'ccgt'],
    self::RENEWABLES => ['embedded_solar', 'embedded_wind', 'wind', 'hydro'],
    self::OTHERS     => ['nuclear', 'biomass']
  ];

}
