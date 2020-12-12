<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Tests;

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitch;
use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Dorumd\FeatureSwitches\Infrastructure\InMemoryFeatureSwitchesStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureSwitchesCanBeEnabledTest
 * @package Dorumd\FeatureSwitches\Tests
 * @covers \Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches
 */
class FeatureSwitchesCanBeEnabledTest extends TestCase
{
    /** @var FeatureSwitchesStorage */
    private ?FeatureSwitchesStorage $featureSwitchesStorage;

    /** @var FeatureSwitches */
    private ?FeatureSwitches $basicFeatureSwitches;

    protected function setUp(): void
    {
        $this->featureSwitchesStorage = new InMemoryFeatureSwitchesStorage();
        $this->featureSwitchesStorage->store(new BasicFeatureSwitch('TEST-1', true));
        $this->featureSwitchesStorage->store(new BasicFeatureSwitch('TEST-2', false));

        $this->basicFeatureSwitches = new BasicFeatureSwitches($this->featureSwitchesStorage);
    }

    protected function tearDown(): void
    {
        $this->basicFeatureSwitches = null;
        $this->featureSwitchesStorage = null;
    }

    public function testIsEnabled(): void
    {
        $this->assertTrue($this->basicFeatureSwitches->featureIsEnabled('TEST-1'));
    }

    public function testIsDisabled(): void
    {
        $this->assertFalse($this->basicFeatureSwitches->featureIsEnabled('TEST-2'));
    }

    public function testMissingFeature(): void
    {
        $this->assertFalse($this->basicFeatureSwitches->featureIsEnabled('MISSING'));
    }
}
