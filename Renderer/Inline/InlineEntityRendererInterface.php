<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Inline;

use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use M6Web\Bundle\DraftjsBundle\Renderer\RendererInterface;

/**
 * Interface InlineEntityRendererInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Entity
 */
interface InlineEntityRendererInterface extends RendererInterface
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
    public function openTag(DraftEntity $entity);

    /**
     * @return string
     */
    public function closeTag();

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
