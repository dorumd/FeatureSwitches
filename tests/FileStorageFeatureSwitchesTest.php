<?php
declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Tests;

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureSwitchesCanBeEnabledTest
 * @package Dorumd\FeatureSwitches\Tests
 * @covers \Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches
 * @covers \Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage
 */
class FileStorageFeatureSwitchesTest extends TestCase
{
    /** @var FeatureSwitchesStorage */
    private ?FeatureSwitchesStorage $featureSwitchesStorage;

    /** @var FeatureSwitches */
    private ?FeatureSwitches $basicFeatureSwitches;

    protected function setUp(): void
    {
        $configPath = __DIR__ . '/config/feature-switches.yaml';
        $this->featureSwitchesStorage = new FileFeatureSwitchesStorage($configPath);

        $this->basicFeatureSwitches = new BasicFeatureSwitches($this->featureSwitchesStorage);
    }

    protected function tearDown(): void
    {
        $this->basicFeatureSwitches = null;
        $this->featureSwitchesStorage = null;
    }

    public function testIsEnabled(): void
    {
        $this->assertTrue($this->basicFeatureSwitches->featureIsEnabled('APP_TEST_1'));
    }

    public function testIsDisabled(): void
    {
        $this->assertFalse($this->basicFeatureSwitches->featureIsEnabled('APP_TEST_2'));
    }

    public function testMissingFeature(): void
    {
        $this->assertFalse($this->basicFeatureSwitches->featureIsEnabled('MISSING'));
    }
}
