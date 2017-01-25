<?php

namespace M6Web\Bundle\DraftjsBundle\Builder;

use M6Web\Bundle\DraftjsBundle\Model\ContentState;

/**
 * Interface BuilderInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Builder
 */
interface BuilderInterface
{
    /**
     * @param ContentState $contentState
     *
     * @return mixed
     */
    public function build(ContentState $contentState);
}
