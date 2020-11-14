<?php
declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Infrastructure;

use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class FileFeatureSwitchesStorage implements FeatureSwitchesStorage
{
    private array $data;

    public function __construct(string $pathToConfigurationFile)
    {
        $this->data = [];

        foreach ($this->processFeatureSwitchesConfiguration($pathToConfigurationFile) as $name => $config) {
            $this->data[$name] = $config['enabled'];
        }
    }

    public function findFeatureSwitchEnabled(string $name): bool
    {
        return $this->data[$name] ?? false;
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
