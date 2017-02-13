<?php

namespace M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class InlineEntityRendererPass
 *
 * @package M6Web\Bundle\DraftjsBundle\DependencyInjection\Compiler
 */
class InlineEntityRendererPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $guesser = 'm6_web_draftjs.inline_entity_guesser';

        if (!$container->has($guesser)) {
            return;
        }

        $definition = $container->findDefinition($guesser);

        $taggedServices = $container->findTaggedServiceIds('draftjs.inline_entity_renderer');

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
