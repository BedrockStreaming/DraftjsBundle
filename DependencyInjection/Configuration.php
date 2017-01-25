<?php

namespace M6Web\Bundle\DraftjsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package M6Web\Bundle\DraftjsBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('m6_web_draftjs');

        $rootNode
            ->children()
                ->arrayNode('classNames')
                    ->useAttributeAsKey('name')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end() // classNames
                ->arrayNode('blocks')
                    ->useAttributeAsKey('name')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end() // blocks
            ->end()
        ;

        return $treeBuilder;
    }
}
