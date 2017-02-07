<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Inline;

use M6Web\Bundle\DraftjsBundle\Renderer\Helper\InlineRendererHelperTrait;

/**
 * Class AbstractInlineEntityRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Inline
 */
abstract class AbstractInlineEntityRenderer implements InlineEntityRendererInterface
{
    use InlineRendererHelperTrait;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param string $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}
