<?php

// Updates the site

use KateMorley\Grid\Database;
use KateMorley\Grid\Data\DataException;
use KateMorley\Grid\Data\Demand;
use KateMorley\Grid\Data\Emissions;
use KateMorley\Grid\Data\FiveMinuteGeneration;
use KateMorley\Grid\Data\HalfHourGeneration;
use KateMorley\Grid\Data\Interconnectors;
use KateMorley\Grid\Data\Pricing;
use KateMorley\Grid\UI\Favicon;
use KateMorley\Grid\UI\UI;

require_once __DIR__ . '/configuration.php';

spl_autoload_register(function ($class) {
  require_once(
    __DIR__
    . '/classes/'
    . strtr(substr($class, 16), '\\', '/')
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

    if ($database->getErrorCount($action, $error) >= ERROR_REPORTING_THRESHOLD) {
      $database->clearErrors($action);
      trigger_error(trim($action) . ' ' . $error);
    }

  }

  echo ' (' . sprintf('%0.3f', microtime(true) - $start) . " seconds)\n";

}
