<?php

namespace M6Web\Bundle\DraftjsBundle\Model;

/**
 * Class ContentBlock
 *
 * @package M6Web\Bundle\DraftjsBundle\Model
 */
class CharacterMetadata
{
    /**
     * @var string $style
     */
    private $styles = [];

    /**
     * @var int $entityIndex
     */
    private $entityIndex = null;

    /**
     * CharacterMetadata constructor.
     *
     * @param array $styles
     * @param int   $entityIndex
     */
    public function __construct(array $styles = [], $entityIndex = null)
    {
        $this->styles = $styles;
        $this->entityIndex = $entityIndex;
    }

    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @param array $styles
     *
     * @return $this
     */
    public function setStyles(array $styles = [])
    {
        $this->styles = $styles;

        return $this;
    }

    /**
     * @param array $style
     */
    public function applyStyle(array $style)
    {
        if (!$this->hasStyle($style)) {
            $this->styles[] = $style;
        }
    }

    /**
     * @param string $style
     *
     * @return bool
     */
    public function hasStyle($style)
    {
        return in_array($style, $this->styles);
    }

    /**
     * @return null|string
     */
    public function getEntityIndex()
    {
        return $this->entityIndex;
    }

    /**
     * @param int $entityIndex
     */
    public function setEntityIndex($entityIndex)
    {
        $this->entityIndex = $entityIndex;
    }
}
