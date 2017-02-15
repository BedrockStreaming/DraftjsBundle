<?php

namespace M6Web\Bundle\DraftjsBundle\Builder;

use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;
use M6Web\Bundle\DraftjsBundle\Guesser\BlockGuesser;
use M6Web\Bundle\DraftjsBundle\Model\ContentState;

/**
 * Class HtmlBuilder
 *
 * @package M6Web\Bundle\DraftjsBundle\Builder
 */
class HtmlBuilder implements BuilderInterface
{
    /**
     * @var BlockGuesser $blockGuesser
     */
    private $blockGuesser;

    /**
     * HtmlBuilder constructor.
     *
     * @param BlockGuesser $blockGuesser
     */
    public function __construct(BlockGuesser $blockGuesser)
    {
        $this->blockGuesser = $blockGuesser;
    }

    /**
     * Build HTML from contentState
     *
     * @param ContentState $contentState
     *
     * @return string
     *
     * @throws DraftjsException
     */
    public function build(ContentState $contentState)
    {
        $output = '';

        $contentBlocks = $contentState->getBlockMap();
        $entities = $contentState->getEntityMap();

        $iterator = new \ArrayIterator($contentBlocks);
        $iterator->rewind();

        $previousBlock = null;
        while ($iterator->valid()) {
            $block = $iterator->current();

            if ($previousBlock === $block) {
                throw new DraftjsException('Iterator must be switch to next value in custom renderer');
            }

            $renderer = $this->blockGuesser->getRenderer($block);

            if (!$renderer) {
                throw new DraftjsException(
                    sprintf('Undefined block renderer for type "%s"', $iterator->current()->getType())
                );
            }

            $output .= $renderer->render($iterator, $entities);

            $previousBlock = $block;
        }

        return $output;
    }
}
