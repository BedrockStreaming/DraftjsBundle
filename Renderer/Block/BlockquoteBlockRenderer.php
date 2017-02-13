<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

/**
 * Class BlockquoteBlockRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
class BlockquoteBlockRenderer extends AbstractBlockRenderer
{
    const TYPE = 'blockquote';

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

        return $this->templating->render('M6WebDraftjsBundle:Block:blockquote.html.twig', [
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
        return self::TYPE === $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE;
    }
}
