<?php

namespace M6Web\Bundle\DraftjsBundle\Guesser;

use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Renderer\Block\BlockRendererInterface;

/**
 * Class BlockGuesser
 *
 * @package M6Web\Bundle\DraftjsBundle\Guesser
 */
class BlockGuesser implements BlockGuesserInterface
{
    /**
     * @var BlockRendererInterface[]
     */
    private $renderers = [];

    /**
     * @param BlockRendererInterface $renderer
     * @param string                 $alias
     *
     * @return $this
     */
    public function addRenderer(BlockRendererInterface $renderer, $alias)
    {
        $this->renderers[$alias] = $renderer;

        return $this;
    }

    /**
     * @param ContentBlock $contentBlock
     *
     * @return null|BlockRendererInterface
     */
    public function getRenderer(ContentBlock $contentBlock)
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($contentBlock->getType())) {
                return $renderer;
            }
        }

        return null;
    }
}
