<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Entity;

use Symfony\Component\Templating\EngineInterface;

/**
 * Class AbstractBlockEntityRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Entity
 */
abstract class AbstractBlockEntityRenderer implements BlockEntityRendererInterface
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $template;

    /**
     * AbstractEntityRenderer constructor.
     *
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

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

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
