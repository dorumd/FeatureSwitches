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

    public function findFeatureSwitch(string $code): ?FeatureSwitch
    {
        return $this->data[$code] ?? null;
    }

    public function store(FeatureSwitch $featureSwitch): void
    {
        $this->data[$featureSwitch->getCode()] = $featureSwitch;
    }
}
