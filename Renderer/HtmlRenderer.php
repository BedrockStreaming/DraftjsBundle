<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer;

use M6Web\Bundle\DraftjsBundle\Converter\ConverterInterface;
use M6Web\Bundle\DraftjsBundle\Builder\BuilderInterface;

/**
 * Class HtmlRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer
 */
class HtmlRenderer implements EngineRendererInterface
{
    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * Renderer constructor.
     *
     * @param ConverterInterface $converter
     * @param BuilderInterface   $builder
     */
    public function __construct(ConverterInterface $converter, BuilderInterface $builder)
    {
        $this->converter = $converter;
        $this->builder = $builder;
    }

    /**
     * @param array $rawState
     *
     * @return string
     */
    public function render(array $rawState)
    {
        return $this->builder->build($this->converter->convertFromRaw($rawState));
    }
}
