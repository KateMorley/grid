<?php

// Represents details of power generation

namespace KateMorley\Grid\State;

class Generation extends Map {

  public const COAL          = 'coal';
  public const GAS           = 'gas';
  public const SOLAR         = 'solar';
  public const WIND          = 'wind';
  public const HYDROELECTRIC = 'hydro';
  public const NUCLEAR       = 'nuclear';
  public const BIOMASS       = 'biomass';

  public const KEYS = [
    self::COAL          => 'Coal',
    self::GAS           => 'Gas',
    self::SOLAR         => 'Solar',
    self::WIND          => 'Wind',
    self::HYDROELECTRIC => 'Hydroelectric',
    self::NUCLEAR       => 'Nuclear',
    self::BIOMASS       => 'Biomass'
  ];

  protected const KEY_COMPONENTS = [
    self::COAL          => ['coal'],
    self::GAS           => ['ocgt', 'ccgt'],
    self::SOLAR         => ['embedded_solar'],
    self::WIND          => ['embedded_wind', 'wind'],
    self::HYDROELECTRIC => ['hydro'],
    self::NUCLEAR       => ['nuclear'],
    self::BIOMASS       => ['biomass']
  ];

}
