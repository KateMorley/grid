<?php

// Updates the site

use KateMorley\Grid\Database;
use KateMorley\Grid\Environment;
use KateMorley\Grid\Data\DataException;
use KateMorley\Grid\Data\Demand;
use KateMorley\Grid\Data\Emissions;
use KateMorley\Grid\Data\Generation;
use KateMorley\Grid\Data\Pricing;
use KateMorley\Grid\Data\Visits;
use KateMorley\Grid\UI\Favicon;
use KateMorley\Grid\UI\UI;

spl_autoload_register(function ($class) {
  require_once(
    __DIR__
    . '/classes/'
    . strtr(substr($class, 16), '\\', '/')
    . '.php'
  );
});

Environment::load(__DIR__ . '/.env');

$database = new Database();

foreach ([
  'Updating generation… ' => function ($database) {
    Generation::update($database);
  },

  'Updating emissions…  ' => function ($database) {
    Emissions::update($database);
  },

  'Updating pricing…    ' => function ($database) {
    Pricing::update($database);
  },

  // demand must be updated after other half-hourly data to exclude future data
  'Updating demand…     ' => function ($database) {
    Demand::update($database);
  },

  'Updating visits…     ' => function ($database) {
    Visits::update($database);
  },

  'Finishing update…    ' => function ($database) {
    $database->finishUpdate();
  },

  'Outputting files…    ' => function ($database) {
    $state = $database->getState();

    ob_start();
    UI::output($state);
    file_put_contents(__DIR__ . '/public/index.html', ob_get_clean(), LOCK_EX);

    file_put_contents(
      __DIR__ . '/public/favicon.svg',
      Favicon::create($state->latest->types),
      LOCK_EX
    );
  }
] as $action => $callback) {
  echo $action;

  $start = microtime(true);

  try {
    $callback($database);

    echo 'OK';

    $database->clearErrors($action);
  } catch (DataException $e) {
    $error = $e->getMessage();
    echo 'ERROR: ' . $error;

    if (
      $database->getErrorCount($action, $error)
      >= (int)getenv('ERROR_REPORTING_THRESHOLD')
    ) {
      $database->clearErrors($action);

      if ((int)getenv('ERROR_REPORTING_THRESHOLD') > 0) {
        trigger_error(trim($action) . ' ' . $error);
      }
    }
  }

  echo ' (' . sprintf('%0.3f', microtime(true) - $start) . " seconds)\n";
}
