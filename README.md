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

## Basic usage:
```php
<?php
// index.php

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;

$configPath = __DIR__ . '/config/feature-switches.yaml';
$featureSwitchesStorage = new FileFeatureSwitchesStorage($configPath);

$featureSwitches = new BasicFeatureSwitches($featureSwitchesStorage);

echo $featureSwitches->featureIsEnabled('SEND_MERCHANT_NOTIFICATIONS');
echo $featureSwitches->featureIsEnabled('SEND_ORDER_EMAILS');
```

## Supported feature switches:

* Basic(default type)

    Basic feature switch solely relies on enabled flag.
    Configuration reference:
    ```yaml
    feature_switches:
      # By default, it will set type to basic:
      SEND_ORDER_EMAILS:
         enabled: true
      # Explicit configuration:
      SEND_NOTIFICATIONS:
         enabled: true
         type: \Dorumd\FeatureSwitches\Domain\BasicFeatureSwitch 
    ```

* Percentage split

    Percentage split feature switch can be used for the gradual rollout, or A/B tests.
    Configuration reference:
    ```yaml
    feature_switches:
      SPLIT_TEST_1:
        enabled: true
        type: \Dorumd\FeatureSwitches\Domain\PercentageSplitFeatureSwitch
        configuration:
          percentage: 50 
    ```
    Note: The accuracy of its percentage split is limited by randomness of php `rand(1, 100)`. With smaller samples(N < 1000), higher deviation is expected. 

* More to come soon...

## Storage

This library comes with 3 storage mechanisms embedded:
* file(yaml format)
  
  ```yaml
  // config/feature_switches.yaml
  feature_switches:
    SEND_ORDER_EMAILS:
      enabled: true
  ```
  
  ```php
  <?php
  // index.php
  use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
  use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;

  $configPath = __DIR__ . '/config/feature-switches.yaml';
  $featureSwitchesStorage = new FileFeatureSwitchesStorage($configPath);
  
  $featureSwitches = new BasicFeatureSwitches($featureSwitchesStorage);
  echo $featureSwitches->featureIsEnabled('SEND_ORDER_EMAILS');
  ```
  
* in memory

  ```php
  <?php
  // index.php
  use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
  use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitch;
  use Dorumd\FeatureSwitches\Domain\PercentageSplitFeatureSwitch;
  use Dorumd\FeatureSwitches\Infrastructure\InMemoryFeatureSwitchesStorage;

  $featureSwitchesStorage = new InMemoryFeatureSwitchesStorage();
  $featureSwitchesStorage->store(new BasicFeatureSwitch('SEND_ORDER_EMAILS', false));
  $featureSwitchesStorage->store(new BasicFeatureSwitch('SEND_NOTIFICATION', true));
  $featureSwitchesStorage->store(
      new PercentageSplitFeatureSwitch('DISPLAY_SURVEY', true, ['percentage' => 50])
  );
  
  $featureSwitches = new BasicFeatureSwitches($featureSwitchesStorage);
  echo $featureSwitches->featureIsEnabled('SEND_ORDER_EMAILS');
  echo $featureSwitches->featureIsEnabled('DISPLAY_SURVEY');
  ```

* composite: use case - file storage for defaults and database storage in admin which overrides it

  ```yaml
  // config/feature_switches.yaml
  feature_switches:
    SEND_ORDER_EMAILS:
      enabled: true
  ```

  ```php
  <?php
  // index.php
  use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
  use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitch;
  use Dorumd\FeatureSwitches\Domain\PercentageSplitFeatureSwitch;
  use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;
  use Dorumd\FeatureSwitches\Infrastructure\CompositeStorage;
  use Dorumd\FeatureSwitches\Infrastructure\InMemoryFeatureSwitchesStorage;
  
  $configPath = __DIR__ . '/config/feature-switches.yaml';
  $fileFeatureSwitchesStorage = new FileFeatureSwitchesStorage($configPath);

  $featureSwitchesStorage = new InMemoryFeatureSwitchesStorage();
  $featureSwitchesStorage->store(new BasicFeatureSwitch('SEND_ORDER_EMAILS', false));
  $featureSwitchesStorage->store(new BasicFeatureSwitch('SEND_NOTIFICATION', true));
  $featureSwitchesStorage->store(
      new PercentageSplitFeatureSwitch('DISPLAY_SURVEY', true, ['percentage' => 50])
  );
  
  $featureSwitches = new BasicFeatureSwitches(
      // ordered ASC, last one overrides first one.
      new CompositeStorage([$fileFeatureSwitchesStorage, $featureSwitchesStorage])
  );
  echo $featureSwitches->featureIsEnabled('SEND_ORDER_EMAILS'); // false, in memory storage is overriding file.
  echo $featureSwitches->featureIsEnabled('DISPLAY_SURVEY');
  ```


## Development:
*Dependencies: Docker*

```bash
# Build the docker image with all deps., run tests and validate code format
make build
```

To fix code formatting errors:
```bash
make fix-code-format
```
