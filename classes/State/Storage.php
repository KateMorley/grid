<?php

namespace KateMorley\Grid\State;

/** Represents details of storage. */
class Storage extends Map {
  public const PUMPED_STORAGE = 'pumped';

  public const KEYS = [
    self::PUMPED_STORAGE => 'Pumped storage'
  ];

  protected const KEY_COMPONENTS = [
    self::PUMPED_STORAGE => ['pumped']
  ];
}
