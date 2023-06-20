<?php

// Updates the site

use KateRoseMorley\Grid\Database;
use KateRoseMorley\Grid\Data\DataException;
use KateRoseMorley\Grid\Data\Demand;
use KateRoseMorley\Grid\Data\Emissions;
use KateRoseMorley\Grid\Data\FiveMinuteGeneration;
use KateRoseMorley\Grid\Data\HalfHourGeneration;
use KateRoseMorley\Grid\Data\Interconnectors;
use KateRoseMorley\Grid\Data\Pricing;
use KateRoseMorley\Grid\UI\Favicon;
use KateRoseMorley\Grid\UI\UI;

spl_autoload_register(function ($class) {
  require_once(
    __DIR__
    . '/classes/'
    . strtr(substr($class, 20), '\\', '/')
    . '.php'
  );
});

$database = new Database();

foreach ([

  'Updating emissions…              ' => function ($database) {
    Emissions::update($database);
  },

  'Updating half-hour generation…   ' => function ($database) {
    HalfHourGeneration::update($database);
  },

  'Updating interconnectors…        ' => function ($database) {
    Interconnectors::update($database);
  },

  'Updating pricing…                ' => function ($database) {
    Pricing::update($database);
  },

  // demand must be updated after other half-hourly data to exclude future data
  'Updating demand…                 ' => function ($database) {
    Demand::update($database);
  },

  'Updating five-minute generation… ' => function ($database) {
    FiveMinuteGeneration::update($database);
  },

  'Finishing update…                ' => function ($database) {
    $database->finishUpdate();
  },

  'Outputting files…                ' => function ($database) {

    $state = $database->getState();

    ob_start();
    UI::output($state);
    file_put_contents(__DIR__ . '/public/index.html', ob_get_clean(), LOCK_EX);

    file_put_contents(
      __DIR__ . '/public/favicon.svg',
      Favicon::create($state->getLatest()->getTypes()),
      LOCK_EX
    );

  }

] as $description => $callback) {

  echo $description;

  $start = microtime(true);

  try {
    $callback($database);
    echo 'OK';
  } catch (DataException $e) {
    echo 'ERROR: ' . $e->getMessage();
    trigger_error(trim($description) . ' ' . $e->getMessage());
  }

  echo ' (' . sprintf('%0.3f', microtime(true) - $start) . " seconds)\n";

}
