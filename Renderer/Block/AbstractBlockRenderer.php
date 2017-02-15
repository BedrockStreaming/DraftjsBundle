<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

use M6Web\Bundle\DraftjsBundle\Renderer\Content\ContentRenderer;
use M6Web\Bundle\DraftjsBundle\Renderer\Helper\BlockRendererHelperTrait;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class AbstractBlockRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
abstract class AbstractBlockRenderer implements BlockRendererInterface
{
    use BlockRendererHelperTrait;

    /**
     * @var ContentRenderer
     */
    protected $contentRenderer;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $blockClassName;

    /**
     * @var string
     */
    protected $textAlignmentClassNames;

    /**
     * AbstractBlockRenderer constructor.
     *
     * @param ContentRenderer $contentRenderer
     * @param EngineInterface $templating
     */
    public function __construct(ContentRenderer $contentRenderer, EngineInterface $templating)
    {
        $this->contentRenderer = $contentRenderer;
        $this->templating = $templating;
    }

    /**
     * @param string $className
     *
     * @return $this
     */
    public function setBlockClassName($className)
    {
        $this->blockClassName = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockClassName()
    {
        return $this->blockClassName;
    }

    /**
     * @param array $classNames
     *
     * @return $this
     */
    public function setTextAlignmentClassNames($classNames)
    {
        $this->textAlignmentClassNames = $classNames;

        return $this;
    }

    /**
     * @param string $textAlignment
     *
     * @return string
     */
    public function getTextAlignmentClassName($textAlignment)
    {
        return $this->textAlignmentClassNames[$textAlignment];
    }
}
