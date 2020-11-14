# Feature Switches

This library provides the most basic implementation of so called feature switches(aka feature toggles/feature flags).

## Setup:

Install the library
```bash
composer require dorumd/feature-switches
```
Configure your feature switches(example):
```yaml
# config/feature-switches.yaml
feature_switches:
  SEND_ORDER_EMAILS:
     enabled: true
  SEND_MERCHANT_NOTIFICATIONS:
     enabled: false
```

## Usage:
```php
<?php
// index.php

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;

$configPath = __DIR__ . '/config/feature-switches.yaml';
$featureSwitchesStorage = new FileFeatureSwitchesStorage($configPath);

$featureSwitches = new BasicFeatureSwitches($featureSwitchesStorage);

$featureSwitches->featureIsEnabled('SEND_MERCHANT_NOTIFICATIONS');
$featureSwitches->featureIsEnabled('SEND_ORDER_EMAILS');
```


## Development:
*Dependencies: Docker*

```bash
# Build the docker image with all deps.
make build
# Run the tests and code style checker
make validate
```

To fix code formatting errors:
```bash
make fix-code-format
```
