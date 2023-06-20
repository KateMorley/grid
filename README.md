# National Grid: Live

This repository contains the source code for [National Grid: Live](https://grid.iamkate.com/).

## Installation

Any officially supported versions of PHP and MySQL can be used.

### Database

Create a database and a user with `SELECT`, `INSERT`, `UPDATE`, and `DELETE` privileges, and import `grid.sql` into the database.

Copy `configuration.php.example` to `configuration.php` and enter the appropriate data connection settings into the PHP constants.

### Web server

Configure the server to serve the contents of the `public` directory. Note that this directory contains only static files, so the web server does not need to support PHP.

### Cron

Set up a cron job to execute the `update.php` script (using the [PHP CLI SAPI](https://www.php.net/manual/en/features.commandline.usage.php)) every five minutes. The cron job must run as a user with write access to `public/favicon.svg` and `public/index.html`.

The script outputs details of the update process to standard output, and details of errors to standard error. An error with an individual data source does not abort the rest of the update process.

### Fonts

The CSS refers to `proza-light.woff2` and `proza-regular.woff2`. These are commercial fonts, so are not included in this repository. Licences for [Proza](http://bureauroffa.com/about-proza) can be purchased from [Bureau Roffa](http://bureauroffa.com/). Alterenatively, the simplified free version [Proza Libre](http://bureauroffa.com/about-proza-libre) can be used instead.

## Codebase structure

PHP classes can be found in the `classes` directory. The [Database](classes/Database.php) class directly within this directory is responsible for all database access. The other classes are divided into three namespaces:

The [Data](classes/Data) namespace contains classes for reading data from the various data sources, as documented further below.

The [State](classes/State) namespace contains classes representing the data needed to output the user interface. The [State](classes/State/State.php) class is the overall container; an instance of this class is returned by the `getState()` method of a `Database` instance.

The [UI](classes/UI) namespace contains classes that output the user interface. The [UI](classes/UI/UI.php) class has overall responsibility for outputting the HTML, while the [Favicon](classes/UI/Favicon.php) class outputs the dynamically-updated favicon.

## Data sources

### [Balancing Mechanism Reporting Service](https://www.bmreports.com/)

This API, developed by Elexon, reports power generation connected to the national transmission network, interconnector imports and exports, and pricing.

Data is available in XML format at 30-minute or 5-minute granularity. Only power generation (and not power consumption for pumping) is reported for pumped storage. Only the separate 30-minute interconnector data reports negative power generation for exports.

PHP classes:  [FiveMinuteGeneration](classes/Data/FiveMinuteGeneration.php),  [HalfHourGeneration](classes/Data/HalfHourGeneration.php),  [Interconnectors](classes/Data/Interconnectors.php),  [Pricing](classes/Data/Pricing.php)

### [Carbon Intensity API](https://carbonintensity.org.uk/)

This API, developed by National Grid ESO and the University Of Oxford Department Of Computer Science, estimates the carbon intensity of electricity generation in grams of carbon dioxide per kilowatt-hour.

Data is available in JSON format at 30-minute granularity. Estimates may be retrospectively updated. Data is derived from the Balancing Mechanism Reporting System so outages occur whenever the BMRS has outages.

PHP class: [Emissions](classes/Data/Emissions.php)

### [National Grid ESO Data Portal](https://data.nationalgrideso.com/)

This API, developed by National Grid ESO, estimates power generation from embedded solar and wind (generation connected to the local distribution network rather than the national transmission network) and power consumption by pumped storage pumping.

Data is available in CSV format at 30-minute granularity. Estimates may be retrospectively updated.

PHP class: [Demand](classes/Data/Demand.php)

## Future plans

Battery storage data isn’t yet shown. The Balancing Mechanism Reporting Service includes several battery storage systems in its ‘other’ category, but only discharging is reported, in the same way as with pumped storage systems. While the National Grid ESO Data Portal offers an API reporting on pumped storage pumping, they don’t yet report on battery charging.

Elexon are in the process of testing a new API that will replace the Balancing Mechanism Reporting Service. Once this API has been finalised I’ll update the code to use it.

I’m not currently planning any other major changes. I believe it’s better for a project like this to have a limited scope and a concise interface serving the general public than to attempt to offer specialised analysis for energy industry experts.
