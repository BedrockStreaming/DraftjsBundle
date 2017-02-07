<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

/**
 * Class HeadingBlockRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
class HeadingBlockRenderer extends AbstractBlockRenderer
{
    const NAME = 'heading';

    const HEADER_ONE = 'header-one';
    const HEADER_TWO = 'header-two';
    const HEADER_THREE = 'header-three';
    const HEADER_FOUR = 'header-four';
    const HEADER_FIVE = 'header-five';
    const HEADER_SIX = 'header-six';

    const TYPES = [
        self::HEADER_ONE,
        self::HEADER_TWO,
        self::HEADER_THREE,
        self::HEADER_FOUR,
        self::HEADER_FIVE,
        self::HEADER_SIX,
    ];

    const H1 = 'h1';
    const H2 = 'h2';
    const H3 = 'h3';
    const H4 = 'h5';
    const H5 = 'h5';
    const H6 = 'h6';

    const TAGS_NAME = [
        self::HEADER_ONE => self::H1,
        self::HEADER_TWO => self::H2,
        self::HEADER_THREE => self::H3,
        self::HEADER_FOUR => self::H4,
        self::HEADER_FIVE => self::H5,
        self::HEADER_SIX => self::H6,
    ];

    /**
     * @param \ArrayIterator $iterator
     * @param array          $entities
     *
     * @return string
     */
    public function render(\ArrayIterator &$iterator, array $entities)
    {
        $contentBlock = $iterator->current();
        $iterator->next();

        $content = $this->contentRenderer->render($contentBlock->getText(), $contentBlock->getCharacterList(), $entities);

        if (!$this->template) {
            return $content;
        }

        return $this->templating->render($this->getTemplate(), [
            'tag' => self::TAGS_NAME[$contentBlock->getType()],
            'classNames' => $this->buildClassNames($contentBlock),
            'content' => $content,
        ]);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports($type)
    {
        return in_array($type, self::TYPES);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
