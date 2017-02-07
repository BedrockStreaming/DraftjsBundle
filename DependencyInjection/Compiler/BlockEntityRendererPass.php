<?php

namespace M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class BlockEntityRendererPass
 *
 * @package M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler
 */
class BlockEntityRendererPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $guesser = 'm6_web_draftjs.block_entity_guesser';

        if (!$container->has($guesser)) {
            return;
        }

        $definition = $container->findDefinition($guesser);

        $taggedServices = $container->findTaggedServiceIds('draftjs.block_entity_renderer');

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addRenderer',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}
