<h1>Evolv Php Sdk</h1>

<strong>Vocabulary</strong>

<strong>Participant:</strong> The end user of the application, the individual who's actions are being recorded in the experiment.

<strong>Allocation:</strong>  The set of configurations that have been given to the participant, the values that are being experimented against.

<h2>Installation</h2>

Install Lodash-PHP through composer:

<code>https://packagist.org/packages/sdk-php/evolv-sdk</code>

<h2>Usage</h2>

```php
  <?php

  declare (strict_types=1);

  use  App\EvolvClient;

  require_once __DIR__ . '/App/EvolvClient.php';

  require 'vendor/autoload.php';
```

<h2>Client Initialization</h2>

```php
  <?php

  $client = new EvolvClient($environment, $uid, $endpoint);
```

<h2>About Evolv and the Ascend Product</h2>

Evolv Delivers Autonomous Optimization Across Web & Mobile.

You can find out more by visiting: <a href="https://www.evolv.ai/">https://www.evolv.ai/</a>
