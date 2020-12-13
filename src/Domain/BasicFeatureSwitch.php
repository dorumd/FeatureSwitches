<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

class BasicFeatureSwitch implements FeatureSwitch
{
    private string $code;
    private bool $enabled;

    public function __construct(string $code, bool $enabled, array $configuration = [])
    {
        unset($configuration);

        $this->code = $code;
        $this->enabled = $enabled;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
