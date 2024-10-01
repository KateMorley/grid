<?php


namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\State;

/** Outputs the user interface. */
class UI {
  /**
   * Outputs the user interface.
   *
   * @param State $state The state
   */
  public static function output(State $state): void {
    $time   = $state->getTime();
    $latest = $state->getLatest();

?>
<!DOCTYPE html>
<html lang="en-gb">
  <head>
    <title>
      National Grid: Live
    </title>
    <meta name="description" content="Shows the live status of Great Britain’s electric power transmission network">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="National Grid: Live">
    <meta name="twitter:description" content="Shows the live status of Great Britain’s electric power transmission network">
    <meta name="twitter:image" content="https://grid.iamkate.com/banner.png">
    <meta name="twitter:site" content="@KateRoseMorley">
    <meta property="og:url" content="https://grid.iamkate.com/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="National Grid: Live">
    <meta property="og:image" content="https://grid.iamkate.com/banner.png">
    <link rel="preload" href="proza-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="proza-light.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="grid.css?<?= filemtime(__DIR__ . '/../../public/grid.css') ?>" type="text/css">
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="icon" href="favicon.svg?<?= floor(time() / 300) ?>" type="image/svg+xml">
    <script src="grid.js?<?= filemtime(__DIR__ . '/../../public/grid.js') ?>" defer></script>
  </head>
  <body>
    <header>
      <nav>
        <a href="https://iamkate.com/"><svg viewBox="0 0 160 256"><title>Home</title><path d="m8,256 30-3 12-77c5-32 31-53 48-53s19 11 19 16-3 37-69 37l-3 22c22 2 20 58 70 58 15 0 29-8 29-28s-14-25-16-26l-5 7c2 1 10 6 10 16s-6 16-13 16c-25 0-35-44-45-51 39-3 69-26 69-54s-20-33-39-33-41 14-46 19l7-44c8-53 30-58 42-58s12 10 12 13c0 24-50 50-88 50-15 0-20-6-20-16s12-19 14-20l-5-7c-2 1-21 10-21 30s14 28 34 28c46 0 110-33 110-70 0-20-15-28-34-28s-62 14-72 78z"/></svg></a>
        <div>
          <a href="https://iamkate.com/code/">Code</a>
          <a href="https://iamkate.com/data/" class="section">Data</a>
        </div>
        <div>
          <a href="https://iamkate.com/games/">Games</a>
          <a href="https://iamkate.com/ideas/">Ideas</a>
        </div>
      </nav>
    </header>
    <main>
      <section id="introduction">
        <h1>
          National Grid: Live
        </h1>
        <p>
          The National Grid is the electric power transmission network for Great Britain
        </p>
      </section>
      <div id="status" class="columns">
        <section>
<?php Status::output($latest, date('g:i', $time), date('a', $time), true); ?>
        </section>
        <section>
<?php Equation::output($latest, true); ?>
        </section>
      </div>
<?php Latest::output($latest); ?>
<?php Tabs::output($state); ?>
      <div class="columns">
<?php Transition::output($state); ?>
<?php About::output($state); ?>
      </div>
    </main>
    <footer>
      <img src="https://iamkate.com/avatar-128.webp" width="128" height="128" sizes="128px" srcset="https://iamkate.com/avatar-128.webp 128w,https://iamkate.com/avatar-256.webp 256w" alt="" loading="lazy">
      <div>
        <span>This site is <a href="https://iamkate.com/ideas/free-content/">free content</a> from Kate Morley.</span>
        <span>Follow me on:</span>
      </div>
      <nav>
        <a rel="me" href="https://fosstodon.org/@kate">Mastodon</a>
        <a href="https://github.com/KateMorley">GitHub</a>
        <a href="https://www.instagram.com/katerosemorley/">Instagram</a>
      </nav>
    </footer>
    <dialog>
      <h2></h2>
      <form method="dialog"><button><svg viewBox="0 0 30 30"><path d="M6,6 24,24"/><path d="M6,24 24,6"/></svg></button></form>
      <div></div>
    </dialog>
  </body>
</html>
<?php
  }
}
