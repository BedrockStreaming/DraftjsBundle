<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

/**
 * Class DefaultBlockRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
class DefaultBlockRenderer extends AbstractBlockRenderer
{
    const NAME = 'default';

    const UNSTYLED = 'unstyled';
    const PARAGRAPH = 'paragraph';

    const TYPES = [
        self::UNSTYLED,
        self::PARAGRAPH,
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
