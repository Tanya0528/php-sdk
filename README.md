<h1>Evolv Php Sdk</h1>

<strong>Vocabulary</strong>

<strong>Participant:</strong> The end user of the application, the individual who's actions are being recorded in the experiment.

<strong>Allocation:</strong>  The set of configurations that have been given to the participant, the values that are being experimented against.

<h2>Installation</h2>
<hr>

Install Lodash-PHP through composer:

<code>https://packagist.org/packages/sdk-php/evolv-sdk</code>

<h2>Usage</h2>
<hr>

<code>
  <?php

  adeclare (strict_types=1);

  use  App\EvolvClient;

  require_once __DIR__ . '/App/EvolvClient.php';

  require 'vendor/autoload.php';

  $client = new EvolvClient($environment, $uid, $endpoint);
  ?>
</code>
