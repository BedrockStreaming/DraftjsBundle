<?php

namespace M6Web\Bundle\DraftjsBundle\Guesser;

use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use M6Web\Bundle\DraftjsBundle\Renderer\Inline\InlineEntityRendererInterface;

/**
 * Class InlineEntityGuesser
 *
 * @package M6Web\Bundle\DraftjsBundle\Guesser
 */
class InlineEntityGuesser implements EntityGuesserInterface
{
    /**
     * @var InlineEntityRendererInterface[]
     */
    private $renderers = [];

    /**
     * @param InlineEntityRendererInterface $renderer
     * @param string                        $alias
     *
     * @return $this
     */
    public function addRenderer(InlineEntityRendererInterface $renderer, $alias)
    {
        $this->renderers[$alias] = $renderer;

        return $this;
    }

    /**
     * @param DraftEntity $entity
     *
     * @return null
     */
    public function getRenderer(DraftEntity $entity)
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports(strtolower($entity->getType()))) {
                return $renderer;
            }
        }

        return null;
    }
}
