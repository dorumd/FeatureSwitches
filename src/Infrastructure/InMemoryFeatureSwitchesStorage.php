<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Infrastructure;

use Dorumd\FeatureSwitches\Domain\FeatureSwitch;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;

class InMemoryFeatureSwitchesStorage implements FeatureSwitchesStorage
{
    /** @var array<FeatureSwitch> */
    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function findFeatureSwitchEnabled(string $code): bool
    {
        $featureSwitch = $this->data[$code];
        if (!$featureSwitch) {
            return false;
        }

        return $featureSwitch->isEnabled();
    }

    public function store(FeatureSwitch $featureSwitch): void
    {
        $this->data[$featureSwitch->getCode()] = $featureSwitch;
    }
}
