<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

class PercentageSplitFeatureSwitch implements FeatureSwitch
{
    private string $code;
    private bool $enabled;
    private int $percentage;

    public function __construct(string $code, bool $enabled, array $configuration)
    {
        $this->code = $code;
        $this->enabled = $enabled;

        if (!$configuration['percentage']) {
            throw new \DomainException('Missing mandatory config: percentage');
        }

        $this->percentage = (int) $configuration['percentage'];
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isEnabled(): bool
    {
        if (!$this->enabled) {
            return false;
        }

        $probability = rand(1, 100);

        return $probability <= $this->percentage;
    }
}
