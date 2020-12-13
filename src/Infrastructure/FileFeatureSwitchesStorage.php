<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Infrastructure;

use Dorumd\FeatureSwitches\Domain\FeatureSwitch;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class FileFeatureSwitchesStorage implements FeatureSwitchesStorage
{
    /** @var array<FeatureSwitch> */
    private array $data;

    public function __construct(string $pathToConfigurationFile)
    {
        $this->data = [];

        foreach ($this->processFeatureSwitchesConfiguration($pathToConfigurationFile) as $code => $config) {
            $this->data[$code] = new $config['type']($code, $config['enabled'], $config['configuration'] ?? []);
        }
    }

    public function findFeatureSwitch(string $code): ?FeatureSwitch
    {
        return $this->data[$code] ?? null;
    }

    private function processFeatureSwitchesConfiguration(string $pathToConfigurationFile)
    {
        $processor = new Processor();
        $featureSwitchesConfiguration = new FeatureSwitchesFileConfiguration();
        $yamlData = Yaml::parseFile($pathToConfigurationFile);

        return $processor->processConfiguration(
            $featureSwitchesConfiguration,
            $yamlData
        );
    }
}
