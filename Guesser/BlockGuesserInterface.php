<?php

namespace M6Web\Bundle\DraftjsBundle\Guesser;

use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;

/**
 * Interface BlockGuesserInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Guesser
 */
interface BlockGuesserInterface extends GuesserInterface
{
    /**
     * @param ContentBlock $contentBlock
     *
     * @return null
     */
    public function getRenderer(ContentBlock $contentBlock);
}
