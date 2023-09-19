<?php

// Outputs the user interface

namespace KateMorley\Grid\UI;

use KateMorley\Grid\State\State;

class UI {

  /**
   * Outputs the user interface
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
    <meta name="theme-color" content="#696">
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
      <a href="https://iamkate.com/"><svg viewbox="0 0 30 30"><title>Home</title><path d="m13,3 13,13h-4v11h-6v-9h-6v9h-6v-11h-4"/></svg></a>
      <nav>
        <ul>
          <li>
            <a href="https://iamkate.com/code/">Code</a>
          </li>
          <li>
            <a href="https://iamkate.com/data/" class="section">Data</a>
          </li>
          <li>
            <a href="https://iamkate.com/games/">Games</a>
          </li>
          <li>
            <a href="https://iamkate.com/ideas/">Ideas</a>
          </li>
        </ul>
      </nav>
      <a href="https://fosstodon.org/@kate"><svg viewBox="0 0 740 790"><title>Kate Morley on Mastodon</title><path d="M737 174C726 91 652 24 564 12C549 10 494 2 364 2H363C233 2 205 10 191 12C106 24 28 83 9 168C0 210-1 256 1 299C3 360 4 420 9 481C13 521 20 561 29 601C47 673 120 734 191 759C267 784 349 789 427 771C436 769 444 767 453 764C472 758 494 751 511 740C511 740 511 739 511 739C511 739 511 738 511 738V679C511 679 511 679 511 679C511 678 511 678 511 678C510 678 510 678 510 678C51 678 509 678 509 678C459 690 407 696 356 695C267 695 243 654 236 636C231 621 227 606 226 590C226 589 226 589 226 589C226 589 226 589 226 588C227 588 227 588 227 588C227 588 228 588 228 588C277 600 328 606 379 606C391 606 403 606 415 605C467 604 520 601 571 592C572 591 573 591 574 591C654 576 729 529 737 409C737 404 738 359 738 355C738 338 743 237 737 174ZM615 473H532V271C532 229 513 207 477 207C437 207 417 232 417 283V394H334V283C334 232 314 207 274 207C238 207 220 229 220 271V473H136V265C136 222 147 189 169 164C192 139 221 126 258 126C301 126 333 142 355 174L376 209L396 174C418 142 450 126 493 126C520 126 559 139 582 164C604 189 615 222 615 265L615 473Z"/></svg></a>
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
<?php Wind::output($state); ?>
        <section>
          <h2>
            About this site
          </h2>
          <p>
            This site is an open source project by <a href="https://iamkate.com/">Kate Morley</a>. I’ve published <a href="https://github.com/KateMorley/grid">the code on GitHub</a> under the terms of the <a href="https://creativecommons.org/publicdomain/zero/1.0/legalcode">Creative Commons CC0 1.0 Universal Legal Code</a>. This means I’ve waived all copyright and related rights to the extent possible under law, with the intention of dedicating the code to the public domain. You can use and adapt it without attribution.
          </p>
          <p>
            If you’d like to thank me for the time I’ve spent working on this project, or help me cover the costs of hosting a site that receives over 8,000,000 visits each year, <a href="https://ko-fi.com/katemorley">I do accept donations</a>.
          </p>
          <p>
            The data comes from <a href="https://data.nationalgrideso.com/">National Grid ESO’s Data Portal</a>, Elexon’s <a href="https://www.bmreports.com/">Balancing Mechanism Reporting Service</a>, and the <a href="https://carbonintensity.org.uk/">Carbon Intensity API</a> (a project by National Grid ESO and the University Of Oxford Department Of Computer Science).
          </p>
        </section>
      </div>
    </main>
    <footer>
      <a href="https://iamkate.com/ideas/free-content/">Free content</a> from Kate Morley
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