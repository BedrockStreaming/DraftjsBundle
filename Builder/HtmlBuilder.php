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

        while ($iterator->valid()) {
            $renderer = $this->blockGuesser->getRenderer($iterator->current());

            if (!$renderer) {
                throw new DraftjsException(
                    sprintf('Undefined block renderer for type "%s"', $iterator->current()->getType())
                );
            }

            $output .= $renderer->render($iterator, $entities);
        }

        return $output;
    }
}
