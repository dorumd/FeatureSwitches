<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

class BasicFeatureSwitch implements FeatureSwitch
{
    private string $code;
    private bool $enabled;

    public function __construct(string $code, bool $enabled)
    {
        $this->code = $code;
        $this->enabled = $enabled;
    }

    public function getType(): string
    {
        return 'basic';
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
