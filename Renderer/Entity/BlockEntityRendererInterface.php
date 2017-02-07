<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Entity;

use M6Web\Bundle\DraftjsBundle\Renderer\RendererInterface;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

/**
 * Interface BlockEntityRendererInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Entity
 */
interface BlockEntityRendererInterface extends RendererInterface
{
    /**
     * @param string $className
     *
     * @return $this
     */
    public function setClassName($className);

    /**
     * @param DraftEntity $entity
     *
     * @return string
     */
    public function render(DraftEntity $entity);

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports($type);

    /**
     * @return string
     */
    public function getName();
}
