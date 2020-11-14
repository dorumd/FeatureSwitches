<?php
declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Domain;

class BasicFeatureSwitches implements FeatureSwitches
{
    private FeatureSwitchesStorage $storage;

    public function __construct(FeatureSwitchesStorage $storage)
    {
        $this->storage = $storage;
    }

    public function featureIsEnabled(string $featureName): bool
    {
        return $this->storage->findFeatureSwitchEnabled($featureName);
    }
}
