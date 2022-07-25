<h1>Evolv Php Sdk</h1>

<strong>Vocabulary</strong>

<strong>Participant:</strong> The end user of the application, the individual who's actions are being recorded in the experiment.

<strong>Allocation:</strong>  The set of configurations that have been given to the participant, the values that are being experimented against.

<h2>Install Php-Sdk through composer:</h2>

<code>https://packagist.org/packages/sdk-php/evolv-sdk</code>

<h2>Install</h2>

```php
  <?php
  composer install
```
<h2>Documentation Build</h2>

```php
  <?php
  composer docs
```

<h2>Tests Build</h2>

```php
  <?php
  composer test
```

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

  $client = new EvolvClient($environment, $endpoint);
  $client->initialize($uid);
```

<h2>About Evolv and the Ascend Product</h2>

Evolv Delivers Autonomous Optimization Across Web & Mobile.

You can find out more by visiting: <a href="https://www.evolv.ai/">https://www.evolv.ai/</a>
