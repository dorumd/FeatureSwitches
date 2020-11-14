*Feature Switches*

This library provides the most basic implementation of so called feature switches(feature toggles/feature flags).

**Setup:**
1. Install the library
```bash
composer require dorumd/feature-switches
```

2. Configure your feature switches(example):
```yaml
# config/feature-switches.yaml
feature_switches:
  SEND_ORDER_EMAILS:
     enabled: true
  SEND_MERCHANT_NOTIFICATIONS:
     enabled: false
```

**Usage:**
```php
<?php
// index.php

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;

$configPath = __DIR__ . '/config/feature-switches.yaml';
$this->featureSwitchesStorage = new FileFeatureSwitchesStorage($configPath);

$featureSwitches = new BasicFeatureSwitches($this->featureSwitchesStorage);

$featureSwitches->featureIsEnabled('SEND_MERCHANT_NOTIFICATIONS');
$featureSwitches->featureIsEnabled('SEND_ORDER_EMAILS');
```

3. Configure services(Symfony example):
```yaml
\Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage:
    arguments:
        $pathToConfigurationFile: '%kernel.root_dir%/config/feature-switches.yaml'

\Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches:
     arguments:
        $storage: '@Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage'

```

Development
```bash
DOCKER_BUILDKIT=1 docker build -t feature-switches:latest .
docker run -v $(pwd):/FeatureSwitches feature-switches:latest
```
