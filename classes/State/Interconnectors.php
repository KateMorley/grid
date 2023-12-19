<?php

// Represents details of interconnectors

namespace KateMorley\Grid\State;

class Interconnectors extends Map {

  public const BELGIUM     = 'belgium';
  public const DENMARK     = 'denmark';
  public const FRANCE      = 'france';
  public const IRELAND     = 'ireland';
  public const NETHERLANDS = 'netherlands';
  public const NORWAY      = 'norway';

  public const KEYS = [
    self::BELGIUM         => 'Belgium',
    self::DENMARK         => 'Denmark',
    self::FRANCE          => 'France',
    self::IRELAND         => 'Ireland',
    self::NETHERLANDS     => 'Netherlands',
    self::NORWAY          => 'Norway'
  ];

  protected const KEY_COMPONENTS = [
    self::BELGIUM     => ['nemo'],
    self::DENMARK     => ['viking'],
    self::FRANCE      => ['ifa', 'ifa2', 'eleclink'],
    self::IRELAND     => ['moyle', 'ewic'],
    self::NETHERLANDS => ['britned'],
    self::NORWAY      => ['nsl']
  ];

}
