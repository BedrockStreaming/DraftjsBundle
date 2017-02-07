<?php

namespace M6Web\Bundle\DraftjsBundle\Guesser;

use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

/**
 * Interface EntityGuesserInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Guesser
 */
interface EntityGuesserInterface extends GuesserInterface
{
    /**
     * @param DraftEntity $entity
     *
     * @return null
     */
    public function getRenderer(DraftEntity $entity);
}
