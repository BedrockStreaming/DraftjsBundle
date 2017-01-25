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

        if ($config['classNames']) {
            $this->addCustomClassNamesBuilder($container, $config['classNames']);
        }

        if ($config['blocks']) {
            $this->addCustomBlocksBuilder($container, $config['blocks']);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array $classNames
     */
    protected function addCustomClassNamesBuilder(ContainerBuilder $container, array $classNames)
    {
        $container
            ->findDefinition('m6_web_draftjs.html_builder')
            ->addMethodCall("setCustomClassNames", [$classNames]);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $blocks
     */
    protected function addCustomBlocksBuilder(ContainerBuilder $container, array $blocks)
    {
        $container
            ->findDefinition('m6_web_draftjs.html_builder')
            ->addMethodCall("setCustomBlocks", [$blocks]);
    }
}
