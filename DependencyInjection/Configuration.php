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
                ->arrayNode('class_names')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('blocks')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('atomic')
                                    ->cannotBeEmpty()
                                    ->defaultValue('block-atomic')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('default')
                                    ->cannotBeEmpty()
                                    ->defaultValue('block-paragraph')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('heading')
                                    ->cannotBeEmpty()
                                    ->defaultValue('block-heading')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('list')
                                    ->cannotBeEmpty()
                                    ->defaultValue('block-list')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('blockquote')
                                    ->cannotBeEmpty()
                                    ->defaultValue('block-blockquote')
                                ->end()
                            ->end()
                        ->end() // blocks
                    ->end()
                    ->children()
                        ->arrayNode('inline')
                            ->useAttributeAsKey('name')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')
                                ->cannotBeEmpty()
                            ->end()
                        ->end() // inline
                    ->end()
                    ->children()
                        ->arrayNode('text_alignment')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('left')
                                    ->cannotBeEmpty()
                                    ->defaultValue('align-left')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('center')
                                    ->cannotBeEmpty()
                                    ->defaultValue('align-center')
                                ->end()
                            ->end()
                            ->children()
                                ->scalarNode('right')
                                    ->cannotBeEmpty()
                                    ->defaultValue('align-right')
                                ->end()
                            ->end()
                        ->end() // inline
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
