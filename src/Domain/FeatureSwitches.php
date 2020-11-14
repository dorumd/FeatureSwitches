<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

interface FeatureSwitches
{
    public function featureIsEnabled(string $featureName): bool;
}
