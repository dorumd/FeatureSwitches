<?php

declare(strict_types=1);

namespace Dorumd\FeatureSwitches\Infrastructure;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class FeatureSwitchesFileConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('feature_switches');

        $treeBuilder->getRootNode()
            ->useAttributeAsKey('name')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('type')->defaultValue('basic')->end()
                    ->booleanNode('enabled')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
