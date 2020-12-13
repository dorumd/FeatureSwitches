<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Tests;

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitch;
use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Dorumd\FeatureSwitches\Domain\PercentageSplitFeatureSwitch;
use Dorumd\FeatureSwitches\Infrastructure\CompositeStorage;
use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;
use Dorumd\FeatureSwitches\Infrastructure\InMemoryFeatureSwitchesStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers CompositeStorage
 */
class MultiStorageFeatureSwitchesTest extends TestCase
{
    /** @var FeatureSwitchesStorage|null */
    private ?FeatureSwitchesStorage $fallbackStorage;

    /** @var FeatureSwitchesStorage|null */
    private ?FeatureSwitchesStorage $primaryStorage;

    /** @var FeatureSwitches|null */
    private ?FeatureSwitches $multiStorageFeatureSwitches;

    protected function setUp(): void
    {
        $configPath = __DIR__ . '/config/feature-switches.yaml';
        $this->fallbackStorage = new FileFeatureSwitchesStorage($configPath);

        $this->primaryStorage = new InMemoryFeatureSwitchesStorage();
        $this->primaryStorage->store(new BasicFeatureSwitch('APP_TEST_1', false));
        $this->primaryStorage->store(new BasicFeatureSwitch('APP_TEST_2', true));
        $this->primaryStorage->store(
            new PercentageSplitFeatureSwitch('SPLIT_TEST_2', true, ['percentage' => 100])
        );
        $this->primaryStorage->store(
            new PercentageSplitFeatureSwitch('SPLIT_TEST_3', false, ['percentage' => 50])
        );

        $this->multiStorageFeatureSwitches = new BasicFeatureSwitches(
            new CompositeStorage(
                [$this->fallbackStorage, $this->primaryStorage]
            )
        );
    }

    public function tearDown(): void
    {
        $this->multiStorageFeatureSwitches = null;
        $this->fallbackStorage = null;
        $this->primaryStorage = null;
    }

    public function testIsEnabledOverridden(): void
    {
        // Overridden by primary storage.
        $this->assertTrue($this->multiStorageFeatureSwitches->featureIsEnabled('APP_TEST_2'));
        // Overridden by primary storage.
        $this->assertTrue($this->multiStorageFeatureSwitches->featureIsEnabled('SPLIT_TEST_2'));
    }

    public function testIsDisabledOverriden(): void
    {
        $this->assertFalse($this->multiStorageFeatureSwitches->featureIsEnabled('APP_TEST_1'));
        $this->assertFalse($this->multiStorageFeatureSwitches->featureIsEnabled('SPLIT_TEST_3'));
    }

    public function testMissingFeature(): void
    {
        $this->assertFalse($this->multiStorageFeatureSwitches->featureIsEnabled('MISSING'));
    }
}
