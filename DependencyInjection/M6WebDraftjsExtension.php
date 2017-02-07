<?php

namespace M6Web\Bundle\DraftjsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

/**
 * Class M6WebDraftjsExtension
 *
 * @package M6Web\Bundle\DraftjsBundle\DependencyInjection
 */
class M6WebDraftjsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->addCustomClassNames($container, $config['class_names']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $classNames
     */
    protected function addCustomClassNames(ContainerBuilder $container, array $classNames)
    {
        $container
            ->findDefinition('m6_web_draftjs.atomic_block_renderer')
            ->addMethodCall('setBlockClassName', [$classNames['blocks']['atomic']])
            ->addMethodCall('setTextAlignmentClassNames', [$classNames['text_alignment']])
        ;

        $container
            ->findDefinition('m6_web_draftjs.default_block_renderer')
            ->addMethodCall('setBlockClassName', [$classNames['blocks']['default']])
            ->addMethodCall('setTextAlignmentClassNames', [$classNames['text_alignment']])
        ;

        $container
            ->findDefinition('m6_web_draftjs.list_block_renderer')
            ->addMethodCall('setBlockClassName', [$classNames['blocks']['list']])
            ->addMethodCall('setTextAlignmentClassNames', [$classNames['text_alignment']])
        ;

        $container
            ->findDefinition('m6_web_draftjs.heading_block_renderer')
            ->addMethodCall('setBlockClassName', [$classNames['blocks']['heading']])
            ->addMethodCall('setTextAlignmentClassNames', [$classNames['text_alignment']])
        ;

        $container
            ->findDefinition('m6_web_draftjs.blockquote_block_renderer')
            ->addMethodCall('setBlockClassName', [$classNames['blocks']['blockquote']])
            ->addMethodCall('setTextAlignmentClassNames', [$classNames['text_alignment']])
        ;

        if (!empty($classNames['inline'])) {
            $container
                ->findDefinition('m6_web_draftjs.content_renderer')
                ->addMethodCall('setInlineClassNames', [$classNames['inline']])
            ;
        }
    }
}
