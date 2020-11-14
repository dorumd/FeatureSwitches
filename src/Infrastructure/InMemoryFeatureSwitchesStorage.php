<?php
declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Infrastructure;

use Dorumd\FeatureSwitches\Domain\FeatureSwitchesStorage;

class InMemoryFeatureSwitchesStorage implements FeatureSwitchesStorage
{
    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function findFeatureSwitchEnabled(string $name): bool
    {
        return $this->data[$name] ?? false;
    }

    /**
     * Not part of the domain interface yet, since there is no use-case outside of tests yet.
     * @param string $name
     * @param bool $enabled
     */
    public function store(string $name, bool $enabled): void
    {
        $this->data[$name] = $enabled;
    }
}
