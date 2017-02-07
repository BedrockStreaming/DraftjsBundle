<?php

namespace M6Web\Bundle\DraftjsBundle\Guesser;

use M6Web\Bundle\DraftjsBundle\Renderer\Entity\BlockEntityRendererInterface;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

/**
 * Class EntityGuesser
 *
 * @package M6Web\Bundle\DraftjsBundle\Guesser
 */
class BlockEntityGuesser implements EntityGuesserInterface
{
    /**
     * @var array
     */
    private $renderers = [];

    /**
     * @param BlockEntityRendererInterface $renderer
     * @param string                       $alias
     *
     * @return $this
     */
    public function addRenderer(BlockEntityRendererInterface $renderer, $alias)
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
