<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Tests;

use Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitches;
use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;
use Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Dorumd\FeatureSwitches\Domain\BasicFeatureSwitches
 * @covers \Dorumd\FeatureSwitches\Infrastructure\FileFeatureSwitchesStorage
 */
class FileStorageFeatureSwitchesTest extends TestCase
{
    /** @var FeatureSwitchesStorage|null */
    private ?FeatureSwitchesStorage $featureSwitchesStorage;

    /** @var FeatureSwitches|null */
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
        $this->assertFalse($this->basicFeatureSwitches->featureIsEnabled('TEST_SPLIT_2'));
    }

    public function testMissingFeature(): void
    {
        $this->assertFalse($this->basicFeatureSwitches->featureIsEnabled('MISSING'));
    }


    /**
     * @dataProvider percentageSplitProvider
     */
    public function testPercentageSplit(
        int $retries,
        int $sampleCount,
        int $expectedLowestCountOfEnabled,
        int $expectedHighestCountOfEnabled
    ): void {
        for ($i = 0; $i < $retries; $i++) {
            $results = [];

            for ($j = 0; $j < $sampleCount; $j++) {
                $results[] = $this->basicFeatureSwitches->featureIsEnabled('SPLIT_TEST_1');
            }

            $enabledResultsCount = count(array_filter($results));

            $this->assertTrue($enabledResultsCount > $expectedLowestCountOfEnabled, "{$enabledResultsCount}");
            $this->assertTrue($enabledResultsCount < $expectedHighestCountOfEnabled, "{$enabledResultsCount}");
        }
    }

    public function percentageSplitProvider(): array
    {
        return [
            '3000 attempts' => [
                'retries' => 100,
                'sampleCount' => 3000,
                'expectedLowestCountOfEnabled' => 1350,
                'expectedHighestCountOfEnabled' => 1650,
            ],
            '500 attempts' => [
                'retries' => 100,
                'sampleCount' => 500,
                'expectedLowestCountOfEnabled' => 200,
                'expectedHighestCountOfEnabled' => 300,
            ],
            '50000 attempts' => [
                'retries' => 20,
                'sampleCount' => 50000,
                'expectedLowestCountOfEnabled' => 24000,
                'expectedHighestCountOfEnabled' => 26000,
            ],
        ];
    }
}
