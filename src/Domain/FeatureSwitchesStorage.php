<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

interface FeatureSwitchesStorage
{
    public function findFeatureSwitch(string $code): ?FeatureSwitch;
}
