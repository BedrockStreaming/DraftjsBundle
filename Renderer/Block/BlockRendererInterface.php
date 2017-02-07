<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

use M6Web\Bundle\DraftjsBundle\Renderer\RendererInterface;

/**
 * Interface BlockRendererInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
interface BlockRendererInterface extends RendererInterface
{
    /**
     * @param string $className
     *
     * @return $this
     */
    public function setBlockClassName($className);

    /**
     * @param array $classNames
     *
     * @return $this
     */
    public function setTextAlignmentClassNames($classNames);

    /**
     * @param \ArrayIterator $iterator
     * @param array          $entities
     *
     * @return mixed
     */
    public function render(\ArrayIterator &$iterator, array $entities);

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
