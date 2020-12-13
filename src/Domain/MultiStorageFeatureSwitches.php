<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

use Webmozart\Assert\Assert;

class MultiStorageFeatureSwitches implements FeatureSwitches
{
    /** @var array<FeatureSwitchesStorage> */
    private array $storages;

    /**
     * @param array $storages List of storages ordered ASC by their priority. The last one will override first.
     */
    public function __construct(array $storages)
    {
        foreach ($storages as $storage) {
            $this->ensureValidStorage($storage);
        }

        $this->storages = $storages;
    }

    public function featureIsEnabled(string $featureName): bool
    {
        $featureSwitch = null;
        foreach ($this->storages as $storage) {
            $featureSwitch = $storage->findFeatureSwitch($featureName);
        }

        if (!$featureSwitch) {
            return false;
        }

        return $featureSwitch->isEnabled();
    }

    private function ensureValidStorage($storage)
    {
        Assert::isInstanceOf($storage, FeatureSwitchesStorage::class);
    }
}
