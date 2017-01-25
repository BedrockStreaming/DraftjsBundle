<?php

namespace M6Web\Bundle\DraftjsBundle\Model;

/**
 * Class ContentBlock
 *
 * @package M6Web\Bundle\DraftjsBundle\Model
 */
class CharacterMetadata
{
    const BOLD = 'BOLD';
    const ITALIC = 'ITALIC';
    const STRIKETHROUGH = 'STRIKETHROUGH';

    const STYLES = [self::BOLD, self::ITALIC, self::STRIKETHROUGH];

    /**
     * @var string $style
     */
    private $styles = [];

    /**
     * @var
     */
    private $entity = null;

    /**
     * CharacterMetadata constructor.
     *
     * @param array $styles
     * @param int   $entity
     */
    public function __construct(array $styles = [], $entity = null)
    {
        $this->styles = $styles;
        $this->entity = $entity;
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
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param DraftEntity $entity
     */
    public function setEntity(DraftEntity $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $style
     *
     * @return bool
     */
    public static function supportsStyle($style)
    {
        return in_array($style, self::STYLES);
    }
}
