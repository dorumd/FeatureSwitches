<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Infrastructure;

use Dorumd\FeatureSwitches\Domain\FeatureSwitch;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Webmozart\Assert\Assert;

class CompositeStorage implements FeatureSwitchesStorage
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

    public function findFeatureSwitch(string $code): ?FeatureSwitch
    {
        $featureSwitch = null;
        foreach ($this->storages as $storage) {
            $foundFeatureSwitch = $storage->findFeatureSwitch($code);
            if (!$foundFeatureSwitch) {
                continue;
            }

            $featureSwitch = $foundFeatureSwitch;
        }

        return $featureSwitch;
    }

    private function ensureValidStorage($storage)
    {
        Assert::isInstanceOf($storage, FeatureSwitchesStorage::class);
    }
}
