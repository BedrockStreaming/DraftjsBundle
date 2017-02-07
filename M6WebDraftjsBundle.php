<?php

namespace M6Web\Bundle\DraftjsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler\BlockRendererPass;
use M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler\BlockEntityRendererPass;
use M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler\InlineEntityRendererPass;

/**
 * Class M6WebDraftjsBundle
 *
 * @package M6Web\Bundle\DraftjsBundle
 */
class M6WebDraftjsBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new BlockRendererPass());
        $container->addCompilerPass(new BlockEntityRendererPass());
        $container->addCompilerPass(new InlineEntityRendererPass());
    }
}
