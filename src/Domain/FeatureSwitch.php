<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

interface FeatureSwitch
{
    public function getCode(): string;
    public function isEnabled(): bool;
}
