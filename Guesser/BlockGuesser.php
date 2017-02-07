<?php

namespace M6Web\Bundle\DraftjsBundle\Guesser;

use M6Web\Bundle\DraftjsBundle\Renderer\Block\BlockRendererInterface;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;

/**
 * Class BlockGuesser
 *
 * @package M6Web\Bundle\DraftjsBundle\Guesser
 */
class BlockGuesser implements BlockGuesserInterface
{
    /**
     * @var array
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
     * @return null
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

    /**
     * @param array $templates
     *
     * @return $this
     */
    public function setTemplates(array $templates = [])
    {
        $this->templates = $templates;

        return $this;
    }
}
